<?php
class ZarzadzajKategoriami {

    private $link;

    public function __construct($link) {
        $this->link = $link;  // Przechowujemy połączenie z bazą danych
    }

    // Dodawanie nowej kategorii
    public function DodajKategorie($nazwa, $matka = 0) {
        $nazwa = mysqli_real_escape_string($this->link, $nazwa);
        $matka = (int)$matka; // Kategoria główna ma wartość 0

        $qry = "INSERT INTO categories (nazwa, matka) VALUES ('$nazwa', $matka)";
        if (mysqli_query($this->link, $qry)) {
            echo "Kategoria została dodana pomyślnie!<br>";
        } else {
            echo "Błąd podczas dodawania kategorii: " . mysqli_error($this->link);
        }

        header("Location: " . $_SERVER['PHP_SELF'] . "?action=categories");
        exit();
    }

    // Edytowanie istniejącej kategorii
public function EdytujKategorie($id, $nazwa, $matka = 0) {
    $id = (int)$id;
    $nazwa = mysqli_real_escape_string($this->link, $nazwa);
    $matka = (int)$matka;

    $qry = "UPDATE categories SET nazwa = '$nazwa' WHERE id = $id";
    if (mysqli_query($this->link, $qry)) {
        echo "Kategoria została edytowana pomyślnie!<br>";
    } else {
        echo "Błąd podczas edytowania kategorii: " . mysqli_error($this->link);
    }

    // Przekierowanie po zapisaniu
    header("Location: " . $_SERVER['PHP_SELF'] . "?action=categories");
    exit();
}

    // Usuwanie kategorii
    public function UsunKategorie($id) {
        $id = (int)$id;

        $qry_check = "SELECT * FROM categories WHERE matka = $id LIMIT 1";
        $result_check = mysqli_query($this->link, $qry_check);

        if (mysqli_num_rows($result_check) > 0) {
            echo "Kategoria ma podkategorie, nie można jej usunąć.<br>";
            echo "
                <script>
                    setTimeout(function() {
                        window.location.href = 'admin.php?action=categories';
                    }, 3000); // 2000ms = 2 sekundy opóźnienia
                </script>
            ";
            return;
        }

        $qry = "DELETE FROM categories WHERE id = $id LIMIT 1";
        if (mysqli_query($this->link, $qry)) {
            echo "Kategoria została usunięta pomyślnie!<br>";
        } else {
            echo "Błąd podczas usuwania kategorii: " . mysqli_error($this->link);
        }
        header("Location: " . $_SERVER['PHP_SELF'] . "?action=categories");
        exit();
    }


