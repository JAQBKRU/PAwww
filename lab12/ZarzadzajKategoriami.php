<?php
class ZarzadzajKategoriami {

    private $link;

    // - - - - - - - - - - - - - - - - //
    //        Konstruktor klasy        //
    // - - - - - - - - - - - - - - - - //
    // Konstruktor klasy  
    // Parametr: $db_connection - obiekt połączenia z bazą danych  
    // Zwraca: void  
    // Sposób działania: Funkcja inicjalizuje połączenie z bazą danych
    public function __construct($db_connection) {
        $this->link = $db_connection;
    }

    // - - - - - - - - - - - - - - - - //
    //      Wyświetlanie kategorii     //
    // - - - - - - - - - - - - - - - - //
    // Funkcja rekurencyjna do wyświetlania kategorii i podkategorii
    // Parametry: $parent_id - ID kategorii nadrzędnej (domyślnie 0)
    // Zwraca: Brak (echo HTML)
    // Sposób działania: Pobiera kategorie, wyświetla je wraz z podkategoriami
    public function pokazKategorie($parent_id = 0) {
        $query = "SELECT * FROM categories WHERE matka = $parent_id ORDER BY id"; 
        $result = mysqli_query($this->link, $query);
    
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<li class='subcategory-item'>";
                echo htmlspecialchars($row['nazwa']);
                echo " <a href='?action=edit_category&id={$row['id']}' class='edit-category btn btn-edit'>Edytuj</a>";
                echo " <a href='?action=delete_category&id={$row['id']}' class='delete-category btn btn-del' onclick='return confirm(\"Czy na pewno chcesz usunąć tę kategorię?\")'>Usuń</a>";
    
                // Sprawdzamy, czy są podkategorie
                $subcategory_query = "SELECT * FROM categories WHERE matka = {$row['id']}";
                $subcategory_result = mysqli_query($this->link, $subcategory_query);
    
                if ($subcategory_result && mysqli_num_rows($subcategory_result) > 0) {
                    echo "<span class='expand-button btn'>Rozwiń</span>";
                    echo "<ul class='subcategory-list' style='display: none;'>";
                    $this->pokazKategorie($row['id']);
                    echo "</ul>";
                }
                echo "</li>";
            }
        }
    }
    
    // - - - - - - - - - - - - - - - - //
    //       Dodawanie kategorii       //
    // - - - - - - - - - - - - - - - - //
    // Dodaje nową kategorię do bazy danych
    // Parametry: $nazwa - nazwa kategorii, $matka - ID kategorii nadrzędnej
    // Zwraca: Brak (echo komunikat i przekierowanie)
    public function DodajKategorie($nazwa, $matka = 0) {
        $nazwa = mysqli_real_escape_string($this->link, $nazwa);
        $matka = (int)$matka;

        $qry = "INSERT INTO categories (nazwa, matka) VALUES ('$nazwa', $matka)";
        if (mysqli_query($this->link, $qry)) {
            echo "Kategoria została dodana pomyślnie!<br>";
        } else {
            echo "Błąd podczas dodawania kategorii: " . mysqli_error($this->link);
        }

        header("Location: " . $_SERVER['PHP_SELF'] . "?action=categories");
        exit();
    }

    // - - - - - - - - - - - - - - - - //
    //      Edytowanie kategorii       //
    // - - - - - - - - - - - - - - - - //
    // Edytuje istniejącą kategorię w bazie danych
    // Parametry: $id - ID kategorii, $nazwa - nowa nazwa, $matka - ID nadrzędnej kategorii
    // Zwraca: Brak (echo komunikat i przekierowanie)
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

        header("Location: " . $_SERVER['PHP_SELF'] . "?action=categories");
        exit();
    }

    // - - - - - - - - - - - - - - - - //
    //        Usuwanie kategorii       //
    // - - - - - - - - - - - - - - - - //
    // Usuwa kategorię, jeśli nie ma podkategorii
    // Parametry: $id - ID kategorii do usunięcia
    // Zwraca: Brak (echo komunikat i przekierowanie)
    public function UsunKategorie($id) {
        $id = (int)$id;

        // Sprawdzenie, czy kategoria ma podkategorie
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

    // - - - - - - - - - - - - - - - - //
    //     Obsługa akcji użytkownika   //
    // - - - - - - - - - - - - - - - - //
    // Obsługuje akcje edycji oraz usuwania kategorii
    // Parametry: brak
    // Zwraca: brak
    // Sposób działania: Analizuje dane przesłane metodą POST, aby wywołać
    // odpowiednie metody obsługi (edycja/usunięcie kategorii).
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

    // - - - - - - - - - - - - - - - - //
    //    Formularz edycji kategorii   //
    // - - - - - - - - - - - - - - - - //
    // Generuje formularz umożliwiający edycję kategorii.
    // Parametry: $id - ID edytowanej kategorii
    // Zwraca: brak (formularz jest wyświetlany bezpośrednio)
    // Sposób działania: Pobiera szczegóły kategorii z bazy danych
    // i generuje HTML formularza z wypełnionymi polami.
    public function formularzEdycji($id) {
        $qry = "SELECT * FROM categories WHERE id = $id";
        $result = mysqli_query($this->link, $qry);
        $category = mysqli_fetch_assoc($result);

        if ($category) {
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
                $matka = isset($_POST['matka']) ? (int)$_POST['matka'] : 0;

                $this->EdytujKategorie($_POST['id'], $nazwa, $matka);
            }
        } else {
            echo "Kategoria nie została znaleziona.<br>";
        }
    }

    // - - - - - - - - - - - - - - - - //
    // Formularz dodania nowej kategorii //
    // - - - - - - - - - - - - - - - - //
    // Wyświetla formularz umożliwiający dodanie nowej kategorii.
    // Parametry: brak
    // Zwraca: brak (formularz jest wyświetlany bezpośrednio)
    // Sposób działania: Generuje listę rozwijaną istniejących kategorii
    // w celu przypisania nadrzędnej kategorii dla nowej.
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
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nazwa'])) {
            $this->DodajKategorie($_POST['nazwa'], $_POST['matka']);
        }
    }
}
?>