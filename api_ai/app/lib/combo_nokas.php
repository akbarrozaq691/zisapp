<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    include "../../bin/koneksi.php";

    $sql	= "SELECT * FROM view_trs_kas WHERE status='Aktif'";

    $hasil	= $konek->query($sql);

    $list   = array();

    while($row 	= $hasil->fetch_assoc()){

        array_push($list, $row);
    }

    echo json_encode($list);

}