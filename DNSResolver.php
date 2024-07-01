<?php
require_once '../app/require.php';
$api = new APIController;

function dns_resolver($domain, $recordType = 'A') {
  $dnsRecords = dns_get_record($domain, $recordType);

  if ($dnsRecords === false) {
      return ['error' => 'An error occurred while trying to retrieve DNS records.'];
  }

  if (count($dnsRecords) == 0) {
      return ['error' => 'No records found for the specified domain and record type.'];
  }

  $result = [];
  foreach ($dnsRecords as $record) {
      if (isset($record['ip'])) {
          $result[] = ['type' => $record['type'], 'ip' => $record['ip']];
      } elseif (isset($record['target'])) {
          $result[] = ['type' => $record['type'], 'target' => $record['target']];
      }
  }

  return $result;
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
      $recordType = $_GET['record_type'] ?? 'A';

      $response = dns_resolver($domain, $recordType);

      // start json check
      if (isset($_GET['json']))
      {
        header("Content-Type: application/json");
        $api->QueryLog($domain, "DNSResolver", $_GET['key']);
        echo json_encode($response, JSON_PRETTY_PRINT);
      }
      else {
        foreach ($response as $result) {
            echo $result['type'] . ': ' . ($result['ip'] ?? $result['target']) . "\n";
        }
        $api->QueryLog($domain, "DNSResolver", $_GET['key']);
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
