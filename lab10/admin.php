<?php
require_once 'contact.php';
session_start();
require_once 'ZarzadzajKategoriami.php';
require 'cfg.php';

// Tworzenie obiektu klasy
$zarzadzajKategoriami = new ZarzadzajKategoriami($link);
// Wywołanie metody obsługi akcji w momencie ładowania strony
$zarzadzajKategoriami->obslugaAkcji(); // Obsługuje edycję i usuwanie

echo '<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel administracyjny</title>
    <link rel="stylesheet" href="./css/style.css"> <!-- Ładowanie zewnętrznego CSS -->
</head>';

if (isset($_SESSION['zalogowany']) && $_SESSION['zalogowany'] == true) {
    // Renderowanie menu
    renderMenu();
} else {
    $_SESSION['zalogowany'] = false;
    FormularzLogowania(); // Wyświetlenie formularza logowania
}

// - - - - - - - - - - - - - - - - //  
//        FormularzLogowania       //  
// - - - - - - - - - - - - - - - - // 
// Funkcja generująca formularz logowania
// Parametr: brak
// Zwraca: void
// Sposób działania: Funkcja generuje formularz logowania z polami na nazwisko i hasło.
function FormularzLogowania()
{
    echo '
    <div class="logowanie">
        <h1 class="heading">Panel CMS:</h1>
        <form method="POST" name="LoginForm" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
            <table class="logowanie">
                <tr>
                    <td class="log4_t">[email]</td>
                    <td><input type="text" name="login_email" class="logowanie"/></td>
                </tr>
                <tr>
                    <td class="log4_t">[haslo]</td>
                    <td><input type="password" name="login_pass" class="logowanie"/></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><input type="submit" name="x1_submit" class="logowanie" value="Zaloguj"/></td>
                </tr>
            </table>
        </form>
    </div>';
    
    // Przetwarzanie danych logowania
    if (isset($_POST['login_email']) && isset($_POST['login_pass'])) {
        $form_login = $_POST['login_email'];
        $form_pass = $_POST['login_pass'];
        global $login;
        global $pass;
        if ($form_login == $login && $form_pass == $pass) {
            $_SESSION['zalogowany'] = true;
            header("Location: admin.php");
            renderMenu(); // Renderowanie menu po udanym logowaniu
        } else {
            echo "<p style='color: red;'>Niepoprawny login lub hasło.</p>";
            FormularzLogowania(); // Ponowne wyświetlenie formularza logowania
        }
    }
}

// - - - - - - - - - - - - - - - - //  
//          ListaPodstron          //  
// - - - - - - - - - - - - - - - - //  
// Funkcja do wyświetlania listy podstron
// Parametr: brak
// Zwraca: void
// Sposób działania: Funkcja generuje listę podstron, która może być wyświetlana na stronie głównej lub w menu.
function ListaPodstron(){
    // Zapytanie SQL do pobrania wszystkich podstron
    $qry = "SELECT * FROM page_list";
    global $link;
    if (!$link) {
        die("Brak połączenia z bazą danych.");
    }
    $rsl = mysqli_query($link, $qry) or die(mysqli_error($link));

    while($row = mysqli_fetch_array($rsl)){
        echo "<p>".$row['id'].": ".$row['page_title']."
        <a href='?delete_id=".$row['id']."' class='btn_del'>DELETE</a>
        <a href='?edit_id=".$row['id']."' class='btn_edit'>EDIT</a>
        </p>";
    }
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

    // Zabezpieczenie przed SQL Injection
    $id = (int)$id;

    // Zapytanie SQL do pobrania danych podstrony
    $qry = "SELECT * FROM page_list WHERE id = $id LIMIT 1";// LIMIT 1 zapewnia, że tylko jedna strona zostanie pobrana
    $result = mysqli_query($link, $qry);
    $page = mysqli_fetch_assoc($result);

    if (!$page) {
        echo "Nie znaleziono strony o podanym ID.";
        return;
    }

    // Formularz edycji podstrony
    echo '
    <div class="subpage_form">
        <form method="POST" action="" style="width: 80%;">
            <h3>Formularz edycji podstrony</h3>
            <label for="title">Tytuł strony:</label><br/>
            <input type="text" id="title" name="title" value="'.$page['page_title'].'"><br/><br/>
            
            <label for="content">Treść strony:</label><br/>
            <textarea id="content" name="content" rows="10" cols="50">' . $page['page_content'].'</textarea><br/><br/>
            
            <label for="status">Strona aktywna:</label>
            <input type="checkbox" id="status" name="status" ' . ($page['status'] ? 'checked' : '') . '><br/><br/>
            
            <input type="submit" name="save_changes" value="Zapisz zmiany">
        </form>
    </div>
    ';

    // Przetwarzanie formularza zapisu zmian
    if (isset($_POST['save_changes'])) {
        // Zabezpieczenie danych przed wstrzyknięciem
        $new_title = mysqli_real_escape_string($link, $_POST['title']);
        $new_content = mysqli_real_escape_string($link, $_POST['content']);
        $new_status = isset($_POST['status']) ? 1 : 0;

        // Zapytanie SQL do aktualizacji danych
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
    <div class="subpage_form">
        <form method="POST" action="" style="width: 80%;">
            <h3>Formularz dodawania nowej podstrony</h3>
            <label for="title">Tytuł nowej strony:</label><br/>
            <input type="text" id="title" name="title" value='.$new_title.'><br/><br/>
            
            <label for="content">Treść nowej strony:</label><br/>
            <textarea id="content" name="content" rows="10" cols="50">'.$new_content.'</textarea><br/><br/>
            
            <label for="status">Strona aktywna:</label>
            <input type="checkbox" id="status" name="status"'. (isset($_SESSION['status']) && $_SESSION['status'] ? "checked" : "") .'><br/><br/>
            
            <input type="submit" name="add_subpage" value="Dodaj nowa podstrone">
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
            
            // Zapytanie SQL do dodania nowej podstrony
            $qry = "INSERT INTO page_list (page_title, page_content, status) VALUES ('$new_title', '$new_content', $new_status)";
            mysqli_query($link, $qry);

            unset($_SESSION['title']);
            unset($_SESSION['content']);
            unset($_SESSION['status']);
            echo "tak";
            // Przekierowanie po dodaniu nowej podstrony
            header("Location: admin.php?action=add_subpage");
            exit();
        } else {
            echo "<p style='color: red;'>Wszystkie pola są wymagane.</p>";
        }
        header("Location: admin.php?action=add_subpage");
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
    
    // Zabezpieczenie przed SQL Injection
    $id = (int)$id; // Zapewnienie, że $id jest liczbą całkowitą

    // Zapytanie SQL do usunięcia podstrony
    if (isset($id) && is_numeric($id)) {
        $qry = "DELETE FROM page_list WHERE id = $id LIMIT 1";
        echo "Usuwanie ID: $id<br/>";
        if (mysqli_query($link, $qry)) {
            // Przekierowanie po usunięciu podstrony
            header("Location: admin.php?action=add_subpage"); // Przekierowanie na stronę z listą podstron
            exit();
        } else {
            echo "Błąd podczas usuwania podstrony: " . mysqli_error($link);
        }
    } else {
        echo "Niepoprawne ID podstrony.";
    }
}


