<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $tgl_donasi = $_GET['tgl_donasi'];

    $kode_petugas = $_SESSION['kode_petugas'];

    $sqlFind 	= "SELECT SUM(jml_donasi) AS total 
    
                    FROM view_penerimaan 
                    
                    WHERE tgl_donasi = '$tgl_donasi' AND createdby='$kode_petugas' AND status='Aktif'";
    
    $hasilFind	= $konek->query($sqlFind);

    $data['data'] = $hasilFind->fetch_assoc();
    
    echo json_encode($data['data']);

}