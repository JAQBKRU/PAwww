<?php
class ZarzadzajProduktami {

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
    //       PobierzKategorie          //  
    // - - - - - - - - - - - - - - - - //  
    // Funkcja pobiera kategorie z bazy danych  
    // Parametr: brak  
    // Zwraca: tablica kategorii  
    // Sposób działania: Funkcja wykonuje zapytanie SQL, pobiera dane i zwraca je w postaci tablicy  
    public function PobierzKategorie() {
        // Zapytanie SQL do pobrania kategorii z bazy danych, sortowanie według kategorii nadrzędnej i nazwy
        $query = "SELECT id, nazwa, matka FROM categories ORDER BY matka, nazwa";
        $result = mysqli_query($this->link, $query);
    
        $kategorie = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $kategorie[] = $row;
        }
    
        return $kategorie;
    }

    // - - - - - - - - - - - - - - - - //  
    //      GenerujOpcjeKategorii      //  
    // - - - - - - - - - - - - - - - - //  
    // Funkcja generuje opcje HTML dla formularza wyboru kategorii  
    // Parametry:  
    //   $kategorie - tablica kategorii  
    //   $parent_id - ID kategorii nadrzędnej (domyślnie null)  
    //   $poziom - poziom hierarchii kategorii (domyślnie 0)  
    // Zwraca: ciąg znaków HTML z opcjami  
    // Sposób działania: Funkcja rekursywnie generuje opcje dla kategorii w zależności od poziomu hierarchii  
    private function GenerujOpcjeKategorii($kategorie, $parent_id = null, $poziom = 0) {
        $html = '';
        foreach ($kategorie as $kategoria) {
            // Sprawdzanie, czy kategoria jest dzieckiem kategorii nadrzędnej
            if (($kategoria['matka'] === $parent_id) || ($kategoria['matka'] == 0 && is_null($parent_id))) {
                // Tworzenie odpowiedniej ilości wcięć dla podkategorii
                $indent = str_repeat('&nbsp;&nbsp;', $poziom);

                $html .= "<option value='{$kategoria['id']}'>{$indent}{$kategoria['nazwa']}</option>";
    
                // Rekurencja dla podrzędnych kategorii
                $html .= $this->GenerujOpcjeKategorii($kategorie, $kategoria['id'], $poziom + 1);
            }
        }
    
        return $html;
    }

    // - - - - - - - - - - - - - - - - //  
    //          DodajProdukt           //  
    // - - - - - - - - - - - - - - - - //  
    // Funkcja dodaje nowy produkt do bazy danych  
    // Parametry:  
    //   - $tytul - tytuł produktu  
    //   - $opis - opis produktu  
    //   - $data_wygasniecia - data wygasnięcia produktu (opcjonalnie null)  
    //   - $cena_netto - cena netto produktu  
    //   - $podatek_vat - stawka VAT produktu  
    //   - $ilosc_magazyn - ilość produktu w magazynie  
    //   - $status_dostepnosci - status dostępności produktu (1 - dostępny, 0 - niedostępny)  
    //   - $kategoria - kategoria produktu  
    //   - $gabaryt_produktu - gabaryt produktu (np. Standard, Duży)  
    //   - $zdjecie - nazwa pliku zdjęcia produktu  
    // Zwraca: void  
    // Sposób działania: Funkcja dodaje nowy produkt do tabeli produktów w bazie danych  
    public function DodajProdukt($tytul, $opis, $data_wygasniecia, $cena_netto, $podatek_vat, $ilosc_magazyn, $status_dostepnosci, $kategoria, $gabaryt_produktu, $zdjecie) {
        // Zapobieganie SQL Injection
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

    // - - - - - - - - - - - - - - - - //  
    //     FormularzDodaniaProduktu    //  
    // - - - - - - - - - - - - - - - - //  
    // Funkcja wyświetla formularz dodania produktu i obsługuje przesyłanie formularza  
    // Parametr: brak  
    // Zwraca: void  
    // Sposób działania:  
    // - Obsługuje wyświetlanie formularza, 
    // - Przetwarza dane z formularza po jego wysłaniu, 
    // - Zapisuje przesłany obrazek i dodaje produkt do bazy danych  
    public function FormularzDodaniaProduktu() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
            $tytul = $_POST['title'];
            $opis = $_POST['description'];
            $cena_netto = $_POST['price'];
            $vat = $_POST['vat'];
            $ilosc = $_POST['quantity'];
            $status = $_POST['status'];
            $kategoria = $_POST['category'];
            $zdjecie = $_FILES['image']['name'];
    
            // Zapis pliku obrazu
            $upload_dir = 'uploads/';
            $unique_id = uniqid(); // Generowanie unikalnego identyfikatora
            $ext = pathinfo($zdjecie, PATHINFO_EXTENSION); // Pobieranie rozszerzenia pliku

            // Tworzenie nowej nazwy pliku
            $new_filename = $upload_dir . $unique_id . "_" . basename($zdjecie, '.' . $ext) . "." . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $new_filename)) {
                $this->DodajProdukt($tytul, $opis, null, $cena_netto, $vat, $ilosc, $status, $kategoria, 'Standard', $new_filename);
            } else {
                echo "<p class='error'>Nie udało się przesłać pliku obrazu.</p>";
            }
        }
        
        // Pobranie kategorii do formularza
        $kategorie = $this->PobierzKategorie();
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

    // - - - - - - - - - - - - - - - - //  
    //           UsunProdukt           //  
    // - - - - - - - - - - - - - - - - //  
    // Funkcja usuwa produkt z bazy danych  
    // Parametry:  
    //   - $id - ID produktu do usunięcia  
    // Zwraca: void  
    // Sposób działania: Funkcja wykonuje zapytanie SQL do usunięcia produktu z tabeli w bazie danych  
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

    // - - - - - - - - - - - - - - - - //  
    //          PokazProdukty          //  
    // - - - - - - - - - - - - - - - - //  
    // Funkcja wyświetla wszystkie produkty z bazy danych  
    // Parametr: brak  
    // Zwraca: void  
    // Sposób działania: Funkcja wykonuje zapytanie SQL do pobrania wszystkich produktów i wyświetlenie ich w tabeli HTML  
    public function PokazProdukty() {
        $qry = "SELECT produkty.*, categories.nazwa 
                FROM produkty
                LEFT JOIN categories ON categories.id = produkty.kategoria_id
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

    // - - - - - - - - - - - - - - - - //  
    //         formularzEdycji         //  
    // - - - - - - - - - - - - - - - - //  
    // Funkcja wyświetla formularz edycji produktu  
    // Parametry:  
    //   - $id - ID produktu do edycji  
    // Zwraca: void  
    // Sposób działania:  
    // - Pobiera dane produktu z bazy danych na podstawie ID, 
    // - Tworzy formularz HTML wypełniony istniejącymi danymi produktu  
    public function formularzEdycji($id) {
        $qry = "SELECT produkty.*, categories.nazwa AS kategoria_nazwa 
        FROM produkty 
        INNER JOIN categories 
        ON produkty.kategoria_id = categories.id 
        WHERE produkty.id = $id";

        $result = mysqli_query($this->link, $qry);
        $product = mysqli_fetch_assoc($result);

        if ($product) {
            $kategorie = $this->PobierzKategorie();
            $opcje = $this->GenerujOpcjeKategorii($kategorie);
            echo "<div class='form-container'>
                <h2>Edytuj produkt</h2>
                <form action='' method='POST' enctype='multipart/form-data'>
                    <div class='form-group'>
                        <label for='product-title'>Tytuł</label>
                        <input type='text' id='product-title' name='title' required placeholder='Wpisz tytuł produktu' value='". htmlspecialchars($product['tytul'], ENT_QUOTES) ."'>
                    </div>

                    <div class='form-group'>
                    <label for='product-category'>Kategoria</label>
                        <select id='product-category' name='category' <select id='product-category' name='category' required>
                            <option selected value='". htmlspecialchars($product['kategoria_id'], ENT_QUOTES) ."'>". htmlspecialchars($product['kategoria_nazwa'], ENT_QUOTES) ."</option>
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
                            <option value='1' " . (($product['status_dostepnosci'] == 1) ? 'selected' : '') . ">Dostępny</option>
                            <option value='0' " . (($product['status_dostepnosci'] == 0) ? 'selected' : '') . ">Niedostępny</option>
                        </select>
                    </div>

                    <div class='form-group'>
                        <label for='product-description'>Opis</label>
                        <textarea id='product-description' name='description' required placeholder='Wpisz opis produktu...'>". $product['opis']."</textarea>
                    </div>

                    <div class='form-group'>
                        <label for='product-modified-date'>Data modyfikacji</label>
                        <input type='text' id='product-modified-date' name='modified_date' value='" . date('Y-m-d H:i:s', strtotime($product['data_modyfikacji'])) . "' readonly>
                    </div>

                    <div class='form-group'>
                        <label for='product-expiry-date'>Data wygaśnięcia
                            <input type='datetime-local' id='product-expiry-date' name='expiry_date' value='". (isset($product['data_wygasniecia']) ? date('Y-m-d\TH:i', strtotime($product['data_wygasniecia'])) : "") ."'>
                        </label>
                    </div>

                    <div class='form-group'>
                        <label for='product-image'>Zdjęcie</label>
                        <input type='file' id='product-image' name='image' onchange='pokazPodglad(this);'>
                        <div class='current-image' id='current-image' style='float: left;'>
                            <img id='current-img' src='" . htmlspecialchars($product['zdjecie'], ENT_QUOTES) . "' alt='Aktualne zdjęcie produktu' style='max-height: 300px;'>
                        </div>
                    </div>

                    <div class='actions' style='clear: both; padding-top: 10px;'>
                        <button type='submit' name='submit' class='save'>Zapisz</button>
                        <button type='button' class='cancel' onclick='window.history.back();'>Anuluj</button>
                    </div>
                </form>
            </div>";

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
                $tytul = $_POST['title'];
                $opis = $_POST['description'];
                $data_wygasniecia = $_POST['expiry_date'];
                $cena_netto = $_POST['price'];
                $vat = $_POST['vat'];
                $ilosc = $_POST['quantity'];
                $status = $_POST['status'];
                $kategoria = $_POST['category'];
                $zdjecie = $product['zdjecie'];
    
                // Zapisanie nowego zdjęcia, jeśli zostało przesłane
                if (!empty($_FILES['image']['name'])) {
                    $upload_dir = 'uploads/';
                    $unique_id = uniqid(); // Generowanie unikalnej nazwy pliku
                    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                    $new_filename = $upload_dir . $unique_id . "_" . basename($_FILES['image']['name'], '.' . $ext) . "." . $ext;
    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $new_filename)) {
                        $zdjecie = $new_filename;
                    } else {
                        echo "<p class='error'>Nie udało się przesłać nowego pliku obrazu.</p>";
                    }
                }
    
                $this->EdytujProdukt($id, $tytul, $opis, $cena_netto, $vat, $ilosc, $status, $kategoria, $zdjecie, $data_wygasniecia);
            }
        } else {
            echo "Produkt o podanym ID nie został znaleziony.<br>";
        }
    }

    // - - - - - - - - - - - - - - - - //  
    //         EdytujProdukt           //  
    // - - - - - - - - - - - - - - - - //  
    // Funkcja edytuje dane produktu w bazie danych  
    // Parametry:  
    //   - $id - ID produktu do edytowania  
    //   - $tytul - zaktualizowany tytuł produktu  
    //   - $opis - zaktualizowany opis produktu  
    //   - $cena_netto - zaktualizowana cena netto produktu  
    //   - $podatek_vat - zaktualizowany podatek VAT  
    //   - $ilosc_magazyn - zaktualizowana ilość produktu w magazynie  
    //   - $status_dostepnosci - nowy status dostępności produktu (1 - dostępny, 0 - niedostępny)  
    //   - $kategoria - zaktualizowana kategoria produktu  
    //   - $zdjecie - ścieżka do nowego zdjęcia produktu  
    //   - $data_wygasniecia - zaktualizowana data wygasnięcia produktu  
    // Zwraca: void  
    // Sposób działania: Funkcja wykonuje zapytanie SQL do zaktualizowania danych produktu w bazie danych  
    public function EdytujProdukt($id, $tytul, $opis, $cena_netto, $podatek_vat, $ilosc_magazyn, $status_dostepnosci, $kategoria, $zdjecie, $data_wygasniecia) {
        if ($status_dostepnosci == 1) {
            $data_wygasniecia = NULL;
        }

        // Przygotowanie zapytania do aktualizacji produktu
        $qry = "UPDATE produkty SET tytul = ?, opis = ?, cena_netto = ?, podatek_vat = ?, ilosc_magazyn = ?, status_dostepnosci = ?, kategoria_id = ?, zdjecie = ?, data_wygasniecia = ? WHERE id = ?";
        
        $stmt = mysqli_prepare($this->link, $qry);
        mysqli_stmt_bind_param($stmt, "ssdiissssi", $tytul, $opis, $cena_netto, $podatek_vat, $ilosc_magazyn, $status_dostepnosci, $kategoria, $zdjecie, $data_wygasniecia, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "Produkt został zaktualizowany.";
        } else {
            echo "Błąd podczas aktualizacji produktu: " . mysqli_error($this->link);
        }
    }

    // - - - - - - - - - - - - - - - - //  
    //   SprawdzWygasniecieProduktow   //  
    // - - - - - - - - - - - - - - - - //  
    // Funkcja sprawdza, czy produkty mają przeterminowaną datę wygaśnięcia  
    // Parametry: brak  
    // Zwraca: void  
    // Sposób działania:  
    // - Funkcja wykonuje zapytanie do bazy, aby zmienić status produktów, których data wygaśnięcia minęła  
    public function SprawdzWygasniecieProduktow() {
        $dzis = date("Y-m-d H:i:s"); // Pobranie dzisiejszej daty
    
        // Zapytanie do zaktualizowania produktów, których data wygaśnięcia minęła
        $qry = "UPDATE produkty SET status_dostepnosci = 0 WHERE data_wygasniecia < '$dzis' AND status_dostepnosci = 1";
    
        if (mysqli_query($this->link, $qry)) {
            //echo "Produkty, których data wygaśnięcia minęła, zostały wyłączone.";
        } else {
            echo "Błąd podczas aktualizacji produktów: " . mysqli_error($this->link);
        }
    }
}
?>