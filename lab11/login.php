<?php
require 'cfg.php';
session_start();
FormularzLogowania();

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
    <head>
        <link rel="stylesheet" href="css/style.css">
    </head>';

    echo '
    <div class="logowanie">
        <h1 class="heading">Panel CMS:</h1>
        <form method="POST" name="LoginForm" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
            <div class="form-group">
                <label for="login_email">Email</label>
                <input type="text" name="login_email" id="login_email" class="input-field" required />
            </div>
            <div class="form-group">
                <label for="login_pass">Hasło</label>
                <input type="password" name="login_pass" id="login_pass" class="input-field" required />
            </div>
            <div class="form-group">
                <button type="submit" name="x1_submit" class="submit-btn">Zaloguj</button>
            </div>
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
            exit();
        } else {
            echo "<p style='color: red;'>Niepoprawny login lub hasło.</p>";
        }
    }
}
?>