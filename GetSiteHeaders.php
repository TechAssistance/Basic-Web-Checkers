<?php

require_once '../app/require.php';
$api = new APIController;

function getHeadersByUrl($url)
{
    $headers = get_headers($url, 1);
    return $headers;
}

if (isset($_GET['key']))
{
  $statusCheck = $api->CheckKey($_GET['key'], $_SERVER['REQUEST_URI']);
  if ($statusCheck === "valid")
  {
    if (isset($_GET['url']))
    {
      $url = $_GET['url'];

      if (filter_var($url, FILTER_VALIDATE_URL))
      {
        $headers = getHeadersByUrl($url);

        if (isset($_GET['json']))
        {
          header("Content-Type: application/json");
          $api->QueryLog($url, "GetHeaders", $_GET['key']);
          echo json_encode([
              'url' => $url,
              'headers' => $headers
          ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }
        else {
          $api->QueryLog($url, "GetHeaders", $_GET['key']);
          foreach ($headers as $header => $value) {
            echo $header . ": " . (is_array($value) ? implode(', ', $value) : $value) . "<br>";
          }
        }
      }
      else {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header("Content-Type: application/json");
        echo json_encode([
            'error' => 'Invalid URL format'
        ]);
      }
    }
    else {
      header("Access-Control-Allow-Origin: *");
      header("Access-Control-Allow-Headers: *");
      header("Content-Type: application/json");
      echo json_encode([
          'error' => 'Missing "url" parameter'
      ]);
    }
  }
  else {
    echo $statusCheck;
  }
}
else {
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: *");
  header("Content-Type: application/json");
  echo json_encode([
      'error' => 'Missing "key" parameter'
  ]);
}
