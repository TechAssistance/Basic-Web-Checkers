<?php

require_once '../app/require.php';
$api = new APIController;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

function get_location_data($host) {
    $ip = gethostbyname($host);

    if (filter_var($ip, FILTER_VALIDATE_IP)) {
        $ipinfoApiUrl = "https://ipinfo.io/{$ip}/json";
        $locationData = json_decode(file_get_contents($ipinfoApiUrl), true);

        if (!empty($locationData)) {
            return [
                'IP' => $ip,
                'Anycast' => $locationData['anycast'] ?? 'N/A',
                'City' => $locationData['city'] ?? 'N/A',
                'Region' => $locationData['region'] ?? 'N/A',
                'Country' => $locationData['country'] ?? 'N/A',
                'Location' => $locationData['loc'] ?? 'N/A',
                'Org' => $locationData['org'] ?? 'N/A',
                'Postal Code' => $locationData['postal'] ?? 'N/A',
                'Timezone' => $locationData['timezone'] ?? 'N/A'
            ];
        }
    }

    return null;
}

if (isset($_GET['key'])) {
    $statusCheck = $api->CheckKey($_GET['key'], $_SERVER['REQUEST_URI']);

    if ($statusCheck === "valid") {
        if (isset($_GET['host'])) {
            $host = $_GET['host'];
            $returnData = get_location_data($host);

            if ($returnData !== null) {
                if (isset($_GET['json'])) {
                    header("Content-Type: application/json");
                    $api->QueryLog($host, "IP Lookup", $_GET['key']);
                    echo json_encode($returnData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                } else {
                    $api->QueryLog($host, "IP Lookup", $_GET['key']);
                    echo "IP: ". $host .
                         "<br>Anycast: ".  $returnData['Anycast'] . PHP_EOL .
                         "<br>City :".  $returnData['City'] . PHP_EOL .
                         "<br>Region :".  $returnData['Region'] . PHP_EOL .
                         "<br>Country :".  $returnData['Country'] . PHP_EOL .
                         "<br>Location: ". $returnData['Location'] . PHP_EOL .
                         "<br>Org: ". $returnData['Org'] . PHP_EOL .
                         "<br>Postal Code: ". $returnData['Postal Code'] . PHP_EOL .
                         "<br>Timezone: ". $returnData['Timezone'] . PHP_EOL;
                }
            } else {
                header("Content-Type: application/json");
                echo json_encode(["error" => "Failed to get location data for host: $host"]);
            }
        } else {
            header("Content-Type: application/json");
            echo json_encode(["error" => "Missing 'host' parameter"]);
        }
    } else {
        echo $statusCheck;
    }
} else {
    header("Content-Type: application/json");
echo json_encode(["error" => "Missing 'key' parameter"]);
}

?>
