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

		$sqlcount= "SELECT kode_mustahik FROM tm_mustahik ORDER BY kode_mustahik DESC";
        
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
    $kode_mustahik  = autonum(4,date('y')."4001");

    $nama_mustahik 	= strip_tags($_POST['nama_mustahik']);
    
    $alamat 		= strip_tags($_POST['alamat']);
    
    $hp 			= strip_tags($_POST['hp']);
    
    $kategori 		= strip_tags($_POST['kategori']);
    
    $status 		= strip_tags($_POST['status']);
      
    $no_kantor 		= strip_tags($_POST['no_kantor']);
    
    /* Validasi Kode */
    $sqlCekMustahik     = "SELECT kode_mustahik FROM tm_mustahik WHERE kode_mustahik='$kode_mustahik'";
    
    $exe_sqlMustahik    = $konek->query($sqlCekMustahik);
   
    $cekMustahik	    = mysqli_num_rows($exe_sqlMustahik);

    /* SQL Query Simpan */
    $sqlMuzaki = "INSERT INTO 
        tm_mustahik(
            kode_mustahik,
            nama_mustahik,
            alamat,
            hp,
            kategori,
            status,
            no_kantor
        )VALUES(
            '$kode_mustahik',
            '$nama_mustahik',
            '$alamat',
            '$hp',
            '$kategori',
            '$status',
            '$no_kantor'
        )";
    
    if($cekMustahik > 0){
    
        $pesan 		= "Data Sudah Terdaftar";
    
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);
    } else {

        $insertMuzaki = $konek->query($sqlMuzaki);           

        $pesan 		= "Data Berhasil Disimpan";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);
    }

}