<?php

$server 	= "localhost";
// $server 	= "101.50.2.45";
$user 		= "root";
$password 	= "";
$db 		= "dblazai";

$konek = mysqli_connect($server,$user, $password,$db);
if($konek){
	//echo "Koneksi Database Sukses";
} else {
	die('Koneksi Gagal');
	mysql_error();
}
?>