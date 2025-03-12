<?php

$jsonServerUrl = 'http://localhost:4000/flights';
$rdfUrl = 'http://proiectexamen/get_rdf.php';
$rdfData = file_get_contents($rdfUrl);

if ($rdfData === FALSE) {
    die('Error retrieving RDF data');
}

$data = json_decode($rdfData, true);


if ($data === NULL) {
    die('Error decoding JSON data');
}


function sendToJSON($url, $data) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    } else {
        echo 'Response:' . $response;
    }

    curl_close($ch);
}


foreach ($data as $item) {
    sendToJSON($jsonServerUrl, $item);
}

echo 'Data sent successfully';

?>
