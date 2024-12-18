<?php
class ZarzadzajProduktami {

    private $link;

    public function __construct($db_connection) {
        $this->link = $db_connection; // Przechowujemy połączenie z bazą danych
    }

    public function PobierzKategorie() {
        $query = "SELECT id, nazwa, matka FROM categories ORDER BY matka, nazwa";
        $result = mysqli_query($this->link, $query);
    
        $kategorie = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $kategorie[] = $row;
        }
    
        return $kategorie;
    }

    private function GenerujOpcjeKategorii($kategorie, $parent_id = null, $poziom = 0) {
        $html = '';
        foreach ($kategorie as $kategoria) {
            if (($kategoria['matka'] === $parent_id) || ($kategoria['matka'] == 0 && is_null($parent_id))) {
                $indent = str_repeat('&nbsp;&nbsp;', $poziom); // Dodawanie wcięcia dla podkategorii
                $html .= "<option value='{$kategoria['id']}'>{$indent}{$kategoria['nazwa']}</option>";
    
                // Rekurencja dla podrzędnych kategorii
                $html .= $this->GenerujOpcjeKategorii($kategorie, $kategoria['id'], $poziom + 1);
            }
        }
    
        return $html;
    }
    

    // Dodawanie nowego produktu
    public function DodajProdukt($tytul, $opis, $data_wygasniecia, $cena_netto, $podatek_vat, $ilosc_magazyn, $status_dostepnosci, $kategoria, $gabaryt_produktu, $zdjecie) {
        $tytul = mysqli_real_escape_string($this->link, $tytul);
        $opis = mysqli_real_escape_string($this->link, $opis);
        $data_wygasniecia = mysqli_real_escape_string($this->link, $data_wygasniecia);
        $cena_netto = (float)$cena_netto;
        $podatek_vat = (int)$podatek_vat;
        $ilosc_magazyn = (int)$ilosc_magazyn;
        $status_dostepnosci = (int)$status_dostepnosci;
        $kategoria = mysqli_real_escape_string($this->link, $kategoria);
        $gabaryt_produktu = mysqli_real_escape_string($this->link, $gabaryt_produktu);
        $zdjecie = mysqli_real_escape_string($this->link, $zdjecie);

        $qry = "INSERT INTO produkty (tytul, opis, data_wygasniecia, cena_netto, podatek_vat, ilosc_magazyn, status_dostepnosci, kategoria, gabaryt_produktu, zdjecie) 
                VALUES ('$tytul', '$opis', '$data_wygasniecia', $cena_netto, $podatek_vat, $ilosc_magazyn, $status_dostepnosci, '$kategoria', '$gabaryt_produktu', '$zdjecie')";
        if (mysqli_query($this->link, $qry)) {
            echo "Produkt został dodany pomyślnie!<br>";
        } else {
            echo "Błąd podczas dodawania produktu: " . mysqli_error($this->link);
        }
    }

    public function FormularzDodaniaProduktu() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
            // Pobierz dane z formularza
            $tytul = $_POST['title'];
            $opis = $_POST['description'];
            $cena_netto = $_POST['price'];
            $vat = $_POST['vat'];
            $ilosc = $_POST['quantity'];
            $status = $_POST['status'];
            $kategoria = $_POST['category'];
            $zdjecie = $_FILES['image']['name'];
    
            // Zapisz plik obrazu
            $upload_dir = 'uploads/';
            $unique_id = uniqid(); // Generuje unikalny identyfikator

            $ext = pathinfo($zdjecie, PATHINFO_EXTENSION); // Pobiera rozszerzenie pliku

            // Tworzymy nową nazwę pliku
            $new_filename = $upload_dir . $unique_id . "_" . basename($zdjecie, '.' . $ext) . "." . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $new_filename)) {
                // Dodaj produkt do bazy danych
                $this->DodajProdukt($tytul, $opis, null, $cena_netto, $vat, $ilosc, $status, $kategoria, 'Standard', $new_filename);
            } else {
                echo "<p class='error'>Nie udało się przesłać pliku obrazu.</p>";
            }
        }
        
        $kategorie = $this->PobierzKategorie(); // Pobranie kategorii z bazy
        $opcje = $this->GenerujOpcjeKategorii($kategorie);
        echo "
        <h2>Dodaj nowy produkt</h2>
        <form action='' method='POST' enctype='multipart/form-data'>
            <div class='form-group'>
                <label for='product-title'>Tytuł</label>
                <input type='text' id='product-title' name='title' required placeholder='Wpisz tytuł produktu'>
            </div>

            <div class='form-group'>
            <label for='product-category'>Kategoria</label>
                <select id='product-category' name='category' required>
                    <option value=''>Wybierz kategorię</option>
                    ".$opcje."
                </select>
            </div>

            <div class='form-group'>
                <label for='product-price'>Cena Netto</label>
                <input type='number' id='product-price' name='price' required placeholder='Wpisz cenę netto' step='0.01'>
            </div>

            <div class='form-group'>
                <label for='product-vat'>VAT (%)</label>
                <input type='number' id='product-vat' name='vat' required placeholder='Wpisz VAT' step='0.01'>
            </div>

            <div class='form-group'>
                <label for='product-quantity'>Ilość</label>
                <input type='number' id='product-quantity' name='quantity' required placeholder='Wpisz ilość'>
            </div>

            <div class='form-group'>
                <label for='product-status'>Status</label>
                <select id='product-status' name='status' required>
                    <option value='1'>Dostępny</option>
                    <option value='0'>Niedostępny</option>
                </select>
            </div>

            <div class='form-group'>
                <label for='product-description'>Opis</label>
                <textarea id='product-description' name='description' required placeholder='Wpisz opis produktu...'></textarea>
            </div>

            <div class='form-group'>
                <label for='product-image'>Zdjęcie</label>
                <input type='file' id='product-image' name='image'>
            </div>

            <div class='actions'>
                <button type='submit' name='submit' class='save'>Dodaj</button>
                <button type='button' class='cancel' onclick='window.history.back();'>Anuluj</button>
            </div>
        </form>
        ";
    }

    // Usuwanie produktu
    public function UsunProdukt($id) {
        $id = (int)$id;
        $qry = "DELETE FROM produkty WHERE id = $id LIMIT 1";
        if (mysqli_query($this->link, $qry)) {
            echo "Produkt został usunięty pomyślnie!<br>";
        } else {
            echo "Błąd podczas usuwania produktu: " . mysqli_error($this->link);
        }

        header("Location: " . $_SERVER['PHP_SELF'] . "?action=products");
        exit();
    }

    // Pokazywanie wszystkich produktów
    public function PokazProdukty() {
        $qry = "SELECT produkty.*, categories.nazwa 
                FROM produkty
                LEFT JOIN categories ON categories.id = produkty.kategoria
                ORDER BY produkty.id;";
        $result = mysqli_query($this->link, $qry);

        if (mysqli_num_rows($result) > 0) {
            echo "<table class='product-table'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tytuł</th>
                            <th>Kategoria</th>
                            <th>Opis</th>
                            <th>Data modyfikacji</th>
                            <th>Data wygasniecia</th>
                            <th>Cena Netto</th>
                            <th>VAT</th>
                            <th>Ilość</th>
                            <th>Status</th>
                            <th>Akcje</th>
                        </tr>
                    </thead>
                    <tbody>";

            while ($row = mysqli_fetch_assoc($result)) {
                $kategoria = $row['nazwa'] ? $row['nazwa'] : 'Brak kategorii';
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['tytul']}</td>
                        <td>{$kategoria}</td>
                        <td>{$row['opis']}</td>
                        <td>{$row['data_modyfikacji']}</td>
                        <td>{$row['data_wygasniecia']}</td>
                        <td>{$row['cena_netto']} zł</td>
                        <td>{$row['podatek_vat']}%</td>
                        <td>{$row['ilosc_magazyn']}</td>
                        <td>" . ($row['status_dostepnosci'] ? 'Dostępny' : 'Niedostępny') . "</td>
                        <td class='actions'>
                            <a href='?action=products&edit_product_id={$row['id']}' class='edit actions'>Edytuj</a>
                            <a href='?action=products&delete_product_id={$row['id']}' class='delete actions'>Usuń</a>
                        </td>
                    </tr>";
            }

            echo "</tbody></table>";
        } else {
            echo "<br/>Brak produktów do wyświetlenia.<br>";
        }
    }

    // Formularz edycji produktu
    public function formularzEdycji($id) {
        // Zapytanie do bazy, aby pobrać dane produktu
        $qry = "SELECT produkty.*, categories.nazwa AS kategoria_nazwa 
        FROM produkty 
        INNER JOIN categories 
        ON produkty.kategoria = categories.id 
        WHERE produkty.id = $id";

        $result = mysqli_query($this->link, $qry);
        $product = mysqli_fetch_assoc($result);

        if ($product) {
            // Rozpoczęcie formularza edycji
            $kategorie = $this->PobierzKategorie(); // Pobranie kategorii z bazy
            $opcje = $this->GenerujOpcjeKategorii($kategorie);
            echo "<div class='form-container'>
                <h2>Edytuj produkt</h2>
                <form action='' method='POST' enctype='multipart/form-data'>
                    <div class='form-group'>
                        <label for='product-title'>Tytuł</label>
                        <input type='text' id='product-title' name='title' required placeholder='Wpisz tytuł produktu' value=". $product['tytul'].">
                    </div>

                    <div class='form-group'>
                    <label for='product-category'>Kategoria</label>
                        <select id='product-category' name='category' required>
                            <option selected value='".$product['kategoria']."'>". $product['kategoria_nazwa']."</option>
                            ".$opcje."
                        </select>
                    </div>

                    <div class='form-group'>
                        <label for='product-price'>Cena Netto</label>
                        <input type='number' id='product-price' name='price' required placeholder='Wpisz cenę netto' step='0.01' value=". $product['cena_netto'].">
                    </div>

                    <div class='form-group'>
                        <label for='product-vat'>VAT (%)</label>
                        <input type='number' id='product-vat' name='vat' required placeholder='Wpisz VAT' step='0.01' value=". $product['podatek_vat'].">
                    </div>

                    <div class='form-group'>
                        <label for='product-quantity'>Ilość</label>
                        <input type='number' id='product-quantity' name='quantity' required placeholder='Wpisz ilość' value=". $product['ilosc_magazyn'].">
                    </div>

                    <div class='form-group'>
                        <label for='product-status'>Status</label>
                        <select id='product-status' name='status' required>
                            <option value='1'>Dostępny</option>
                            <option value='0'>Niedostępny</option>
                        </select>
                    </div>

                    <div class='form-group'>
                        <label for='product-description'>Opis</label>
                        <textarea id='product-description' name='description' required placeholder='Wpisz opis produktu...'>". $product['opis']."</textarea>
                    </div>

                    <div class='form-group'>
                        <label for='product-image'>Zdjęcie</label>
                        <input type='file' id='product-image' name='image'>
                    </div>

                    <div class='actions'>
                        <button type='submit' name='submit' class='save'>Dodaj</button>
                        <button type='button' class='cancel' onclick='window.history.back();'>Anuluj</button>
                    </div>
                </form>
            </div>";

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
                // Pobierz dane z formularza
                $tytul = $_POST['title'];
                $opis = $_POST['description'];
                $cena_netto = $_POST['price'];
                $vat = $_POST['vat'];
                $ilosc = $_POST['quantity'];
                $status = $_POST['status'];
                $kategoria = $_POST['category'];
                $zdjecie = $_FILES['image']['name'];

                $this->EdytujProdukt($id, $tytul, $opis, $cena_netto, $vat, $ilosc, $status, $kategoria, "test_file");
            }
            
        } else {
            echo "Produkt o podanym ID nie został znaleziony.<br>";
        }
    }
    // Funkcja edytowania produktu
    public function EdytujProdukt($id, $tytul, $opis, $cena_netto, $podatek_vat, $ilosc_magazyn, $status_dostepnosci, $kategoria, $zdjecie) {
        // Przygotowanie zapytania do aktualizacji danych produktu w bazie
        $qry = "UPDATE produkty SET tytul = ?, opis = ?, cena_netto = ?, podatek_vat = ?, ilosc_magazyn = ?, status_dostepnosci = ?, kategoria = ?, zdjecie = ? WHERE id = ?";
        
        // Przygotowanie zapytania
        $stmt = mysqli_prepare($this->link, $qry);
        
        // Bindowanie parametrów
        mysqli_stmt_bind_param($stmt, "ssdiisssi", $tytul, $opis, $cena_netto, $podatek_vat, $ilosc_magazyn, $status_dostepnosci, $kategoria, $zdjecie, $id);
        
        // Wykonanie zapytania
        if (mysqli_stmt_execute($stmt)) {
            echo "Produkt został zaktualizowany.";
        } else {
            echo "Błąd podczas aktualizacji produktu: " . mysqli_error($this->link);
        }
    }
}
?>