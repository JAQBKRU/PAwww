<?php

require_once 'contact.php';

session_start();
require 'cfg.php';

echo '<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel administracyjny</title>
    <link rel="stylesheet" href="./css/style.css"> <!-- Ładowanie zewnętrznego CSS -->
</head>';

if(isset($_SESSION['zalogowany']) && $_SESSION['zalogowany'] == true){
    echo "Witaj w panelu administracyjnym!<br/><br/>";

    // Renderowanie menu
    renderMenu();
    $_SESSION['zalogowany'] = true;
}else{
    $_SESSION['zalogowany'] = false;
    FormularzLogowania();// Wyświetlenie formularza logowania

    // Przetwarzanie danych logowania
    if(isset($_POST['login_email']) && isset($_POST['login_pass'])){
        $form_login = $_POST['login_email'];
        $form_pass = $_POST['login_pass'];
        
        if($form_login == $login && $form_pass == $pass){
            $_SESSION['zalogowany'] = true;
            echo "Zalogowano pomyslnie!<br/><br/>";

            renderMenu();// Renderowanie menu po udanym logowaniu
        }else{
            echo "<p style='color: red';>Niepoprawny login lub haslo.</p>";
            echo FormularzLogowania();// Ponowne wyświetlenie formularza logowania
            exit();
        }
    }else{
        echo FormularzLogowania();// Wyświetlenie formularza logowania
        exit();
    }
}

// - - - - - - - - - - - - - - - - //  
//        FormularzLogowania       //  
// - - - - - - - - - - - - - - - - // 
// Funkcja generująca formularz logowania
// Parametr: brak
// Zwraca: void
// Sposób działania: Funkcja generuje formularz logowania z polami na nazwisko i hasło.
function FormularzLogowania(){
    $wynik = '
    <div class="logowanie">
        <h1 class="heading">Panel CMS:</h1>
        <div class="logowanie">
            <form method="POST" name="LoginForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'">
                <table class="logowanie">
                    <tr>
                        <td class="log4_t">[email]</td>
                        <td>
                            <input type="text" name="login_email" class="logowanie"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="log4_t">[haslo]</td>
                        <td>
                            <input type="password" name="login_pass" class="logowanie"/>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <input type="submit" name="x1_submit" class="logowanie" value="Zaloguj"/>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    ';

    return $wynik;
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
            echo "Zmiany zostały zapisane pomyślnie!";
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
            
            // Przekierowanie po dodaniu nowej podstrony
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "<p style='color: red;'>Wszystkie pola są wymagane.</p>";
        }
        header("Location: ".$_SERVER['PHP_SELF']);
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
    $id = (int)$id;

    // Zapytanie SQL do usunięcia podstrony
    if (isset($id) && is_numeric($id)) {
        $qry = "DELETE FROM page_list WHERE id = $id LIMIT 1";

        if (mysqli_query($link, $qry)) {
            // Przekierowanie po usunięciu podstrony
            header("Location: ".$_SERVER['PHP_SELF']);
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
function renderMenu() {
    echo ListaPodstron();
    echo DodajNowaPodstrone();// Wyświetlanie formularza dodawania nowej podstrony
    echo PokazKontakt();

    // Edytowanie podstrony po ID
    if (isset($_GET['edit_id'])) {
        $edit_id = (int)$_GET['edit_id'];// Zabezpieczenie przed atakami
        EdytujPodstrone($edit_id);
    } 

    // Usuwanie podstrony po ID
    if (isset($_GET['delete_id'])) {
        $delete_id = (int)$_GET['delete_id'];// Zabezpieczenie przed atakami
        UsunPodstrone($delete_id);
    }
}