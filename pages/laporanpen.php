<?php

	if($_SESSION[md5('level')] == 3 || $_SESSION[md5('level')] == 2){
		include "laporan_atasan.php";
	}else if($_SESSION[md5('level')] == 1){
		include "laporan_guru.php";
	}

?>