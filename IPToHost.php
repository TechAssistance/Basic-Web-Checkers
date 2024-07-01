<?php
require_once '../app/require.php';
$api = new APIController;

function getServerIP($host) {
  $ip = gethostbyname($host);
  return $ip === $host ? false : $ip;
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
      $ip = getServerIP($host);

      // start ip check
      if ($ip !== false)
      {
        // start json check
        if (isset($_GET['json']))
        {
          header("Content-Type: application/json");
          $api->QueryLog($host, "GetIP", $_GET['key']);
          echo json_encode(['host' => $host, 'ip' => $ip], JSON_PRETTY_PRINT);
        }
        else {
          echo 'Host: ' . $host . ' | IP: ' . $ip . "\n";
          $api->QueryLog($host, "GetIP", $_GET['key']);
        }
        // end json check
      }
      else {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header("Content-Type: application/json");
        echo json_encode(["error" => "Failed to get IP for host: $host"]);
      }
      // end ip check
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
?>
