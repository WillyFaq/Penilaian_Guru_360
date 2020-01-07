<?php
	include '../../config/koneksi.php';
	if(isset($_POST['btnSimpan'])){
		$btn = $_POST['btnSimpan'];
		
		$id_jenis_user = isset($_POST['id_jenis_user'])?mysql_real_escape_string(htmlspecialchars($_POST['id_jenis_user'])):"";
		$jabatan = isset($_POST['jabatan'])?mysql_real_escape_string(htmlspecialchars($_POST['jabatan'])):"";
		$level = isset($_POST['level'])?mysql_real_escape_string(htmlspecialchars($_POST['level'])):"";

		if($btn=="Tambah"){
			$sql = "INSERT INTO jenis_user (jabatan, level) VALUES('$jabatan', '$level') ";
		}else{
			$sql = "UPDATE jenis_user SET jabatan = '$jabatan', level = '$level' WHERE id_jenis_user = '$id_jenis_user'";
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
		header("location:../index.php?p=mjuser");
	}

	if(isset($_POST['btnDelete'])){
		$id_jenis_user = isset($_POST['id_delete'])?mysql_real_escape_string(htmlspecialchars($_POST['id_delete'])):"";
		$sql = "DELETE  FROM jenis_user WHERE id_jenis_user = $id_jenis_user";
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
		header("location:../index.php?p=mjuser");
	}

	if(isset($_GET['id_jenis_user'])){
		$id_jenis_user = isset($_GET['id_jenis_user'])?mysql_real_escape_string(htmlspecialchars($_GET['id_jenis_user'])):"";
		$sql = "SELECT * FROM jenis_user WHERE id_jenis_user = $id_jenis_user";
		$q = mysql_query($sql);
		$data = [];
		while ($row = mysql_fetch_assoc($q)) {
			$data = $row; 
		}
		echo json_encode($data);
	}

?>