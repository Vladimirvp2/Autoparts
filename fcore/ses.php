<?php

include_once('parsers/zp.php');

include_once('data.php');

session_start();

if( ! isset($_SESSION["h"]) ){
	$_SESSION["h"] = "5";
	echo "Session set";
}

if( ! isset($_SESSION["b"]) ){
	$ob = new SiteZP();
	$_SESSION["b"] = $ob;
	echo "Session ZP set";
}

if( ! isset($_SESSION["d"]) ){
	$d = new Data();
	$_SESSION["d"] = $d;
	echo "Session Data set";
}


$_SESSION["b"]->test();
echo $_SESSION["h"] . '<br>';
echo 'Finish';

$links = $_SESSION["d"]->getBrands();
foreach ($links as &$link){
	echo "Brand:     " . $link['text'] . ',   ' . $link['link'] . '<br>';
}

?>