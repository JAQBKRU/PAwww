<?php
require_once 'contact.php';
session_start();
require_once 'ZarzadzajProduktami.php';
require_once 'ZarzadzajKategoriami.php';
require 'cfg.php';

// Sprawdzamy, czy użytkownik jest zalogowany
// Jeśli nie, przekierowujemy na stronę logowania.
if (!isset($_SESSION['zalogowany']) || $_SESSION['zalogowany'] !== true) {
    header("Location: login.php");
    exit();
}

$zarzadzajProdukty = new ZarzadzajProduktami($link);
$zarzadzajKategorie = new ZarzadzajKategoriami($link);

// - - - - - - - - - - - - - - - - //  
//      handleActionProducts       //  
// - - - - - - - - - - - - - - - - //  
// Funkcja do obsługi działań związanych z produktami
// Parametr: $zarzadzajProdukty - instancja klasy ZarzadzajProduktami
// Zwraca: void
// Sposób działania: Funkcja zarządza akcjami wyświetlania, edycji lub dodawania produktów.
function handleActionProducts($zarzadzajProdukty) {
    $zarzadzajProdukty->SprawdzWygasniecieProduktow(); // Sprawdzamy, czy produkty nie wygasły
    
    if (isset($_GET['action']) && $_GET['action'] == 'products') {
        echo '<a href=?action=products/add_new_product class="add-product">Dodaj Nowy Produkt</a>';
        return $zarzadzajProdukty->PokazProdukty();
    } elseif (isset($_GET['edit_product_id'])) {
        return $zarzadzajProdukty->formularzEdycji($_GET['edit_product_id']);
    }

    return "<p>Wybierz akcję w menu, aby zarządzać panelem.</p>";
}

