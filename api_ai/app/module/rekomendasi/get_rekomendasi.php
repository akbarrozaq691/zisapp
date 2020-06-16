<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    include "../../../bin/koneksi.php";

    $sql	= "SELECT * FROM view_pengajuan WHERE status !='REJECT'";

    $hasil	= $konek->query($sql);

    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['no_pengajuan'] = $row['no_pengajuan'];
        
        $r['no_disposisi'] = $row['no_disposisi'];

        $r['nama_mustahik'] = $row['nama_mustahik'];

        $r['kegiatan'] = $row['kegiatan'];

        $r['perihal'] = $row['perihal'];

        $r['tgl_survey'] = $row['tgl_survey'];

        $r['keterangan'] = $row['keterangan'];

        $r['jml_pengajuan'] = number_format($row['jml_pengajuan']);

        $r['nama_kantor'] = $row['nama_kantor'];

        $r['jenis'] = $row['jenis'];

        $r['status'] = $row['status'];

        array_push($response["data"], $r);
    }

    echo json_encode($response);
}