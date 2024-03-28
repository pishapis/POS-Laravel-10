<?php
function get_tanggal($tanggal){
    $date = date("d F Y", strtotime($tanggal));

    $date = preg_replace("/^0/", "", $date);
    $date = str_replace("January", "Januari", $date);
    $date = str_replace("February", "Pebruari", $date);
    $date = str_replace("March", "Maret", $date);
    $date = str_replace("April", "April", $date);
    $date = str_replace("May", "Mei", $date);
    $date = str_replace("June", "Juni", $date);
    $date = str_replace("July", "Juli", $date);
    $date = str_replace("August", "Agustus", $date);
    $date = str_replace("September", "September", $date);
    $date = str_replace("October", "Oktober", $date);
    $date = str_replace("November", "Nopember", $date);
    $date = str_replace("December", "Desember", $date);

    return $date;
}
?>