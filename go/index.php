<?php

// ClickMagick Custom Domain Method #2 v1.6 1/30/2016

// Change XXXXX to your ClickMagick username
$username = "cpaexamguide";

// Set to Y to cloak your links by default, or N to redirect to your
// actual ClickMagick tracking link (which you can then further control
// by editing the cloaking setting at the link level in your account).
$cloak_default = "N";

// Enter the title you want to show at the top of the users' browser
$title = "";

// Enter a backup URL in case of bad links due to typos, etc.
$backup_url = "http://www.clkmg.com/cpaexamguide/sysbackupurl";

// ############################################################################
// ########## DO NOT EDIT ANYTHING BELOW THIS LINE ############################
// ############################################################################

$cloak = $cloak_default;

if (isset($_GET['cloak'])) {
	if ($_GET['cloak'] == 'cl') {
		$cloak = 'Y';
	} elseif ($_GET['cloak'] == 'nc') {
		$cloak = 'N';
	}
}

if( isset($_SERVER['HTTPS'] ) ) { $proto = "https"; }
else                             { $proto = "http";  }

$url = "${proto}://www.clkmg.com/$username/";

$l  = isset($_GET['l'])  ? $_GET['l']  : null;
$s1 = isset($_GET['s1']) ? $_GET['s1'] : null;
$s2 = isset($_GET['s2']) ? $_GET['s2'] : null;
$s3 = isset($_GET['s3']) ? $_GET['s3'] : null;
$s4 = isset($_GET['s4']) ? $_GET['s4'] : null;
$s5 = isset($_GET['s5']) ? $_GET['s5'] : null;
$c  = isset($_GET['c'])  ? $_GET['c']  : null;

$qs = $_SERVER['QUERY_STRING'];
$qsp = explode('q=', $qs);
$q = count($qsp) == 2 ? $qsp[1] : null;

if (!empty($l))  { $url .= $l . '/';  }
if (!empty($s1)) { $url .= $s1 . '/'; }
if (!empty($s2)) { $url .= $s2 . '/'; }
if (!empty($s3)) { $url .= $s3 . '/'; }
if (!empty($s4)) { $url .= $s4 . '/'; }
if (!empty($s5)) { $url .= $s5 . '/'; }
if (!empty($c))  { $url .= $c . '/';  }
if (!empty($q))  { $url .= '?' . $q;  }

if (preg_match('/^https?\:\/\/www\.clkmg\.com\/[a-zA-Z0-9\-_]{4,}\/[a-zA-Z0-9\-]{4,}\/?([a-zA-Z0-9\-\_\.\=\?\%\@\+\:\{\}\ ]+)?\/?([a-zA-Z0-9\-\_\.\=\?\%\@\+\:\{\}\ ]+)?\/?([a-zA-Z0-9\-\_\.\=\?\%\@\+\:\{\}\ ]+)?\/?([a-zA-Z0-9\-\_\.\=\?\%\@\+\:\{\}\ ]+)?\/?([a-zA-Z0-9\-\_\.\=\?\%\@\+\:\{\}\ ]+)?\/?([0-9]{1,2}\.[0-9]{2,3})?\/?(.+)?$/', $url)) {

	if ($cloak == 'Y') {

		echo "<!DOCTYPE html>\n";
		echo "<html><head><meta name=\"viewport\" content=\"width=device-width, initial-scale=1, maximum-scale=1\" /><title>$title</title></head>\n";
		echo "<frameset rows=\"100%\"><frame src=\"$url\"/></frameset>\n";
		echo "</html>\n";
		
	}

	else {

		header("Location: $url");
		die();

	}

}

else {

	header("Location: $backup_url");
	die();

}

?>
