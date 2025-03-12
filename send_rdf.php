<?php
require_once "vendor/autoload.php";


$graph = new EasyRdf\Graph("http://proiectexamen/grafexamen");
$prefixes = new EasyRdf\RdfNamespace();
$prefixes->set('ex', 'http://proiectexamen/');


$data = json_decode(file_get_contents('php://input'), true);

foreach ($data as $item) {
    $subject = $prefixes->expand('ex:' . $item['ReservationID']);
    $graph->addLiteral($subject, 'ex:name', $item['Name']);
    $graph->addLiteral($subject, 'ex:email', $item['Email']);
    $graph->addLiteral($subject, 'ex:telephone', $item['Telephone']);
    $graph->addLiteral($subject, 'ex:flightNumber', $item['Flight Number']);
    $graph->addLiteral($subject, 'ex:departureTime', $item['Departure']);
}


$client = new EasyRdf\Sparql\Client("http://localhost:8080/rdf4j-server/repositories/grafexamen/statements");
$client->insert($graph, "http://proiectexamen/grafexamen");
?>