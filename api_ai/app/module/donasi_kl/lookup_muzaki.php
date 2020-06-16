<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    
    include "../../../bin/koneksi.php";

    $no_kantor = $_SESSION['no_kantor'];

    $sql	= "SELECT npwz, nama_donatur FROM tm_donatur WHERE status='Aktif'";

    $hasil	= $konek->query($sql);
    
    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['npwz'] = $row['npwz'];

        $r['nama_donatur'] = $row['nama_donatur'];

        array_push($response["data"], $r);

    }

    echo json_encode($response);

}