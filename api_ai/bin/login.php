<?php
include "koneksi.php";

	/*
	* Variabel Username dan Password
	*/

	function noInjection($data){
		
		include "koneksi.php";
 
		$filter_sql = mysqli_real_escape_string($konek, stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES))));
		 
		return $filter_sql;
		 
	}

	$username = noInjection($_POST['username']);
	$password = noInjection(base64_encode($_POST['password']));

	/*
	* Jika Tombol Login di klik Field Username & Password tidak kosong
	* Maka akan melakukan proses login
	*/
	if($username != NULL && $password != NULL){
		$sqllogin 	= " SELECT * FROM view_user WHERE username='$username' AND password='$password' AND active='Yes' ";
		$hasil		= $konek->query($sqllogin);
		$row 		= $hasil->fetch_assoc();
		$ketemu 	= mysqli_num_rows($hasil);

		/* Apabila Username & Passwod Terdapat Pada Database */
		if($ketemu > 0){

			/* Membuat Session Login dan Menentukan Timeout Login */
			session_start();
			// include "bin/timeout.php";

			$_SESSION['id_user']	= $row['id_user'];
			$_SESSION['username'] = $row['username'];
            $_SESSION['password']	= $row['password'];
            $_SESSION['level'] 	= $row['level'];
			$_SESSION['kode_petugas']	= $row['kode_petugas'];
			$_SESSION['nama_petugas']	= $row['nama_petugas'];
			$_SESSION['no_kantor']	= $row['no_kantor'];
			$_SESSION['status']	= 'LOGIN';
			$pesan 		= "Login Berhasil";
			$response	= array(
				'pesan'=>$pesan,
				'username'=>$_SESSION['username'],
				'password'=>$_SESSION['password'],
				'level'=>$_SESSION['level'],
				'kode_petugas'=>$_SESSION['kode_petugas'],
				'nama_petugas'=>$_SESSION['nama_petugas'],
				'status'=>$_SESSION['status'],
				'no_kantor'=>$_SESSION['no_kantor']
			);
			/* Membuat JSON file untuk dikembalikan login.js */
			echo json_encode($response);
		/* Apabila Username & Password tidak terdapat pada Database */
		} else {
			// include "module/error.php";
			$pesan = "Login Gagal, Cek Username & Password Anda..!";
			$response['pesan'] = $pesan;
			echo json_encode($response);
		}
	}