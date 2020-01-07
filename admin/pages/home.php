<div class="container">
	<h1>Selamat Datang di Aplikasi Penilaian Kinerja Guru</h1>
	<div class="row">
		<div class="col">
		 <div class="card border-primary mb-3" style="max-width: 100%;">
		  <div class="card-header">
		  	<h5 class="text-right">Jumlah User <span data-feather="users"></span></h5>
		  </div>
		  <div class="card-body text-white bg-info">
		  	<div class="row">
		  		<div class="col">
		  			<?php
		  				$q = mysql_query("SELECT * FROM user");
		  				$nr = mysql_num_rows($q);
		  			?>
		  			<h1><?= $nr; ?></h1>
		  		</div>
		  	</div>
		  </div>
		</div>	
		</div>
	</div>
</div>