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

$sql = "CREATE TABLE IF NOT EXISTS `nexus_navbar` (
    sid INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    lname VARCHAR(30) NOT NULL,
    link VARCHAR(500) NOT NULL,
    sortby VARCHAR(15) NOT NULL

   )";
$conn->exec($sql);

$sql = "CREATE TABLE IF NOT EXISTS `nexus_permissions` (
    `id` int(10) UNSIGNED NOT NULL,
    `display_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL

   )";
$conn->exec($sql);

$statement = $conn->prepare('SELECT * FROM nexus_permissions');
$statement->execute();
if ($statement->rowCount() > 0) {
} else {
    $sql = "INSERT INTO `nexus_permissions` (`id`, `display_name`, `description`) VALUES
            (1, 'General Settings', 'Edit general settings & server ip'),
            (2, 'Navbar Settings', 'Edit navigation links'),
            (3, 'View Users', 'View all the users signed up'),
            (4, 'Permissions Settings', 'Be able to change users permissions');

            )";
    $conn->exec($sql);
}

$sql = "CREATE TABLE IF NOT EXISTS `nexus_permission_user` (
    `permission_id` int(10) UNSIGNED NOT NULL,
    `user_steamid` VARCHAR(50) NOT NULL

   )";
$conn->exec($sql);

$sql = "CREATE TABLE IF NOT EXISTS `nexus_forms` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`name` TEXT NOT NULL,
	`pic` TEXT NOT NULL,
	`created_by` INT NOT NULL,
	`orderby` INT NOT NULL,
    `cooldown` INT DEFAULT '0',
	PRIMARY KEY (`id`)
    )";
$conn->exec($sql);

$sql = "CREATE TABLE IF NOT EXISTS `nexus_questions` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `form_id` int(11) NOT NULL,
    `type` text NOT NULL,
    `question` text NOT NULL,
    `placeholder` text NOT NULL,
    `selectables` text,
    `orderby` text(11) NOT NULL,
    `created_by` text(11) NOT NULL,
      PRIMARY KEY (`id`)
  )";
$conn->exec($sql);

$sql = "CREATE TABLE IF NOT EXISTS `nexus_apps` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`form_id` INT NOT NULL,
	`data` LONGTEXT NOT NULL,
	`status` TEXT NOT NULL,
	`userid` TEXT NOT NULL,
	`date_created` TEXT NOT NULL,
	`date_updated` TEXT,
    `reason` TEXT,
	PRIMARY KEY (`id`)
);";
$conn->exec($sql);
