<?php
// used tool
// https://github.com/PHPMailer/PHPMailer
// https://www.mailersend.com/

/* acc for testing:
    sabiyo3640@kindomd.com
    zaq1@WSX
    Username: MS_a4vco0@trial-351ndgwee95gzqx8.mlsender.net
    Password: epWPbU6WiOojvZLM
    API: 

    jesin10747@kindomd.com
    zaq1@WSX
    Username: MS_TbwpOE@trial-o65qngkyyqwlwr12.mlsender.net
    Password: VyliRB4Z44iJLytb
    API: mlsn.06a85fedfeb01a7cd6d8db16812b007187725297754b4e4acddc2cb416437dbb
*/
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer-master/src/PHPMailer.php';
require './PHPMailer-master/src/Exception.php';
require './PHPMailer-master/src/SMTP.php';

function UtworzMailer() {
    $phpmailer = new PHPMailer(true);
    try {
        $phpmailer->isSMTP();
        $phpmailer->Host = 'smtp.mailersend.net';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Username = 'MS_TbwpOE@trial-o65qngkyyqwlwr12.mlsender.net';
        $phpmailer->Password = 'VyliRB4Z44iJLytb';
        $phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $phpmailer->Port = 587;
        $phpmailer->CharSet = 'UTF-8'; 
        return $phpmailer;
    } catch (Exception $e) {
        echo "SMTP configuration error: {$e->getMessage()}";
        return null;
    }
}

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
        $phpmailer->setFrom("MS_TbwpOE@trial-o65qngkyyqwlwr12.mlsender.net", "Zespol Obslugi Klienta");
        $phpmailer->addAddress($odbiorca, 'Odbiorca');
        $phpmailer->isHTML(false);
        $phpmailer->Subject = $_POST['temat'];
        $phpmailer->Body = $_POST['tresc'];
        $phpmailer->send();
        echo '[message_sent]';
    } catch (Exception $e) {
        echo "Sending error: {$phpmailer->ErrorInfo}";
    }
}

function PokazKontakt() {
    return '
        <div class="send_mail_form">
        <form method="POST" action="">
            <label for="temat">Temat:</label><br>
            <input type="text" id="temat" name="temat"><br><br>
            
            <label for="tresc">Treść wiadomości:</label><br>
            <textarea id="tresc" name="tresc" rows="5" cols="30"></textarea><br><br>
            
            <label for="email">Twój e-mail:</label><br>
            <input type="email" id="email" name="email" required><br><br>
            
            <button type="submit" name="send_email">Wyślij</button>
            <button type="submit" name="reset_pass">Resetuj haslo</button>
        </form>
        </div>
    ';
}

function ResetPassword() {
    $hasloAdmina = 'your_password';
    $odbiorca = $_POST['email'];
    PrzypomnijHaslo($odbiorca, $hasloAdmina);
}

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
        $phpmailer->setFrom("MS_TbwpOE@trial-o65qngkyyqwlwr12.mlsender.net", "System powiadomien");
        $phpmailer->addAddress($email, 'Admin');
        $phpmailer->isHTML(false);
        $phpmailer->Subject = 'Przypomnienie hasła do panelu admina';
        $phpmailer->Body = "Twoje hasło do panelu admina to: $hasloAdmina\n\nProsimy o zachowanie poufności.";
        $phpmailer->send();
        echo '[password_sent]';
    } catch (Exception $e) {
        echo "Sending error: {$phpmailer->ErrorInfo}";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['temat']) && isset($_POST['tresc']) && !empty($_POST['temat']) && !empty($_POST['tresc']) && isset($_POST['send_email'])) {
        $odbiorca = $_POST['email'];
        WyslijMailKontakt($odbiorca);
    } elseif (isset($_POST['email']) && isset($_POST['reset_pass'])) {
        ResetPassword();
    }
}
?>
