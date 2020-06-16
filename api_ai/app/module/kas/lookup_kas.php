<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    
    include "../../../bin/koneksi.php";

    $sql	= "SELECT no_trskas, kode_kas, nama_kas, kode_akun FROM view_kas";

    $hasil	= $konek->query($sql);
    
    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['no_trskas'] = $row['no_trskas'];

        $r['kode_kas'] = $row['kode_kas'];

        $r['nama_kas'] = $row['nama_kas'];

        $r['kode_akun'] = $row['kode_akun'];

        array_push($response["data"], $r);

    }

    echo json_encode($response);

}