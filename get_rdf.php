<?php
require 'vendor/autoload.php';

use EasyRdf\Sparql\Client;


$client = new Client("http://localhost:8080/rdf4j-server/repositories/grafexamen");


$interogare = "
PREFIX : <http://proiectexamen/>

SELECT ?reservationID ?name ?email ?telephone ?flightNumber ?departureTime
WHERE {
  ?reservationID :name ?name ;
                 :email ?email ;
                 :telephone ?telephone ;
                 :flightNumber ?flightNumber ;
                 :departureTime ?departureTime .
}";


$rezultat = $client->query($interogare);

if (!$rezultat) {
    echo json_encode(["error" => "No results found."]);
    exit;
}


$data = [];

foreach ($rezultat as $row) {
    $data[] = [
        'Name' => (string)$row->name,
        'Email' => (string)$row->email,
        'Telephone' => (string)$row->telephone,
        'Flight Number' => (string)$row->flightNumber,
        'Departure' => (string)$row->departureTime
    ];
}


header('Content-Type: application/json');
echo json_encode($data);
?>
