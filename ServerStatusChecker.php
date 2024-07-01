<?php

function url_get_status($url)
{
    $process = curl_init($url);
    curl_setopt($process, CURLOPT_TIMEOUT, 30);
    curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($process, CURLOPT_FOLLOWLOCATION, true);

    curl_exec($process);
    $http_code = curl_getinfo($process, CURLINFO_HTTP_CODE);
    curl_close($process);
    return $http_code;
}

function get_data($link)
{
    try {
        $http_code = url_get_status($link);
        $data["link"] = $link;
        $data["http_code"] = is_numeric($http_code) ? $http_code : "None";
      //  $data["status"] = is_numeric($http_code) ? $STATUS_CODES[$http_code] : "None";
      switch ($http_code)
      {
        case "100": $data['status'] = "Continue"; break;
        case "101": $data['status'] = "Switching Protocols"; break;
        case "102": $data['status'] = "Processing"; break;
        case "103": $data['status'] = "Early Hints"; break;
        case "200": $data['status'] = "OK"; break;
        case "201": $data['status'] = "Created"; break;
        case "202": $data['status'] = "Accepted"; break;
        case "203": $data['status'] = "Non-Authoritative Information"; break;
        case "204": $data['status'] = "No Content"; break;
        case "205": $data['status'] = "Reset Content"; break;
        case "206": $data['status'] = "Partial Content"; break;
        case "207": $data['status'] = "Multi-Status"; break;
        case "208": $data['status'] = "Already Reported"; break;
        case "226": $data['status'] = "IM Used"; break;
        case "300": $data['status'] = "Multiple Choices"; break;
        case "301": $data['status'] = "Moved Permanently"; break;
        case "302": $data['status'] = "Found"; break;
        case "303": $data['status'] = "See Other"; break;
        case "304": $data['status'] = "Not Modified"; break;
        case "305": $data['status'] = "Use Proxy"; break;
        case "306": $data['status'] = "Switch Proxy"; break;
        case "307": $data['status'] = "Temporary Redirect"; break;
        case "308": $data['status'] = "Permanent Redirect"; break;
        case "400": $data['status'] = "Bad Request"; break;
        case "401": $data['status'] = "Unauthorized"; break;
        case "402": $data['status'] = "Payment Required"; break;
        case "403": $data['status'] = "Forbidden"; break;
        case "404": $data['status'] = "Not Found"; break;
        case "405": $data['status'] = "Method Not Allowed"; break;
        case "406": $data['status'] = "Not Acceptable"; break;
        case "407": $data['status'] = "Proxy Authentication Required"; break;
        case "408": $data['status'] = "Request Timeout"; break;
        case "409": $data['status'] = "Conflict"; break;
        case "410": $data['status'] = "Gone"; break;
        case "411": $data['status'] = "Length Required"; break;
        case "412": $data['status'] = "Precondition Failed"; break;
        case "413": $data['status'] = "Payload Too Large"; break;
        case "414": $data['status'] = "URI Too Long"; break;
        case "415": $data['status'] = "Unsupported Media Type"; break;
        case "416": $data['status'] = "Range Not Satisfiable"; break;
        case "417": $data['status'] = "Expectation Failed"; break;
        case "418": $data['status'] = "I\'m a teapot"; break;
        case "421": $data['status'] = "Misdirected Request"; break;
        case "422": $data['status'] = "Unprocessable Entity"; break;
        case "423": $data['status'] = "Locked"; break;
        case "424": $data['status'] = "Failed Dependency"; break;
        case "425": $data['status'] = "Too Early"; break;
        case "426": $data['status'] = "Upgrade Required"; break;
        case "428": $data['status'] = "Precondition Required"; break;
        case "429": $data['status'] = "Too Many Requests"; break;
        case "431": $data['status'] = "Request Header Fields Too Large"; break;
        case "451": $data['status'] = "Unavailable For Legal Reasons"; break;
        case "500": $data['status'] = "Internal Server Error"; break;
        case "501": $data['status'] = "Not Implemented"; break;
        case "502": $data['status'] = "Bad Gateway"; break;
        case "503": $data['status'] = "Service Unavailable"; break;
        case "504": $data['status'] = "Gateway Timeout"; break;
        case "505": $data['status'] = "HTTP Version Not Supported"; break;
        case "506": $data['status'] = "Variant Also Negotiates"; break;
        case "507": $data['status'] = "Insufficient Storage"; break;
        case "508": $data['status'] = "Loop Detected"; break;
        case "510": $data['status'] = "Not Extended"; break;
        case "511": $data['status'] = "Network Authentication Required"; break;
        case "103": $data['status'] = "Checkpoint"; break;
        case "218": $data['status'] = "This is fine"; break;
        case "419": $data['status'] = "Page Expired"; break;
        case "420": $data['status'] = "Method Failure"; break;
        case "420": $data['status'] = "Enhance Your Calm"; break;
        case "430": $data['status'] = "Request Header Fields Too Large"; break;
        case "450": $data['status'] = "Blocked by Windows Parental Controls"; break;
        case "498": $data['status'] = "Invalid Token"; break;
        case "499": $data['status'] = "Token Required"; break;
        case "509": $data['status'] = "Bandwidth Limit Exceeded"; break;
        case "526": $data['status'] = "Invalid SSL Certificate"; break;
        case "529": $data['status'] = "Site is overloaded"; break;
        case "530": $data['status'] = "Site is frozen"; break;
        case "598": $data['status'] = "Network read timeout error"; break;
        case "440": $data['status'] = "Login Time-out"; break;
        case "449": $data['status'] = "Retry With"; break;
        case "451": $data['status'] = "Redirect"; break;
        case "444": $data['status'] = "No Response"; break;
        case "494": $data['status'] = "Request header too large"; break;
        case "495": $data['status'] = "SSL Certificate Error"; break;
        case "496": $data['status'] = "SSL Certificate Required"; break;
        case "497": $data['status'] = "HTTP Request Sent to HTTPS Port"; break;
        case "499": $data['status'] = "Client Closed Request"; break;
        case "520": $data['status'] = "Web Server Returned an Unknown Error"; break;
        case "521": $data['status'] = "Web Server Is Down"; break;
        case "522": $data['status'] = "Connection Timed Out"; break;
        case "523": $data['status'] = "Origin Is Unreachable"; break;
        case "524": $data['status'] = "A Timeout Occurred"; break;
        case "525": $data['status'] = "SSL Handshake Failed"; break;
        case "526": $data['status'] = "Invalid SSL Certificate"; break;
        case "527": $data['status'] = "Railgun Error"; break;
      }
        //return $data;
        return array(
            'link' => $data["link"],
            'http_code' => $data["http_code"],
            'status' => $data['status'],
        );
    } catch (\Exception $e) {
        session()->flash("status", "error");
        session()->flash("message", __($e->getMessage()));
        return;
    }
}
if (isset($_GET["url"])) {
    $SetData = get_data($_GET["url"]);
    if ($SetData != null) {
      echo nl2br("URL: ". $SetData['link'] ."\nResponse Code: ". $SetData['http_code'] ."\nStatus: ". $SetData['status']);
    }
} else {
    die("Get Parameter Not Set");
}
