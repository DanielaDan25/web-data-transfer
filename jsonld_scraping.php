<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;

$client = new Client();
$headers = [
    "headers" => [
        "User-Agent" => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36"
    ]
];

$response = $client->get("http://proiectexamen/", $headers);
$httpResponse = $response->getBody();
libxml_use_internal_errors(true);
$doc = new DOMDocument();
@$doc->loadHTML($httpResponse);
$xpath = new DOMXPath($doc);
$result = $xpath->query("//script[@type='application/ld+json']");
$data = [];
$people = [];
$reservationIDs = [];

if ($result->length > 0) {
    $jsonLD = $result->item(0)->nodeValue;
    $decodedData = json_decode($jsonLD, true);

    if (isset($decodedData['@graph'])) {
        foreach ($decodedData['@graph'] as $graph) {
            foreach ($graph as $item) {
                $id = $item['identifier']['value'] ?? null;

                if ($item['@type'] === 'Person') {
                    $people[$id] = [
                        "Name" => $item['name'],
                        "Email" => $item['email'],
                        "Telephone" => $item['telephone']
                    ];
                } elseif ($item['@type'] === 'FlightReservation') {
                    $reservationID = $item['identifier']['value'] ?? "Unknown"; 
                    $reservationIDs[] = $reservationID;
                    $personId = $item['underName']['identifier'] ?? null;

                    if ($personId && isset($people[$personId])) {
                        $data[] = [
                            "ReservationID" => $reservationID,
                            "Name" => $people[$personId]["Name"],
                            "Email" => $people[$personId]["Email"],
                            "Telephone" => $people[$personId]["Telephone"],
                            "Flight Number" => $item['reservationFor']['flightNumber'],
                            "Departure" => $item['reservationFor']['departureTime']
                        ];
                    } else {
                        $data[] = [
                            "ReservationID" => $reservationID,
                            "Name" => "",
                            "Email" => "",
                            "Telephone" => "",
                            "Flight Number" => $item['reservationFor']['flightNumber'],
                            "Departure" => $item['reservationFor']['departureTime']
                        ];
                    }
                }
            }
        }
    }
}
libxml_clear_errors();

header('Content-Type: application/json');
echo json_encode(["data" => $data, "reservationIDs" => $reservationIDs]);
?>