// - - - - - - - - - - - - - - - - //  
//      handleActionCategories     //  
// - - - - - - - - - - - - - - - - //  
// Funkcja do obsługi działań związanych z kategoriami
// Parametr: $zarzadzajKategorie - instancja klasy ZarzadzajKategoriami
// Zwraca: void
// Sposób działania: Funkcja zarządza akcjami wyświetlania, edycji lub dodawania kategorii.
function handleActionCategories($zarzadzajKategorie) {
    global $link;
    if (isset($_GET['action']) && $_GET['action'] == 'categories') {
        echo '<a href=?action=categories/add_new_category class="add-category">Dodaj Nowa Kategorie</a>';
        return $zarzadzajKategorie->PokazKategorie(0, $link);
    } elseif (isset($_GET['edit_category'])) {
        return $zarzadzajKategorie->formularzEdycji($_GET['edit_category_id']);
    } elseif (isset($_GET['action']) && $_GET['action'] == 'edit_category' && isset($_GET['id'])) {
        $zarzadzajKategorie->formularzEdycji($_GET['id']);
    } 

    return "<p>Wybierz akcję w menu, aby zarządzać kategoriami.</p>";
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style2.css">
    <title>Panel Administratora</title>
</head>
<body>
<div class="sidebar">
    <h2>Panel Administratora</h2>
    <div class="admin-menu">
        <a href="index.php" class="menu-item home-link">
            <span>Powrót na stronę główną</span>
        </a>
        <a href="?action=categories" class="menu-item">
            <span>Zarządzaj Kategoriami</span>
        </a>
        <a href="?action=add_subpage" class="menu-item">
            <span>Zarządzaj Podstronami</span>
        </a>
        <a href="?action=contact" class="menu-item">
            <span>Zarządzaj Kontaktem</span>
        </a>
        <a href="?action=products" class="menu-item">
            <span>Zarządzaj Produktami</span>
        </a>
    </div>
    <form method="POST" action="index.php">
        <button type="submit" class="logout-btn" name="logout">Wyloguj się</button>
    </form>
</div>
<div class="content">
        <?php
            if (isset($_GET['action'])) {
                // Obsługuje akcję produktów
                if ($_GET['action'] == 'products') {
                    if (!isset($_GET['edit_product_id'])) {
                        echo handleActionProducts($zarzadzajProdukty);
                    } elseif (isset($_GET['edit_product_id'])) {
                        $product_id = (int) $_GET['edit_product_id'];
                        $zarzadzajProdukty->formularzEdycji($product_id);
                    } elseif (isset($_GET['delete_product_id'])) {
                        $product_id = (int) $_GET['delete_product_id'];
                        $zarzadzajProdukty->UsunProdukt($product_id);
                    }
                } 
                // Obsługuje dodanie nowego produktu
                elseif ($_GET['action'] == 'products/add_new_product') {
                    echo $zarzadzajProdukty->FormularzDodaniaProduktu();
                } 
                // Obsługuje akcję kontaktu
                elseif ($_GET['action'] == 'contact') {
                    echo PokazKontakt();
                } 
                // Obsługuje dodanie nowej podstrony
                elseif ($_GET['action'] == 'add_subpage') {
                    echo DodajNowaPodstrone();
                } 
                // Obsługuje usuwanie podstrony
                elseif ($_GET['action'] == 'subpage' && isset($_GET['delete_subpage_id'])) {
                    $subpage_id = (int) $_GET['delete_subpage_id'];
                    UsunPodstrone($subpage_id);
                } 
                // Obsługuje edycję podstrony
                elseif ($_GET['action'] == 'subpage' && isset($_GET['edit_subpage_id'])) {
                    $subpage_id = (int) $_GET['edit_subpage_id'];
                    EdytujPodstrone($subpage_id);
                } 
                // Obsługuje akcję kategorii
                elseif ($_GET['action'] == 'categories') {
                    echo handleActionCategories($zarzadzajKategorie);
                } 
                // Obsługuje dodanie nowej kategorii
                elseif ($_GET['action'] == 'categories/add_new_category') {
                    echo $zarzadzajKategorie->formularzDodaniaKategorii();
                } 
                // Obsługuje edycję kategorii
                elseif ($_GET['action'] == 'edit_category') {
                    echo $zarzadzajKategorie->formularzEdycji($_GET['id']);
                } 
                // Obsługuje usunięcie kategorii
                elseif ($_GET['action'] == 'delete_category') {
                    echo $zarzadzajKategorie->UsunKategorie($_GET['id']);
                } 
            }else{
                echo "<p>Wybierz akcję w menu, aby zarządzać produktami lub kategoriami.</p>";
            }
        ?>
</div>
<footer>
    <p>&copy; 2024 Panel Administratora. Wszelkie prawa zastrzeżone.</p>
</footer>
<script>
    // - - - - - - - - - - - - - - - - //  
    //       expandButtonsHandler      //  
    // - - - - - - - - - - - - - - - - //  
    // Funkcja do obsługi rozwijania i zwijania list podkategorii
    // Parametr: brak
    // Zwraca: void
    // Sposób działania: Funkcja przypisuje wszystkim przyciskom z klasą 'expand-button' zdarzenie kliknięcia,
    // które pozwala na rozwijanie i zwijanie elementów list podkategorii ('subcategory-list').
    // Jeśli element nie istnieje, generowane jest ostrzeżenie w konsoli.
    const expandButtons = document.querySelectorAll('.expand-button');
    expandButtons.forEach((button) => {
        button.addEventListener('click', function () {
            const subcategoryList = this.parentElement.querySelector('.subcategory-list');
            
            if (subcategoryList) {
                if (subcategoryList.style.display === 'none' || subcategoryList.style.display === '') {
                    subcategoryList.style.display = 'block';
                    this.textContent = 'Zwiń';
                } else {
                    subcategoryList.style.display = 'none';
                    this.textContent = 'Rozwiń';
                }
            } else {
                console.warn('Element .subcategory-list nie został znaleziony.');
            }
        });
    });
    
    // - - - - - - - - - - - - - - - - //  
    //          pokazPodglad           //  
    // - - - - - - - - - - - - - - - - //  
    // Funkcja do wyświetlania podglądu obrazu
    // Parametr: input - element typu HTMLInputElement, zawierający dane pliku
    // Zwraca: void
    // Sposób działania: Funkcja wyświetla podgląd wybranego pliku obrazka w elemencie
    // o identyfikatorze 'current-img'. Jeśli obraz zostanie wybrany, wczytuje jego zawartość 
    // przy użyciu FileReader i ustawia źródło obrazu. Jeśli plik nie zostanie wybrany,
    // podgląd zostanie ukryty.
    function pokazPodglad(input) {
        const podglad = document.getElementById('current-img');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                podglad.src = e.target.result;
                podglad.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            podglad.style.display = 'none';
        }
    }
