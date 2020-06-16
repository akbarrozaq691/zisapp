<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    include "../../../bin/koneksi.php";

    $kode_petugas = $_SESSION['kode_petugas'];

    if($kode_petugas==''){

        $sql	= "SELECT * FROM view_donasi WHERE status_hdr='Aktif'";        

    } else {

        $sql	= "SELECT * FROM view_donasi WHERE status_hdr='Aktif' AND petugas='$kode_petugas'";

    }

    $hasil	= $konek->query($sql);

    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['no_donasi'] = $row['no_donasi'];

        $r['npwz'] = $row['npwz'];

        $r['nama_donatur'] = $row['nama_donatur'];

        $r['norek_bank'] = $row['norek_bank'];

        $r['program'] = $row['program'];

        $r['jml_donasi'] = $row['jml_donasi'];

        array_push($response["data"], $r);
    }

    echo json_encode($response);
}