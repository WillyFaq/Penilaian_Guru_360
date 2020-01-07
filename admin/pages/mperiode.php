<style >

.container table {
  width: 100%;
  font-size: 1rem;
}

.container td, .container th {
  padding: 10px;
}

.container td:first-child, .container th:first-child {
  padding-left: 20px;
}

.container td:last-child, .container th:last-child {
  padding-right: 20px;
}

.container th {
  border-bottom: 1px solid #ddd;
  position: relative;
}

</style>

<script >

(function(document) {
	'use strict';

	var LightTableFilter = (function(Arr) {

		var _input;

		function _onInputEvent(e) {
			_input = e.target;
			var tables = document.getElementsByClassName(_input.getAttribute('data-table'));
			Arr.forEach.call(tables, function(table) {
				Arr.forEach.call(table.tBodies, function(tbody) {
					Arr.forEach.call(tbody.rows, _filter);
				});
			});
		}

		function _filter(row) {
			var text = row.textContent.toLowerCase(), val = _input.value.toLowerCase();
			row.style.display = text.indexOf(val) === -1 ? 'none' : 'table-row';
		}

		return {
			init: function() {
				var inputs = document.getElementsByClassName('form-control');
				Arr.forEach.call(inputs, function(input) {
					input.oninput = _onInputEvent;
				});
			}
		};
	})(Array.prototype);

	document.addEventListener('readystatechange', function() {
		if (document.readyState === 'complete') {
			LightTableFilter.init();
		}
	});

	})(document);

</script>

<?php
	if(isset($_GET['setaktif'])){
		$id_periode = isset($_GET['id_periode'])?mysql_real_escape_string(htmlspecialchars($_GET['id_periode'])):"";
		$sql = "UPDATE periode SET status_periode = 0";
		$up = mysql_query($sql);
		if($up){
			if(mysql_query("UPDATE periode SET status_periode = 1 WHERE id_periode = $id_periode")){
				$_SESSION["flash"]["type"] = "success";
				$_SESSION["flash"]["head"] = "Sukses";
				$_SESSION["flash"]["msg"] = "Data berhasil disimpan!";
			}
		}
		$_SESSION["flash"]["type"] = "danger";
		$_SESSION["flash"]["head"] = "Terjadi Kesalahan";
		$_SESSION["flash"]["msg"] = "Data gagal disimpan! ".mysql_error();
		echo "<script>document.location='index.php?p=mperiode';</script>";

	}
?>

