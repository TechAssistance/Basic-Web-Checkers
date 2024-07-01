<?php
require_once '../app/require.php';
$api = new APIController;

function isDomainAvailable($domain) {
  $domainParts = explode('.', $domain);
  $extension = end($domainParts);
  if (checkdnsrr($domain, 'ANY')) {
      return false;
  }
  return true;
}

// start key
if (isset($_GET['key']))
{
  $statusCheck = $api->CheckKey($_GET['key'], $_SERVER['REQUEST_URI']);
  // start statusCheck
  if ($statusCheck === "valid")
  {
    // start domain check
    if (isset($_GET['domain']))
    {
      $domain = $_GET['domain'];
      $isAvailable = isDomainAvailable($domain);

      // start json check
      if (isset($_GET['json']))
      {
        header("Content-Type: application/json");
        $api->QueryLog($domain, "DomainAvailability", $_GET['key']);
        echo json_encode(["domain" => $domain, "isAvailable" => $isAvailable], JSON_PRETTY_PRINT);
      }
      else {
        echo 'Domain: ' . $domain . ' | Is Available: ' . ($isAvailable ? 'Yes' : 'No') . "\n";
        $api->QueryLog($domain, "DomainAvailability", $_GET['key']);
      }
      // end json check
    }
    else {
      header("Access-Control-Allow-Origin: *");
      header("Access-Control-Allow-Headers: *");
      header("Content-Type: application/json");
      echo json_encode(["error" => "Missing 'domain' parameter"]);
    }
    // end domain check
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
