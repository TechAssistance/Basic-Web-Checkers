<?php
require_once '../app/require.php';
$api = new APIController;

function detectFirewall($url) {
  $firewallHeaders = [
      'server' => [
          'cloudflare' => 'Cloudflare',
            'akamai' => 'Akamai',
            'imperva' => 'Imperva',
               'f5 networks' => 'F5 Networks',
               'fortinet' => 'Fortinet',
               'barracuda networks' => 'Barracuda Networks',
               'radware' => 'Radware',
               'nsfocus' => 'NSFOCUS',
               'qualys' => 'Qualys',
               'sucuri' => 'Sucuri',
               'sophos' => 'Sophos',
               'trustwave' => 'Trustwave',
               'armor' => 'Armor',
               'alert logic' => 'Alert Logic',
               'denyall' => 'DenyAll',
               'wallarm' => 'Wallarm',
               'sitelock' => 'SiteLock',
               'signal sciences' => 'Signal Sciences',
               'indusface' => 'Indusface',
               'neustar' => 'Neustar',
               'incapsula' => 'Incapsula',
               'akamai (prolexic)' => 'Akamai (Prolexic)',
               'stackpath' => 'StackPath',
               'a10 networks' => 'A10 Networks',
               'check point software technologies' => 'Check Point Software Technologies',
               'cisco systems' => 'Cisco Systems',
               'corero network security' => 'Corero Network Security',
               'cyxtera technologies' => 'Cyxtera Technologies',
               'dome9 security' => 'Dome9 Security',
               'eset' => 'ESET',
               'f5 networks (nginx)' => 'F5 Networks (NGINX)',
               'fireeye' => 'FireEye',
               'forcepoint' => 'Forcepoint',
               'globaldots' => 'GlobalDots',
               'ibm corporation' => 'IBM Corporation',
               'indeni' => 'Indeni',
               'juniper networks' => 'Juniper Networks',
               'kemp technologies' => 'KEMP Technologies',
               'keycdn' => 'KeyCDN',
               'litespeed technologies' => 'LiteSpeed Technologies',
               'logrhythm' => 'LogRhythm',
               'mcafee' => 'McAfee',
               'nginx' => 'NGINX',
               'oracle corporation' => 'Oracle Corporation',
               'penta security systems' => 'Penta Security Systems',
               'perimeterx' => 'PerimeterX',
               'positive technologies' => 'Positive Technologies',
               'qrator labs' => 'Qrator Labs',
               'rackspace' => 'Rackspace',
               'reblaze' => 'Reblaze',
               'redshield security' => 'RedShield Security',
               'sangfor technologies' => 'Sangfor Technologies',
               'securelayer7' => 'SecureLayer7',
               'sentinelone' => 'SentinelOne',
               'shape security' => 'Shape Security',
               'sonicwall' => 'SonicWall',
               'squarespace' => 'Squarespace',
               'symantec corporation' => 'Symantec Corporation',
               'tenable' => 'Tenable',
               'threatstop' => 'ThreatSTOP',
               'venafi' => 'Venafi',
               'virsec systems' => 'Virsec Systems',
               'webroot' => 'Webroot',
               'yottaa' => 'Yottaa',
               'zenedge' => 'Zenedge',
               'alertenterprise' => 'AlertEnterprise',
               'astra security' => 'Astra Security',
               'balabit' => 'BalaBit',
               'beyond security' => 'Beyond Security',
               'bitninja' => 'BitNinja',
               'blockdos' => 'BlockDoS',
               'bluedon information security technologies' => 'Bluedon Information Security Technologies',
               'bockchainlayer' => 'BockchainLayer',
               'brocade communications systems' => 'Brocade Communications Systems',
               'centrify' => 'Centrify',
               'cloudbric' => 'Cloudbric',
               'cognosec' => 'Cognosec',
               'cyberark' => 'CyberArk',
               'datadog' => 'Datadog',
               'dflabs' => 'DFLabs',
               'digicert' => 'DigiCert',
               'duo security' => 'Duo Security',
               'edgewise networks' => 'Edgewise Networks',
               'fastly' => 'Fastly',
               'impreva counterbreach' => 'Impreva CounterBreach',
               'instart logic' => 'Instart Logic',
               'keyfactor' => 'Keyfactor',
               'lightcyber' => 'LightCyber',
               'link11' => 'Link11',
               'manageengine' => 'ManageEngine',
               'microsoft corporation' => 'Microsoft Corporation',
               'netskope' => 'Netskope',
               'niddel' => 'Niddel',
               'nudata security' => 'NuData Security',
               'opswat' => 'Opswat',
               'palo alto networks' => 'Palo Alto Networks',
               'pradeo' => 'Pradeo',
               'proofpoint' => 'Proofpoint',
               'qualysguard' => 'QualysGuard',
               'rsa security' => 'RSA Security',
      ],
      'x-content-type-options' => [
          'nosniff' => 'Microsoft IIS',
      ],
      'x-powered-by' => [
          'plesklin' => 'Plesk',
      ],
  ];
  $ch = curl_init();
  curl_setopt_array($ch, [
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_NOBODY => true,
      CURLOPT_HEADER => true,
  ]);
  $responseHeaders = curl_exec($ch);
  if (curl_errno($ch)) {
      echo 'Error: ' . curl_error($ch);
      exit;
  }
  curl_close($ch);
  $headers = [];
  foreach (explode("\r\n", $responseHeaders) as $headerLine) {
      if (strpos($headerLine, ': ') !== false) {
          list($headerName, $headerValue) = explode(': ', $headerLine);
          $headers[strtolower($headerName)] = strtolower($headerValue);
      }
  }
  $detectedFirewalls = [];
  foreach ($firewallHeaders as $headerName => $firewallSignatures) {
      if (isset($headers[$headerName])) {
          foreach ($firewallSignatures as $signature => $firewallName) {
              if (strpos($headers[$headerName], $signature) !== false) {
                  $detectedFirewalls[] = $firewallName;
              }
          }
      }
  }
  return array_unique($detectedFirewalls);
}

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
      $url = $_GET['url'];
      $response = [
          'url' => $url,
          'firewalls' => detectFirewall($url)
      ];

      // start json check
      if (isset($_GET['json']))
      {
        header("Content-Type: application/json");
        $api->QueryLog($url, "FirewallDetection", $_GET['key']);
        echo json_encode($response, JSON_PRETTY_PRINT);
      }
      else {
        echo "URL: " . $response['url'] . "\n";
        echo "Firewalls: " . implode(", ", $response['firewalls']);
        $api->QueryLog($url, "FirewallDetection", $_GET['key']);
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
