<?php
class ZarzadzajKategoriami {

    private $link;

    public function __construct($link) {
        $this->link = $link;  // Przechowujemy połączenie z bazą danych
    }

    public function PokazKategorie1($parent_id = 0) {
        // Wywołanie funkcji rekurencyjnej pokazKategorie
        return $this->pokazKategorie($parent_id);
    }

    // Prywatna funkcja do wyświetlania kategorii i podkategorii
    public function pokazKategorie($parent_id = 0) {
        // Pobieramy kategorie, które mają 'matka' równe $parent_id
        $query = "SELECT * FROM categories WHERE matka = $parent_id ORDER BY id"; 
        $result = mysqli_query($this->link, $query);
    
        if ($result && mysqli_num_rows($result) > 0) {
    
            // Przechodzimy przez wszystkie kategorie
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<li class='subcategory-item'>";
                echo $row['nazwa']; // Wyświetlamy nazwę kategorii
    
                // Przyciski do edycji i usuwania
                echo " <a href='?action=edit_category&id={$row['id']}' class='edit-category btn btn-edit'>Edytuj</a>";
                echo " <a href='?action=delete_category&id={$row['id']}' class='delete-category btn btn-del' onclick='return confirm(\"Czy na pewno chcesz usunąć tę kategorię?\")'>Usuń</a>";
    
                // Sprawdzamy, czy są podkategorie
                $subcategory_query = "SELECT * FROM categories WHERE matka = {$row['id']}";
                $subcategory_result = mysqli_query($this->link, $subcategory_query);
    
                if ($subcategory_result && mysqli_num_rows($subcategory_result) > 0) {
                    // Jeśli są podkategorie, dodajemy listę i przycisk "Rozwiń"
                    echo "<span class='expand-button btn'>Rozwiń</span>";
                    echo "<ul class='subcategory-list' style='display: none;'>"; // Domyślnie ukryte podkategorie
                    $this->pokazKategorie($row['id']); // Rekurencyjne wywołanie dla podkategorii
                    echo "</ul>";
                }
    
                echo "</li>"; // Kończymy bieżący element listy
            }
    
        }
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
    
        // Generowanie listy rozwijanej z kategoriami
        echo "<label>Matka: <select name='matka'>";
        echo "<option value='0'>Brak (kategoria główna)</option>";
        
        // Pobierz wszystkie istniejące kategorie
        $query = "SELECT id, nazwa FROM categories ORDER BY nazwa ASC";
        $result = mysqli_query($this->link, $query);
    
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='{$row['id']}'>" . htmlspecialchars($row['nazwa']) . "</option>";
            }
        }
    
        echo "</select></label>";
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