
<style >

.container table {
  width: 100%;
  font-size: 1rem;
}

.container td, .container th {
  padding: 10px;
}
/* 
.container td:first-child, .container th:first-child {
  padding-left: 20px;
}
 */
.container td:last-child, .container th:last-child {
  padding-right: 20px;
}

.container th {
  border-bottom: 1px solid #ddd;
  position: relative;
}

.tr_odd{
	background-color: rgba(0,0,0,.05);
}
/*  */
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
			<h1>Memilih Penilai</h1>
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
				Tambah Guru Penilai
			</button>
			<!-- Modal -->
			<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			  	<div class="modal-dialog modal-dialog-centered" role="document">
				    <div class="modal-content">
				      	<div class="modal-header">
					        <h5 class="modal-title" id="exampleModalCenterTitle">Data Penilai</h5>
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          	<span aria-hidden="true">&times;</span>
					        </button>
				      	</div>
				      	<?php
				      		echo '<script>';
				      		$i=0;
				      		$sql_guru = "SELECT * FROM user a JOIN jenis_user b ON a.id_jenis_user = b.id_jenis_user WHERE b.level = 1 AND (SELECT COUNT(*) FROM penilai c WHERE c.nip = a.nip ) = 0";
				      		$q = mysql_query($sql_guru);
				      		echo 'var data_guru = [';
				      		while($row = mysql_fetch_array($q)){
				      			if($i!=0){
				      				echo ",";
				      			}
				      			echo '{ nip : "'.$row['nip'].'", ';
				      			echo ' nama : "'.$row['nama_guru'].'"}';
				      			
				      			$i++;
				      		}
				      		echo '];';

				      		$i=0;
				      		$sql_guru = "SELECT * FROM user a JOIN jenis_user b ON a.id_jenis_user = b.id_jenis_user WHERE b.LEVEL = 1 AND (SELECT COUNT(*) FROM penilai_detail c WHERE c.nip = a.nip)<5";
				      		$q = mysql_query($sql_guru);
				      		echo 'var data_guru_pen2 = [';
				      		while($row = mysql_fetch_array($q)){
				      			if($i!=0){
				      				echo ",";
				      			}
				      			echo '{ nip : "'.$row['nip'].'", ';
				      			echo ' nama : "'.$row['nama_guru'].'"}';
				      			
				      			$i++;
				      		}
				      		echo '];';
				      		echo '</script>';
				      	?>
				      	<div class="modal-body">
				      	<form action="modal/p_penilai.php" method="post">
				      		<input type="hidden" name="txt_id_penilai" id="txt_id_penilai" value="" />
				      		<input type="hidden" name="tahun_ajar" value="<?= get_tahun_ajar_id(); ?>" />
				      		<div class="form-group row">
			                	<span class="label-text col-md-6 col-form-label text-md-left">Guru Dinilai</span>
			                    <div class="col-md-6">
			                        <select name="penilai" id="cb_guru_penilai" class="form-control" required>
			                            
			                        </select>
			                    </div>
			           	 	</div>
				      		<div class="form-group row">
			                	<span class="label-text col-md-6 col-form-label text-md-left">Guru Penilai 1</span>
			                    <div class="col-md-6">
			                        <select name="guru_1" id="cb_guru_dinilai_1" class="form-control" required>
			                            
			                        </select>
			                    </div>
			           	 	</div>
				      		<div class="form-group row">
			                	<span class="label-text col-md-6 col-form-label text-md-left">Guru Penilai 2</span>
			                    <div class="col-md-6">
			                        <select name="guru_2" id="cb_guru_dinilai_2" class="form-control" required>
			                            
			                        </select>
			                    </div>
			           	 	</div>
				      		<div class="form-group row">
			                	<span class="label-text col-md-6 col-form-label text-md-left">Guru Penilai 3</span>
			                    <div class="col-md-6">
			                        <select name="guru_3" id="cb_guru_dinilai_3" class="form-control" required>
			                            
			                        </select>
			                    </div>
			           	 	</div>
							
				            <!-- <div class="row">
				            					            <div class="col-md-4"><label><input type="checkbox" value=""> budi</label></div>
				            					            <div class="col-md-4"><label><input type="checkbox" value=""> budi</label></div>
				            					            <div class="col-md-4"><label><input type="checkbox" value=""> budi</label></div>
				            </div> -->
				      	</div>
				      	<div class="modal-footer">
				        	<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
				        	<input type="submit" class="btn btn-primary btnSimpan" name="btnSimpan" value="Tambah">
				      	</form>
				      	</div>
				    </div>
			  	</div>
			</div>
			<hr>
			<div style="background: rgba(79,195,247 ,0.3); padding: 10px 10px 10px 10px; border-radius: 1rem;">
				<input type="search" class="form-control " data-table="order-table" placeholder="Cari Data Penilai" />
				<hr>
				<table class="order-table table">
					<thead>
						<tr>
							<th width="10%">No</th>
							<th width="30%">Guru Dinilai</th>
							<th width="30%">Guru Penilai</th>
							<th width="30%">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$i=0;
							$fi = 0;
							$id_pen = "";
							$idper = get_tahun_ajar_id();
							$sql = "SELECT a.*, b.id_penilai_detail, b.nip as 'nip_dinilai', c.nama_guru as 'penilai', d.nama_guru as 'dinilai', e.jabatan FROM penilai a JOIN penilai_detail b ON a.id_penilai = b.id_penilai  JOIN user c ON a.nip = c.nip JOIN user d ON b.nip = d.nip JOIN jenis_user e ON d.id_jenis_user = e.id_jenis_user WHERE a.id_periode = $idper ORDER BY a.nip, e.level DESC";
							$q = mysql_query($sql);
							while($row = mysql_fetch_array($q)){
								if($row['nip']!=$row['nip_dinilai']){
									$ket = $row['jabatan'];
								}else{
									$ket = "Diri Sendiri";
								}
							
								if($fi==0){
									$odd='';
									if($i%2==0){
										$odd = 'class="tr_odd"';
									}
								$id_pen = $row['id_penilai'];
						?>

						<tr <?= $odd; ?> >
							<td rowspan="6" style="vertical-align:middle"><?= ++$i; ?></td>
							<td rowspan="6" style="vertical-align:middle"><?= $row['penilai'].'<br><small>NIP : '.$row['nip'].'</small>'; ?></td>
							<td><?= $row['dinilai'].' ('.$ket.') <br><small>NIP : '.$row['nip_dinilai'].'</small>'; ?></td>
							<td rowspan="6" style="vertical-align:middle; text-align:center;">
								<button class="btn btn-dark btn-sm btn_ubah" data-id="<?= $row['id_penilai']; ?>" ><span data-feather="edit"></span> </button>
								<button class="btn btn-danger btn-sm  btn_hapus" data-id="<?= $row['id_penilai']; ?>" ><span data-feather="trash-2"></span> </button>
							</td>
						</tr>
						<?php }else{ ?>
						<tr <?= $odd; ?> >
							
							<td><?= $row['dinilai'].' ('.$ket.') <br><small>NIP : '.$row['nip_dinilai'].'</small>'; ?></td>
						</tr>
						<?php } 
							$fi++;
							if($fi>=6){
								$fi=0;	
							}
							
							} 
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade hapusdata" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-md">
	    <div class="modal-content">
	      	<div class="modal-header">
		   	 	<h5 class="modal-title" id="exampleModalLabel">Hapus Penilai</h5>
		      	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">&times;</span>
		      	</button>
		    </div>
		    <div class="modal-body">
		    	<form action="modal/p_penilai.php" method="post">
		    		<input type="hidden" name="id_delete" id="id_delete">
			    	<button type="submit" class="btn btn-danger">Hapus</button>
			    	<button type="button" class="btn btn-secondary">Batal</button>
		    	</form>
		    </div>
		</div>
  	</div>
