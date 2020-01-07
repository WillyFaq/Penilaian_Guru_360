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
			<h1>Laporan Penilaian Kinerja Guru</h1>
			<hr/>
		<table class="table">
			<thead>
				<tr>
					<th>No</th>
					<th>NIP</th>
					<th>Nama</th>
					<th>Jabatan</th>
					<th>Pedagogik</th>
					<th>Kepribadian</th>
					<th>Sosial</th>
					<th>Profesional</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>
		<?php
			$nip_user = $_SESSION[md5('user')];//'2012091200113504';
			

			$sql = "SELECT * FROM jenis_kompetensi";
			$q = mysql_query($sql);
			
			while($row = mysql_fetch_array($q)){
				${"b_".$row['nama_kompetensi']} = $row['bobot_kompetensi'];
			}

			$sql = "SELECT * FROM penilai a JOIN penilai_detail b ON a.id_penilai = b.id_penilai WHERE a.nip = '$nip_user' ";
			$q = mysql_query($sql);
			$id_penilai_detail = '';
			$i=0;
			while($row = mysql_fetch_array($q)){
				if($i==0){
					$id_penilai_detail .= $row['id_penilai_detail'];
				}else{
					$id_penilai_detail .= ", ".$row['id_penilai_detail'];
				}
				$i++;
			}
			$id_periode = get_tahun_ajar_id();
			$sql = "SELECT 
						tbnilai.nip_penilai,
						tbnilai.penilai,
						tbnilai.level,
						tbnilai.jabatan,
						SUM( IF(tbnilai.nama_kompetensi = 'Pedagogik', tbnilai.nilai, 0) ) AS 'Pedagogik',
						SUM( IF(tbnilai.nama_kompetensi = 'Kepribadian', tbnilai.nilai, 0) ) AS 'Kepribadian',
						SUM( IF(tbnilai.nama_kompetensi = 'Sosial', tbnilai.nilai, 0) ) AS 'Sosial',
						SUM( IF(tbnilai.nama_kompetensi = 'Profesional', tbnilai.nilai, 0) ) AS 'Profesional'
					FROM 
					(SELECT 
						a.id_nilai, 
						h.nip as nip_dinilai,
						h.nama_guru as 'dinilai',
						e.nip as nip_penilai, 
						e.nama_guru as 'penilai',
						f.jabatan,
						f.level,
						c.id_kompetensi,
						c.nama_kompetensi,
						c.bobot_kompetensi,
						SUM(a.hasil_nilai) as nilai
					FROM penilaian a 
					JOIN isi_kompetensi b ON a.id_isi = b.id_isi
					JOIN jenis_kompetensi c ON b.id_kompetensi = c.id_kompetensi
					JOIN (penilai_detail d JOIN user e ON d.nip = e.nip JOIN jenis_user f ON f.id_jenis_user = e.id_jenis_user) ON d.id_penilai_detail = a.id_penilai_detail 
					JOIN (penilai g JOIN user h ON g.nip = h.nip ) ON d.id_penilai = g.id_penilai
					WHERE a.id_penilai_detail IN ($id_penilai_detail) AND g.id_periode = $id_periode
					GROUP BY a.id_penilai_detail, c.id_kompetensi
					ORDER BY 4) as tbnilai
					GROUP BY tbnilai.penilai";
			//echo $sql;
			$q = mysql_query($sql);
			$nno = 0;
			echo "<br>";
			$tot_arr['atasan'] = 0;
			$tot_arr['guru'] = 0;
			$tot_arr['sendiri'] = 0;
			while($row = mysql_fetch_array($q)){
				$tot = 0;
				$pg = ($row['Pedagogik']/10)*100;
				$kp = ($row['Kepribadian']/5)*100;
				$ss = ($row['Sosial']/4)*100;
				$pr = ($row['Profesional']/5)*100;
				/* prestasi kinerja individu */
				$tot = ($pg*($b_Pedagogik/100)) + ($kp*($b_Kepribadian/100)) + ($ss*($b_Sosial/100)) + ($pr*($b_Profesional/100));

				if($row['level']==2 || $row['level']==3){
					$tot_arr['atasan'] += $tot;
				}else if($row['level']==1 && $row['nip_penilai']!= $nip_user){
					$tot_arr['guru'] += $tot;
				}else{
					$tot_arr['sendiri'] += $tot;
				}
				echo "<tr>";
				echo "<td>".++$nno."</td>";
				echo "<td>$row[nip_penilai]</td>";
				echo "<td>$row[penilai]</td>";
				echo "<td>$row[jabatan]</td>";

				echo "<td>$row[Pedagogik] <hr> ($row[Pedagogik] / 10) * 100 = $pg</td>";
				echo "<td>$row[Kepribadian] <hr> ($row[Kepribadian] / 5) * 100 = $kp</td>";
				echo "<td>$row[Sosial] <hr> ($row[Sosial] / 4) * 100 = $ss</td>";
				echo "<td>$row[Profesional] <hr> ($row[Profesional] / 5) * 100 = $pr</td>";
				echo "<td>($pg*$b_Pedagogik%) + ($kp*$b_Kepribadian%) + ($ss*$b_Sosial%) + ($pr*$b_Profesional%) =  $tot</td>";
				echo "</tr>";
			}
		?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="8">Total Nilai Kinerja <?= "($tot_arr[atasan]*50%) + ($tot_arr[guru]*30%) + ($tot_arr[sendiri]*20%)"; ?> =  </th>
					<th>
		<?php
			
			$ak = ($tot_arr['atasan']*0.5) + ($tot_arr['guru']*0.3) + ($tot_arr['sendiri']*0.2);
			echo $ak;	
				
		?>
					</th>
				</tr>
			</tfoot>
					</table>
		
		<script>
			<?php
				echo "var data_bar = [";
				echo "{oleh: 'Atasan', nilai: $tot_arr[atasan] },";
				echo "{oleh: 'Rekan Kerja', nilai: $tot_arr[guru] },";
				echo "{oleh: 'Diri Sendiri', nilai: $tot_arr[sendiri] }";
				echo "];";
			?>
		</script>
		<!-- <pre>
		<?php print_r($tot_arr); ?>
		<?= $sql; ?>
		</pre> -->
		</div>
	</div>
	<div class="row">
		<div class="col-6">
			<div class="card">
			  	<div class="card-header bg-success">
			    	<p class="card-title text-white"><strong>Nilai Akhir</strong></p>
			  	</div>
			  	<div class="card-body">
			    	<div id="chart-nilai-akhir"></div>
			  	</div>
			</div>
		</div>
		<div class="col-6">
			<div class="card">
			  	<div class="card-header bg-primary">
			    	<p class="card-title text-white"><strong>Nilai Perwakilan</strong></p>
			  	</div>
			  	<div class="card-body">
			    	<div id="chart-nilai-perwakilan"></div>
			  	</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" >
	var size = $("#chart-nilai-akhir").width()/2;//150,
    thickness = 60;

	//console.log(size);
	var color = d3.scaleLinear()
	    //.domain([0, 50, 100])
	    //.range(['#db2828', '#fbbd08', '#21ba45']);
	    .domain([0, 450, 600, 740, 840])
	    .range(['#db4639', '#FFCD42', '#48ba17', '#12ab24', '#0f9f59']);

	var arc = d3.arc()
	    .innerRadius(size - thickness)
	    .outerRadius(size)
	    .startAngle(-Math.PI / 2);

	var svg = d3.select('#chart-nilai-akhir').append('svg')
	    .attr('width', size * 2)
	    .attr('height', size + 20)
	    .attr('class', 'gauge');


	var chart = svg.append('g')
	    .attr('transform', 'translate(' + size + ',' + size + ')')

	var background = chart.append('path')
	    .datum({
	        endAngle: Math.PI / 2
	    })
	    .attr('class', 'background')
	    .attr('d', arc);

	var foreground = chart.append('path')
	    .datum({
	        endAngle: -Math.PI / 2
	    })
	    .style('fill', '#db2828')
	    .attr('d', arc);

	var value = svg.append('g')
	    .attr('transform', 'translate(' + size + ',' + (size * .9) + ')')
	    .append('text')
	    .text(0)
	    .attr('text-anchor', 'middle')
	    .attr('class', 'value');


	var kete = svg.append('g')
	    .attr('transform', 'translate(' + size + ',' + (size * 1.05) + ')')
	    .append('text')
	    .text(0)
	    .attr('text-anchor', 'middle')
	    .attr('class', 'nhuruf');

	var scale = svg.append('g')
	    .attr('transform', 'translate(' + size + ',' + (size + 15) + ')')
	    .attr('class', 'scale');

	scale.append('text')
	    .text(840)
	    .attr('text-anchor', 'middle')
	    .attr('x', (size - thickness / 2));

	scale.append('text')
	    .text(0)
	    .attr('text-anchor', 'middle')
	    .attr('x', -(size - thickness / 2));
	/*
	setInterval(function() {
	    update(Math.random() * 840);
	}, 1500);*/
	//update_gauge(500);
	update_gauge(<?= $ak; ?>);

	function update_gauge(v) {
	    v = d3.format('.1f')(v);
	    //console.log("update", v);
	    foreground.transition()
	        .duration(750)
	        .style('fill', function() {
	            return color(v);
	        })
	        .call(arcTween, v);

	    value.transition()
	        .duration(750)
	        .call(textTween, v);

	    kete.transition()
	        .duration(750)
	        .call(textKet, rentang(v));
	}

	function arcTween(transition, v) {
	    var newAngle = v / 840 * Math.PI - Math.PI / 2;
	    transition.attrTween('d', function(d) {
	        var interpolate = d3.interpolate(d.endAngle, newAngle);
	        return function(t) {
	            d.endAngle = interpolate(t);
	            return arc(d);
	        };
	    });
	}

	function textTween(transition, v) {
		//console.log(v);
	    transition.tween('text', function() {
	        var interpolate = d3.interpolate(this.innerHTML, v),
	            split = (v + '').split('.'),
	            round = (split.length > 1) ? Math.pow(10, split[1].length) : 1;
	        return function(t) {
	            this.innerHTML = d3.format('.1f')(Math.round(interpolate(t) * round) / round);
	        };
	    });
	}

	function textKet(transition, v) {
		//console.log(v);
	    transition.tween('text', function() {
	        var interpolate = d3.interpolate(this.innerHTML, v),
	            split = (v + '').split('.'),
	            round = (split.length > 1) ? Math.pow(10, split[1].length) : 1;
	        return function(t) {
	            this.innerHTML = v//d3.format('.1f')(Math.round(interpolate(t) * round) / round);
	        };
	    });
	}

	function rentang(v){
		v = Number(v);
		
		if(v<=840 && v>=741){
			return "Sangat Baik";
		}else if(v<=740 && v>=601){
			return "Baik";
		}else if(v<=597 && v>=451){
			return "Kurang";
		}else if(v<=450){
			return "Sangat Kurang";
		}else{
			return "Kurang Ajar";
		}
	}
