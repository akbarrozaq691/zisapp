<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $kode_kategori = $_GET['kode_kategori'];

    $periode = $_GET['periode'];

    $tgl_transaksi = $_GET['tgl_transaksi'];

    $petugas = $_GET['petugas'];

    $sqlFind 	= "SELECT SUM(jml_donasi) AS jml_donasi FROM view_donasi 
    
                    WHERE 
                        
                        kode_kategori = '$kode_kategori' AND 
                        
                        periode='$periode' AND 
                        
                        tgl_donasi='$tgl_transaksi' AND 
                        
                        petugas = '$petugas'";
    
    $hasilFind	= $konek->query($sqlFind);

    $data['data'] = $hasilFind->fetch_assoc();
    
    echo json_encode($data['data']);

}