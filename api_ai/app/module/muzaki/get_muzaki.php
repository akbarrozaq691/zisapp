<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    include "../../../bin/koneksi.php";

    $no_kantor = $_SESSION['no_kantor'];

    $sql	= "SELECT * FROM view_donatur WHERE status='Aktif'";

    $hasil	= $konek->query($sql);

    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['npwz'] = $row['npwz'];

        $r['nama_donatur'] = $row['nama_donatur'];

        $r['nik'] = $row['nik'];

        $r['kategori'] = $row['kategori'];

        $r['no_hp'] = $row['no_hp'];

        $r['nama_petugas'] = $row['nama_petugas'];

        $r['no_kantor'] = $row['no_kantor'];

        array_push($response["data"], $r);
    }

    echo json_encode($response);
}