</script>

<script>

	var size_bar = $("#chart-nilai-perwakilan").width()/2;//150,
    thickness_bar = 60;
    margin = 30;
    bar_width = (size_bar * 2) - 2 * margin;
    bar_height = (size_bar + 2) - 1 * margin;

	var svg_bar = d3.select('#chart-nilai-perwakilan').append('svg')
	    .attr('width', size_bar * 2)
	    .attr('height', size_bar + 20)
	    .attr('class', 'bar');

    var chart_bar = svg_bar.append('g')
	    .attr('transform', 'translate(' + margin + ',' + margin + ')')

    var xScale = d3.scaleBand()
      	.range([0, bar_width])
      	.domain(data_bar.map((s) => s.oleh))
      	.padding(0.4)
    
    var yScale = d3.scaleLinear()
      	.range([bar_height, 0])
      	.domain([0, 840]);


    var makeYLines = () => d3.axisLeft()
      	.scale(yScale)

    chart_bar.append('g')
      	.attr('transform', 'translate(0, '+bar_height+')')
      	.call(d3.axisBottom(xScale));

    chart_bar.append('g')
      	.call(d3.axisLeft(yScale));

    chart_bar.append('g')
      	.attr('class', 'grid')
      	.call(makeYLines()
        	.tickSize(-bar_width, 0, 0)
        	.tickFormat('')
      	)


    var barGroups = chart_bar.selectAll()
    	.data(data_bar)
      	.enter()
      	.append('g')

    barGroups
      	.append('rect')
      	.attr('class', 'bar')
      	.attr('x', function(g) { return xScale(g.oleh)})
      	.attr('width', xScale.bandwidth())
      	.on('mouseenter', mouseOver)
      	.on('mouseleave', mouseLeave)
      	.attr('y', function(g) { return yScale(0)})
      	.attr('height', function (g) { return bar_height - yScale(0)})
      	.transition()
         	.ease(d3.easeExp)
         	.duration(750)
         	.delay(function (g, i) {
         		//console.log(i+" "+yScale(g.nilai));
            	return i * 50;
         	})
      	.attr('y', function(g) { return yScale(g.nilai)})
      	.attr('height', function (g) { return bar_height - yScale(g.nilai)})

    barGroups 
      	.append('text')
      	.attr('class', 'value_bar')
      	.attr('x', (a) => xScale(a.oleh) + xScale.bandwidth() / 2)
       	.attr('y', (a) => yScale(a.nilai) + 30)
        .attr('fill', 'white')
      	.attr('text-anchor', 'middle')
      	.attr('opacity', 1)
      	.text((a) => a.nilai)


    function mouseOver(actual, i){
  		/*d3.selectAll('.value_bar')
          .attr('opacity', 0)*/

       /* d3.select(this)
          .transition()
          .duration(300)
          .attr('opacity', 0.6)
          .attr('x', (a) => xScale(a.oleh) - 5)
          .attr('width', xScale.bandwidth() + 10)
*/
       /* var y = yScale(actual.nilai)

        line = chart_bar.append('line')
          .attr('id', 'limit')
          .attr('x1', 0)
          .attr('y1', y)
          .attr('x2', bar_width)
          .attr('y2', y)

        barGroups.append('text')
          .attr('class', 'divergence')
          .attr('x', (a) => xScale(a.oleh) + xScale.bandwidth() / 2)
          .attr('y', (a) => yScale(a.nilai) + 30)
          .attr('fill', 'white')
          .attr('text-anchor', 'middle')
          .text((a, idx) => {
            var divergence = (a.nilai - actual.nilai).toFixed(1)
            
            var text = ''
            if (divergence > 0) text += '+'
            	text += ' '+divergence+' '
            text = a.nilai;
            return idx !== i ? text : '';
          })*/
    }

    function mouseLeave () {
    	/*d3.selectAll('.value_bar')
          .attr('opacity', 1)

        d3.select(this)
          .transition()
          .duration(300)
          .attr('opacity', 1)
          .attr('x', (a) => xScale(a.oleh))
          .attr('width', xScale.bandwidth())

        chart.selectAll('#limit').remove()
        chart.selectAll('.divergence').remove()*/
    }
</script>
