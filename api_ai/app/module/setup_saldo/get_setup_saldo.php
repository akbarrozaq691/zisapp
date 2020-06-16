<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    include "../../../bin/koneksi.php";

    $sql	= "SELECT no_trskas, nama_kas, saldo_awal, periode FROM view_kas WHERE status ='Aktif'";

    $hasil	= $konek->query($sql);

    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['no_trskas'] = $row['no_trskas'];

        $r['nama_kas'] = $row['nama_kas'];

        $r['saldo_awal'] = number_format($row['saldo_awal'], 0, ',', '.');

        $r['periode'] = $row['periode'];

        array_push($response["data"], $r);
    }

    echo json_encode($response);
}