<?php
  require_once('../config/koneksi.php'); 

  if(!isset($_SESSION[md5('user')]) || $_SESSION[md5('user')]==''){
    echo "<script>document.location='../login.php';</script>";
  }else if($_SESSION[md5('level')]!=0){
    echo "<script>document.location='../index.php';</script>";

  }

  if(isset($_GET['logout'])){
   $_SESSION[md5('user')] = ''; 
   $_SESSION[md5('nama')] = '';
   $_SESSION[md5('level')] = '';
   unset($_SESSION[md5('user')]);
   unset($_SESSION[md5('nama')]);
   unset($_SESSION[md5('level')]);
    echo "<script>document.location='../login.php';</script>";
  }
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>Admin Panel</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/css/dashboard.css" rel="stylesheet">
    <link href="assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet">
    <link href="assets/plugins/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">
    <script src="assets/js/prompt.js"></script>

    <script src="assets/js/jquery.js" ></script>
  </head>

  <body>
    <nav class="navbar navbar-dark sticky-top bg-primary flex-md-nowrap p-0">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">
        <strong>SD Hang Tuah VII</strong>
        <br>
        <span style="font-size:0.8em;">Tahun Ajar <?= get_tahun_ajar(); ?></span>
      </a>
      
      <ul class="navbar-nav col-sm-6 nav_bar">
        <li class="nav-item text-nowrap">
          <a class="nav-link" href="index.php?logout">Sign out</a>
        </li>
        <li class="nav-item text-nowrap">
          <a class="nav-link" ><?= $_SESSION[md5('nama')]; ?></a>
        </li>
      </ul>

    </nav>
    <div class="container-fluid ">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link" href="index.php?p=home">
                  <span data-feather="home"></span>Home <span class="sr-only">(current)</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="index.php?p=mjuser">
                  <span data-feather="users"></span>
                  Jenis User
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="index.php?p=muser">
                  <span data-feather="user"></span>
                  user
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="index.php?p=mjeniskom">
                  <span data-feather="file-text"></span>
                  Jenis Kompetensi
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="index.php?p=misikom">
                  <span data-feather="file"></span>
                  Isi Kompetensi
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="index.php?p=mperiode">
                  <span data-feather="clock"></span>
                  Periode
                </a>
              </li>
            </ul>
          </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
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
      </div>
    </div>
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
    <!-- <script src="assets/js/jquery-slim.min.js"></script> -->
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatables-plugins/dataTables.bootstrap.js"></script>
  
<script type="text/javascript" src="assets/plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <!-- Icons -->
    <script src="assets/js/feather.min.js"></script>
    <script>
      feather.replace()
    </script>

    <!-- Graphs -->
    <script type="text/javascript">
        $(document).ready(function(){


          var url = document.URL;
          var segments = url.split('/');
          //console.log(url);
          console.log(segments[5]);
          
          if(segments[5]=="index.php" || segments[5]==""){
              $("[href='index.php?p=home']").addClass('active');
            return;
          }else{
              $("[href='"+segments[5]+"']").addClass('active');
            /*var a = segments[5];
            $("#"+a).addClass('active');*/
          }

          //$('.dataTable').DataTable();
          
        });

        setTimeout(function(){
            $(".alert").hide(500);
        }, 3000);
    </script>  
  </body>
</html>
