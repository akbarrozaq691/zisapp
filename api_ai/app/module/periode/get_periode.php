<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    include "../../../bin/koneksi.php";

    $sql	= "SELECT periode, status FROM trs_periode WHERE status !='REJECT'";

    $hasil	= $konek->query($sql);

    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['periode'] = $row['periode'];

        $r['status'] = $row['status'];

        array_push($response["data"], $r);
    }

    echo json_encode($response);
}