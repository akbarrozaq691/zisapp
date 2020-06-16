<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $tgl_donasi = $_POST['tgl_donasi'];

    $kode_petugas =  $_SESSION['kode_petugas'];

    $sql	= "SELECT kategori, SUM(jml_donasi) AS jumlah FROM view_penerimaan

                WHERE tgl_donasi='$tgl_donasi' AND createdby='$kode_petugas' AND status='Aktif'

                GROUP BY kode_kategori";
    
    $hasil	= $konek->query($sql);

    while($row = $hasil->fetch_assoc()){

        $data ="
                <tr>

                    <td>". $row['kategori'] ."</td>

                    <td id='jumlah' align='right'>". number_format($row['jumlah'], 0, ',', '.') ."</td>

                </tr>
            ";

        echo $data;
    }

}