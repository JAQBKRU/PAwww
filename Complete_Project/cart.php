<?php
session_start();
require 'cfg.php';

$timeToAdd = '7 days'; // +20 seconds | +5 minutes

// Sprawdzamy, czy użytkownik jest zalogowany
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
} else {
    header("Location: login.php");
    exit;
}

$koszyk_produkty = [];
$suma_calkowita = 0;

// Zapytanie SQL, które pobiera produkty z koszyka użytkownika i oblicza ich ceny
$sql = "SELECT p.id, p.tytul, (p.cena_netto * 1.23) AS cena_brutto, SUM(k.quantity) AS ilosc, k.reserved_until
        FROM produkty p
        JOIN koszyk k ON p.id = k.product_id
        WHERE k.user_id = $userId
        GROUP BY p.id, p.tytul";
$result = $link->query($sql);

// Iterujemy przez wyniki i obliczamy sumę całkowitą koszyka
while ($product = $result->fetch_assoc()) {
    $product['cena_calkowita'] = $product['ilosc'] * $product['cena_brutto'];
    $suma_calkowita += $product['cena_calkowita'];
    $koszyk_produkty[] = $product;
}

// Obsługa zmiany ilości produktów w koszyku
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    $link->begin_transaction();

    try {
        // Iterujemy po wszystkich produktach w koszyku i aktualizujemy ilości
        foreach ($_POST['quantity'] as $productId => $newQuantity) {
            $newQuantity = intval($newQuantity);

            // Pobieramy dostępność produktu w magazynie
            $sql = "SELECT ilosc_magazyn FROM produkty WHERE id = $productId";
            $result = $link->query($sql);
            $product = $result->fetch_assoc();

            if ($product) {
                $availableQuantity = $product['ilosc_magazyn']; // Ilość dostępna w magazynie

                $sqlCurrentQuantity = "SELECT quantity FROM koszyk WHERE user_id = $userId AND product_id = $productId";
                $resultCurrentQuantity = $link->query($sqlCurrentQuantity);
                $currentQuantity = $resultCurrentQuantity->fetch_assoc()['quantity'];

                // Obsługujemy zwiększenie ilości w koszyku
                if ($newQuantity > 0) {
                    if ($newQuantity > $currentQuantity) {
                        // Zwiększenie ilości w koszyku
                        $quantityToReduce = $newQuantity - $currentQuantity;
                        if ($quantityToReduce <= $availableQuantity) {
                            $sqlUpdateStock = "UPDATE produkty SET ilosc_magazyn = ilosc_magazyn - $quantityToReduce WHERE id = $productId";
                            $link->query($sqlUpdateStock);
                        } else {
                            throw new Exception('Brak wystarczającej ilości w magazynie.');
                        }
                    } elseif ($newQuantity < $currentQuantity) {
                        // Zmniejszenie ilości w koszyku
                        $quantityToRestore = $currentQuantity - $newQuantity;
                        $sqlUpdateStock = "UPDATE produkty SET ilosc_magazyn = ilosc_magazyn + $quantityToRestore WHERE id = $productId";
                        $link->query($sqlUpdateStock);
                    }

                    // Aktualizujemy ilość w koszyku
                    $sqlUpdateCart = "
                        UPDATE koszyk 
                        SET quantity = ?, reserved_until = ? 
                        WHERE user_id = ? AND product_id = ?
                    ";
                    $reservedUntil = date('Y-m-d H:i:s', strtotime($timeToAdd));
                    $stmtUpdateCart = $link->prepare($sqlUpdateCart);
                    $stmtUpdateCart->bind_param('ssii', $newQuantity, $reservedUntil, $_SESSION['user_id'], $productId);
                    $stmtUpdateCart->execute();
                } elseif ($newQuantity <= 0) {
                    $sqlDeleteCart = "DELETE FROM koszyk WHERE user_id = $userId AND product_id = $productId";
                    $link->query($sqlDeleteCart);

                    // Przywracamy ilość produktu do magazynu
                    $sqlUpdateStock = "UPDATE produkty SET ilosc_magazyn = ilosc_magazyn + $currentQuantity WHERE id = $productId";
                    $link->query($sqlUpdateStock);
                }
            }
        }

        $link->commit();

        header("Location: cart.php");
        exit;
    } catch (Exception $e) {
        $link->rollback();
        echo "Błąd: " . $e->getMessage();
    }
}

