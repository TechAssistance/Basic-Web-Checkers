<?php
require_once '../app/require.php';
$api = new APIController;

function format_size($bytes, $force_unit = null, $format = null, $si = true)
  {
      // Format string
      $format = ($format === null) ? '%01.2f %s' : (string) $format;
      if (($si == false) || (strpos($force_unit, 'i') !== false)) {
          // IEC prefixes (binary)
          $units = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB');
          $mod   = 1024;
      } else {
          // SI prefixes (decimal)
          $units = array('B', 'kB', 'MB', 'GB', 'TB', 'PB');
          $mod   = 1000;
      }
      // Determine unit to use
      if (($power = array_search((string) $force_unit, $units)) === false) {
          $power = ($bytes > 0) ? floor(log($bytes, $mod)) : 0;
      }
      return sprintf($format, $bytes / pow($mod, $power), $units[$power]);
  }
  function url_get_contents($url)
  {
      $process = curl_init($url);
      curl_setopt($process, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36');
      curl_setopt($process, CURLOPT_TIMEOUT, 60);
      curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($process, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($process, CURLOPT_SSL_VERIFYHOST, 2);

      $return = curl_exec($process);
      curl_close($process);
      return $return;
  }
function get_data($link)
{
      try {
          $get_source    = url_get_contents($link);
          $data['link']  = $link;
          $data['bytes'] = strlen($get_source);
          $data['kb']    = format_size(strlen($get_source), 'kB');
          $data['mb']    = format_size(strlen($get_source), 'MB');
          $data['gb']    = format_size(strlen($get_source), 'GB');
          //return $data;
          return array(
              'link' => $data["link"],
              'bytes' => $data["bytes"],
              'kb' => $data['kb'],
              'mb' => $data['mb'],
              'gb' => $data['gb'],
          );
      } catch (\Exception $e) {

          session()->flash('status', 'error');
          session()->flash('message', __($e->getMessage()));
          return;
      }
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
      if (strpos($_GET['url'], "http") !== false)
      {
        $api->QueryLog("Invalid URL: ".$_GET['url'], "URLSize", $_GET['key']);
        echo nl2br("Bad Request Input: ". $_GET['url'] ."\nURL Parameter Does NOT use HTTP/HTTPS!\nUse Raw URL [site.com/file.extension]\nExample: site.com/index.php");
      } else {
        $GetData = get_data($_GET['url']);
        if ($GetData != null)
        {
          $api->QueryLog($_GET['url'], "URLSize", $_GET['key']);
          echo nl2br("URL: ". $GetData['link'] ."\nBytes: ". $GetData['bytes'] ."\nkB: ". $GetData['kb'] ."\nMB: ". $GetData['mb'] ."\nGB: ". $GetData['gb'] ."");
        }
      }
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
