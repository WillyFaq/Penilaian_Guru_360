<?php
	include '../../config/koneksi.php';
	if(isset($_POST['btnSimpan'])){
		$btn = $_POST['btnSimpan'];
		
		$nip = isset($_POST['nip'])?mysql_real_escape_string(htmlspecialchars($_POST['nip'])):"";
		$id_jenis_user = isset($_POST['id_jenis_user'])?mysql_real_escape_string(htmlspecialchars($_POST['id_jenis_user'])):"";
		$password = isset($_POST['password'])?mysql_real_escape_string(htmlspecialchars($_POST['password'])):"";
		$nama_guru = isset($_POST['nama_guru'])?mysql_real_escape_string(htmlspecialchars($_POST['nama_guru'])):"";
		$status_guru = isset($_POST['status_guru'])?mysql_real_escape_string(htmlspecialchars($_POST['status_guru'])):"";
		$alamat = isset($_POST['alamat'])?mysql_real_escape_string(htmlspecialchars($_POST['alamat'])):"";
		$tempat_lahir = isset($_POST['tempat_lahir'])?mysql_real_escape_string(htmlspecialchars($_POST['tempat_lahir'])):"";
		$tgl_lahir = isset($_POST['tgl_lahir'])?mysql_real_escape_string(htmlspecialchars($_POST['tgl_lahir'])):"";
		$jenis_kelamin = isset($_POST['jenis_kelamin'])?mysql_real_escape_string(htmlspecialchars($_POST['jenis_kelamin'])):"";
		$status_nikah = isset($_POST['status_nikah'])?mysql_real_escape_string(htmlspecialchars($_POST['status_nikah'])):"";
		$no_telp = isset($_POST['no_telp'])?mysql_real_escape_string(htmlspecialchars($_POST['no_telp'])):"";

		if($btn=="Tambah"){
			$sql = "INSERT INTO user (nip, id_jenis_user, password, nama_guru, status_guru, alamat, tempat_lahir, tgl_lahir, jenis_kelamin, status_nikah, no_telp) VALUES('$nip', '$id_jenis_user', '$password', '$nama_guru', '$status_guru', '$alamat', '$tempat_lahir', '$tgl_lahir', '$jenis_kelamin', '$status_nikah', '$no_telp') ";
		}else{
			$sql = "UPDATE user SET id_jenis_user = '$id_jenis_user', password = '$password', nama_guru = '$nama_guru', status_guru = '$status_guru', alamat = '$alamat', tempat_lahir = '$tempat_lahir', tgl_lahir = '$tgl_lahir', jenis_kelamin = '$jenis_kelamin', status_nikah = '$status_nikah', no_telp = '$no_telp' WHERE nip = '$nip'";
		}
		$query = mysql_query($sql);
		if($query){
			$_SESSION["flash"]["type"] = "success";
			$_SESSION["flash"]["head"] = "Sukses";
			$_SESSION["flash"]["msg"] = "Data berhasil disimpan!";
		}else{
			$_SESSION["flash"]["type"] = "danger";
			$_SESSION["flash"]["head"] = "Terjadi Kesalahan";
			$_SESSION["flash"]["msg"] = "Data gagal disimpan! ".mysql_error();
		}
		header("location:../index.php?p=muser");
	}

	if(isset($_POST['btnDelete'])){
		$nip = isset($_POST['id_delete'])?mysql_real_escape_string(htmlspecialchars($_POST['id_delete'])):"";
		$sql = "DELETE  FROM user WHERE nip = $nip";
		$query = mysql_query($sql);
		if($query){
			$_SESSION["flash"]["type"] = "success";
			$_SESSION["flash"]["head"] = "Sukses";
			$_SESSION["flash"]["msg"] = "Data berhasil dihapus!";
		}else{
			$_SESSION["flash"]["type"] = "danger";
			$_SESSION["flash"]["head"] = "Terjadi Kesalahan";
			$_SESSION["flash"]["msg"] = "Data gagal dihapus! ".mysql_error();
		}
		header("location:../index.php?p=muser");
	}

	if(isset($_GET['nip'])){
		$nip = isset($_GET['nip'])?mysql_real_escape_string(htmlspecialchars($_GET['nip'])):"";
		$sql = "SELECT * FROM user a JOIN jenis_user b ON a.id_jenis_user = b.id_jenis_user WHERE a.nip = $nip";
		$q = mysql_query($sql);
		$data = [];
		while ($row = mysql_fetch_assoc($q)) {
			$data = $row; 
		}
		echo json_encode($data);
	}

?>