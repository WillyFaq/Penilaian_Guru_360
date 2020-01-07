<?php
	
	include '../config/koneksi.php';
	
	if($_POST['nip_dinilai']){

		$nip_dinilai = $_POST['nip_dinilai'];
		$nip_penilai = $_POST['nip_penilai'];

		$sql = "SELECT * FROM penilai a JOIN penilai_detail b  ON a.id_penilai = b.id_penilai WHERE a.nip = '$nip_dinilai' AND b.nip = '$nip_penilai' ";
		$q = mysql_query($sql);
		$row = mysql_fetch_array($q);

		$id_penilaian_detail = $row['id_penilai_detail'];
		$sql = "SELECT * FROM penilaian WHERE id_penilai_detail = $id_penilaian_detail ";
		$q = mysql_query($sql);
		if(mysql_num_rows($q)>0){
			if(!mysql_query("DELETE FROM penilaian WHERE id_penilai_detail = $id_penilaian_detail")){
				$_SESSION["flash"]["type"] = "danger";
				$_SESSION["flash"]["head"] = "Terjadi Kesalahan";
				$_SESSION["flash"]["msg"] = "Data gagal disimpan! ";

				header("location:../index.php?p=melakukanpen");
			}
		}
		$sql = "INSERT INTO penilaian (id_penilai_detail, id_isi, hasil_nilai) VALUES ";
		$i = 0;
		foreach ($_POST as $k => $v) {
			if(substr($k, 0, 10)=='kompetensi'){
				//echo "$k = $v <br>";
				$id_isi = explode("_", $k)[1];
				if($i==0){
					$sql .= "($id_penilaian_detail, $id_isi, $v)";
				}else{
					$sql .= ", ($id_penilaian_detail, $id_isi, $v)";
				}
				$i++;
			}
		}
		$insert = mysql_query($sql);
		if($insert){

			$_SESSION["flash"]["type"] = "success";
			$_SESSION["flash"]["head"] = "Sukses";
			$_SESSION["flash"]["msg"] = "Data berhasil disimpan!";
		}else{
			
			$_SESSION["flash"]["type"] = "danger";
			$_SESSION["flash"]["head"] = "Terjadi Kesalahan";
			$_SESSION["flash"]["msg"] = "Data gagal disimpan! ";
		}

		header("location:../index.php?p=melakukanpen");
	}
?>