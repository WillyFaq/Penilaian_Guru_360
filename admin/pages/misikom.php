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
<link rel="stylesheet" href="assets/plugins/bootstrap-select/bootstrap-select.css">

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

<div class="container">
	<div class="row">
		<div class="col">
			<h3>Data Master Isi Kompetensi</h3>
		</div>
	</div>
	<hr class="bg-primary" width="100%">
	<div class="container">
		<div class="col-xs-12">
			<!-- Button trigger modal -->
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
			  	<span data-feather="user-plus"></span>
			</button>

			<?php 
				$btn = "Tambah"; 
				if(isset($_GET['ubah'])){
					$id_isi = isset($_GET['id_isi'])?mysql_real_escape_string(htmlspecialchars($_GET['id_isi'])):"";
					$sql = "SELECT * FROM isi_kompetensi WHERE id_isi = $id_isi";
					$q = mysql_query($sql);
					$data = [];
					while ($row = mysql_fetch_assoc($q)) {
						$id_isi = $row['id_isi']; 
						$id_kompetensi = $row['id_kompetensi']; 
						$$row['id_kompetensi'] = $row['id_kompetensi']; 
						$isi_kompetensi = $row['isi_kompetensi'];
						$btn = "Ubah"; 
					}

			?>
			<script type="text/javascript">
			    $(document).ready(function(){
					$('#exampleModal').modal('show');
					
					$('#exampleModal').on('hidden.bs.modal', function(e){
						document.location = 'index.php?p=mjuser';
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
				        	<h5 class="modal-title" id="exampleModalLabel">Data User</h5>
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          	<span aria-hidden="true">&times;</span>
					        </button>
				      	</div>
				     	<div class="modal-body">
				      	<!-- form -->
					       	<form class="form-horizontal" method="post" action="modal/p_isi_kompetensi.php">
					       		<input type="hidden" id="id_isi" name="id_isi" value="<?= isset($id_isi)?$id_isi:""; ?>" >
					       	 	<div class="form-group row">
								   	<label for="id_kompetensi" class="col-sm-3 col-form-label col-form-label-sm">Jenis Kompetensi</label>
								    <div class="col-sm-9">
							          	<select class="form-control form-control-sm" id="id_kompetensi" name="id_kompetensi">
							          		<?php
							          			$jb = mysql_query("SELECT * FROM jenis_kompetensi");
							          			while($rj = mysql_fetch_array($jb)){
							          		?>
									      	<option value="<?= $rj['id_kompetensi']?>" <?= isset($$rj['nama_kompetensi'])?"selected":''?> ><?= $rj['nama_kompetensi']; ?></option>
									   		<?php } ?>
									   	</select>
							    	</div>
							  	</div>
								

								<div class="form-group row">
									<label for="isi_kompetensi" class="col-sm-3 control-form-label col-form-label-sm">Isi Kompetensi</label>
									<div class="col-sm-9">
										<textarea class="form-control form-control-sm" id="isi_kompetensi" name="isi_kompetensi" placeholder="Isi Kompetensi" rows="10"><?= isset($isi_kompetensi)?$isi_kompetensi:""; ?></textarea>
									</div>
								</div>
								<div class="form-group row">
									<label for="ket" class="col-sm-3 control-form-label col-form-label-sm">Penilai</label>
									<div class="col-sm-9">
										<select class="form-control form-control-sm sel-penilai" multiple id="ket" name="ket">
							          		<option value="0">Atasan</option>
							          		<option value="1">Rekan Kerja</option>
							          		<option value="2">Diri Sendiri</option>
									   	</select>
									</div>
								</div>
								<input type="hidden" name="penilai" id="penilai">
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
				<table class="order-table dataTable" width="100%">
					<thead>
						<tr>
							<th width="10%">No</th>
							<th width="20%">Jenis Kompetensi</th>
							<th width="25%">Isi Kompetensi</th>
							<th>Penilai</th>
							<th width="25%">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$sql = "SELECT * FROM isi_kompetensi a JOIN jenis_kompetensi b ON a.id_kompetensi = b.id_kompetensi ORDER BY b.id_kompetensi ASC";
							$q = mysql_query($sql);
							$i=0;
							while($row = mysql_fetch_array($q)){
						?>
						<tr>
							<td><?= ++$i; ?></td>
							<td><?= $row['nama_kompetensi']; ?></td>
							<td><?= $row['isi_kompetensi']; ?></td>
							<td><?php 
								$a = ['Atasan', 'Rekan Kerja', 'Diri Sendiri'];
								$ret = '';
								if($row['ket']!=''){
									$ket = explode(",", $row['ket']);
									$b = [];
									foreach ($ket as $k => $v) {
										array_push($b, $a[$v]);
									}
									$ret = join(", ", $b);
								}
								echo $ret;
							?></td>
							<td>
								<button class="btn btn-outline-info btn-sm btn_info" id="<?= $row['id_isi'];?>"><span data-feather="info"></span></button>
								<a href="index.php?p=misikom&ubah=true&id_isi=<?= $row['id_isi'];?>" class="btn btn-outline-warning btn-sm" id="<?= $row['id_isi'];?>"><span data-feather="edit"></span></a>
								<button href="#" class="btn btn-outline-danger btn-sm btn_hapus" id="<?= $row['id_isi'];?>"><span data-feather="trash-2"></span></button>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade infolengkap" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
	      	<div class="modal-header">
		   	 	<h5 class="modal-title" id="exampleModalLabel">Data Isi Kompetensi</h5>
		      	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">&times;</span>
		      	</button>
		    </div>
		    <div class="modal-body">
		     	<table class="table">
		     		<tr>
		     			<th width="30%">Jenis Kompetensi</th>
		     			<td width="5%"> : </td>
		     			<td id="td_jenis_kompetensi">  Sekolah </td>
		     		</tr>
		     		<tr>
		     			<th>Isi Kompetensi</th>
		     			<td> : </td>
		     			<td  id="td_isi_kompetensi">  </td>
		     		</tr>
		     	</table>
			</div>
    	</div>
  	</div>
</div>

<div class="modal fade hapusdata" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-md">
    	<div class="modal-content">
      		<div class="modal-header">
		   	 	<h5 class="modal-title" id="exampleModalLabel">Hapus Data Isi Kompetensi</h5>
		      	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">&times;</span>
		        	<form method="post" action="modal/p_isi_kompetensi.php">
		        		
		        	<input type="hidden" name="id_delete" id="id_delete">
		      	</button>
		    </div>
		    <div class="modal-body">
		    	<input type="submit" class="btn btn-danger btn_delete" name="btnDelete" value="Hapus">
		        </form>
		    	<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
		    </div>
		</div>
  	</div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
		$(".btn_info").click(function(){
			var id = $(this).attr("id");
			var _url = "modal/p_isi_kompetensi.php?id_isi="+id;
			$.ajax({
				url: _url, 
				success: function(result){
			  		var res = JSON.parse(result);
			  		$("#td_jenis_kompetensi").html(res.nama_kompetensi);
			  		$("#td_isi_kompetensi").html(res.isi_kompetensi);
			  	}
			});
			$('.infolengkap').modal('show');
		});
		$(".btn_hapus").click(function(){
			var id = $(this).attr("id");
			$("#id_delete").val(id);
			$('.hapusdata').modal('show');
		});
		$('.sel-penilai').selectpicker();
		$(".sel-penilai").change(function(){
			var a = $(this).val();
			var b = a.join();
			$("#penilai").val(b);
		});
    });
</script>