<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    include "../../../bin/koneksi.php";

    $sql	= "SELECT kode_mustahik, nama_mustahik, kategori, hp FROM tm_mustahik WHERE status='Aktif'";

    $hasil	= $konek->query($sql);

    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['kode_mustahik'] = $row['kode_mustahik'];

        $r['nama_mustahik'] = $row['nama_mustahik'];

        $r['kategori'] = $row['kategori'];

        $r['hp'] = $row['hp'];

        array_push($response["data"], $r);
    }

    echo json_encode($response);
}