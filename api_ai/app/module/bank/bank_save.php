<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    /*
	* Auto Number Untuk Code Buku 
	*/
	function autonum($lebar=0, $awalan=''){
        include "../../../bin/koneksi.php";

		$sqlcount= "SELECT npwz FROM tm_donatur ORDER BY npwz DESC";
        
        $hasil= $konek->query($sqlcount);
        
        $jumlahrecord = mysqli_num_rows($hasil);

		if($jumlahrecord == 0)
			$nomor=1;
		else {
			$nomor = $jumlahrecord+1;
		}

		if($lebar>0)
			$angka = $awalan.str_pad($nomor,$lebar,"0",STR_PAD_LEFT);
		else
			$angka = $awalan.$nomor;
		return $angka;
    }
    
    /** Variabel From Post */

    $no_rekening 	= strip_tags($_POST['no_rekening']);
    
    $nama_bank	    = strip_tags($_POST['nama_bank']);
    
    $status 	    = strip_tags($_POST['status']);
    
    $kode_akun 		= strip_tags($_POST['kode_akun']);
    
    /* Validasi Kode */
    $sqlCekNoRekening   = "SELECT no_rekening FROM tm_bank WHERE no_rekening='$no_rekening'";
    
    $exe_sqlNoRekening  = $konek->query($sqlCekNoRekening);
   
    $cekNoRekening	    = mysqli_num_rows($exe_sqlNoRekening);

    /* SQL Query Simpan */
    $sqlBank = "INSERT INTO 
        tm_bank(
            no_rekening,
            nama_bank,
            status,
            kode_akun
        )VALUES(
            '$no_rekening',
            '$nama_bank',
            '$status',
            '$kode_akun'
        )";
    
    if($cekNoRekening > 0){
    
        $pesan 		= "Data Sudah Terdaftar";
    
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);
    } else {

        $insertBank = $konek->query($sqlBank);           

        $pesan 		= "Data Berhasil Disimpan";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);
    }

}