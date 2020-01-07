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

<div class="container">
	<div class="row">
		<div class="col">
			<h3>Data Master User</h3>
		</div>
	</div>
	<hr class="bg-primary" width="100%">
	<div class="contaienr">
		<div class="col">
			<!-- Button trigger modal -->
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
			  	<span data-feather="user-plus"></span>
			</button>

			<?php 
				$btn = "Tambah"; 
				if(isset($_GET['ubah'])){
					$nip = isset($_GET['nip'])?mysql_real_escape_string(htmlspecialchars($_GET['nip'])):"";
					$sql = "SELECT * FROM user a JOIN jenis_user b ON a.id_jenis_user = b.id_jenis_user WHERE a.nip = $nip";
					$q = mysql_query($sql);
					$data = [];
					while ($row = mysql_fetch_assoc($q)) {
						
						$nip = $row['nip'];
						$jabatan =  $row['jabatan'];
						$$jabatan = $row['jabatan']; 
						$password = $row['password']; 
						$nama_guru = $row['nama_guru']; 
						$status_guru = $row['status_guru']; 
						$alamat = $row['alamat']; 
						$tempat_lahir = $row['tempat_lahir']; 
						$tgl_lahir = $row['tgl_lahir']; 
						$jenis_kelamin = $row['jenis_kelamin']; 
						$$row['jenis_kelamin'] = $row['jenis_kelamin']; 
						$status_nikah = $row['status_nikah']; 
						$$row['status_nikah'] = $row['status_nikah']; 
						$no_telp = $row['no_telp']; 
						$btn = "Ubah"; 
					}

			?>
			<script type="text/javascript">
			    $(document).ready(function(){
					$('#exampleModal').modal('show');
					
					$('#exampleModal').on('hidden.bs.modal', function(e){
						document.location = 'index.php?p=muser';
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
					       	<form class="form-horizontal" method="post" action="modal/p_user.php">
				       	 	<div class="form-group row">
						   		<label for="nip" class="col-sm-2 col-form-label col-form-label-sm">Nip</label>
							    <div class="col-sm-10">
							      	<input type="text" class="form-control form-control-sm" id="nip" name="nip" <?= isset($nip)?'value="'.$nip.'" readonly':""; ?> placeholder="NIP">
							    </div>
						  	</div>
				       	 	<div class="form-group row">
						   		<label for="nama_guru" class="col-sm-2 col-form-label col-form-label-sm">Nama</label>
							    <div class="col-sm-10">
							      	<input type="text" class="form-control form-control-sm" id="nama_guru" name="nama_guru" value="<?= isset($nama_guru)?$nama_guru:""; ?>" placeholder="Nama">
							    </div>
						  	</div>
						  	<div class="form-group row">
								<label for="password" class="col-sm-2 col-form-label col-form-label-sm">Password</label>
								<div class="col-sm-10">
									<input type="text" class="form-control form-control-sm" id="password" name="password" value="<?= isset($password)?$password:""; ?>" placeholder="Password">
								</div>
							</div>
				       	 	<div class="form-group row">
							   	<label for="id_jenis_user" class="col-sm-2 col-form-label col-form-label-sm">Jabatan</label>
							    <div class="col-sm-10">
						          	<select class="form-control form-control-sm" id="id_jenis_user" name="id_jenis_user">
						          		<?php
						          			$jb = mysql_query("SELECT * FROM jenis_user");
						          			while($rj = mysql_fetch_array($jb)){
						          		?>
								      	<option value="<?= $rj['id_jenis_user']?>" <?= isset($$rj['jabatan'])?"selected":''?> ><?= $rj['jabatan']; ?></option>
								   		<?php } ?>
								   	</select>
						    	</div>
						  	</div>
						  	<div class="form-group row">
								<label for="status_guru" class="col-sm-2 control-form-label col-form-label-sm">Status Guru</label>
								<div class="col-sm-10">
									<input type="text" class="form-control form-control-sm" id="status_guru" name="status_guru" value="<?= isset($status_guru)?$status_guru:""; ?>" placeholder="Status Guru">
								</div>
							</div>

							<div class="form-group row">
								<label for="alamat" class="col-sm-2 control-form-label col-form-label-sm">Alamat</label>
								<div class="col-sm-10">
									<textarea class="form-control form-control-sm" id="alamat" name="alamat" placeholder="Alamat" rows="10"><?= isset($alamat)?$alamat:""; ?></textarea>
								</div>
							</div>

							<div class="form-group row">
								<label for="tempat_lahir" class="col-sm-2 control-form-label col-form-label-sm">Tempat Lahir</label>
								<div class="col-sm-10">
									<input type="text" class="form-control form-control-sm" id="tempat_lahir" name="tempat_lahir" value="<?= isset($tempat_lahir)?$tempat_lahir:""; ?>" placeholder="Tempat Lahir">
								</div>
							</div>

							<div class="form-group row">
								<label for="tgl_lahir" class="col-sm-2 control-form-label col-form-label-sm">Tgl Lahir</label>
								<div class="col-sm-10">
									<input type="date" class="form-control form-control-sm" id="tgl_lahir" name="tgl_lahir" value="<?= isset($tgl_lahir)?$tgl_lahir:""; ?>" placeholder="Tgl Lahir">
								</div>
							</div>

							<div class="form-group row">
								<label for="jenis_kelamin" class="col-sm-2 control-form-label col-form-label-sm">Jenis Kelamin</label>
								<div class="col-sm-10">
									<input type="radio" id="jenis_kelamin_l" name="jenis_kelamin" value="L" <?= isset($L)?"checked":""; ?> > Laki-laki
									&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio" id="jenis_kelamin_p" name="jenis_kelamin" value="P" <?= isset($P)?"checked":""; ?> > Perempuan
								</div>
							</div>

							<div class="form-group row">
								<label for="status_nikah" class="col-sm-2 control-form-label col-form-label-sm">Status Nikah</label>
								<div class="col-sm-10">
									<input type="radio" id="status_nikah_b" name="status_nikah" value="B" <?= isset($B)?"checked":""; ?> > Belum Nikah
									&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio" id="status_nikah_n" name="status_nikah" value="N" <?= isset($N)?"checked":""; ?> > Sudah Nikah
								</div>
							</div>

							<div class="form-group row">
								<label for="no_telp" class="col-sm-2 control-form-label col-form-label-sm">No Telp</label>
								<div class="col-sm-10">
									<input type="text" class="form-control form-control-sm" id="no_telp" name="no_telp" value="<?= isset($no_telp)?$no_telp:""; ?>" placeholder="No Telp">
								</div>
							</div>
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
							<th width="25%">NIP</th>
							<th width="25%">Nama</th>
							<th width="25%">Jabatan</th>
							<th width="25%">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$sql = "SELECT * FROM user a JOIN jenis_user b ON a.id_jenis_user = b.id_jenis_user";
							$q = mysql_query($sql);
							$i=0;
							while($row = mysql_fetch_array($q)){
						?>
						<tr>
							<td><?= $row['nip']; ?></td>
							<td><?= $row['nama_guru']; ?></td>
							<td><?= $row['jabatan']; ?></td>
							<td>
								<button class="btn btn-outline-info btn-sm btn_info" id="<?= $row['nip'];?>"><span data-feather="info"></span></button>
								<a href="index.php?p=muser&ubah=true&nip=<?= $row['nip'];?>" class="btn btn-outline-warning btn-sm" id="<?= $row['nip'];?>"><span data-feather="edit"></span></a>
								<button href="#" class="btn btn-outline-danger btn-sm btn_hapus" id="<?= $row['nip'];?>"><span data-feather="trash-2"></span></button>
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
		   	 	<h5 class="modal-title" id="exampleModalLabel">Data User</h5>
		      	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">&times;</span>
		      	</button>
		    </div>
		    <div class="modal-body">
		     	<table class="table">
		     		<tr>
						<th width="20%">Nip</th>
						<td width="5%">:</td>
						<td id="td_nip">:</td>
					</tr>

					<tr>
						<th>Jabatan</th>
						<td>:</td>
						<td id="td_jabatan">:</td>
					</tr>

					<tr>
						<th>Password</th>
						<td>:</td>
						<td id="td_password">:</td>
					</tr>

					<tr>
						<th>Nama Guru</th>
						<td>:</td>
						<td id="td_nama_guru">:</td>
					</tr>

					<tr>
						<th>Status Guru</th>
						<td>:</td>
						<td id="td_status_guru">:</td>
					</tr>

					<tr>
						<th>Alamat</th>
						<td>:</td>
						<td id="td_alamat">:</td>
					</tr>

					<tr>
						<th>Tempat, Tgl Lahir</th>
						<td>:</td>
						<td id="td_ttl">:</td>
					</tr>

					<tr>
						<th>Jenis Kelamin</th>
						<td>:</td>
						<td id="td_jk">:</td>
					</tr>

					<tr>
						<th>Status Nikah</th>
						<td>:</td>
						<td id="td_status_nikah">:</td>
					</tr>

					<tr>
						<th>No Telp</th>
						<td>:</td>
						<td id="td_notelp">:</td>
					</tr>
		     	</table>
			</div>
    	</div>
  	</div>
</div>

<div class="modal fade hapusdata" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-xs">
    	<div class="modal-content">
      		<div class="modal-header">
		   	 	<h5 class="modal-title" id="exampleModalLabel">Hapus Data User</h5>
		      	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">&times;</span>
		        	<form method="post" action="modal/p_user.php">
		        		
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
			var _url = "modal/p_user.php?nip="+id;
			$.ajax({
				url: _url, 
				success: function(result){
			  		var res = JSON.parse(result);
			  		console.log(res);
			  		$("#td_nip").html(res.nip);
			  		$("#td_jabatan").html(res.jabatan);
			  		$("#td_password").html(res.password);
			  		$("#td_nama_guru").html(res.nama_guru);
			  		$("#td_status_guru").html(res.status_guru);
			  		$("#td_alamat").html(res.alamat);
			  		$("#td_ttl").html(res.tempat_lahir+", "+res.tgl_lahir);
			  		$("#td_jk").html(res.jenis_kelamin=="L"?"Laki-laki":"Perempuan");
			  		$("#td_status_nikah").html(res.status_nikah=="B"?"Belum Nikah":"Sudah Nikah");
			  		$("#td_notelp").html(res.no_telp);
			  	}
			});
			$('.infolengkap').modal('show');
		});
		$(".btn_hapus").click(function(){
			var id = $(this).attr("id");
			$("#id_delete").val(id);
			$('.hapusdata').modal('show');
		});
    });
</script>