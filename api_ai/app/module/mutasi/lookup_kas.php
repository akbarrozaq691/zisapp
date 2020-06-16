<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    
    include "../../../bin/koneksi.php";

    $sql	= "SELECT no_trskas, nama_kas, saldo_berjalan, kode_akun, akun FROM view_trs_kas";

    $hasil	= $konek->query($sql);
    
    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['no_trskas'] = $row['no_trskas'];

        $r['nama_kas'] = $row['nama_kas'];
        
        $r['saldo_berjalan'] = $row['saldo_berjalan'];

        $r['kode_akun'] = $row['kode_akun'];

        $r['akun'] = $row['akun'];

        array_push($response["data"], $r);

    }

    echo json_encode($response);

}