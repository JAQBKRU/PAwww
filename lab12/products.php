<?php
session_start();
require 'cfg.php';

// Zmienna do sprawdzenia, czy produkt jest niedostępny
$showOutOfStockPopup = false;

if (!isset($_SESSION['user_id'])) {
    header('Location: login_user.php');
    exit;
}else{
    // Przedłużenie rezerwacji dla zalogowanego użytkownika
    $sqlExtendCart = "
    UPDATE koszyk
    SET reserved_until = NOW() + INTERVAL 7 DAY
    WHERE user_id = ?
    ";
    $stmtExtendCart = $link->prepare($sqlExtendCart);
    $stmtExtendCart->bind_param('i', $_SESSION['user_id']);
    $stmtExtendCart->execute();
}

$timeToExtend = '+7 days'; // Czas przedłużenia dla zalogowanego użytkownika

// Przywracanie stanów magazynowych dla usuniętych rezerwacji
$sqlRestoreStock = "
    UPDATE produkty p
    JOIN (
        SELECT product_id, SUM(quantity) AS returned_quantity
        FROM koszyk
        WHERE reserved_until < NOW() - INTERVAL 7 DAY
        GROUP BY product_id
    ) k ON p.id = k.product_id
    SET p.ilosc_magazyn = p.ilosc_magazyn + k.returned_quantity
";
$stmtRestoreStock = $link->prepare($sqlRestoreStock);
$stmtRestoreStock->execute();

// Usuwanie koszyków, które są starsze niż 7 dni
$sqlDeleteOldCarts = "
    DELETE FROM koszyk
    WHERE reserved_until < NOW() - INTERVAL 7 DAY
";
$stmtDeleteOldCarts = $link->prepare($sqlDeleteOldCarts);
$stmtDeleteOldCarts->execute();

// - - - - - - - - - - - - - - - - //
//        Przedłużenie rezerwacji    //
// - - - - - - - - - - - - - - - - //  
// Funkcja przedłużająca czas rezerwacji produktów w koszyku
// Parametry: 
//   $userId - identyfikator użytkownika
//   $extendTime - czas przedłużenia
//   $link - połączenie z bazą danych
// Zwraca: void
// Sposób działania: Funkcja przedłuża rezerwację wszystkich produktów w koszyku danego użytkownika.
function extendCartReservation($userId, $extendTime, $link) {
    // Pobranie produktów w koszyku dla zalogowanego użytkownika
    $sql = "SELECT product_id FROM koszyk WHERE user_id = ?";
    $stmt = $link->prepare($sql);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $productId = $row['product_id'];
        $newReservedUntil = date('Y-m-d H:i:s', strtotime($extendTime));
        
        // Przedłużenie rezerwacji produktu w koszyku
        $sqlUpdate = "UPDATE koszyk SET reserved_until = ? WHERE user_id = ? AND product_id = ?";
        $stmtUpdate = $link->prepare($sqlUpdate);
        $stmtUpdate->bind_param('sii', $newReservedUntil, $userId, $productId);
        $stmtUpdate->execute();
    }
}

// Przedłużenie rezerwacji po zalogowaniu
extendCartReservation($_SESSION['user_id'], $timeToExtend, $link);

