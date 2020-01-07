<?php
	include '../../config/koneksi.php';
	if(isset($_POST['btnSimpan'])){
		$btn = $_POST['btnSimpan'];
		
		$id_periode = isset($_POST['id_periode'])?mysql_real_escape_string(htmlspecialchars($_POST['id_periode'])):"";
		$tahun_ajar = isset($_POST['tahun_ajar'])?mysql_real_escape_string(htmlspecialchars($_POST['tahun_ajar'])):"";
		$semester = isset($_POST['semester'])?mysql_real_escape_string(htmlspecialchars($_POST['semester'])):"";
		$status_periode = 1;

		$atasan = isset($_POST['atasan'])?mysql_real_escape_string(htmlspecialchars($_POST['atasan'])):"";
		$rekan = isset($_POST['rekan'])?mysql_real_escape_string(htmlspecialchars($_POST['rekan'])):"";
		$diri = isset($_POST['diri'])?mysql_real_escape_string(htmlspecialchars($_POST['diri'])):"";

		$tot = $atasan+$rekan+$diri;
		echo "$tot = $atasan+$rekan+$diri";

		if($tot==100){
			
			$setting = "$atasan;$rekan;$diri";

			if($btn=="Tambah"){
				

				$ssq = "SELECT * FROM periode WHERE tahun_ajar = $tahun_ajar AND LOWER(semester) = LOWER('$semester')";
				$q = mysql_query($ssq);
				if(mysql_num_rows($q)>0){
					$sql = "";
				}else{
					$ssq = "UPDATE periode SET status_periode = 0";
					mysql_query($ssq);
					$sql = "INSERT INTO periode (tahun_ajar, semester, status_periode, setting) VALUES( '$tahun_ajar', '$semester', '$status_periode', '$setting') ";
					
				}
			}else{
				$sql = "UPDATE periode SET tahun_ajar = '$tahun_ajar', semester = '$semester', setting='$setting' WHERE id_periode = '$id_periode'";
			}

			$query = mysql_query($sql);
			if($query){
				$_SESSION["flash"]["type"] = "success";
				$_SESSION["flash"]["head"] = "Sukses";
				$_SESSION["flash"]["msg"] = "Data berhasil disimpan!";
			}else{
				$_SESSION["flash"]["type"] = "danger";
				$_SESSION["flash"]["head"] = "Terjadi Kesalahan";
				$_SESSION["flash"]["msg"] = "Data gagal disimpan! ";//.mysql_error();
			}
			header("location:../index.php?p=mperiode");
		}else{
			
			$_SESSION["flash"]["type"] = "danger";
			$_SESSION["flash"]["head"] = "Terjadi Kesalahan";
			$_SESSION["flash"]["msg"] = "Data gagal disimpan! ";//.mysql_error();
			header("location:../index.php?p=mperiode");
		}
	}

	if(isset($_POST['btnDelete'])){
		$id_periode = isset($_POST['id_delete'])?mysql_real_escape_string(htmlspecialchars($_POST['id_delete'])):"";
		$sql = "DELETE  FROM periode WHERE id_periode = $id_periode";
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
		header("location:../index.php?p=mperiode");
	}

	if(isset($_GET['id_periode'])){
		$id_periode = isset($_GET['id_periode'])?mysql_real_escape_string(htmlspecialchars($_GET['id_periode'])):"";
		$sql = "SELECT * FROM periode WHERE id_periode = $id_periode";
		$q = mysql_query($sql);
		$data = [];
		while ($row = mysql_fetch_assoc($q)) {
			$data = $row; 
		}
		echo json_encode($data);
	}

?>