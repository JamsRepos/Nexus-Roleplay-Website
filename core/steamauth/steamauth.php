<?php
ob_start();
session_start();

function logoutbutton()
{
	echo "<form action='' method='get'><button style='cursor: pointer' class='dropdown-item' name='logout' type='submit'>Logout</button></form>"; //logout button
}

function loginbutton($buttonstyle = "square")
{
	$button['rectangle'] = "01";
	$button['square'] = "02";
	$button = "<a href='?login'><img src='https://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_" . $button[$buttonstyle] . ".png'></a>";

	echo $button;
}

if (isset($_GET['login'])) {
	require 'openid.php';
	try {
		require 'SteamConfig.php';
		$openid = new LightOpenID($steamauth['domainname']);

		if (!$openid->mode) {
			$openid->identity = 'https://steamcommunity.com/openid';
			header('Location: ' . $openid->authUrl());
		} elseif ($openid->mode == 'cancel') {
			echo 'User has canceled authentication!';
		} else {
			if ($openid->validate()) {
				$id = $openid->identity;
				$ptn = "/^https?:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
				preg_match($ptn, $id, $matches);

				$_SESSION['steamid'] = $matches[1];
				if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
					$ip = $_SERVER['HTTP_CLIENT_IP'];
				} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
					$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
				} else {
					$ip = $_SERVER['REMOTE_ADDR'];
				}
				$statement = $conn->prepare('SELECT * FROM nexus_siteusers WHERE steamid = :id');
				$statement->execute(array(':id' => $_SESSION['steamid']));
				if ($statement->rowCount() > 0) {
					$stmt = $conn->prepare("UPDATE nexus_siteusers SET lastip= ? WHERE steamid = ?");
					$stmt->execute([$ip, $_SESSION['steamid']]);
				} else {
					$pid = '0';
					$url = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=" . $steamauth['apikey'] . "&steamids=" . $_SESSION['steamid']);
					$content = json_decode($url, true);
					$_sudata['steam_personaname'] = $content['response']['players'][0]['personaname'];
					$_sudata['steam_avatarmedium'] = $content['response']['players'][0]['avatarmedium'];
					$stmt = $conn->prepare("INSERT INTO nexus_siteusers (steamname, steamid, steampic, pid, lastip) VALUES (?,?,?,?, ?) ON DUPLICATE KEY UPDATE steamid=VALUES(steamid);");
					$stmt->execute([$_sudata['steam_personaname'], $_SESSION['steamid'], $_sudata['steam_avatarmedium'], $pid, $ip]);
				}
				if (!headers_sent()) {
					header('Location: ' . $steamauth['loginpage']);
					exit;
				} else {
?>
					<script type="text/javascript">
						window.location.href = "<?= $steamauth['loginpage'] ?>";
					</script>
					<noscript>
						<meta http-equiv="refresh" content="0;url=<?= $steamauth['loginpage'] ?>" />
					</noscript>
<?php
					exit;
				}
			} else {
				echo "User is not logged in.\n";
			}
		}
	} catch (ErrorException $e) {
		echo $e->getMessage();
	}
}

if (isset($_GET['logout'])) {
	require 'SteamConfig.php';
	session_unset();
	session_destroy();
	header('Location: ' . $steamauth['logoutpage']);
	exit;
}

if (isset($_GET['update'])) {
	unset($_SESSION['steam_uptodate']);
	require 'userInfo.php';
	header('Location: ' . $_SERVER['PHP_SELF']);
	exit;
}

// Version 4.0

?>