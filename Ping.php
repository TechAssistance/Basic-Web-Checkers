<?php
require_once '../app/require.php';
$api = new APIController;
function pingDomain($host, $count = 4) {
    $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    $pingCommand = $isWindows ? "ping -n {$count} {$host}" : "ping -c {$count} {$host}";
    exec($pingCommand, $output, $status);
    if ($status !== 0) {
        return ['error' => "An error occurred while trying to ping {$host}."];
    }
    return $output;
  //  return ['host' => $host, 'result' => $output];
}
function pingDomainString($host, $count = 4) {
    $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    $pingCommand = $isWindows ? "ping -n {$count} {$host}" : "ping -c {$count} {$host}";
    exec($pingCommand, $output, $status);
    if ($status !== 0) {
        return "An error occurred while trying to ping {$host}.";
    }
    return implode("\n", $output);
}
function pingDomainv2($host, $count = 4) {
    $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    $pingCommand = $isWindows ? "ping -n {$count} {$host}" : "ping -c {$count} {$host}";
    exec($pingCommand, $output, $status);
    if ($status !== 0) {
        return ["An error occurred while trying to ping {$host}."];
    }
    return $output;
}
// start key
if (isset($_GET['key']))
{
  $statusCheck = $api->CheckKey($_GET['key'], $_SERVER['REQUEST_URI']);
  // start statusCheck
  if ($statusCheck === "valid")
  {
    // start host check
    if (isset($_GET['host']))
    {
      $host = $_GET['host'];
      $returnData = pingDomain($host);
      $returnDataString = pingDomainString($host);
      $returnDataStringv2 = pingDomainv2($host);
      // start data check
      if ($returnData !== null)
      {
        if (isset($_GET['json']))
        {
          header("Content-Type: application/json");
          $api->QueryLog($_GET['host'], "Ping", $_GET['key']);
          echo json_encode($returnData, JSON_PRETTY_PRINT);
        }
        else {
          foreach ($returnDataStringv2 as $result) {
            echo $result . "<br>";
          }
          $api->QueryLog($_GET['host'], "Ping", $_GET['key']);
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
      echo json_encode(["error" => "Missing 'host' parameter"]);
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
