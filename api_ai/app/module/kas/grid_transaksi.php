<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $no_trskas  = $_POST['no_trskas'];

    $sql	= "SELECT * FROM trs_kas_dtl WHERE no_trskas='$no_trskas' AND status='Aktif'";
    
    $result	= $konek->query($sql);

    $data   = '';

    while($row = $result->fetch_assoc()){

        $data .="
            <tr>

                <td>". $row['tgl_transaksi'] ."</td>

                <td>". $row['keterangan'] ."</td>

                <td>". number_format($row['debit'], 0, ',', '.') ."</td>

                <td>". number_format($row['kredit'], 0, ',', '.') ."</td>

            </tr>
        ";

    }

    echo $data;
}