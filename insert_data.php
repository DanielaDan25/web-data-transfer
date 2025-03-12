<?php
require 'vendor/autoload.php';

use EasyRdf\Http\Client as EasyRdfClient;

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['reservationID']) && !empty($data['reservationID']) && 
    isset($data['name']) && !empty($data['name']) && 
    isset($data['email']) && !empty($data['email']) && 
    isset($data['telephone']) && !empty($data['telephone'])) {

    $reservationID = addslashes($data['reservationID']);
    $name = addslashes($data['name']);
    $email = addslashes($data['email']);
    $telephone = addslashes($data['telephone']);

    $adresa = "http://localhost:8080/rdf4j-server/repositories/grafexamen/statements?update=";
    $prefix = "prefix : <http://proiectexamen/> ";
    $insertData = "
        INSERT DATA { 
            :" . $reservationID . " 
                :name '" . $name . "' ; 
                :email '" . $email . "' ; 
                :telephone '" . $telephone . "' 
        }";
    $interogare = urlencode($prefix . $insertData);
    
    $clienthttp = new EasyRdfClient($adresa . $interogare);

    try {
        $response = $clienthttp->request("POST");
        echo json_encode(['success' => true, 'message' => 'Data successfully inserted into RDF4J.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error inserting data into RDF4J: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
}
?>