// Obsługa usuwania produktu z koszyka (po kliknięciu w link "Usuń")
if (isset($_GET['remove'])) {
    $removeProductId = intval($_GET['remove']);

    // Pobieramy ilość produktu przed usunięciem
    $sql = "SELECT quantity FROM koszyk WHERE user_id = $userId AND product_id = $removeProductId";
    $result = $link->query($sql);
    $product = $result->fetch_assoc();
    $quantityInCart = $product['quantity'];

    // Usuwamy produkt z koszyka
    $sqlDelete = "DELETE FROM koszyk WHERE user_id = $userId AND product_id = $removeProductId";
    $link->query($sqlDelete);

    // Przywracamy ilość produktu do magazynu
    $sqlUpdateStock = "UPDATE produkty SET ilosc_magazyn = ilosc_magazyn + $quantityInCart WHERE id = $removeProductId";
    $link->query($sqlUpdateStock);

    header('Location: cart.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koszyk</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="cart-header">
        <nav class="cart-header-nav">
            <ul class="cart-header-nav-list">
                <li class="cart-header-nav-item"><a href="index.php">Strona główna</a></li>
                <li class="cart-header-nav-item"><a href="products.php">Produkty</a></li>
                <li class="cart-header-nav-item"><a href="logout.php">Wyloguj się</a></li>
            </ul>
        </nav>
    </header>
    <main class="cart-main">
        <h1 class="cart-title">Twój Koszyk</h1>
        <div class="cart-container">
            <?php if (!empty($koszyk_produkty)): ?>
                <form action="" method="POST">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th class="cart-table-header">Produkt</th>
                                <th class="cart-table-header">Cena jednostkowa</th>
                                <th class="cart-table-header">Ilość</th>
                                <th class="cart-table-header">Razem</th>
                                <!--<th class="cart-table-header">Czas rezerwacji</th>-->
                                <th class="cart-table-header">Usuń</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($koszyk_produkty as $product): ?>
                                <tr class="cart-table-row">
                                    <td class="cart-table-cell"><?php echo htmlspecialchars($product['tytul']); ?></td>
                                    <td class="cart-table-cell"><?php echo number_format($product['cena_brutto'], 2, ',', ' ') . " PLN"; ?></td>
                                    <td class="cart-table-cell">
                                        <input type="number" name="quantity[<?php echo $product['id']; ?>]" value="<?php echo htmlspecialchars($product['ilosc']); ?>" min="1" required>
                                    </td>
                                    <td class="cart-table-cell"><?php echo number_format($product['cena_calkowita'], 2, ',', ' ') . " PLN"; ?></td>
                                    <!--<td class="cart-table-cell">
                                        <?php 
                                        // Wyświetlanie czasu rezerwacji
                                        //echo "Zarezerwowane do: " . htmlspecialchars($product['reserved_until']);
                                        ?>
                                    </td>-->
                                    <td class="cart-table-cell">
                                        <a href="cart.php?remove=<?php echo $product['id']; ?>" class="remove-btn">Usuń</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="cart-summary">
                        <span class="cart-summary-text">Suma całkowita: </span>
                        <span class="cart-summary-value"><?php echo number_format($suma_calkowita, 2, ',', ' ') . " PLN"; ?></span>
                    </div>
                    <button type="submit" name="update_quantity" class="update-btn">Zaktualizuj koszyk</button>
                </form>
            <?php else: ?>
                <p class="cart-empty">Twój koszyk jest pusty.</p>
            <?php endif; ?>
        </div>
        <a href="products.php" class="cart-continue-shopping">Kontynuuj zakupy</a>
    </main>
</body>
</html>