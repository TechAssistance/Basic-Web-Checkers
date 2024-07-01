<?php
require_once '../app/require.php';
$api = new APIController;

function isTorExitNode($ip) {
    $torExitListUrl = 'https://check.torproject.org/torbulkexitlist';
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $torExitListUrl,
        CURLOPT_RETURNTRANSFER => true,
    ]);
    $torExitList = curl_exec($curl);
    if (curl_errno($curl)) {
        echo 'Error: ' . curl_error($curl);
        exit;
    }
    curl_close($curl);
    $torExitNodes = array_filter(explode("\n", $torExitList));
    return in_array($ip, $torExitNodes);
}
// start key
if (isset($_GET['key']))
{
  $statusCheck = $api->CheckKey($_GET['key'], $_SERVER['REQUEST_URI']);
  // start statusCheck
  if ($statusCheck === "valid")
  {
    // start host check
    if (isset($_GET['ip']))
    {
      $ip = $_GET['ip'];
      $returnData = isTorExitNode($ip);
      // start data check
      if ($returnData !== null)
      {
        if (isset($_GET['json']))
        {
          header("Content-Type: application/json");
          $api->QueryLog($_GET['ip'], "TorChecker", $_GET['key']);
          echo json_encode($returnData, JSON_PRETTY_PRINT);
        }
        else {
          echo json_encode($returnData, JSON_PRETTY_PRINT);
          $api->QueryLog($_GET['ip'], "TorChecker", $_GET['key']);
        }
      }
      else {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header("Content-Type: application/json");
        echo json_encode(["error" => "Failed to get location data for host: $host"]);
      }
      // end data check
    }
    else {
      header("Access-Control-Allow-Origin: *");
      header("Access-Control-Allow-Headers: *");
      header("Content-Type: application/json");
      echo json_encode(["error" => "Missing 'ip' parameter"]);
    }
    // end host check
  }
  else {
    echo $statusCheck;
  }
}
else {
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: *");
  header("Content-Type: application/json");
  echo json_encode(["error" => "Missing 'key' parameter"]);
}

?>
