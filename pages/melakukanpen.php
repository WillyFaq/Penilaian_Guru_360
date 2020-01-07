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

tr.bold > td{
    font-weight: bolder;
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
    <?php

        if(!isset($_GET['id'])){
    ?>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h2>Data guru yang dinilai</h2> <a class="float-right" title="Rubrik Penilaian" href="assets/file/rubrik.pdf"><i class="fa fa-file-pdf-o fa-2x"></i></a>
            <br>
            <table class="table">
                <thead>
                    <tr>
                        <td>No</td>
                        <td>NIP</td>
                        <td>Nama</td>
                        <td>Aksi</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i=0;
                        $nip_s = $_SESSION[md5('user')];
                        $sql = "SELECT a.id_penilai, a.nip, c.nama_guru, b.id_penilai_detail FROM penilai a JOIN penilai_detail b  ON a.id_penilai = b.id_penilai
                                JOIN user c ON a.nip = c.nip WHERE b.nip = '$nip_s' ";
                                /*AND b.id_penilai_detail NOT IN(SELECT id_penilai_detail FROM penilaian)*/
                        //echo $sql;
                        $q = mysql_query($sql);
                        //if(mysql_num_rows($q)>0)
                        while($row = mysql_fetch_array($q)){
                    ?>
                    <tr class="<?= sudah($row['id_penilai_detail']); ?>">
                        <td><?= ++$i; ?></td>
                        <td><?= $row['nip']; ?></td>
                        <td><?= $row['nama_guru']; ?></td>
                        <td>
                            <a href="index.php?p=melakukanpen&id=<?= $row['id_penilai']; ?>" class="btn btn-success btn-sm" >
                                <span class="fa fa-pencil fa-2x"></span> 
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php }else{ ?>
        <?php
            $nip_s = $_SESSION[md5('user')];
            $ssql = "SELECT * FROM user c JOIN jenis_user d ON c.id_jenis_user = d.id_jenis_user WHERE c.nip = '$nip_s'";
            $q = mysql_query($ssql);
            $rw = mysql_fetch_array($q);
            $sebagai = $rw['level']==3?'0':($rw['level']==1?'1':($nip_s==$rw['nip']?'2':''));

            $id_penilai = isset($_GET['id'])?mysql_real_escape_string(htmlspecialchars($_GET['id'])):"";
            $sql = "SELECT a.id_penilai, a.nip, b.nama_guru, c.jabatan FROM penilai a JOIN user b ON a.nip = b.nip JOIN jenis_user c ON b.id_jenis_user = c.id_jenis_user WHERE a.id_penilai = '$id_penilai'";
            $q = mysql_query($sql);
            $row  = mysql_fetch_array($q);
        ?>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h2>Penilaian Kinerja</h2>
            <br>
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
            </table>
            <hr>
            <form class="form-horizontal" method="post" action="modal/p_nilai.php">
                <input type="hidden" name="nip_dinilai" value="<?= $row['nip']; ?>" >
                <input type="hidden" name="nip_penilai" value="<?= $_SESSION[md5('user')]; ?>" >
            <nav class="">
              <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <!-- <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Kompetensi Pedagogik</a>
                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Kompetensi Kepribadian</a>
                <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Kompetensi Sosial</a>
                <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contacti" role="tab" aria-controls="nav-contacti" aria-selected="false">Kompetensi Profesional</a>
                 --><?php 
                    $sql = "SELECT * FROM jenis_kompetensi";
                    $q = mysql_query($sql);
                    $i = 0;
                    $data_kompetensi = [];
                    while($row = mysql_fetch_array($q)){
                        $data_kompetensi[$i]['id_kompetensi'] = $row['id_kompetensi'];
                        $data_kompetensi[$i]['nama_kompetensi'] = $row['nama_kompetensi'];
                        $data_kompetensi[$i]['bobot_kompetensi'] = $row['bobot_kompetensi'];
                        if($i==0){
                ?>
                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-<?= $row['id_kompetensi']; ?>" role="tab" aria-controls="nav-home" aria-selected="true"><?= $row['nama_kompetensi']; ?></a>
                <?php
                        }else{
                ?>
                    <a class="nav-item nav-link" id="nav-home-tab" data-toggle="tab" href="#nav-<?= $row['id_kompetensi']; ?>" role="tab" aria-controls="nav-home" aria-selected="true"><?= $row['nama_kompetensi']; ?></a>
                <?php 
                        }
                        $i++;
                    }
                ?>
              </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
              <?php
                foreach ($data_kompetensi as $k => $v) {
                    if($k==0){
                        $ext = "show active";
                    }else{
                        $ext = "";
                    }
                ?>
                    <div class="tab-pane fade <?= $ext;?>" id="nav-<?= $v['id_kompetensi']; ?>" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <br>
                        <div>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="70%">Isi Kompetensi</th>
                                        <th >1</th>
                                        <th >2</th>
                                        <th >3</th>
                                        <th >4</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $i=0;
                                        $sq = "SELECT * FROM isi_kompetensi WHERE id_kompetensi = $v[id_kompetensi] AND ket LIKE '%$sebagai%' ";
                                        $qs = mysql_query($sq);
                                        while($row = mysql_fetch_array($qs)){
                                    ?>
                                    <tr>
                                        <td ><?= ++$i; ?></td>
                                        <td ><?= $row['isi_kompetensi']; ?></td>
                                        <td class="form-group">
                                            <input class="form-control form-control-lg" type="radio" name="kompetensi_<?= $row['id_isi']; ?>" id="kompetensi_<?= $row['id_isi']; ?>_1" title="Tidak Mampu" value="1" required>
                                        </td>
                                        <td class="form-group">
                                            <input class="form-control form-control-lg" type="radio" name="kompetensi_<?= $row['id_isi']; ?>" id="kompetensi_<?= $row['id_isi']; ?>_2" title="Kurang Mampu" value="2" required>
                                        </td>
                                        <td class="form-group">
                                            <input class="form-control form-control-lg" type="radio" name="kompetensi_<?= $row['id_isi']; ?>" id="kompetensi_<?= $row['id_isi']; ?>_3" title="Mampu" value="3" required>
                                        </td>
                                        <td class="form-group">
                                            <input class="form-control form-control-lg" type="radio" name="kompetensi_<?= $row['id_isi']; ?>" id="kompetensi_<?= $row['id_isi']; ?>_4" title="Sangat Mampu" value="4" required>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php
                }
              ?>
            </div>
            <?php
                $nip_s = $_SESSION[md5('user')];
                $sql = "SELECT * FROM penilai a JOIN penilai_detail b ON a.id_penilai = b.id_penilai JOIN penilaian c ON b.id_penilai_detail = c.id_penilai_detail WHERE a.id_penilai = '$id_penilai' AND b.nip = '$nip_s'";
                $q = mysql_query($sql);
                if(mysql_num_rows($q)>0){
                    echo '<script>';
                    while($row = mysql_fetch_array($q)){
                        echo '$("#kompetensi_'.$row['id_isi'].'_'.$row['hasil_nilai'].'").attr("checked",true);';
                    }
                    echo '</script>';
                }
            ?>
            <div class="container">
                <div class="float-right">
                    <br>
                    <button type="submit" class="btn btn-primary btn-md">Simpan</button>
                </div>
            </div>
            </form>
        <?php } ?>
        </div>
    </div>
</div>


<?php

    function sudah($idpdt=''){
        $sql = "SELECT * FROM penilai a JOIN penilai_detail b ON a.id_penilai = b.id_penilai JOIN penilaian c ON b.id_penilai_detail = c.id_penilai_detail WHERE b.id_penilai_detail = $idpdt";
        //echo $sql; 
        $q = mysql_query($sql);
        if(mysql_num_rows($q)>0){
            return 'bold';
        }else{
            return '';
        }
    }
?>