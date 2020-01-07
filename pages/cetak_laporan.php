<?php
  require_once('../config/koneksi.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <link rel="icon" href="<?= base_url('../assets/img/logo.png'); ?>">
	<title>Laporan Penilaian Kinerja</title>
	<style>
		.header{
			clear: both;
			border-bottom: 2px solid black;
		}
		.img-header{
			width:100px;
			float: left;
		}
		.header > h1,.header > h2,.header > h3{
			margin: 0;
			text-align: center;
		}
		.header > h2,.header > h3{
			font-weight: normal;
		}
		.header>hr{
			margin: 0;	
		}
		.main{
			padding-top: 50px;
		}

		table{
			width: 100%;
		}
		.footer{
			padding-top: 50px;
			text-align: right;
		}
		.txt_center{
			text-align: center;
			
		}
		.txt_right{
		}
		thead:before, thead:after { display: none; }
		tbody:before, tbody:after { display: none; }
	</style>
</head>
<body>
	<div class="header">
		<img class="img-header" src="file://<?= $_SERVER["DOCUMENT_ROOT"].'/bim/assets/img/logo.png'; ?>" alt="Logo">
		<h2>Laporan Penilaian Kinerja Priode <?= get_tahun_ajar(); ?></h2>
		<h1>SD Hang Tuah VII Surabaya</h1>
		<h3>Jl. Golf 1 Surabaya, Jawa Timur</h3>
		<br>
		<hr>
	</div>

	<div class="main">
		<?php if(!isset($_GET['detail'])): ?>
		<table class="table" border="1" cellspacing="0" cellpadding="5">
			<thead>
				<tr>
					<th>No</th>
					<th>NIP</th>
					<th>Nama</th>
					<th>Total Nilai</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$id_periode = get_tahun_ajar_id();
					$i=0;
					$sql = "SELECT
								d.nip,
								d.nama_guru,
								SUM(a.hasil_nilai) as nilai,
								COUNT(a.id_nilai) as jml
							FROM penilaian a
							JOIN penilai_detail b ON a.id_penilai_detail = b.id_penilai_detail
							JOIN penilai c ON b.id_penilai = c.id_penilai
							JOIN user d ON c.nip = d.nip
							WHERE c.id_periode = $id_periode
							GROUP BY d.nip
							HAVING COUNT(a.id_nilai) = (
															SELECT 
															(
																(SELECT COUNT(*) FROM penilai p
																JOIN penilai_detail pd ON p.id_penilai = pd.id_penilai
																WHERE p.nip = d.nip)
																*
																(SELECT COUNT(*) FROM isi_kompetensi)
															) as tot
															FROM dual
														)
							ORDER BY nilai DESC";
					$q = mysql_query($sql);
					while($row = mysql_fetch_array($q)){
				?>
				<tr>
					<td><?= ++$i; ?></td>
					<td><?= $row['nip']; ?></td>
					<td><?= $row['nama_guru']; ?></td>
					<td><?= number_format($row['nilai'], 2); ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php 
		else:
			$nip_user = $_GET['detail'];
			$id_penilai = isset($_GET['id'])?mysql_real_escape_string(htmlspecialchars($_GET['id'])):"";
            $sql = "SELECT * FROM user a JOIN jenis_user b ON a.id_jenis_user = b.id_jenis_user WHERE a.nip = '$nip_user' ";
            $q = mysql_query($sql);
            $row  = mysql_fetch_array($q);
		?>
		<table class="table" cellspacing="0">
            <tr>
                <td width="10%"><strong>NIP</strong></td>
                <td width="1%">:</td>
                <td> <?= $row['nip']; ?></td>
            </tr>
            <tr>
                <td><strong>Nama</strong></td>
                <td>:</td>
                <td> <?= $row['nama_guru']; ?></td>
            </tr>
            <tr>
                <td><strong>Jabatan</strong></td>
                <td>:</td>
                <td> <?= $row['jabatan']; ?></td>
            </tr>
        </table>
		<h4>Detail Penilaian</h4>
		<?php
			$sql = "SELECT 
							a.id_kompetensi,
							a.nama_kompetensi,
							a.bobot_kompetensi,
							COUNT(b.id_isi) as jml
						FROM jenis_kompetensi a
						JOIN isi_kompetensi b ON a.id_kompetensi = b.id_kompetensi
						GROUP BY a.id_kompetensi";
				$q = mysql_query($sql);
				
				$data_kompetensi = [];

				while($row = mysql_fetch_array($q)){
					${"b_".$row['nama_kompetensi']} = $row['bobot_kompetensi'];
					${"jml_".$row['nama_kompetensi']} = $row['bobot_kompetensi'];
					$data_kompetensi[] = $row;
				}
		?>

        <table class="table table-bordered" border="1" cellspacing="0" cellpadding="5">
				<tr>
					<th class="txt_center" width="1%" rowspan="2">No</th>
					<th class="txt_center" width="15%" rowspan="2">NIP</th>
					<th class="txt_center" width="20%" rowspan="2">Nama</th>
					<th class="txt_center" width="16%" rowspan="2">Jabatan</th>
					<th class="txt_center" colspan="4">Kompetensi</th>
					<th class="txt_center" rowspan="2">Total</th>
				</tr>
				<tr>
					<?php
						foreach ($data_kompetensi as $key => $value) {
							echo "<th class='txt_center'>$value[nama_kompetensi]</th>";
						}
					?>
				</tr>
			<?php

				

				$sql = "SELECT * FROM penilai a JOIN penilai_detail b ON a.id_penilai = b.id_penilai WHERE a.nip = '$nip_user' ";
				$q = mysql_query($sql);
				$id_penilai_detail = '0';
				$i=0;
				while($row = mysql_fetch_array($q)){
					if($i==0){
						$id_penilai_detail = $row['id_penilai_detail'];
					}else{
						$id_penilai_detail .= ", ".$row['id_penilai_detail'];
					}
					$i++;
				}
				$id_periode = get_tahun_ajar_id();
				$komp = '';
				foreach ($data_kompetensi as $key => $value) {
					$komp .= "SUM( IF(tbnilai.nama_kompetensi = '$value[nama_kompetensi]', tbnilai.nilai, 0) ) AS '$value[nama_kompetensi]', ";
				} 

				$sql = "SELECT 
							tbnilai.nip_penilai,
							tbnilai.penilai,
							tbnilai.level,
							tbnilai.jabatan,
							$komp
							1
						FROM 
						(SELECT 
							a.id_nilai, 
							h.nip as nip_dinilai,
							h.nama_guru as 'dinilai',
							e.nip as nip_penilai, 
							e.nama_guru as 'penilai',
							f.jabatan,
							f.level,
							c.id_kompetensi,
							c.nama_kompetensi,
							c.bobot_kompetensi,
							SUM(a.hasil_nilai) as nilai
						FROM penilaian a 
						JOIN isi_kompetensi b ON a.id_isi = b.id_isi
						JOIN jenis_kompetensi c ON b.id_kompetensi = c.id_kompetensi
						JOIN (penilai_detail d JOIN user e ON d.nip = e.nip JOIN jenis_user f ON f.id_jenis_user = e.id_jenis_user) ON d.id_penilai_detail = a.id_penilai_detail 
						JOIN (penilai g JOIN user h ON g.nip = h.nip ) ON d.id_penilai = g.id_penilai
						WHERE a.id_penilai_detail IN ($id_penilai_detail) AND g.id_periode = $id_periode
						GROUP BY a.id_penilai_detail, c.id_kompetensi
						ORDER BY 4) as tbnilai
						GROUP BY tbnilai.penilai";
				//echo $sql;
				$q = mysql_query($sql);
				$nno = 0;
				echo "<br>";
				$tot_arr['atasan'] = 0;
				$tot_arr['guru'] = 0;
				$tot_arr['sendiri'] = 0;
				while($row = mysql_fetch_array($q)){						
					echo "<tr>";
					echo "<td>".++$nno."</td>";
					echo "<td>$row[nip_penilai]</td>";
					echo "<td>$row[penilai]</td>";
					echo "<td>$row[jabatan]</td>";

					$tot = 0;
					foreach ($data_kompetensi as $key => $value) {
						$nil = ($row[$value['nama_kompetensi']]/$value['jml'])*100; 
						echo "<td class='txt_right'>".number_format($nil,2)."</td>";
						$tot += $nil * ($value['bobot_kompetensi']/100);
					}

					if($row['level']==2 || $row['level']==3){
						$tot_arr['atasan'] += $tot;
					}else if($row['level']==1 && $row['nip_penilai']!= $nip_user){
						$tot_arr['guru'] += $tot;
					}else{
						$tot_arr['sendiri'] += $tot;
					}

					echo "<td class='txt_right'>".number_format($tot, 2)."</td>";
					echo "</tr>";
				}
			?>
				<tr>
					<th colspan="8">Total Nilai Kinerja</th>
					<th class="txt_right">
					<?php
						
						$ak = ($tot_arr['atasan']*0.5) + ($tot_arr['guru']*0.3) + ($tot_arr['sendiri']*0.2);
						echo $ak;	
							
					?>
					</th>
				</tr>
			
		</table>
		<?php endif; ?>
	</div>
	<div class="footer">
		<?php
			$array_bulan = array(1=>"Januari","Februari","Maret", "April", "Mei", "Juni","Juli","Agustus","September","Oktober", "November","Desember");
			$tgl = date("d")." ".$array_bulan[date("n")]." ".date("Y");
		?>
		<p>Surabaya, <?= $tgl; ?></p>
	</div>
</body>
</html>