</script>
</body>
</html>
<?php
// - - - - - - - - - - - - - - - - //  
//          ListaPodstron          //  
// - - - - - - - - - - - - - - - - //  
// Funkcja do wyświetlania listy podstron
// Parametr: brak
// Zwraca: void
// Sposób działania: Funkcja generuje listę podstron, która może być wyświetlana na stronie głównej lub w menu.
function ListaPodstron(){
    global $link;
    $qry = "SELECT * FROM page_list";
    $rsl = mysqli_query($link, $qry) or die(mysqli_error($link));

    echo '<div class="subpage-list" style="width: 33.33%; float: left;">';
    echo '<h3>Lista Podstron</h3>';

    while($row = mysqli_fetch_array($rsl)){
        echo '
        <div class="subpage-item">
            <span><strong>' . htmlspecialchars($row['id']) . '</strong>: ' . htmlspecialchars($row['page_title']) . '</span>
            <div class="subpage-actions">
                <a href="?action=subpage&delete_subpage_id=' . $row['id'] . '" class="btn btn-del">Usuń</a>
                <a href="?action=subpage&edit_subpage_id=' . $row['id'] . '" class="btn btn-edit">Edytuj</a>
            </div>
        </div>';
    }
    echo '</div>';
}

// - - - - - - - - - - - - - - - - //  
//          EdytujPodstrone        //  
// - - - - - - - - - - - - - - - - //  
// Funkcja do edytowania podstrony
// Parametr: $id - identyfikator podstrony
// Zwraca: void
// Sposób działania: Funkcja umożliwia edytowanie treści podstrony na podstawie identyfikatora.
function EdytujPodstrone($id) {
    global $link;
    $id = (int)$id;

    $qry = "SELECT * FROM page_list WHERE id = $id LIMIT 1";
    $result = mysqli_query($link, $qry);
    $page = mysqli_fetch_assoc($result);

    if (!$page) {
        echo "Nie znaleziono strony.";
        return;
    }

    echo '
    <div class="subpage-form">
        <h3>Edytuj Podstronę</h3>
        <form method="POST" action="">
            <label for="title">Tytuł:</label>
            <input type="text" id="title" name="title" value="' . htmlspecialchars($page['page_title']) . '">

            <label for="content">Treść:</label>
            <textarea id="content" name="content">' . htmlspecialchars($page['page_content']) . '</textarea>

            <label for="status">Aktywna:</label>
            <input type="checkbox" id="status" name="status" ' . ($page['status'] ? 'checked' : '') . '>

            <button type="submit" name="save_changes" class="btn-save">Zapisz</button>
        </form>
    </div>';

    if (isset($_POST['save_changes'])) {
        $new_title = mysqli_real_escape_string($link, $_POST['title']);
        $new_content = mysqli_real_escape_string($link, $_POST['content']);
        $new_status = isset($_POST['status']) ? 1 : 0;

        $update_qry = "UPDATE page_list SET page_title='$new_title', page_content='$new_content', status=$new_status WHERE id=$id";
        
        if (mysqli_query($link, $update_qry)) {
            echo "Zmiany zostały zapisane pomyślnie! Następuje przekierowanie...";
            echo "
                <script>
                    setTimeout(function() {
                        window.location.href = 'admin.php?action=add_subpage';
                    }, 3000); // 2000ms = 2 sekundy opóźnienia
                </script>
            ";
        } else {
            echo "Błąd podczas zapisywania zmian: " . mysqli_error($link);
        }
    }
}

