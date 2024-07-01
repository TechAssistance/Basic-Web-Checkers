<?php
require_once '../app/require.php';
$api = new APIController;

// start key
if (isset($_GET['key']))
{
  $statusCheck = $api->CheckKey($_GET['key'], $_SERVER['REQUEST_URI']);
  // start statusCheck
  if ($statusCheck === "valid")
  {
    // start url check
    if (isset($_GET['url']))
    {
      $subdomains = [
      "dc", "api", "irc", "irix", "fileserver", "backup", "agent", "c2c", "login",
      "mssql", "mysql", "localhost", "nameserver", "netstats", "mobile", "mobil",
      "ftp", "webadmin", "uploads", "transfer", "tmp", "support", "smtp0#", "smtp#",
      "smtp", "sms", "shopping", "sandbox", "proxy", "manager", "cpanel", "webmail",
      "forum", "driect- connect", "vb", "forums", "pop#", "pop", "home", "direct",
      "mail", "access", "admin", "oracle", "monitor", "administrator", "email",
      "downloads", "ssh", "webmin", "paralel", "parallels", "www0", "www", "www1",
      "www2", "www3", "www4", "www5", "autoconfig.admin", "autoconfig",
      "autodiscover.admin", "autodiscover", "sip", "msoid", "lyncdiscover"
      ];

      $url = $_GET['url'];

      $results = [];
      foreach ($subdomains as $row) {
          $host = $row . '.' . $url;
          $ip = gethostbyname($host);
          if ($ip !== $host) {
              $results[] = ['host' => $host, 'ip' => $ip];
          }
      }

      // start json check
      if (isset($_GET['json']))
      {
        header("Content-Type: application/json");
        $api->QueryLog($url, "SubdomainCheck", $_GET['key']);
        echo json_encode($results, JSON_PRETTY_PRINT);
      }
      else {
        foreach ($results as $result) {
            echo $result['host'] . ' | ' . $result['ip'] . "\n";
        }
        $api->QueryLog($url, "SubdomainCheck", $_GET['key']);
      }
      // end json check
    }
    else {
      header("Access-Control-Allow-Origin: *");
      header("Access-Control-Allow-Headers: *");
      header("Content-Type: application/json");
      echo json_encode(["error" => "Missing 'url' parameter"]);
    }
    // end url check
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
