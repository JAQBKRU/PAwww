<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

$idp = 'glowna';
$nr_indeksu = "169323";
$nr_grupy = "2";

if (isset($_GET['idp'])) {
  $idp = $_GET['idp'];
  $pageDescription = null;

  if($idp == 'glowna'){
      $pageTitle = 'Największe Budynki Świata';
      $pageDescription = "Największe budynki świata to imponujące osiągnięcia architektoniczne, które wyróżniają się nie tylko wysokością, ale również nowoczesnością, designem i innowacjami technologicznymi. Poniżej znajduje się krótka prezentacja kilku z nich.";
  }elseif ($idp == 'about'){
      $pageTitle = 'O Nas';
      $pageDescription = "Jesteśmy pasjonatami architektury i nowoczesnych rozwiązań urbanistycznych. 
      Nasza strona została stworzona, aby zaprezentować imponujące budynki, które są świadectwem 
      ludzkiej kreatywności, innowacji oraz zaawansowanej technologii. Fascynuje nas sposób, w jaki 
      miasta na całym świecie zmieniają swoje krajobrazy poprzez monumentalne wieżowce i złożone konstrukcje. 
      Naszym celem jest dostarczenie szczegółowych informacji na temat największych budynków na świecie, ich historii, 
      inspiracji projektowych i znaczenia dla współczesnej architektury.";
  }elseif ($idp == 'services') $pageTitle = 'Usługi';
  elseif ($idp == 'gallery') $pageTitle = 'Galeria';
  elseif ($idp == 'contact') $pageTitle = 'Kontakt';
  elseif ($idp == 'js') $pageTitle = 'JavaScript';
  elseif ($idp == 'jquery') $pageTitle = 'jQuery';
  elseif ($idp == 'filmy'){
      $pageTitle = 'Filmy o Architektura i Budownictwie';
      $pageDescription = " ";
  }
  else $pageTitle = '404';
}


$contentFile = "html/$idp.html";

if (!file_exists($contentFile)) {
    //$contentFile = 'html/glowna.html';
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
                <li><a href="index.php?idp=glowna">Strona Główna</a></li>
                <li><a href="index.php?idp=about">O nas</a></li>
                <li><a href="index.php?idp=services">Usługi</a></li>
                <li><a href="index.php?idp=gallery">Galeria</a></li>
                <li><a href="index.php?idp=contact">Kontakt</a></li>
                <li><a href="index.php?idp=js">JavaScript</a></li>
                <li><a href="index.php?idp=jquery">jQuery</a></li>
                <li><a href="index.php?idp=filmy">Filmy</a></li>
            </ul>
        </nav>
        <?php if ($pageDescription != null) echo "<h2>$pageTitle</h2>"; ?>
        <?php if ($pageDescription != null) echo "<p>$pageDescription</p>"; ?>
    </header>
    <main>
        <?php include($contentFile); ?>
    </main>
    <footer>
        <p>© 2024 Moja Strona Internetowa</p>
        <p><?php echo "Autor: Jakub Krupicki $nr_indeksu grupa $nr_grupy";?></p>
    </footer>
</body>
</html>