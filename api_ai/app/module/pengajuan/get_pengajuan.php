<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    include "../../../bin/koneksi.php";

    $no_kantor = $_SESSION['no_kantor'];

    if($_SESSION['no_kantor']==''){

        $sql	= "SELECT * FROM view_pengajuan WHERE status !='REJECT' ORDER BY no_pengajuan DESC";

    }else{

        $sql	= "SELECT * FROM view_pengajuan WHERE no_kantor='$no_kantor' AND status !='REJECT' ORDER BY no_pengajuan DESC";

    }

    $hasil	= $konek->query($sql);

    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['no_pengajuan'] = $row['no_pengajuan'];

        $r['nama_mustahik'] = $row['nama_mustahik'];

        $r['kegiatan'] = $row['kegiatan'];

        $r['jml_pengajuan'] = number_format($row['jml_pengajuan']);

        $r['jenis'] = $row['jenis'];

        $r['tgl_realisasi'] = $row['tgl_realisasi'];

        $r['jml_realisasi'] = number_format($row['jml_realisasi']);

        $r['status'] = $row['status'];

        array_push($response["data"], $r);
    }

    echo json_encode($response);
}