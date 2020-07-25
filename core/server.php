<?php
require('steamauth/SteamConfig.php');

$stmt = $conn->prepare('SELECT * FROM nexus_serverlist');
$stmt->execute();
while ($row = $stmt->fetch()) {
    $ip = $row['serverip'];
    $port = $row['serverport'];
    $serverip = $ip . ':' . $port;
}
// FiveM API Shit
function callAPI($method, $url, $data)
{
    $curl = curl_init();
    switch ($method) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }
    // OPTIONS:
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'APIKEY: 111111111111111111111',
        'Content-Type: application/json',
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    // EXECUTE:
    $result = curl_exec($curl);
    if (!$result) {
        die("Connection Failure");
    }
    curl_close($curl);
    return $result;
}
function isAPIOnline($domain)
{
    //check, if a valid url is provided
    if (!filter_var($domain, FILTER_VALIDATE_URL)) {
        return false;
    }

    //initialize curl
    $curlInit = curl_init($domain);
    curl_setopt($curlInit, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($curlInit, CURLOPT_HEADER, true);
    curl_setopt($curlInit, CURLOPT_NOBODY, true);
    curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);

    //get answer
    $response = curl_exec($curlInit);

    curl_close($curlInit);

    if ($response) return true;

    return false;
}




if (isAPIOnline('http://'. $serverip .'/info.json')) {

    $status = "online";

    $get_data = callAPI('GET', "http://$serverip/info.json", false);
    $response = json_decode($get_data, true);
    $maxplayers = $response["vars"]["sv_maxClients"];

    $get_data2 = callAPI('GET', "http://$serverip/players.json", false);
    $response2 = json_decode($get_data2, true);
    $players = count($response2);
    $msg = "Players Online";
} else {
    $status = "offline";
    $players = 0;
    $maxplayers = 0;
    $msg = "Server Offline";
}
?>

<button type="button" class="btn btn-nexus <?=$status?>"><?=$players?>/<?=$maxplayers?> <?=$msg?></button>
<button type="button" class="btn btn-nexus <?=$status?>" style="margin-left: 0px; padding-left: 0px!important;"><i class="fas fa-sign-in-alt"></i></button>