</div>

<script type="text/javascript">
	var guru_penilaian = '';
	var guru_dinilai_1 = data_guru_pen2;
	var guru_dinilai_2 = guru_dinilai_1;
    $(document).ready(function(){

    	$("#exampleModalCenter").on('hidden.bs.modal', function (event) {
    		document.location="index.php?p=memilihpen";
    	});
    	$("#exampleModalCenter").on('show.bs.modal', function (event) {
	    	guru_penilaian = '';
	    	data_guru.forEach(isi_guru);
			$("#cb_guru_penilai").html('');
			$("#cb_guru_penilai").append('<option value="">[ Pilih Guru ]</value>');
			$("#cb_guru_penilai").append(guru_penilaian);
    	});

		$("#cb_guru_penilai").change(function(){
			var v = $(this).val();
			var ind = get_index(v);
			guru_dinilai_1.splice(ind, 1);
			guru_penilaian = '';
			guru_dinilai_1.forEach(isi_guru);
			$("#cb_guru_dinilai_1").html('');
			$("#cb_guru_dinilai_1").append('<option value="">[ Pilih Guru ]</value>');
			$("#cb_guru_dinilai_1").append(guru_penilaian);
		});


		$("#cb_guru_dinilai_1").change(function(){
			var v = $(this).val();
			var ind = get_index(v);
			guru_dinilai_2 = guru_dinilai_1;
			guru_dinilai_2.splice(ind, 1);
			guru_penilaian = '';
			guru_dinilai_2.forEach(isi_guru);
			$("#cb_guru_dinilai_2").html('');
			$("#cb_guru_dinilai_2").append('<option value="">[ Pilih Guru ]</value>');
			$("#cb_guru_dinilai_2").append(guru_penilaian);
		});


		$("#cb_guru_dinilai_2").change(function(){
			var v = $(this).val();
			var ind = get_index(v);
			var guru_dinilai_3 = guru_dinilai_2;
			guru_dinilai_3.splice(ind, 1);
			guru_penilaian = '';
			guru_dinilai_3.forEach(isi_guru);
			$("#cb_guru_dinilai_3").html('');
			$("#cb_guru_dinilai_3").append('<option value="">[ Pilih Guru ]</value>');
			$("#cb_guru_dinilai_3").append(guru_penilaian);
		});


		$(".btn_hapus").click(function(){
			var daid = $(this).attr("data-id");
			$(".hapusdata").modal("show");
			$("#id_delete").val(daid);
		});


		$(".btn_ubah").click(function(){
			var daid = $(this).attr("data-id");
			var _url = "modal/p_penilai.php?id_penilai="+daid;
			$("#exampleModalCenter").modal("show");
			$(".btnSimpan").val("Ubah");
			$.ajax({
				url: _url, 
				success: function(result){
			  		var res = JSON.parse(result);
			  		
			  		$("#txt_id_penilai").val(res.id_penilai);

			  		$("#cb_guru_penilai").html("");
			  		$("#cb_guru_penilai").append("<option value='"+res.nip+"'>"+get_nama(res.nip, data_guru_pen2)+"</option>");
			  		$("#cb_guru_penilai").attr("readonly", true);
					guru_penilaian = '';

					var ind = get_index(res.nip);
					guru_dinilai_1.splice(ind, 1);
					guru_dinilai_1.forEach(isi_guru);
			  		$("#cb_guru_penilai_1").html("");
					$("#cb_guru_dinilai_1").append('<option value="">[ Pilih Guru ]</value>');
					$("#cb_guru_dinilai_1").append(guru_penilaian);
					$("#cb_guru_dinilai_1>option[value="+res.penilai1+"]").attr("selected", true);
			  		

					guru_dinilai_2 = guru_dinilai_1;
					ind = get_index(res.penilai1);
					guru_dinilai_2.splice(ind, 1);
					guru_penilaian = '';
					guru_dinilai_2.forEach(isi_guru);
			  		$("#cb_guru_penilai_2").html("");
					$("#cb_guru_dinilai_2").append('<option value="">[ Pilih Guru ]</value>');
					$("#cb_guru_dinilai_2").append(guru_penilaian);
					$("#cb_guru_dinilai_2>option[value="+res.penilai2+"]").attr("selected", true);


					var guru_dinilai_3 = guru_dinilai_2;
					ind = get_index(res.penilai2);
					guru_dinilai_3.splice(ind, 1);
					guru_penilaian = '';
					guru_dinilai_3.forEach(isi_guru);
			  		$("#cb_guru_penilai_2").html("");
					$("#cb_guru_dinilai_3").append('<option value="">[ Pilih Guru ]</value>');
					$("#cb_guru_dinilai_3").append(guru_penilaian);
					$("#cb_guru_dinilai_3>option[value="+res.penilai3+"]").attr("selected", true);
			  	}
			});
		});


    });

    function isi_guru(value) {
  		guru_penilaian = guru_penilaian + "<option value='"+value.nip+"'>"+value.nama+"</option>" ; 
	}

	function get_index (nip) {
		for(var i = 0; i < data_guru_pen2.length; i++){
			if(data_guru_pen2[i].nip == nip){
				return i;
			}
		}
		return -1; 
	}

	function get_nama (nip, arr) {
		for(var i = 0; i < arr.length; i++){
			if(arr[i].nip == nip){
				return arr[i].nama;
			}
		}
		return ""; 
	}
</script>

