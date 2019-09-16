<?php
header("Location: ". $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["HTTP_HOST"].str_replace("index.php","",$_SERVER["PHP_SELF"])."admin/");
?>