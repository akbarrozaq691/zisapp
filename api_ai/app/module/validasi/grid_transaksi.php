<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $kode_kategori  = $_POST['kode_kategori'];

    $periode        = $_POST['periode'];

    $tgl_transaksi  =  $_POST['tgl_transaksi'];

    $petugas        =  $_POST['petugas'];

    $sql	        = "SELECT * FROM view_donasi 
                    
                        WHERE 
                            
                            kode_kategori='$kode_kategori' AND 
                            
                            periode='$periode' AND 
                            
                            tgl_donasi = '$tgl_transaksi' AND

                            petugas = '$petugas' AND 

                            status='Complete'";
    
    $result	= $konek->query($sql);

    $data   = '';

    while($row = $result->fetch_assoc()){

        $data .="
            <tr>

                <td>". $row['nama_donatur'] ."</td>

                <td>". $row['program'] ."</td>

                <td>". number_format($row['jml_donasi'], 0, ',', '.') ."</td>

            </tr>
        ";

    }

    echo $data;
}