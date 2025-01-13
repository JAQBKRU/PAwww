<?php
require 'cfg.php';
session_start();

// - - - - - - - - - - - - - - - - //  
//        FormularzLogowaniaUser    //  
// - - - - - - - - - - - - - - - - //  
// Funkcja obsługująca formularz logowania użytkownika
// Parametr: brak
// Zwraca: void
// Sposób działania: Funkcja generuje formularz logowania, a następnie sprawdza poprawność danych logowania (email i hasło).
// Jeśli dane są poprawne, użytkownik zostaje zalogowany, a następnie przekierowany na stronę produktów.
function FormularzLogowaniaUser()
{
    echo '
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Logowanie - Użytkownik</title>
        <link rel="stylesheet" href="css/style.css">
    </head>';

    echo '
    <div class="logowanie">
        <h1 class="heading">Logowanie - Użytkownik:</h1>
        
        <a href="index.php" class="back-link">Powrót na stronę główną</a>

        <form method="POST" name="LoginFormUser" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
            <div class="form-group">
                <label for="login_name">Login</label>
                <input type="text" name="login_name" id="login_name" class="input-field" required placeholder="user1 | user2 | user3" />
            </div>
            <div class="form-group">
                <label for="login_pass">Hasło</label>
                <input type="password" name="login_pass" id="login_pass" class="input-field" required placeholder="pass1 | pass2 | pass3" />
            </div>
            <div class="form-group">
                <button type="submit" name="x1_submit" class="submit-btn">Zaloguj</button>
            </div>
        </form>
    </div>';


    if (isset($_POST['login_name']) && isset($_POST['login_pass'])) {
        $form_login = $_POST['login_name'];
        $form_pass = $_POST['login_pass'];
        global $link;

        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $link->prepare($sql);
        $stmt->bind_param('s', $form_login);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Sprawdzenie, czy użytkownik istnieje i czy hasło jest poprawne
        if ($user && $user['password'] == $form_pass) {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            header("Location: products.php");
            exit();
        } else {
            echo "<p style='color: red;'>Niepoprawny login lub hasło.</p>";
        }
    }
}

FormularzLogowaniaUser();
?>