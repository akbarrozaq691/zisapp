<?php
session_start();
if(!$_SESSION){
    $pesan = "Your Access Not Authorized";
    header("Content-type:application/json");
    echo json_encode($pesan);
} else {
    include "../../../bin/koneksi.php";
    $no_donasi      = $_GET['no_donasi'];
    $sql 	        = "SELECT * FROM view_donasi WHERE no_donasi = '$no_donasi'";
    $hasil	        = $konek->query($sql);
    $data['data']   = $hasil->fetch_assoc();
    
    $data['data']['nominal']        = penyebut($data['data']['total_donasi'])." Rupiah"; 
    $data['data']['total_donasi']   = number_format($data['data']['total_donasi'], 0, ',', '.');
    $data['data']['jml_donasi']     = number_format($data['data']['jml_donasi'], 0, ',', '.');
    $data['data']['tgl_transaksi']  = tgl_indo($data['data']['tgl_donasi']);
   // $data['data']['tgl_transaksi']  = date_format(date_create($data['data']['tgl_donasi']), 'd F Y');
    $data['data']['tgl_donasi']     = date_format(date_create($data['data']['tgl_donasi']), 'd/m/Y');

    header("Content-type:application/json");
    echo json_encode($data, true);
}

function tgl_indo($tanggal){
	$bulan = array (
		1 =>   'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember'
	);
	$pecahkan = explode('-', $tanggal);
	
	// variabel pecahkan 0 = tanggal
	// variabel pecahkan 1 = bulan
	// variabel pecahkan 2 = tahun
 
	return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}

function penyebut($nilai) {
    $nilai = abs($nilai);
    $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "sebelas");
   // $temp = "";
    if ($nilai < 12) {
       return " ". $huruf[$nilai];
    } else if ($nilai <20) {
        return penyebut($nilai - 10). " Belas";
    } else if ($nilai < 100) {
        return penyebut($nilai/10)." Puluh". penyebut($nilai % 10);
    } else if ($nilai < 200) {
        return " Seratus" . penyebut($nilai - 100);
    } else if ($nilai < 1000) {
        return penyebut($nilai/100) . " Ratus" . penyebut($nilai % 100);
    } else if ($nilai < 2000) {
        return " Seribu" . penyebut($nilai - 1000);
    } else if ($nilai < 1000000) {
        return penyebut($nilai/1000) . " Ribu" . penyebut($nilai % 1000);
    } else if ($nilai < 1000000000) {
        return penyebut($nilai/1000000) . " Juta" . penyebut($nilai % 1000000);
    } else if ($nilai < 1000000000000) {
        return penyebut($nilai/1000000000) . " Milyar" . penyebut(fmod($nilai,1000000000));
    } else if ($nilai < 1000000000000000) {
        return penyebut($nilai/1000000000000) . " Trilyun" . penyebut(fmod($nilai,1000000000000));
    }     
 // return $temp;
}


?>