// - - - - - - - - - - - - - - - - //  
//          renderMenu            //  
// - - - - - - - - - - - - - - - - //  
// Funkcja do renderowania menu
// Parametr: brak
// Zwraca: void
// Sposób działania: Funkcja generuje i wyświetla menu na stronie, zawierające linki do głównych sekcji witryny.
function renderMenu()
{
    echo '<nav>
            <h1 style="text-align: center;">Witaj w panelu administratora</h1>
            <ul>
                <li><a href="?action=categories">Kategorie</a></li>
                <li><a href="?action=add_subpage">Dodaj podstronę</a></li>
                <li><a href="?action=contact">Pokaz kontakt</a></li>
            </ul>
        </nav>';
}

// Sprawdzanie akcji
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    switch ($action) {
        case 'categories':
            // Obsługuje kategorie
            echo "<p>Lista dostępnych kategorii:</p>";
            $zarzadzajKategoriami->PokazKategorie();
            echo "<div class='xxx'>";

            if (isset($_GET['action']) && $_GET['action'] == 'categories' || true) {
                
                // Wyświetlanie kategorii
                global $zarzadzajKategoriami;
        
                // Dodawanie kategorii
                if (isset($_POST['add_category'])) {
                    $nazwa = $_POST['nazwa'];
                    $matka = isset($_POST['matka']) ? $_POST['matka'] : 0;
                    $zarzadzajKategoriami->DodajKategorie($nazwa, $matka);
                }
        
                // Formularz dodawania kategorii
                echo '
                    <form method="POST">
                        <h3>Dodaj nową kategorię</h3>
                        <label for="nazwa">Nazwa kategorii:</label>
                        <input type="text" id="nazwa" name="nazwa" required><br>
                        
                        <label for="matka">Kategoria nadrzędna:</label>
                        <select name="matka">
                            <option value="0">Brak (kategoria główna)</option>';
                            
                            // Pobieranie kategorii głównych z bazy danych
                            global $link;  // Zakładając, że masz połączenie do bazy danych w zmiennej $link
                            $qry = "SELECT id, nazwa FROM categories WHERE matka = 0";  // Zakładając, że "parent_id" = 0 oznacza kategorię główną
                            $result = mysqli_query($link, $qry);
                            
                            if ($result) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<option value="' . $row['id'] . '">' . $row['nazwa'] . '</option>';
                                }
                            } else {
                                echo "<option value='0'>Brak kategorii głównych</option>";
                            }

                        echo '</select><br>
                        
                        <input type="submit" name="add_category" value="Dodaj kategorię">
                    </form>';
            }
            break;
        
        case 'add_subpage':
            ListaPodstron();
            DodajNowaPodstrone();
            break;
        
        case 'contact':
            echo PokazKontakt();
            break;
        
        case 'edit':
            if (isset($_GET['edit_id'])) {
                EdytujPodstrone($_GET['edit_id']);
            }
            break;
    }
}

// Sprawdzanie obecności parametru delete_id i usuwanie podstrony
if (isset($_GET['delete_id'])) {
    // Usuwanie podstrony na podstawie ID
    UsunPodstrone($_GET['delete_id']);
}

if (isset($_GET['edit_id'])) {
    // Edytowanie podstrony na podstawie ID
    EdytujPodstrone($_GET['edit_id']);
}

if (isset($_GET['edit_category_id'])) {
    // Edytowanie kategorii na podstawie ID
    $zarzadzajKategoriami->formularzEdycji($_GET['edit_category_id']);
}

if (isset($_GET['delete_category_id'])) {
    // Usuwanie kategorii na podstawie ID
    $zarzadzajKategoriami->UsunKategorie($_GET['delete_category_id']);
}



echo '</html>';