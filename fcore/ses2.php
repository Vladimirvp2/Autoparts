<?php

include_once('parsers/zp.php');

include_once('data.php');

session_start();



echo session_save_path() . '<br>';

$links = $_SESSION["d"]->getBrands();
foreach ($links as &$link){
	echo "Brand:     " . $link['text'] . ',   ' . $link['link'] . '<br>';
}




?>