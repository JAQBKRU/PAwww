<?php
// used tool
// https://github.com/PHPMailer/PHPMailer
// https://www.mailersend.com/

/* acc for testing:
    xekino9050@xcmexico.com
    zaq1@WSX
    Username: MS_XE2v8r@trial-neqvygmpzpdg0p7w.mlsender.net
    Password: lkJlnqTgZQOiri2h
    API: mlsn.8c5b2b67d05868b3b0e7704744d5abc1fce5aba98c1be490cfa723a5f3e5c0c6


    prestikewivp acc
    Username: MS_ETBWbT@trial-jy7zpl9o5d3l5vx6.mlsender.net
    Password: kYPeIosEv5l8eXZi
    API: 
*/

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer-master/src/PHPMailer.php';
require './PHPMailer-master/src/Exception.php';
require './PHPMailer-master/src/SMTP.php';

// - - - - - - - - - - - - - - - - //  
//          UtworzMailer           //  
// - - - - - - - - - - - - - - - - //  
// Funkcja tworząca konfigurację PHPMailera
// Parametr: brak
// Zwraca: obiekt PHPMailer lub null w przypadku błędu
// Sposób działania: Funkcja tworzy obiekt PHPMailer, konfiguruje połączenie SMTP oraz ustawia wszystkie niezbędne dane logowania i parametry do wysyłania wiadomości e-mail.
// - Używa serwera SMTP: smtp.mailersend.net
// - Autentykacja SMTP jest włączona (wymaga loginu i hasła)
// - Wykorzystuje szyfrowanie STARTTLS
// - Ustawia kodowanie znaków na UTF-8
function UtworzMailer() {
    $phpmailer = new PHPMailer(true);
    try {
        // Konfiguracja SMTP
        $phpmailer->isSMTP();
        $phpmailer->Host = 'smtp.mailersend.net';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Username = 'MS_ETBWbT@trial-jy7zpl9o5d3l5vx6.mlsender.net';
        $phpmailer->Password = 'kYPeIosEv5l8eXZi';
        $phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $phpmailer->Port = 587;
        $phpmailer->CharSet = 'UTF-8'; 
        return $phpmailer;
    } catch (Exception $e) {
        echo "SMTP configuration error: {$e->getMessage()}";
        return null;
    }
}

// - - - - - - - - - - - - - - - - //  
//        WyslijMailKontakt        //  
// - - - - - - - - - - - - - - - - //  
// Funkcja wysyłająca e-mail
// Parametr: $odbiorca - e-mail
// Zwraca: bool - wynik (true/false)
// Sposób działania: Funkcja wysyła e-mail kontaktowy do podanego odbiorcy.
function WyslijMailKontakt($odbiorca) {
    if (empty($_POST['temat']) || empty($_POST['tresc']) || empty($_POST['email'])) {
        echo PokazKontakt();
        return;
    }

    $phpmailer = UtworzMailer();
    if (!$phpmailer) {
        echo '[mailer_configuration_error]';
        return;
    }

    try {
        $phpmailer->setFrom("MS_ETBWbT@trial-jy7zpl9o5d3l5vx6.mlsender.net", "Zespol Obslugi Klienta");
        $phpmailer->addAddress($odbiorca, 'Odbiorca');
        $phpmailer->isHTML(false);
        $phpmailer->Subject = str_replace(["\r", "\n"], '', $_POST['temat']);
        $phpmailer->Body = str_replace(["\r", "\n"], '', $_POST['tresc']);
        $phpmailer->send();
    } catch (Exception $e) {
        echo "Sending error: {$phpmailer->ErrorInfo}";
    }
}

// - - - - - - - - - - - - - - - - //  
//          PokazKontakt          //  
// - - - - - - - - - - - - - - - - //  
// Funkcja wyświetlająca formularz kontaktowy
// Parametr: brak
// Zwraca: void
// Sposób działania: Funkcja wyświetla formularz kontaktowy z polami do wpisania imienia, e-maila i wiadomości.
function PokazKontakt() {
    return '
        <div class="send_mail_form">
        <form method="POST" action="">
            <h2 class="form-header">Formularz kontaktowy i resetujący hasło</h2>
            
            <div class="form-group">
                <label for="temat">Temat:</label>
                <input type="text" id="temat" name="temat" class="form-input">
            </div>
            
            <div class="form-group">
                <label for="tresc">Treść wiadomości:</label>
                <textarea id="tresc" name="tresc" rows="5" class="form-textarea"></textarea>
            </div>
            
            <div class="form-group">
                <label for="email">Twój e-mail:</label>
                <input type="email" id="email" name="email" required class="form-input">
            </div>

            <p class="form-info">Aby zresetować hasło, uzupełnij tylko pole z adresem e-mail i kliknij „Resetuj hasło”.</p>
            
            <div class="form-actions">
                <button type="submit" name="send_email" class="btn btn-primary">Wyślij</button>
                <button type="submit" name="reset_pass" class="btn btn-secondary">Resetuj hasło</button>
            </div>
        </form>
        </div>
    ';
}

// - - - - - - - - - - - - - - - - //  
//          ResetPassword          //  
// - - - - - - - - - - - - - - - - //  
// Funkcja resetująca hasło
// Parametr: brak
// Zwraca: void
// Sposób działania: Funkcja umożliwia resetowanie hasła użytkownika na podstawie podanego e-maila.
function ResetPassword() {
    $hasloAdmina = 'your_password';
    $odbiorca = $_POST['email'];
    PrzypomnijHaslo($odbiorca, $hasloAdmina);
}

// - - - - - - - - - - - - - - - - //  
//          PrzypomnijHaslo        //  
// - - - - - - - - - - - - - - - - //  
// Funkcja przypominająca hasło
// Parametr: $email - adres e-mail
//           $hasloAdmina - hasło administratora
// Zwraca: bool - wynik (true/false)
// Sposób działania: Funkcja sprawdza, czy podany e-mail istnieje w systemie, a następnie wysyła przypomnienie hasła lub link do resetowania hasła.
function PrzypomnijHaslo($email, $hasloAdmina) {
    if (empty($email)) {
        echo '[no_email_provided]';
        return;
    }

    $phpmailer = UtworzMailer();
    if (!$phpmailer) {
        echo '[mailer_configuration_error]';
        return;
    }

    try {
        $phpmailer->setFrom("MS_ETBWbT@trial-jy7zpl9o5d3l5vx6.mlsender.net", "System powiadomien");
        $phpmailer->addAddress($email, 'Admin');
        $phpmailer->isHTML(false);
        $phpmailer->Subject = 'Przypomnienie hasła do panelu admina';
        $phpmailer->Body = "Twoje hasło do panelu admina to: $hasloAdmina\n\nProsimy o zachowanie poufności.";
        $phpmailer->send();
    } catch (Exception $e) {
        echo "Sending error: {$phpmailer->ErrorInfo}";
    }
}

// Obsługa formularza, który został wysłany metodą POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sprawdzenie, czy formularz kontaktowy został wysłany
    if (isset($_POST['temat']) && isset($_POST['tresc']) && !empty($_POST['temat']) && !empty($_POST['tresc']) && isset($_POST['send_email'])) {
        $odbiorca = $_POST['email'];
        WyslijMailKontakt($odbiorca);
    }
    // Sprawdzenie, czy żądanie resetu hasła zostało wysłane
    elseif (isset($_POST['email']) && isset($_POST['reset_pass'])) {
        ResetPassword();
    }
}
?>