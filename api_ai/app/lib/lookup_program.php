<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    
    include "../../bin/koneksi.php";

    $sql	= "SELECT kode_program, program, kategori, kode_kategori, kode_akun, akun, kode_kas, nama_kas FROM view_program";

    $hasil	= $konek->query($sql);
    
    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['kode_program'] = $row['kode_program'];

        $r['program'] = $row['program'];

        $r['kategori'] = $row['kategori'];

        $r['kode_kategori'] = $row['kode_kategori'];

        $r['kode_akun'] = $row['kode_akun'];

        $r['akun'] = $row['akun'];

        $r['kode_kas'] = $row['kode_kas'];

        $r['nama_kas'] = $row['nama_kas'];

        array_push($response["data"], $r);

    }

    echo json_encode($response);

}