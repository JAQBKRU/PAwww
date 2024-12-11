<?php
class ZarzadzajKategoriami {

    private $link;

    public function __construct($db_connection) {
        $this->link = $db_connection;  // Przechowujemy połączenie z bazą danych
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

    // Pokazywanie wszystkich kategorii w formie drzewa
    public function PokazKategorie() {
        $qry = "SELECT * FROM categories ORDER BY matka, id"; 
        $result = mysqli_query($this->link, $qry);

        if (mysqli_num_rows($result) > 0) {
            echo "<ul>"; // Zaczynamy listę główną
            $this->wyswietlKategorie(mysqli_fetch_all($result, MYSQLI_ASSOC)); 
            echo "</ul>";
        } else {
            echo "Brak kategorii do wyświetlenia.<br>";
        }
    }

    // Rekurencyjne wyświetlanie kategorii i podkategorii
    private function wyswietlKategorie($categories, $parent = 0, $level = 0) {
        foreach ($categories as $row) {
            if ($row['matka'] == $parent) {
                echo "<li>";
                //echo "ID: " . $row['id'] . " - Nazwa: " . $row['nazwa'];
                
                // Linki do edycji i usuwania
                echo "<p>".$row['id'].": ".$row['nazwa']."
                <a href='?delete_category_id=".$row['id']."' class='btn_del'>DELETE</a>
                <a href='?edit_category_id=".$row['id']."' class='btn_edit'>EDIT</a>
                </p>";
    
    
                // Rekursywnie wywołujemy funkcję dla podkategorii
                echo "<ul>"; // Nowa lista dla podkategorii
                $this->wyswietlKategorie($categories, $row['id'], $level + 1);
                echo "</ul>"; // Kończymy listę podkategorii
                echo "</li>";
            }
        }
    }

    // Obsługa akcji edycji i usuwania w tym samym pliku
    public function obslugaAkcji() {
        // Sprawdzamy, czy wykonujemy operację edycji lub usuwania
        if (isset($_POST['action'])) {
            $action = $_POST['action'];
            if ($action == 'edit' && isset($_POST['id']) && isset($_POST['nazwa'])) {
                $this->EdytujKategorie($_POST['id'], $_POST['nazwa']);
            } elseif ($action == 'delete' && isset($_POST['id'])) {
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
            echo "<div style='clear: both;'><form method='POST'>
            <h3>Formularz do zmiany nazwy kategorii</h3>";
            echo "<input type='hidden' name='action' value='edit'>";
            echo "<input type='hidden' name='id' value='" . $category['id'] . "'>";
            echo "<label>Nazwa: <input type='text' name='nazwa' value='" . htmlspecialchars($category['nazwa']) . "' required></label>";
            echo "<button type='submit'>Zapisz zmiany</button>";
            echo "</form></div>";
        } else {
            echo "Kategoria nie została znaleziona.<br>";
        }
    }
}
?>