<div class="container">
	<div class="row">
		<div class="col">
			<h3>Data Master Periode</h3>
		</div>
	</div>
	<hr class="bg-primary" width="100%">
	<div class="contaier">
		<div class="col">
			<!-- Button trigger modal -->
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
			  	<span data-feather="clock"></span><span data-feather="plus"></span>
			</button>
			<?php 
				$btn = "Tambah"; 
				if(isset($_GET['ubah'])){
					$id_periode = isset($_GET['id_periode'])?mysql_real_escape_string(htmlspecialchars($_GET['id_periode'])):"";
					$sql = "SELECT * FROM periode WHERE id_periode = $id_periode";
					$q = mysql_query($sql);
					$data = [];
					while ($row = mysql_fetch_assoc($q)) {
						$id_periode = $row['id_periode']; 
						$tahun_ajar = $row['tahun_ajar']; 
						$semester = $row['semester']; 
						$btn = "Ubah"; 
						if($row['setting']!=''){
							$set = explode(';', $row['setting']);
							$atasan = $set[0];
							$rekan = $set[1];
							$diri = $set[2];
						}
					}

			?>
			<script type="text/javascript">
			    $(document).ready(function(){
					$('#exampleModal').modal('show');
					
					$('#exampleModal').on('hidden.bs.modal', function(e){
						document.location = 'index.php?p=mperiode';
					});
			    });
			</script>
			<?php
				}
			?>
			<!-- Modal -->
			<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			  	<div class="modal-dialog modal-lg" role="document">
			    	<div class="modal-content">
				      	<div class="modal-header">
				        	<h5 class="modal-title" id="exampleModalLabel">Data Periode</h5>
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          	<span aria-hidden="true">&times;</span>
					        </button>
				      	</div>
				      	<div class="modal-body">
				       	<!-- form -->
					       	<form class="form-horizontal" method="post" action="modal/p_periode.php">
				       		<input type="hidden" name="id_periode" <?= isset($id_periode)?'value="'.$id_periode.'"':""; ?>  >
				       	 	<div class="form-group row">
							   	<label for="tahun_ajar" class="col-sm-3 col-form-label col-form-label-sm">Tahun Ajaran</label>
							    <div class="col-sm-9">
							      	<input type="text" class="form-control form-control-sm" id="tahun_ajar" name="tahun_ajar" value="<?= isset($tahun_ajar)?$tahun_ajar:""; ?>" placeholder="Tahun Ajaran">
							    </div>
						  	</div>
				       	 	<div class="form-group row">
							   	<label for="semester" class="col-sm-3 col-form-label col-form-label-sm">Semester</label>
							    <div class="col-sm-9">
							      	<input type="text" class="form-control form-control-sm" id="semester" name="semester" value="<?= isset($semester)?$semester:""; ?>" placeholder="Semester">
							    </div>
						  	</div>
						  	<fieldset>
						  		<legend>Presentase Penilaian</legend>
					       	 	<div class="form-group row">
								   	<label for="atasan" class="col-sm-3 col-form-label col-form-label-sm">Presentase Atasan</label>
								    <div class="col-sm-9">
								      	<input type="number" class="form-control form-control-sm presentase" min="0" max="100" id="atasan" name="atasan" value="<?= isset($atasan)?$atasan:""; ?>" placeholder="Presentase Atasan">
								    </div>
							  	</div>
							  	<div class="form-group row">
								   	<label for="rekan" class="col-sm-3 col-form-label col-form-label-sm">Presentase Rekan Kerja</label>
								    <div class="col-sm-9">
								      	<input type="number" class="form-control form-control-sm presentase" min="0" max="100" id="rekan" name="rekan" value="<?= isset($rekan)?$rekan:""; ?>" placeholder="Presentase Rekan Kerja">
								    </div>
							  	</div>
							  	<div class="form-group row">
								   	<label for="diri" class="col-sm-3 col-form-label col-form-label-sm">Presentase Diri Sendiri</label>
								    <div class="col-sm-9">
								      	<input type="number" class="form-control form-control-sm presentase" min="0" max="100" id="diri" name="diri" value="<?= isset($diri)?$diri:""; ?>" placeholder="Presentase Diri Sendiri">
								    </div>
							  	</div>
						  	<fieldset>
				      	</div>
				      	<div class="modal-footer">
				        	<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
				        	<input type="submit" class="btn btn-primary" value="<?= $btn; ?>" name="btnSimpan">
							</form>
				      	</div>
			    	</div>
			  	</div>
			</div>
		</div>
	</div>
	<br>
	<div class="container">
		<div class="row">
			<div class="col">
			<input type="search" class="form-control" data-table="order-table" placeholder="Cari Data User" />
			<hr>
				<table class="order-table">
					<thead>
						<tr>
							<th width="10%">No</th>
							<th width="20%">Tahun Ajaran</th>
							<th width="20%">Semester</th>
							<th width="20%">Penilaian</th>
							<th width="10%">Status</th>
							<th width="30%">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$sql = "SELECT * FROM periode";
							$q = mysql_query($sql);
							$i=0;
							while($row = mysql_fetch_array($q)){
								$setting = '';
								if($row['setting']!=''){
									$set = explode(';', $row['setting']);
									$setting = "Atasan = $set[0]% <br>Rekan Kerja = $set[1]% <br>Diri Sendiri = $set[2]%";
								}

						?>
						<tr>
							<td><?= ++$i; ?></td>
							<td><?= $row['tahun_ajar']; ?></td>
							<td><?= $row['semester']; ?></td>
							<td><?= $setting; ?></td>
							<td>
								<?php if($row['status_periode']==0){ ?>
									<span class="label label-danger">Tidak Aktif</span>
								<?php }else{ ?>
									<span class="label label-success">Aktif</span>
								<?php } ?>
							</td>
							<td>
								<?php if($row['status_periode']==0){ ?>
									<!-- <a href="index.php?p=mperiode&setaktif=true&id_periode=<?= $row['id_periode'];?>" class="btn btn-outline-info btn-sm"><span data-feather="check"></span></a> -->
								<?php } ?>
								<a href="index.php?p=mperiode&ubah=true&id_periode=<?= $row['id_periode'];?>" class="btn btn-outline-warning btn-sm"><span data-feather="edit"></span></a>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>