    // Pobieranie kategorii z bazy danych
// Pobieranie kategorii z bazy danych
public function PokazKategorie() {
    $output = '';
    
    // Pobieranie wszystkich kategorii z bazy
    $query = "SELECT * FROM categories ORDER BY matka, id"; 
    $result = mysqli_query($this->link, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $categories = [];
        
        // Grupa kategorii w tablicy, kategoryzujemy je po ID
        while ($category = mysqli_fetch_assoc($result)) {
            // Jeśli kategoria nie ma matki (jest kategorią główną), dodaj ją do listy
            if ($category['matka'] == 0) {
                $categories[$category['id']] = [
                    'nazwa' => $category['nazwa'],
                    'subcategories' => []  // pusta tablica podkategorii
                ];
            } else {
                // Jeśli to podkategoria, dodaj ją do odpowiedniej kategorii
                // Dodajemy całą tablicę z id i nazwą podkategorii
                $categories[$category['matka']]['subcategories'][] = [
                    'id' => $category['id'],  // Zapisywanie ID podkategorii
                    'nazwa' => $category['nazwa']
                ];
            }
        }

        // Wyświetlanie kategorii
        foreach ($categories as $categoryId => $categoryData) {
            $categoryName = $categoryData['nazwa'];
            $output .= "<li class='category-item'>";
            $output .= $categoryName;

            // Link do edycji kategorii
            $output .= " <a href='?action=edit_category&id={$categoryId}' class='edit-category'>Edytuj</a>";
            // Link do usunięcia kategorii
            $output .= " <a href='?action=delete_category&id={$categoryId}' class='delete-category' onclick='return confirm(\"Czy na pewno chcesz usunąć tę kategorię?\")'>Usuń</a>";

            // Sprawdzenie, czy są podkategorie
            if (!empty($categoryData['subcategories'])) {
                // Rozwiń dla podkategorii
                $output .= "<span class='expand-button'>Rozwiń</span>";
                $output .= "<ul class='subcategory-list'>";
                
                // Iterowanie po podkategoriach
                foreach ($categoryData['subcategories'] as $subcategory) {
                    $subcategoryId = $subcategory['id'];  // Pobieranie ID podkategorii
                    $subcategoryName = $subcategory['nazwa'];  // Pobieranie nazwy
                    // Wyświetlanie podkategorii
                    $output .= "<li class='subcategory-item'>$subcategoryName 
                                <a href='?action=edit_category&id={$subcategoryId}' class='edit-category'>Edytuj</a>
                                <a href='?action=delete_category&id={$subcategoryId}' class='delete-category' onclick='return confirm(\"Czy na pewno chcesz usunąć tę podkategorię?\")'>Usuń</a></li>";
                }
                
                $output .= "</ul>";
            }
            
            $output .= "</li>";
        }
    } else {
        $output .= "<p>Brak kategorii w bazie danych.</p>";
    }

    return "<ul class='category-list'>$output</ul>";
}


    // Obsługa akcji edycji i usuwania
public function obslugaAkcji() {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Obsługa edycji kategorii
        if ($action == 'edit' && isset($_POST['id']) && isset($_POST['nazwa'])) {
            $this->EdytujKategorie($_POST['id'], $_POST['nazwa']);
        }
        // Obsługa usuwania kategorii
        elseif ($action == 'delete' && isset($_POST['id'])) {
            $this->UsunKategorie($_POST['id']);
        }
    }
}

    // Formularz edycji kategorii
public function formularzEdycji($id) {
    $qry = "SELECT * FROM categories WHERE id = $id";
    $result = mysqli_query($this->link, $qry);
    $category = mysqli_fetch_assoc($result);

    if ($category) {
        // Formularz edycji
        echo "<div style='clear: both;'>
                <form method='POST'>
                    <h3>Formularz do zmiany nazwy kategorii</h3>
                    <input type='hidden' name='action' value='edit'>
                    <input type='hidden' name='id' value='" . $category['id'] . "'>
                    <label>Nazwa: 
                        <input type='text' name='nazwa' value='" . htmlspecialchars($category['nazwa']) . "' required>
                    </label>
                    <button type='submit'>Zapisz zmiany</button>
                </form>
              </div>";
        
        // Obsługa formularza po wysłaniu
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'edit') {
            $nazwa = $_POST['nazwa'];
            $matka = isset($_POST['matka']) ? (int)$_POST['matka'] : 0;  // Domyślnie 0, jeśli nie podano

            $this->EdytujKategorie($_POST['id'], $nazwa, $matka);  // Wywołanie metody do edycji
        }
    } else {
        echo "Kategoria nie została znaleziona.<br>";
    }
}

    public function formularzDodaniaKategorii() {
        echo "<div style='clear: both;'><form method='POST'>
        <h3>Formularz dodania nowej kategorii</h3>";
        echo "<input type='hidden' name='action' value='add'>";
        echo "<label>Nazwa: <input type='text' name='nazwa' required></label>";
        echo "<label>Matka: <input type='number' name='matka' value='0'></label>";
        echo "<button type='submit'>Dodaj kategorię</button>";
        echo "</form></div>";

        // Sprawdzamy, czy formularz został wysłany metodą POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nazwa'])) {
            // Wywołanie funkcji dodającej kategorię
                $this->DodajKategorie($_POST['nazwa'], $_POST['matka']);
            }
        }
}
?>