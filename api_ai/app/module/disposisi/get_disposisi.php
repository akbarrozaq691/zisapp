<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    include "../../../bin/koneksi.php";

    $sql	= "SELECT no_disposisi, perihal, pengirim, tgl_surat, no_surat, status FROM trs_disposisi WHERE status !='REJECT' ORDER BY no_disposisi DESC";

    $hasil	= $konek->query($sql);

    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['no_disposisi'] = $row['no_disposisi'];

        $r['perihal'] = $row['perihal'];

        $r['pengirim'] = $row['pengirim'];

        $r['tgl_surat'] = $row['tgl_surat'];

        $r['no_surat'] = $row['no_surat'];

        $r['status'] = $row['status'];

        array_push($response["data"], $r);
    }

    echo json_encode($response);
}