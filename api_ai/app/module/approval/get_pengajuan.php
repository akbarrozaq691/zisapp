<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    include "../../../bin/koneksi.php";

    $no_kantor = $_SESSION['no_kantor'];

    $sql	= "SELECT * FROM view_pengajuan WHERE status ='Proses'";

    $hasil	= $konek->query($sql);

    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['no_pengajuan'] = $row['no_pengajuan'];

        $r['no_disposisi'] = $row['no_disposisi'];

        $r['nama_mustahik'] = $row['nama_mustahik'];

        $r['kegiatan'] = $row['kegiatan'];

        $r['jml_pengajuan'] = number_format($row['jml_pengajuan']);

        $r['jenis'] = $row['jenis'];

        $r['status'] = $row['status'];

        array_push($response["data"], $r);
    }

    echo json_encode($response);
}