<?php
include("cfg.php");
include("showpage.php");
session_start();

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
}

$pageInfo = [
    1 => ['title' => 'Największe Budynki Świata'],
    2 => ['title' => 'Galeria'],
    3 => ['title' => 'Filmy o Architektura i Budownictwie'],
    4 => ['title' => 'Kontakt'],
    5 => ['title' => 'O Nas'],
    6 => ['title' => '404'],
    7 => ['title' => 'jQuery'],
    8 => ['title' => 'JavaScript'],
    9 => ['title' => 'Usługi'],
];

// Bezpieczne pobieranie parametru idp z URL
$idp = filter_input(INPUT_GET, 'idp', FILTER_VALIDATE_INT) ?: 1;

// Dynamiczne ustawienie tytułu i opisu strony na podstawie idp
$pageTitle = $pageInfo[$idp]['title'];
$nr_indeksu = "169323";
$nr_grupy = "2";

if (isset($pageInfo[$idp])) {
    $pageContent = PokazPodstrone($idp);
} else {
    $pageTitle = '404';
}

$contentFile = isset($contentFile) ? $contentFile : 'html/404.html';
if (!file_exists($contentFile)) {
    $contentFile = 'html/404.html';
}
?>
<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="Author" content="Jakub Krupicki" />
    <link rel="stylesheet" href="css/style.css">
    <title><?php echo $pageTitle; ?></title>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Language" content="pl" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <header>
        <nav>
            <ul id="menu">
                <li><a href="index.php?idp=1">Strona Główna</a></li>
                <li><a href="index.php?idp=5">O nas</a></li>
                <li><a href="index.php?idp=9">Usługi</a></li>
                <li><a href="index.php?idp=2">Galeria</a></li>
                <li><a href="index.php?idp=4">Kontakt</a></li>
                <li><a href="index.php?idp=8">JavaScript</a></li>
                <li><a href="index.php?idp=7">jQuery</a></li>
                <li><a href="index.php?idp=3">Filmy</a></li>
                <li><a href="admin.php">Panel administratora</a></li>
                <li><a href="products.php">Sklep internetowy</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="page-container">
            <?php echo $pageContent; ?>
        </div>
    </main>
    <footer>
        <p>© 2024 Moja Strona Internetowa</p>
        <p><?php echo "Autor: Jakub Krupicki $nr_indeksu grupa $nr_grupy"; ?></p>
    </footer>
    <script src="js/galleryAnimations.js"></script>
    <script src="js/kolorujtlo.js"></script>
    <script src="js/timedata.js"></script>
</body>
</html>