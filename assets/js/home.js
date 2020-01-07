var size_bar = $("#chart-nilai-tertinggi").width()/2;//150,
    thickness_bar = 60;
    margin = 10;
    bar_width = (size_bar * 2) - 2 * margin;
    bar_height = (size_bar + 2) - 1 * margin;

  var svg_bar = d3.select('#chart-nilai-tertinggi').append('svg')
      .attr('width', size_bar * 2)
      .attr('height', size_bar + 20)
      .attr('class', 'bar');

    var chart_bar = svg_bar.append('g')
      .attr('transform', 'translate(' + (margin+25) + ',' + margin + ')')

    var xScale = d3.scaleBand()
        .range([0, bar_width])
        .domain(data_tertinggi.map((s) => s.nama))
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
      .data(data_tertinggi)
        .enter()
        .append('g')

    barGroups
        .append('rect')
        .attr('class', 'bar')
        .attr('x', function(g) { return xScale(g.nama)})
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
        .attr("fill", function(g) {
          //['#db4639', '#FFCD42', '#48ba17', '#12ab24', '#0f9f59']
          var v = g.nilai;
          console.log(v);
          if(v>=682){
            return "#0f9f59";
          }else if(v>=525){
            return "#12ab24";
          }else if(v>=366){
            return "#48ba17";
          }else if(v>=201){
            return "#FFCD42";
          }else{
            return "#db4639";
          }  
        })

    barGroups 
        .append('text')
        .attr('class', 'value_bar')
        .attr('x', (a) => xScale(a.nama) + xScale.bandwidth() / 2)
        .attr('y', (a) => yScale(a.nilai) + 30)
        .attr('fill', 'white')
        .attr('text-anchor', 'middle')
        .attr('opacity', 1)
        .text((a) => a.nilai)


    function mouseOver(actual, i){
      /*d3.selectAll('.value_bar')
          .attr('opacity', 0)*/
          console.log("asd");
        /*d3.select(this)
          .transition()
          .duration(300)
          .attr('opacity', 0.6)
          .attr('x', (a) => xScale(a.nama) - 5)
          .attr('width', xScale.bandwidth() + 10)

        var y = yScale(actual.nilai)

        line = chart_bar.append('line')
          .attr('id', 'limit')
          .attr('x1', 0)
          .attr('y1', y)
          .attr('x2', bar_width)
          .attr('y2', y)

        barGroups.append('text')
          .attr('class', 'divergence')
          .attr('x', (a) => xScale(a.nama) + xScale.bandwidth() / 2)
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
*/
      /*  d3.select(this)
          .transition()
          .duration(300)
          .attr('opacity', 1)
          .attr('x', (a) => xScale(a.nama))
          .attr('width', xScale.bandwidth())

        chart.selectAll('#limit').remove()
        chart.selectAll('.divergence').remove()*/
    }


/*======================================================================================================================*/
/*======================================================================================================================*/
/*======================================================================================================================*/
/*=======================================TERENDAH=======================================================================*/
/*======================================================================================================================*/
/*======================================================================================================================*/
/*======================================================================================================================*/


var size_bar = $("#chart-nilai-terendah").width()/2;//150,
    thickness_bar = 60;
    margin = 10;
    bar_width = (size_bar * 2) - 2 * margin;
    bar_height = (size_bar + 2) - 1 * margin;

  var svg_bar = d3.select('#chart-nilai-terendah').append('svg')
      .attr('width', size_bar * 2)
      .attr('height', size_bar + 20)
      .attr('class', 'bar');

    var chart_bar = svg_bar.append('g')
      .attr('transform', 'translate(' + (margin+25) + ',' + margin + ')')

    var xScale = d3.scaleBand()
        .range([0, bar_width])
        .domain(data_terendah.map((s) => s.nama))
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
      .data(data_terendah)
        .enter()
        .append('g')

    barGroups
        .append('rect')
        .attr('class', 'bar')
        .attr('x', function(g) { return xScale(g.nama)})
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
        .attr("fill", function(g) {
          //['#db4639', '#FFCD42', '#48ba17', '#12ab24', '#0f9f59']
          var v = g.nilai;
          if(v>=682){
            return "#0f9f59";
          }else if(v>=525){
            return "#12ab24";
          }else if(v>=366){
            return "#48ba17";
          }else if(v>=201){
            return "#FFCD42";
          }else{
            return "#db4639";
          }  
        })
    barGroups 
        .append('text')
        .attr('class', 'value_bar')
        .attr('x', (a) => xScale(a.nama) + xScale.bandwidth() / 2)
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
          .attr('x', (a) => xScale(a.nama) - 5)
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
          .attr('x', (a) => xScale(a.nama) + xScale.bandwidth() / 2)
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
          .attr('x', (a) => xScale(a.nama))
          .attr('width', xScale.bandwidth())

        chart.selectAll('#limit').remove()
        chart.selectAll('.divergence').remove()*/
    }


