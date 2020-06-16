<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    include "../../../bin/koneksi.php";

    $sql	= "SELECT no_rekening, nama_bank, phone FROM view_bank_kl WHERE status='Aktif'";

    $hasil	= $konek->query($sql);

    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['no_rekening'] = $row['no_rekening'];

        $r['nama_bank'] = $row['nama_bank'];

        $r['phone'] = $row['phone'];

        array_push($response["data"], $r);
    }

    echo json_encode($response);
}