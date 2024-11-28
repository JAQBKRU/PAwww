<?php
    $db_host = 'localhost';
    $db_user = 'root';
    $db_pass = '';
    $db_db = 'moja_strona';

    $link = mysqli_connect($db_host, $db_user, $db_pass, $db_db);
    if (!$link) {
        echo '<b>Przerwane połączenie:</b> '.mysqli_connect_error();
        exit();
    }

    // Dane logowania do panelu CMS (tymczasowo zapisane w zmiennych)
    $login = "admin";
    $pass = "123";
?>