// - - - - - - - - - - - - - - - - //
//       Funkcja dodająca produkt    //
// - - - - - - - - - - - - - - - - //  
// Funkcja dodająca produkt do koszyka
// Parametry: 
//   $productId - identyfikator produktu
//   $quantityToAdd - ilość do dodania
//   $link - połączenie z bazą danych
//   $timeToAdd - czas rezerwacji
// Zwraca: void
// Sposób działania: Funkcja dodaje produkt do koszyka lub aktualizuje jego ilość, jeśli już istnieje.
function addToCart($productId, $quantityToAdd, $link, $timeToAdd) {
    global $showOutOfStockPopup;

    $link->begin_transaction();

    try {
        // Pobranie aktualnego stanu magazynowego
        $sql = "SELECT ilosc_magazyn AS ilosc FROM produkty WHERE id = ?";
        $stmt = $link->prepare($sql);
        $stmt->bind_param('i', $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $result->free();
        $stmt->close();

        if ($product) {
            $iloscMagazyn = $product['ilosc'];

            // Pobranie sumy zarezerwowanych ilości dla tego produktu
            $sqlReserved = "
                SELECT SUM(quantity) AS ilosc_zarezerwowana 
                FROM koszyk 
                WHERE product_id = ? AND reserved_until >= NOW()
            ";
            $stmtReserved = $link->prepare($sqlReserved);
            $stmtReserved->bind_param('i', $productId);
            $stmtReserved->execute();
            $resultReserved = $stmtReserved->get_result();
            $resultReserved->free();
            $stmtReserved->close();

            // Obliczenie dostępnej ilości
            $iloscDostepna = $iloscMagazyn;

            if ($iloscDostepna >= $quantityToAdd) {
                // Obliczenie czasu rezerwacji
                $reservedUntil = date('Y-m-d H:i:s', strtotime($timeToAdd));

                // Sprawdzenie, czy produkt już istnieje w koszyku
                $sqlCheckCart = "SELECT quantity FROM koszyk WHERE user_id = ? AND product_id = ?";
                $stmtCheck = $link->prepare($sqlCheckCart);
                $stmtCheck->bind_param('ii', $_SESSION['user_id'], $productId);
                $stmtCheck->execute();
                $resultCheck = $stmtCheck->get_result();

                if ($resultCheck->num_rows > 0) {
                    // Aktualizacja ilości w koszyku
                    $existingProduct = $resultCheck->fetch_assoc();
                    $newQuantity = $existingProduct['quantity'] + $quantityToAdd;

                    if (0 < $iloscDostepna) {
                        $sqlUpdateCart = "UPDATE koszyk SET quantity = ?, reserved_until = ? WHERE user_id = ? AND product_id = ?";
                        $stmtUpdateCart = $link->prepare($sqlUpdateCart);
                        $stmtUpdateCart->bind_param('isii', $newQuantity, $reservedUntil, $_SESSION['user_id'], $productId);
                        $stmtUpdateCart->execute();
                    } else {
                        throw new Exception('Za mało produktów w magazynie.');
                    }
                } else {
                    // Dodanie produktu do koszyka
                    $sqlInsertCart = "INSERT INTO koszyk (user_id, product_id, quantity, reserved_until) VALUES (?, ?, ?, ?)";
                    $stmtInsertCart = $link->prepare($sqlInsertCart);
                    $stmtInsertCart->bind_param('iiis', $_SESSION['user_id'], $productId, $quantityToAdd, $reservedUntil);
                    $stmtInsertCart->execute();
                }

                // Zmniejszenie ilości w magazynie
                $newQuantityInStock = $iloscMagazyn - $quantityToAdd;
                $sqlUpdateStock = "UPDATE produkty SET ilosc_magazyn = ? WHERE id = ?";
                $stmtUpdateStock = $link->prepare($sqlUpdateStock);
                $stmtUpdateStock->bind_param('ii', $newQuantityInStock, $productId);
                $stmtUpdateStock->execute();

                $link->commit();
            } else {
                throw new Exception('Produkt jest niedostępny.');
            }
        } else {
            throw new Exception('Produkt nie istnieje.');
        }
    } catch (Exception $e) {
        $link->rollback();

        $showOutOfStockPopup = true;
    }
}

// Obsługa dodawania do koszyka
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = intval($_POST['product_id']);
    $quantityToAdd = 1;
    addToCart($productId, $quantityToAdd, $link, $timeToExtend);

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Pobieranie produktów z bazy danych
$sql = "SELECT p.id, p.tytul, p.opis, (p.cena_netto * 1.23) AS cena_brutto, p.kategoria_id, k.nazwa AS kategoria_nazwa, p.zdjecie, p.ilosc_magazyn AS ilosc, p.status_dostepnosci
        FROM produkty p
        LEFT JOIN categories k ON p.kategoria_id = k.id
        WHERE p.status_dostepnosci = 1
        ORDER BY p.id ASC";
$result = $link->query($sql);

if (!$result) {
    die("Błąd zapytania: " . $link->error);
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista Produktów</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Popup styles */
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .popup.active {
            display: block;
        }

        .popup button {
            background-color: #ff6347;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <header class="shop-header">
        <nav class="shop-header-nav">
            <ul>
                <li><a href="index.php">Strona główna</a></li>
                <li><a href="cart.php">Koszyk</a></li>
                <li><a href="admin.php">Panel administratora</a></li>
                <li><a href="logout.php">Wyloguj się</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="shop-container">
            <?php if ($result->num_rows > 0): ?>
                <?php 
                while ($product = $result->fetch_assoc()) {
                    echo "<div id='product-" . htmlspecialchars($product['id']) . "' class='shop-product-box'>
                            <img src='" . htmlspecialchars($product['zdjecie']) . "' alt='" . htmlspecialchars($product['tytul']) . "' class='shop-product-image'>
                            <div class='shop-product-title'>" . htmlspecialchars($product['tytul']) . "</div>
                            <div class='shop-product-price'>" . number_format($product['cena_brutto'], 2, ',', ' ') . " PLN</div>
                            <div class='shop-product-description'>" . htmlspecialchars($product['opis']) . "</div>
                            <div class='shop-product-category'>Kategoria: " . htmlspecialchars($product['kategoria_nazwa']) . "</div>
                            <div class='shop-product-quantity'>Dostępna ilość: " . $product['ilosc'] . "</div>
                            <!-- Form do dodawania do koszyka -->
                            <form class='shop-add-to-cart-form' method='POST' action='#product-" . htmlspecialchars($product['id']) . "'>
                                <input type='hidden' name='product_id' value='" . htmlspecialchars($product['id']) . "'>
                                <button type='submit' class='shop-add-to-cart-btn'>Dodaj do koszyka</button>
                            </form>
                        </div>";
                }
                ?>
            <?php else: ?>
                <p>Brak produktów do wyświetlenia.</p>
            <?php endif; ?>
        </div>
    </main>
    <!-- Pop-up dla braku dostępnych produktów -->
    <?php if ($showOutOfStockPopup): ?>
        <div id="outOfStockPopup" class="popup active">
            <p>Przepraszamy, ten produkt jest obecnie niedostępny.</p>
            <button onclick="closePopup()">Zamknij</button>
        </div>
    <?php endif; ?>
    <script>
        function closePopup() {
            document.getElementById('outOfStockPopup').classList.remove('active');
        }

        // Zapisywanie pozycji przewinięcia w localStorage
        window.addEventListener('scroll', function () {
            localStorage.setItem('scrollPosition', window.scrollY);
        });

        // Przywracanie pozycji przewinięcia po załadowaniu strony
        window.addEventListener('load', function () {
            const scrollPosition = localStorage.getItem('scrollPosition');
            if (scrollPosition) {
                window.scrollTo(0, parseInt(scrollPosition, 10));
            }
        });
    </script>
</body>
</html>