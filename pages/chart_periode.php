<?php
require_once('../config/koneksi.php');

$ret = array(
        "type" => "spline",
        "visible" => true,
        "showInLegend" => true,
        "yValueFormatString" => "##.00" 
);
$p_sql = "SELECT * FROM periode";
$p_q = mysql_query($p_sql);
$i = 0;
$daa = [];
while($p_row = mysql_fetch_array($p_q)){
    if(isset($_GET['nip'])){
        $nip = mysql_real_escape_string(htmlspecialchars($_GET['nip']));
        $u_sql = "SELECT * FROM user a JOIN jenis_user b ON a.id_jenis_user = b.id_jenis_user WHERE b.level = 1 AND a.nip = '$nip'";
    }else{
        $u_sql = "SELECT * FROM user a JOIN jenis_user b ON a.id_jenis_user = b.id_jenis_user WHERE b.level = 1";
        $ret["name"] = "Semua Guru";
    }
    $u_q = mysql_query($u_sql);
    $j=0;
    $dd[$p_row['id_periode']] = [];

    while($u_row = mysql_fetch_array($u_q)){
        if(isset($_GET['nip'])){
            $ret["name"] = $u_row['nama_guru'];
        }
        $sql = "SELECT
                    d.nip,
                    d.nama_guru,
                    SUM(a.hasil_nilai) as nilai,
                    COUNT(a.id_nilai) as jml
                FROM penilaian a
                JOIN penilai_detail b ON a.id_penilai_detail = b.id_penilai_detail
                JOIN penilai c ON b.id_penilai = c.id_penilai
                JOIN user d ON c.nip = d.nip
                WHERE c.id_periode = $p_row[id_periode] AND d.nip = '$u_row[nip]' 
                GROUP BY d.nip
                HAVING COUNT(a.id_nilai) = (
                                                SELECT 
                                                (
                                                    (SELECT COUNT(*) FROM penilai p
                                                    JOIN penilai_detail pd ON p.id_penilai = pd.id_penilai
                                                    WHERE p.nip = d.nip)
                                                    *
                                                    (SELECT COUNT(*) FROM isi_kompetensi)
                                                ) as tot
                                                FROM dual
                                            )
                ORDER BY nilai DESC";
    /*echo $sql;
    echo "<br><br><br><br><br><br><br><br><br>";
    */
        $q = mysql_query($sql);
        if(mysql_num_rows($q)>0){
            array_push($dd[$p_row['id_periode']], get_tot_nilai($u_row['nip'], $p_row['id_periode']));
        }else{
            array_push($dd[$p_row['id_periode']], 0);
        }
    }
    $i++;
    $da[] = array('label' => $p_row['tahun_ajar']." ".$p_row['semester'],
                    'y' => array_sum($dd[$p_row['id_periode']])/count($dd[$p_row['id_periode']])
                     );
}
$ret['dataPoints'] = $da;
$as[] = $ret;
echo json_encode($as);







/*



            $sql = "SELECT
                        d.nip,
                        d.nama_guru,
                        IFNULL(SUM(a.hasil_nilai), 0) as nilai,
                        COUNT(a.id_nilai) as jml
                    FROM penilaian a
                    JOIN penilai_detail b ON a.id_penilai_detail = b.id_penilai_detail
                    JOIN penilai c ON b.id_penilai = c.id_penilai
                    JOIN user d ON c.nip = d.nip
                    WHERE c.id_periode = $p_row[id_periode] AND c.nip = '$u_row[nip]'";
            $q = mysql_query($sql);
            $row = mysql_fetch_array($q);
            if($j==0){
                $dd .= "{nama:'$row[nama_guru]',tahun_ajar:'$p_row[tahun_ajar] $p_row[semester]', nilai:$row[nilai]}";
            }else{
                $dd .= ", {nama:'$row[nama_guru]',tahun_ajar:'$p_row[tahun_ajar] $p_row[semester]', nilai:$row[nilai]}";
            }
            $daa[$row['nama_guru']][$i] = array('tahun_ajar'=> $p_row['tahun_ajar'].' '.$p_row['semester'], 'nilai'=>$row['nilai']); 
            $j++;
            $dd .= ",";

*/


?>


