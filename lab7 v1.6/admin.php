<?php

session_start();
require 'cfg.php';
echo '<style>';
include './css/style.css';
echo '</style>';

if(isset($_SESSION['zalogowany']) && $_SESSION['zalogowany'] == true){
    echo "Witaj w panelu administracyjnym!<br/><br/>";

    echo ListaPodstron();

    if (isset($_GET['edit_id'])) {
        $edit_id = (int)$_GET['edit_id'];
        EdytujPodstrone($edit_id);
    } 
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

            echo ListaPodstron();

            if (isset($_GET['edit_id'])) {
                $edit_id = (int)$_GET['edit_id'];
                EdytujPodstrone($edit_id);
            } 
        }else{
            echo "<p style='color: red';>Niepoprawny login lub haslo.</p>";
            echo FormularzLogowania();
        }
    }else{
        echo FormularzLogowania();
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
        //echo "<p>".$row['id'].": ".$row['page_title']."<span class='btn_del'>DELETE</span><span class='btn_edit'>EDIT</span></p>";

        echo "<p>".$row['id'].": ".$row['page_title']."<span class='btn_del'>DELETE</span><a href='?edit_id=".$row['id']."' class='btn_edit'>EDIT</a></p>";

        
    }
}

function EdytujPodstrone($id) {
    global $link;

    $qry = "SELECT * FROM page_list WHERE id = $id";
    $result = mysqli_query($link, $qry);
    $page = mysqli_fetch_assoc($result);

    if (!$page) {
        echo "Nie znaleziono strony o podanym ID.";
        return;
    }

    echo '
    <form method="POST" action="">
        <label for="title">Tytuł strony:</label><br/>
        <input type="text" id="title" name="title" value="'.$page['page_title'].'"><br/><br/>
        
        <label for="content">Treść strony:</label><br/>
        <textarea id="content" name="content" rows="10" cols="50">' . $page['page_content'].'</textarea><br/><br/>
        
        <label for="status">Strona aktywna:</label>
        <input type="checkbox" id="status" name="status" ' . ($page['status'] ? 'checked' : '') . '><br/><br/>
        
        <input type="submit" name="save_changes" value="Zapisz zmiany">
    </form>
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