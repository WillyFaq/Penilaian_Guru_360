<div class="container">
	<div class="row">
		<div class="col">
			<h1>Laporan Penilaian Kinerja Guru</h1>
			<h3>Periode <?= get_tahun_ajar(); ?></h3>
			<hr/>
		</div>
	</div>

	<div class="row">
		<?php if(!isset($_GET['detail'])): ?>
		<div class="col">
			<a class="btn btn-danger btn-sm pull-right" target="blank" data-toggle="tooltip" data-placement="top" title="Export PDF" href="pages/pdf.php"><i class="fa fa-file-pdf-o fa-2x"></i></a>
			<h3 class="text-center">Laporan Keseluruhan</h3>
			<br>
			<table class="table">
				<thead>
					<tr>
						<th>No</th>
						<th>NIP</th>
						<th>Nama</th>
						<th>Total Nilai</th>
						<th>Aksi</th>
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
						<td><?= get_tot_nilai($row['nip'], get_tahun_ajar_id()); ?>
						</td>
						<td>
							<a class="btn btn-outline-success btn-sm" href="index.php?p=laporanpen&detail=<?= $row['nip'] ?>" data-toggle="tooltip" data-placement="top" title="Detail" ><i class="fa fa-eye fa-2x"></i></a>
							<a class="btn btn-outline-danger btn-sm" href="pages/pdf.php?detail=<?= $row['nip'] ?>" target="blank" data-toggle="tooltip" data-placement="top" title="Export Pdf" ><i class="fa fa-file-pdf-o fa-2x"></i></a>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<?php else:
			$nip_user = $_GET['detail'];
			$id_penilai = isset($_GET['id'])?mysql_real_escape_string(htmlspecialchars($_GET['id'])):"";
            $sql = "SELECT * FROM user a JOIN jenis_user b ON a.id_jenis_user = b.id_jenis_user WHERE a.nip = '$nip_user' ";
            $q = mysql_query($sql);
            $row  = mysql_fetch_array($q);
		?>
			<div class="col" style="margin-bottom:20px; text-align:right;">
				<a class="btn btn-danger btn-sm" target="blank" data-toggle="tooltip" data-placement="top" title="Export PDF" href="pages/pdf.php?detail=<?= $row['nip'] ?>" ><i class="fa fa-file-pdf-o fa-2x"></i></a>			
			</div>
			
			<table class="table">
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
                <tr>
                	<td></td>
                	<td></td>
                	<td></td>
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
					/*echo '<pre>';
					//print_r($data_kompetensi);
					//echo $data_kompetensi[1]['nama_kompetensi'];
					
					foreach ($data_kompetensi as $key => $value) {
						echo "$key => $value[nama_kompetensi]";
						echo "SUM( IF(tbnilai.$value[nama_kompetensi] = '$value[nama_kompetensi]', tbnilai.nilai, 0) ) AS '$value[nama_kompetensi]', <br>";
					}
					echo '</pre>';*/
			?>

            <table class="table table-bordered">
				<thead class="text-center">
					<tr>
						<th rowspan="2">No</th>
						<th rowspan="2">NIP</th>
						<th rowspan="2">Nama</th>
						<th rowspan="2">Jabatan</th>
						<th colspan="4">Kompetensi</th>
						<th rowspan="2">Total</th>
					</tr>
					<tr>
						<?php
							foreach ($data_kompetensi as $key => $value) {
								echo "<th>$value[nama_kompetensi]</th>";
							}
						?>
						<!-- <th>Pedagogik</th>
						<th>Kepribadian</th>
						<th>Sosial</th>
						<th>Profesional</th> -->
					</tr>
				</thead>
				<tbody>
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
					/*
								SUM( IF(tbnilai.nama_kompetensi = 'Pedagogik', tbnilai.nilai, 0) ) AS 'Pedagogik',
								SUM( IF(tbnilai.nama_kompetensi = 'Kepribadian', tbnilai.nilai, 0) ) AS 'Kepribadian',
								SUM( IF(tbnilai.nama_kompetensi = 'Sosial', tbnilai.nilai, 0) ) AS 'Sosial',
								SUM( IF(tbnilai.nama_kompetensi = 'Profesional', tbnilai.nilai, 0) ) AS 'Profesional'
					*/

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
							echo "<td>".number_format($nil,2)."</td>";
							$tot += $nil * ($value['bobot_kompetensi']/100);
						}

						if($row['level']==2 || $row['level']==3){
							$tot_arr['atasan'] += $tot;
						}else if($row['level']==1 && $row['nip_penilai']!= $nip_user){
							$tot_arr['guru'] += $tot;
						}else{
							$tot_arr['sendiri'] += $tot;
						}

						echo "<td>".number_format($tot, 2)."</td>";
						echo "</tr>";
					}
				?>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="8">Total Nilai Kinerja</th>
						<th>
						<?php
							$sql = "SELECT * FROM periode WHERE id_periode = $id_periode";
							$q = mysql_query($sql);
							$row = mysql_fetch_array($q);
							if($row['setting']!=''){
								$set = explode(";", $row['setting']);
								
								$set[0] = $set[0]/100;
								$set[1] = $set[1]/100;
								$set[2] = $set[2]/100;
							}else{
								$set[0] = 0.5;
								$set[1] = 0.3;
								$set[2] = 0.2;
							}

							$ak = ($tot_arr['atasan']*$set[0]) + ($tot_arr['guru']*$set[1]) + ($tot_arr['sendiri']*$set[2]);
							$ak = ($tot_arr['atasan']*0.5) + ($tot_arr['guru']*0.3) + ($tot_arr['sendiri']*0.2);
							echo number_format($ak, 2);			
						?>
						</th>
					</tr>
				</tfoot>
			</table>
			
		<?php endif; ?>
	</div>
</div>
<?='<script type="text/javascript">' ?>
    $(document).ready(function(){
		$('[data-toggle="tooltip"]').tooltip();
    });
<?='</script>' ?>
