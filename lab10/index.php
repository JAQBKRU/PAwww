<?php
include("cfg.php");
include("showpage.php");

error_reporting(E_ALL);

$pageInfo = [
    1 => ['title' => 'Największe Budynki Świata', 'description' => 'Największe budynki świata to imponujące osiągnięcia architektoniczne, które wyróżniają się nie tylko wysokością, ale również nowoczesnością, designem i innowacjami technologicznymi. Poniżej znajduje się krótka prezentacja kilku z nich.'],
    2 => ['title' => 'Galeria', 'description' => ''],
    3 => ['title' => 'Filmy o Architektura i Budownictwie', 'description' => ''],
    4 => ['title' => 'Kontakt', 'description' => ''],
    5 => ['title' => 'O Nas', 'description' => 'Jesteśmy pasjonatami architektury i nowoczesnych rozwiązań urbanistycznych. Nasza strona została stworzona, aby zaprezentować imponujące budynki, które są świadectwem ludzkiej kreatywności, innowacji oraz zaawansowanej technologii. Fascynuje nas sposób, w jaki miasta na całym świecie zmieniają swoje krajobrazy poprzez monumentalne wieżowce i złożone konstrukcje. Naszym celem jest dostarczenie szczegółowych informacji na temat największych budynków na świecie, ich historii, inspiracji projektowych i znaczenia dla współczesnej architektury.'],
    6 => ['title' => '404', 'description' => ''],
    7 => ['title' => 'jQuery', 'description' => ''],
    8 => ['title' => 'JavaScript', 'description' => ''],
    9 => ['title' => 'Usługi', 'description' => ''],
];

// Bezpieczne pobieranie parametru idp z URL
$idp = filter_input(INPUT_GET, 'idp', FILTER_VALIDATE_INT) ?: 1;
// Ustawienie tytułu i opisu strony na podstawie idp
$pageTitle = $pageInfo[$idp]['title'];
$pageDescription = $pageInfo[$idp]['description'];
$nr_indeksu = "169323";
$nr_grupy = "2";


// Dynamiczna zmiana tytułu i opisu na podstawie idp
if (isset($pageInfo[$idp])) {
    $pageContent = PokazPodstrone($idp);
    $pageTitle = $pageInfo[$idp]['title'];
    $pageDescription = $pageInfo[$idp]['description'];
} else {
    $pageTitle = '404';
    $pageDescription = null;
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
  <title><?php echo $pageTitle;?></title>
  <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
  <meta http-equiv="Content-Language" content="pl" />
  <script src="https://developers.google.com/speed/libraries?hl=pl#jquery"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="js/galleryAnimations.js"></script>
  <?php if ($contentFile == "html/js.html") echo" 
  <script src='js/kolorujtlo.js'></script>
  <script src='js/timedata.js'></script>"?>
</head>
<body <?php if ($contentFile == "html/js.html") echo "onclick='startclock()'"; ?>>
    <header>
        <nav>
            <ul>
                <li><a href="index.php?idp=1">Strona Główna</a></li>
                <li><a href="index.php?idp=5">O nas</a></li>
                <li><a href="index.php?idp=9">Usługi</a></li>
                <li><a href="index.php?idp=2">Galeria</a></li>
                <li><a href="index.php?idp=4">Kontakt</a></li>
                <li><a href="index.php?idp=8">JavaScript</a></li>
                <li><a href="index.php?idp=7">jQuery</a></li>
                <li><a href="index.php?idp=3">Filmy</a></li>
            </ul>
        </nav>
        <?php if ($pageDescription != null) echo "<h2>$pageTitle</h2>"; ?>
        <?php if ($pageDescription != null) echo "<p>$pageDescription</p>"; ?>
    </header>
    <main>
        <?php echo $pageContent; ?>
    </main>
    <footer>
        <p>© 2024 Moja Strona Internetowa</p>
        <p><?php echo "Autor: Jakub Krupicki $nr_indeksu grupa $nr_grupy";?></p>
    </footer>
</body>
</html>