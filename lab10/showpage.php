<?php

include("cfg.php");

// - - - - - - - - - - - - - - - - //  
//          PokazPodstrone         //  
// - - - - - - - - - - - - - - - - //  
// Funkcja wyświetlająca podstronę
// Parametr: $id - identyfikator podstrony
// Zwraca: void
// Sposób działania: Funkcja pobiera identyfikator podstrony i wyświetla jej zawartość.
function PokazPodstrone($id){
    $id_clear = htmlspecialchars($id);
    global $link;

    $qry = "SELECT * FROM page_list WHERE id='$id_clear' LIMIT 1";
    $result = mysqli_query($link, $qry) or die(mysqli_error($link));
    $row = mysqli_fetch_array($result);

    if(empty($row['id'])) $web = '[nie_znaleziono_strony]';
    else $web = $row['page_content'];

    return $web;
}