// - - - - - - - - - - - - - - - - //  
//        DodajNowaPodstrone       //  
// - - - - - - - - - - - - - - - - //  
// Funkcja do dodawania nowej podstrony
// Parametr: brak
// Zwraca: void
// Sposób działania: Funkcja umożliwia dodanie nowej podstrony do systemu, przyjmując dane formularza.
function DodajNowaPodstrone(){
    $new_title = isset($_SESSION['title']) ? $_SESSION['title'] : '';
    $new_content = isset($_SESSION['content']) ? $_SESSION['content'] : '';
    $new_status = isset($_SESSION['status']) ? $_SESSION['status'] : 0;

    echo '
    <div class="subpage-form" style="width: 66.66%; float: left; padding-right: 20px;">
        <form method="POST" action="">
            <h3>Formularz dodawania nowej podstrony</h3>
            <label for="title">Tytuł nowej strony:</label><br/>
            <input type="text" id="title" name="title" value="' . $new_title . '"><br/><br/>
            
            <label for="content">Treść nowej strony:</label><br/>
            <textarea id="content" name="content" rows="10" cols="50">' . $new_content . '</textarea><br/><br/>
            
            <label for="status">Strona aktywna:</label>
            <input type="checkbox" id="status" name="status" ' . (isset($_SESSION['status']) && $_SESSION['status'] ? "checked" : "") . '><br/><br/>
            
            <input class="btn btn-primary" type="submit" name="add_subpage" value="Dodaj nową podstronę">
        </form>
    </div>
    ';
    
    if (isset($_POST['add_subpage'])) {
        $_SESSION['title'] = isset($_POST['title']) ? $_POST['title'] : '';
        $_SESSION['content'] = isset($_POST['content']) ? $_POST['content'] : '';
        $_SESSION['status'] = isset($_POST['status']) ? 1 : 0;

        if (!empty($_SESSION['title']) && !empty($_SESSION['content'])) {
            $new_title = $_SESSION['title'];
            $new_content = $_SESSION['content'];
            $new_status = $_SESSION['status'];

            global $link;
            $qry = "INSERT INTO page_list (page_title, page_content, status) VALUES ('$new_title', '$new_content', $new_status)";
            mysqli_query($link, $qry);

            unset($_SESSION['title']);
            unset($_SESSION['content']);
            unset($_SESSION['status']);

            header("Location: admin.php?action=add_subpage");
            exit();
        }
    }
    echo ListaPodstron();

    if (isset($_GET['success']) && $_GET['success'] == 1) {
        echo '<p>Podstrona została dodana pomyślnie!</p>';
    }
}

// - - - - - - - - - - - - - - - - //  
//          UsunPodstrone          //  
// - - - - - - - - - - - - - - - - //  
// Funkcja do usuwania podstrony
// Parametr: $id - identyfikator podstrony
// Zwraca: bool - wynik (true/false)
// Sposób działania: Funkcja usuwa podstronę o podanym identyfikatorze z systemu (np. z bazy danych).
function UsunPodstrone($id){
    global $link;
    $id = (int)$id;

    if (isset($id) && is_numeric($id)) {
        $qry = "DELETE FROM page_list WHERE id = $id LIMIT 1";
        echo "Usuwanie ID: $id<br/>";
        if (mysqli_query($link, $qry)) {
            header("Location: admin.php?action=add_subpage");
            exit();
        }
        else echo "Błąd podczas usuwania podstrony: " . mysqli_error($link);
    }
    else echo "Niepoprawne ID podstrony.";
}
?>