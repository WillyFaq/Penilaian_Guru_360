var size = $("#chart-nilai-akhir").width()/2;//150,
    thickness = 60;

console.log(size);
var color = d3.scale.linear()
    //.domain([0, 50, 100])
    //.range(['#db2828', '#fbbd08', '#21ba45']);
    .domain([0, 450, 600, 740, 840])
    .range(['#db4639', '#db7f29', '#48ba17', '#12ab24', '#0f9f59']);

var arc = d3.svg.arc()
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
update_gauge(500);

function update_gauge(v) {
    v = d3.format('.1f')(v);
    console.log("update", v);
    foreground.transition()
        .duration(750)
        .style('fill', function() {
            return color(v);
        })
        .call(arcTween, v);

    value.transition()
        .duration(750)
        .call(textTween, v);
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
    transition.tween('text', function() {
        var interpolate = d3.interpolate(this.innerHTML, v),
            split = (v + '').split('.'),
            round = (split.length > 1) ? Math.pow(10, split[1].length) : 1;
        return function(t) {
            this.innerHTML = d3.format('.1f')(Math.round(interpolate(t) * round) / round) + '<tspan></tspan>';
        };
    });
}