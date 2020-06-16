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

		$sqlcount= "SELECT no_kantor FROM tm_kantor_layanan ORDER BY no_kantor DESC";
        
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
    $periode 	= strip_tags($_POST['periode']);
    
    $status	    = 'Aktif';
    
    /* Validasi Kode */
    $sqlCekPeriode     = "SELECT periode FROM trs_periode WHERE periode='$periode' AND status='Aktif'";
    
    $exe_sqlCekPeriode = $konek->query($sqlCekPeriode);

    $cekPeriode	    = mysqli_num_rows($exe_sqlCekPeriode);

    /* SQL Query Simpan */
    $sqlPeriode = "INSERT INTO 
        trs_periode(
            periode,
            status
        )VALUES(
            '$periode',
            '$status'
        )";
    
    if($cekPeriode > 0){
    
        $pesan 		= "Data Sudah Terdaftar";
    
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);
    } else {

        $insertPeriode = $konek->query($sqlPeriode);           

        $pesan 		= "Data Berhasil Disimpan";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);
    }

}