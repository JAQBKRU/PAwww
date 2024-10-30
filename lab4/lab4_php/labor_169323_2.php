<?php
$nr_indeksu = "169323";
$nr_grupy = "2";

echo "Jakub Krupicki".$nr_indeksu." grupa ".$nr_grupy."<br/><br/>";

// a)
echo "a)<br/>";
include("somefile.php");
require_once("anotherfile.php");
echo "$somefile<br/>$anotherfile<br/>";

// b)
$value = 2;
echo "b)<br/>";
if ($value == 1) echo "Value is 1<br/>";
elseif ($value == 2) echo "Value is 2<br/>";
else echo "Value is something else<br/>";

switch ($value) {
    case 1:
        echo "one<br/>";
        break;
    case 2:
        echo "two<br/>";
        break;
    default:
        echo "not one and two<br/>";
        break;
}

echo "c)<br/>";
$i = 0;
while ($i < 3) {
    echo "while: $i<br/>";
    $i++;
}

for ($i = 0; $i < 3; $i++) {
    echo "for: $i<br/>";
}

echo "d)<br/>";
$_GET['name'] = "Jan";
if (isset($_GET['name'])) echo "GET[name]: ".$_GET['name']."<br/>";
else echo "Not found GET['name']<br/>";

if (isset($_POST['surname'])) echo "POST[surname]: ".$_POST['surname']."<br/>";
else echo "Not found POST['surname']<br/>";

session_start();
if (!isset($_SESSION['count'])) $_SESSION['count'] = 1;
else $_SESSION['count']++;
echo "Session count: ".$_SESSION['count']."<br/>";
?>