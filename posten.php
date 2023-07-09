<?php

$postcodein = $_GET["postcode"];
$postcode = filter_var($postcodein, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

function formatDate($dateString) {
    $dateObject = new DateTime($dateString);
    return $dateObject->format('D j M Y');
}

function checkPosten($postcode) {

    // Headers
    $headers0 = [
        "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/111.0.0.0 Safari/537.36"
    ];

    // Start Session
    $session = curl_init();

    // Load Posten Website to get URL and API token
    $start_url = "https://www.posten.no/levering-av-post";
    $start_payload = [];

    curl_setopt($session, CURLOPT_URL, $start_url);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($session, CURLOPT_HTTPHEADER, $headers0);
    curl_setopt($session, CURLOPT_ENCODING , "gzip");
    $start_response = curl_exec($session);

    $httpCode = curl_getinfo($session, CURLINFO_HTTP_CODE);
    // Check if the response code is 200
    if ($httpCode == 200) {
        //echo "Response code is 200. Success!";
    } else {
        echo "Error: " . $httpCode . " when attempting to connect to " . $start_url;
        exit;
    }

    preg_match('/"serviceUrl":"([^"]*)"/', $start_response, $serviceUrlMatches);
    $serviceUrl = $serviceUrlMatches[1];

    preg_match('/"apiKey":"([^"]*)"/', $start_response, $apiKeyMatches);
    $apiKey = $apiKeyMatches[1];

    // Check post code and delivery days
    $headers1 = [
        "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/111.0.0.0 Safari/537.36",
        "kp-api-token: " . $apiKey
    ];

    $checkpostcode_url = $serviceUrl . "?postalCode=" . $postcode;
    $checkpostcode_payload = [];

    curl_setopt($session, CURLOPT_URL, $checkpostcode_url);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($session, CURLOPT_HTTPHEADER, $headers1);
    $checkpostcode_response = curl_exec($session);

    $httpCode = curl_getinfo($session, CURLINFO_HTTP_CODE);
    // Check if the response code is 200
    if ($httpCode == 200) {
        //echo "Response code is 200. Success!";
    } else {
        echo "Error: " . $httpCode . " when attempting to connect to " . $checkpostcode_url;
        exit;
    }
    
    $deliveryjson = json_decode($checkpostcode_response, true);
    $delivery0 = $deliveryjson["delivery_dates"][0];
    $delivery1 = $deliveryjson["delivery_dates"][1];
    $delivery2 = $deliveryjson["delivery_dates"][2];
    $delivery3 = $deliveryjson["delivery_dates"][3];
    $delivery4 = $deliveryjson["delivery_dates"][4];

    # Format Dates
    $delivery0_formatted = formatDate($delivery0);
    $delivery1_formatted = formatDate($delivery1);
    $delivery2_formatted = formatDate($delivery2);
    $delivery3_formatted = formatDate($delivery3);
    $delivery4_formatted = formatDate($delivery4);

    $postentoday = 0;
    $today = date("Y-m-d");
    if ($delivery0 === $today) {
        $postentoday = 1;
    }

    return [$postentoday, $delivery0, $delivery1, $delivery2, $delivery3, $delivery4, $delivery0_formatted, $delivery1_formatted, $delivery2_formatted, $delivery3_formatted, $delivery4_formatted];
}

?>

<?php

list($postentoday, $delivery0, $delivery1, $delivery2, $delivery3, $delivery4, $delivery0_formatted, $delivery1_formatted, $delivery2_formatted, $delivery3_formatted, $delivery4_formatted) = checkPosten($postcode);

// Create an associative array with the results
$result = [
    "postentoday" => $postentoday,
    "delivery0" => $delivery0,
    "delivery1" => $delivery1,
    "delivery2" => $delivery2,
    "delivery3" => $delivery3,
    "delivery4" => $delivery4,
    "delivery0_formatted" => $delivery0_formatted,
    "delivery1_formatted" => $delivery1_formatted,
    "delivery2_formatted" => $delivery2_formatted,
    "delivery3_formatted" => $delivery3_formatted,
    "delivery4_formatted" => $delivery4_formatted
];

echo json_encode($result, JSON_PRETTY_PRINT);

?>
