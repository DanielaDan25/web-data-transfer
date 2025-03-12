<?php
    header("Content-type:application/json");
    $cerere=curl_init();
    $configurari=[CURLOPT_RETURNTRANSFER=>1,CURLOPT_URL=>"http://localhost:4000/flights"];
    curl_setopt_array($cerere,$configurari);
    $rezultat=curl_exec($cerere);
    print $rezultat;
    curl_close($cerere);
?>