<?php

$servername = "";
$dbname = "";
$username = "";
$password = "";

//Version 4.0
$steamauth['apikey'] = ""; // Your Steam WebAPI-Key found at https://steamcommunity.com/dev/apikey
$steamauth['domainname'] = "http://localhost/nexusrp/"; // The main URL of your website displayed in the login page
$steamauth['logoutpage'] = "http://localhost/nexusrp/"; // Page to redirect to after a successfull logout (from the directory the SteamAuth-folder is located in) - NO slash at the beginning!
$steamauth['loginpage'] = "http://localhost/nexusrp/admin/"; // Page to redirect to after a successfull login (from the directory the SteamAuth-folder is located in) - NO slash at the beginning!

// System stuff
if (empty($steamauth['apikey'])) {
    die("<div style='display: block; width: 100%; background-color: red; text-align: center;'>SteamAuth:<br>Please supply an API-Key!<br>Find this in steamauth/SteamConfig.php, Find the '<b>\$steamauth['apikey']</b>' Array. </div>");
}
if (empty($steamauth['domainname'])) {
    $steamauth['domainname'] = $_SERVER['SERVER_NAME'];
}
if (empty($steamauth['logoutpage'])) {
    $steamauth['logoutpage'] = $_SERVER['PHP_SELF'];
}
if (empty($steamauth['loginpage'])) {
    $steamauth['loginpage'] = $_SERVER['PHP_SELF'];
}

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = "CREATE TABLE IF NOT EXISTS `nexus_siteusers` (
        uid INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        steamname VARCHAR(50) NOT NULL,
        steamid VARCHAR(50) NOT NULL UNIQUE,
        steampic VARCHAR(150) NOT NULL,
        pid INT(1) DEFAULT '0',
        lastip VARCHAR(15) DEFAULT NULL
       )";
$conn->exec($sql);

$sql = "CREATE TABLE IF NOT EXISTS `nexus_serverlist` (
        sid INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        serverip VARCHAR(30) DEFAULT '185.141.207.151',
        serverport INT(6) DEFAULT '30120'
       )";
$conn->exec($sql);

$statement = $conn->prepare('SELECT * FROM nexus_serverlist');
$statement->execute();
if ($statement->rowCount() > 0) {
} else {
    $stmt = $conn->prepare("INSERT INTO nexus_serverlist (serverip, serverport) VALUES('185.141.207.151', '30120')");
    $stmt->execute();
}

$sql = "CREATE TABLE IF NOT EXISTS `nexus__sitesetting` (
        sid INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        sname VARCHAR(30) DEFAULT 'Nexus Roleplay',
        svalue VARCHAR(500) DEFAULT 'The community built for roleplayers'
       )";
$conn->exec($sql);

$statement = $conn->prepare('SELECT * FROM nexus__sitesetting');
$statement->execute();
if ($statement->rowCount() > 0) {
} else {
    $stmt = $conn->prepare("INSERT INTO nexus__sitesetting (sname, svalue) VALUES('Nexus Roleplay', 'The community built for roleplayers')");
    $stmt->execute();
}


