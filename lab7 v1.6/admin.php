<?php

session_start();
require 'cfg.php';
echo '<style>';
//include './css/style.css';
echo '</style>';

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

    renderMenu();
    $_SESSION['zalogowany'] = true;
}else{
    $_SESSION['zalogowany'] = false;
    FormularzLogowania();

    if(isset($_POST['login_email']) && isset($_POST['login_pass'])){
        $form_login = $_POST['login_email'];
        $form_pass = $_POST['login_pass'];
        
        if($form_login == $login && $form_pass == $pass){
            $_SESSION['zalogowany'] = true;
            echo "Zalogowano pomyslnie!<br/><br/>";

            renderMenu();
        }else{
            echo "<p style='color: red';>Niepoprawny login lub haslo.</p>";
            echo FormularzLogowania();
            exit();
        }
    }else{
        echo FormularzLogowania();
        exit();
    }
}

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

function ListaPodstron(){
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

function EdytujPodstrone($id) {
    global $link;

    $qry = "SELECT * FROM page_list WHERE id = $id LIMIT 1";
    $result = mysqli_query($link, $qry);
    $page = mysqli_fetch_assoc($result);

    if (!$page) {
        echo "Nie znaleziono strony o podanym ID.";
        return;
    }

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

    if (isset($_POST['save_changes'])) {
        $new_title = $_POST['title'];
        $new_content = $_POST['content'];
        $new_status = isset($_POST['status']) ? 1 : 0;

        $update_qry = "UPDATE page_list SET page_title='$new_title', page_content='$new_content', status=$new_status WHERE id=$id";
        
        if (mysqli_query($link, $update_qry)) {
            echo "Zmiany zostały zapisane pomyślnie!";
        } else {
            echo "Błąd podczas zapisywania zmian: " . mysqli_error($link);
        }
    }
}

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

            $qry = "INSERT INTO page_list (page_title, page_content, status) VALUES ('$new_title', '$new_content', $new_status)";
            mysqli_query($link, $qry);

            unset($_SESSION['title']);
            unset($_SESSION['content']);
            unset($_SESSION['status']);

            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "<p style='color: red;'>Wszystkie pola są wymagane.</p>";
        }
        header("Location: ".$_SERVER['PHP_SELF']);
    }
}

function UsunPodstrone($id){
    global $link;

    if (isset($id) && is_numeric($id)) {
        $qry = "DELETE FROM page_list WHERE id = $id LIMIT 1";

        if (mysqli_query($link, $qry)) {
            header("Location: ".$_SERVER['PHP_SELF']);
        } else {
            echo "Błąd podczas usuwania podstrony: " . mysqli_error($link);
        }
    } else {
        echo "Niepoprawne ID podstrony.";
    }
}

function renderMenu() {
    echo ListaPodstron();
    echo DodajNowaPodstrone();

    if (isset($_GET['edit_id'])) {
        $edit_id = (int)$_GET['edit_id'];
        EdytujPodstrone($edit_id);
    } 

    if (isset($_GET['delete_id'])) {
        $delete_id = (int)$_GET['delete_id'];
        UsunPodstrone($delete_id);
    }
}