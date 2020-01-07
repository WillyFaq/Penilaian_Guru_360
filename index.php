<?php
  require_once('config/koneksi.php'); 


  if(!isset($_SESSION[md5('user')]) || $_SESSION[md5('user')]==''){
    echo "<script>document.location='login.php';</script>";
  }else if($_SESSION[md5('level')]==0){
    echo "<script>document.location='admin/index.php';</script>";

  }

  if(isset($_GET['logout'])){
   $_SESSION[md5('user')] = ''; 
   $_SESSION[md5('nama')] = '';
   $_SESSION[md5('level')] = '';
   unset($_SESSION[md5('user')]);
   unset($_SESSION[md5('nama')]);
   unset($_SESSION[md5('level')]);
    echo "<script>document.location='login.php';</script>";
  }
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="<?= base_url('assets/img/logo.png'); ?>">

    <title>SD Hang Tuah VII Surabaya</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/plugins/d3-chart/gauge.css" rel="stylesheet">
    <link href="assets/plugins/d3-chart/bar.css" rel="stylesheet">

    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/navbar-top-fixed.css" rel="stylesheet">

    <script type="text/javascript" src="assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap.js"></script>
    <script type="text/javascript" src="assets/js/popper.min.js"></script>
    <!--<script type="text/javascript" src="assets/plugins/d3-chart/d3.min.js"></script>-->
    <script type="text/javascript" src="assets/plugins/d3-chart/d3.v5.min.js"></script>
    <!-- Custom styles for this template -->

  </head>
  <body style="background-image: url(assets/img/pattern.jpg)">
    

    <nav class="navbar navbar-icon-top navbar-expand-lg navbar-dark bg-primary fixed-top">
      <a class="navbar-brand" href="index.php">SD Hang Tuah VII Surabaya</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.php?p=home">
              <i class="fa fa-home"></i>
              Home
              <span class="sr-only">(current)</span>
              </a>
          </li>
          <?php if($_SESSION[md5('level')] == 3 || $_SESSION[md5('level')] == 2 ): ?>
          <li class="nav-item">
            <a class="nav-link" href="index.php?p=memilihpen">
              <i class="fa fa-hand-o-right"></i>
              Memilih Penilai
            </a>
          </li>
          <?php endif; ?>
          <li class="nav-item">
            <a class="nav-link" href="index.php?p=melakukanpen">
              <i class="fa fa-check-square-o">
              </i>
              Penilaian Kinerja
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php?p=laporanpen">
              <i class="fa fa-file-text">
              </i>
              Laporan Penilaian Kinerja
            </a>
          </li>
        </ul>
        <ul class="navbar-nav ">
          <li class="nav-item active dropdown">
            <?php
              $nip_user = $_SESSION[md5('user')];
              $sql = "SELECT * FROM penilai a JOIN penilai_detail b ON a.id_penilai = b.id_penilai JOIN user d ON a.nip = d.nip WHERE b.nip = '$nip_user' AND b.id_penilai_detail NOT IN(SELECT c.id_penilai_detail FROM penilaian c WHERE c.id_penilai_detail = b.id_penilai_detail) GROUP BY a.id_penilai";
              $q = mysql_query($sql);
              $nr = mysql_num_rows($q);

              if($_SESSION[md5('level')] == 3 || $_SESSION[md5('level')] == 2 ){
                $sql_a = "SELECT 
                          a.nip AS 'nip_dinilai',
                          d.nama_guru AS 'nama_dinilai',
                          b.nip AS 'nama_penilai',
                          e.nama_guru AS 'nama_penilai',
                          SUM(c.hasil_nilai) AS nilai
                        FROM (penilai a JOIN user d ON a.nip = d.nip)
                        JOIN (penilai_detail b  JOIN user e ON b.nip = e.nip) ON a.id_penilai = b.id_penilai
                        LEFT JOIN penilaian c ON b.id_penilai_detail = c.id_penilai_detail
                        GROUP BY a.nip, b.nip
                        HAVING  ISNULL(SUM(c.hasil_nilai))";
                $q_a = mysql_query($sql_a);
                $nr_a = mysql_num_rows($q_a);
              }

            ?>
            
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" href="#">
              <i class="fa fa-bell">
                <?php if($nr>0 && isset($nr_a)){ $nr+=$nr_a; ?>
                <span class="badge badge-danger"><?= $nr; ?></span>
                <?php }else if(isset($nr_a) && $nr_a>0){ ?>
                <span class="badge badge-danger"><?= $nr_a; ?></span>
                <?php }else if($nr>0){ ?>
                <span class="badge badge-danger"><?= $nr; ?></span>
                <?php } ?>
              </i>
              Notifikasi
            </a>
            <?php if($nr>0 && isset($nr_a)){?>
            <div class="dropdown-menu" style="left:-400px; height: 400px; overflow-y: scroll;">
              <p class="dropdown-item disabled" >
                Anda belum melakukan penilaian kepada : 
              </p>
              <?php
                while($row = mysql_fetch_array($q)){
              ?>
              <a class="dropdown-item" href="index.php?p=melakukanpen&id=<?= $row['id_penilai']; ?>">
                <span data-feather="user"></span> &nbsp;&nbsp;&nbsp;
                <?= $row['nama_guru']; ?>
              </a>
              <?php } ?>
              <p class="dropdown-item disabled" >
                Guru yang belum melakukan penilaian : 
              </p>
              <?php
                while($row = mysql_fetch_array($q_a)){
              ?>
              <p class="dropdown-item" >
                <span data-feather="user"></span> &nbsp;&nbsp;&nbsp;
                <?= '<strong>'.$row['nama_penilai'].'</strong> kepada : <strong>'.$row['nama_dinilai'].'</strong>'; ?>
              </p>
              <?php } ?>
            </div>
            <?php }else if($nr>0){?>
            <div class="dropdown-menu" style="left:-250px">
              <p class="dropdown-item disabled" >
                Anda belum melakukan penilaian kepada : 
              </p>
              <?php
                while($row = mysql_fetch_array($q)){
              ?>
              <a class="dropdown-item" href="index.php?p=melakukanpen&id=<?= $row['id_penilai']; ?>">
                <span data-feather="user"></span> &nbsp;&nbsp;&nbsp;
                <?= $row['nama_guru']; ?>
              </a>
              <?php } ?>
            </div>
            <?php }else if($nr>0){?>
            <div class="dropdown-menu" style="left:-250px">
              <p class="dropdown-item disabled" >
                Guru yang belum melakukan penilaian : 
              </p>
              <?php
                while($row = mysql_fetch_array($q_a)){
              ?>
              <p class="dropdown-item" >
                <span data-feather="user"></span> &nbsp;&nbsp;&nbsp;
                <?= '<strong>'.$row['nama_penilai'].'</strong> kepada : <strong>'.$row['nama_dinilai'].'</strong>'; ?>
              </p>
            <?php } ?>  
            </div>
            <?php } ?>
          </li>
          <li class="nav-item active dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" href="#">
              <i class="fa fa-user"></i>
              <?= $_SESSION[md5("nama")]; ?>
            </a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="index.php?p=profil"><span data-feather="user"></span>Profile</a>
              <a class="dropdown-item" href="index.php?logout"><span data-feather="log-out"></span>Log Out</a>
            </div>
          </li>
        </ul>
      </div>
    </nav>
    <main role="main" class="container"  id="page-wraper">
                <?php
                    if(isset($_GET['p'])){
                        $dir = 'pages';
                        $page = $_GET['p'].'.php';
                        $hal = scandir($dir);
                        if(in_array($page, $hal)){
                            include $dir.'/'.$page;
                        }else{
                            echo 'NOT FOUND';
                        }
                    }else{
                        include 'pages/home.php';
                    }
                ?>
    </main>

    <footer class="footer bg-primary">
      <div class="container">
        <div class="row">
          <div class="col text-right">
            <span class="text-white">SD Hang Tuah VII Surabaya | 2019</span>
          </div>
        </div>
      </div>
    </footer>
    <?php if(isset($_SESSION["flash"])){ ?>
    <div class="alert alert-<?= $_SESSION["flash"]["type"]; ?> alert-dismissible alert_model" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <strong><?= $_SESSION["flash"]["head"]; ?></strong> <?= $_SESSION["flash"]["msg"]; ?>
    </div>
    <?php unset($_SESSION['flash']); } ?>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/feather.min.js"></script>

    <script>
      feather.replace()
    </script>
    <script type="text/javascript">
        $(document).ready(function(){


          var url = document.URL;
          var segments = url.split('/');
          //console.log(url);
          //console.log(segments[4]);
          
          if(segments[4]=="index.php" || segments[4]==""){
              $("[href='index.php?p=home']").addClass('active');
            //return;
          /*}else if(segments[4]=="index.php?p=memilihpen" || segments[4]=="index.php?p=melakukanpen"){
              $("[href='"+segments[4]+"']").parent().parent().addClass('active');
              console.log("disis");
          */
          }else{
              var ar = segments[4].split("&");
              if(ar.length>1){
                $("[href='"+ar[0]+"']").addClass('active');
              }else{
                $("[href='"+segments[4]+"']").addClass('active');
                
              }
          }

          //$('.dataTable').DataTable();
          
        });

        setTimeout(function(){
            $(".alert").hide(500);
        }, 3000);
    </script>  
  </body>

</html>
