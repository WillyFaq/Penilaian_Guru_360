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
			<h3>Data Master Jenis Kompetensi</h3>
		</div>
	</div>
	<hr class="bg-primary" width="100%">
	<div class="container" >
		<div class="col-xs-12">
			<!-- Button trigger modal -->
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
			  	<span data-feather="file-plus"></span>
			</button>
			<?php 
				$btn = "Tambah"; 
				if(isset($_GET['ubah'])){
					$id_kompetensi = isset($_GET['id_kompetensi'])?mysql_real_escape_string(htmlspecialchars($_GET['id_kompetensi'])):"";
					$sql = "SELECT * FROM jenis_kompetensi WHERE id_kompetensi = $id_kompetensi";
					$q = mysql_query($sql);
					$data = [];
					while ($row = mysql_fetch_assoc($q)) {
						$id_kompetensi = $row['id_kompetensi']; 
						$nama_kompetensi = $row['nama_kompetensi']; 
						$bobot_kompetensi = $row['bobot_kompetensi'];
						$btn = "Ubah"; 
					}

			?>
			<script type="text/javascript">
			    $(document).ready(function(){
					$('#exampleModal').modal('show');
					
					$('#exampleModal').on('hidden.bs.modal', function(e){
						document.location = 'index.php?p=mjeniskom';
					});
			    });
			</script>
			<?php
				}
			?>
			<!-- Modal -->
			<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			  	<div class="modal-dialog" role="document">
				    <div class="modal-content">
				     	<div class="modal-header">
					        <h5 class="modal-title" id="exampleModalLabel">Data Jenis Kompetensi</h5>
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          	<span aria-hidden="true">&times;</span>
					        </button>
				      	</div>
				      	<div class="modal-body">
			       			<!-- form -->
					       	<form class="form-horizontal" method="post" action="modal/p_jenis_kompetensi.php">
			       			<input type="hidden" id="id_kompetensi" name="id_kompetensi" <?= isset($id_kompetensi)?'value="'.$id_kompetensi.'" readonly':""; ?> >
						  	<div class="form-group row">
								<label for="nama_kompetensi" class="col-sm-4 col-form-label col-form-label-sm">Jenis Kompetensi</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" id="nama_kompetensi" name="nama_kompetensi" value="<?= isset($nama_kompetensi)?$nama_kompetensi:""; ?>" placeholder="Nama Kompetensi">
								</div>
							</div>

							<div class="form-group row">
								<label for="bobot_kompetensi" class="col-sm-4 col-form-label col-form-label-sm">Bobot Kompetensi</label>
								<div class="col-sm-8">
									<input type="number" class="form-control" id="bobot_kompetensi" name="bobot_kompetensi" value="<?= isset($bobot_kompetensi)?$bobot_kompetensi:""; ?>" placeholder="Bobot Kompetensi">
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
				<input type="search" class="form-control" data-table="order-table" placeholder="Cari Data Jenis Kompetensi" />
				<hr>
				<table class="order-table">
					<thead>
						<tr>
							<th width="10%">No</th>
							<th width="45%">Jenis Kompetensi</th>
							<th width="15%">Bobot (%)</th>
							<th width="30%">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$sql = "SELECT * FROM jenis_kompetensi";
							$q = mysql_query($sql);
							$i=0;
							while($row = mysql_fetch_array($q)){
						?>
						<tr>
							<td><?= ++$i; ?></td>
							<td><?= $row['nama_kompetensi']; ?></td>
							<td><?= $row['bobot_kompetensi']; ?></td>
							<td>
								<button class="btn btn-outline-info btn-sm btn_info" id="<?= $row['id_kompetensi'];?>"><span data-feather="info"></span></button>
								<a href="index.php?p=mjeniskom&ubah=true&id_kompetensi=<?= $row['id_kompetensi'];?>" class="btn btn-outline-warning btn-sm" id="<?= $row['id_kompetensi'];?>"><span data-feather="edit"></span></a>
								<button href="#" class="btn btn-outline-danger btn-sm btn_hapus" id="<?= $row['id_kompetensi'];?>"><span data-feather="trash-2"></span></button>
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
  	<div class="modal-dialog modal-md">
    	<div class="modal-content">
	      	<div class="modal-header">
		   	 	<h5 class="modal-title" id="exampleModalLabel">Data Jenis Kompetensi</h5>
		      	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">&times;</span>
		      	</button>
		    </div>
		    <div class="modal-body">
		     	<table class="table">
		     		<tr>
		     			<th width="30%">Jenis Kompetensi</th>
		     			<td width="5%"> : </td>
		     			<td id="td_jenis_kompetensi">  </td>
		     		</tr>
		     		<tr>
		     			<th>Bobot</th>
		     			<td> : </td>
		     			<td  id="td_bobot"> 0 </td>
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
		   	 	<h5 class="modal-title" id="exampleModalLabel">Hapus Data Jenis Kompetensi</h5>
		      	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">&times;</span>
		        	<form method="post" action="modal/p_jenis_kompetensi.php">
		        		
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
			var _url = "modal/p_jenis_kompetensi.php?id_kompetensi="+id;
			$.ajax({
				url: _url, 
				success: function(result){
			  		var res = JSON.parse(result);
			  		console.log(res);
			  		$("#td_jenis_kompetensi").html(res.nama_kompetensi);
			  		$("#td_bobot").html(res.bobot_kompetensi);
			  	}
			});
			$('.infolengkap').modal('show');
		});

		$(".btn_hapus").click(function(){
			var id = $(this).attr("id");
			$("#id_delete").val(id);
			$('.hapusdata').modal('show');
		});

		$('#exampleModal').on('shown.bs.modal', function () {
			var _url = "modal/p_jenis_kompetensi.php?sum";
			$.ajax({
				url: _url, 
				success: function(result){
					var sum = result;
					var max = 100-sum;
					console.log(max);
					$("#bobot_kompetensi").attr("max", max);
			  	}
			});
		});
    });
</script>