<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    
    include "../../../bin/koneksi.php";

    $sql	= "SELECT no_kantor, nama_kantor, pimpinan FROM tm_kantor_layanan";

    $hasil	= $konek->query($sql);
    
    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['no_kantor'] = $row['no_kantor'];

        $r['nama_kantor'] = $row['nama_kantor'];

        $r['pimpinan'] = $row['pimpinan'];

        array_push($response["data"], $r);

    }

    echo json_encode($response);

}