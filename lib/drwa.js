var $_GET = (function(){
    var url = window.document.location.href.toString();
    var u = url.split("?");
    if(typeof(u[1]) == "string"){
        u = u[1].split("&");
        var get = {};
        for(var i in u){
            var j = u[i].split("=");
            get[j[0]] = j[1];
        }
        return get;
    } else {
        return {};
    }
})();
var tissue =new Array();
var ex = new Array();
var $pid=$('#ProjectDropdown');
val=$pid.children('option:selected').val();
dJax = $.ajax({
		url : base_url('lib/draw_drug_gene_chart.php', false, false, false, {gene:$_GET['gene'],project : val}),
		dataType : 'json'
	})
	.done(function(data, textStatus, jqXHR) {
		_.each(data, function(name, id) {
			tissue.push(id);
			ex.push(name);
		});
		draws('#gene_exp_draw',tissue,ex);
	});

$pid.on('change', function(event) {

        if (dJax !== null) {
            dJax.abort();
        }

        var val = $(this).val();
		tissue=Array();
		ex=Array();
        if (val !== '') {
			val=$pid.children('option:selected').val();
			dJax = $.ajax({
				url : base_url('lib/draw_drug_gene_chart.php', false, false, false, {gene:$_GET['gene'],project:val}),
				dataType : 'json'
			})
			.done(function(data, textStatus, jqXHR) {
				_.each(data, function(name, id) {
					tissue.push(id);
					ex.push(name);
				});
				$('#gene_exp_draw').empty();
				draws('#gene_exp_draw',tissue,ex);
			});
        }
    });
	
function draws(id,tissue,ex){ 
var width = 450,
	height = 300,
	radius = Math.min(width, height) / 2;

var svg = d3.select(id)
	.append("svg")
	.attr("width", width)
    .attr("height", height)
	.append("g")
	.attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");
	
svg.append("g")
	.attr("class", "slices");
svg.append("g")
	.attr("class", "labels");
svg.append("g")
	.attr("class", "lines");



var pie = d3.layout.pie()
	.sort(null)
	.value(function(d) {
		return d.value;
	});

var arc = d3.svg.arc()
	.outerRadius(radius * 0.8)
	.innerRadius(radius * 0.4);

var outerArc = d3.svg.arc()
	.innerRadius(radius * 0.9)
	.outerRadius(radius * 0.9);




var key = function(d){ return d.data.label; };

var color = d3.scale.ordinal()
	.domain(tissue)
	.range(["#9E0041","#C32F4B","#E1514B","#F47245","#FB9F59","#FEC574","#FAE38C","#EAF195","#C7E89E","#9CD6A4","#6CC4A4","#4D9DB4","#4776B4","#5E4EA1"]);

function randomData (){
	n=-1;
	var labels = color.domain();
	return labels.map(function(label){
		n++;
		return { label: label, value: ex[n] };
	});
}

change(randomData ());

d3.select(".randomize")
	.on("click", function(){
		change(randomData ());
	});


function change(data) {

	/* ------- PIE SLICES -------*/
	var slice = svg.select(".slices").selectAll("path.slice")
		.data(pie(data), key);

	slice.enter()
		.insert("path")
		.style("fill", function(d) { return color(d.data.label); })
		.attr("class", "slice");

	slice		
		.transition().duration(1000)
		.attrTween("d", function(d) {
			this._current = this._current || d;
			var interpolate = d3.interpolate(this._current, d);
			this._current = interpolate(0);
			return function(t) {
				return arc(interpolate(t));
			};
		})

	slice.exit()
		.remove();

	/* ------- TEXT LABELS -------*/

	var text = svg.select(".labels").selectAll("text")
		.data(pie(data), key);

	text.enter()
		.append("text")
		.attr("dy", ".35em")
		.text(function(d) {
			return d.data.label;
		});
	
	function midAngle(d){
		return d.startAngle + (d.endAngle - d.startAngle)/2;
	}

	text.transition().duration(1000)
		.attrTween("transform", function(d) {
			this._current = this._current || d;
			var interpolate = d3.interpolate(this._current, d);
			this._current = interpolate(0);
			return function(t) {
				var d2 = interpolate(t);
				var pos = outerArc.centroid(d2);
				pos[0] = radius * (midAngle(d2) < Math.PI ? 1 : -1);
				return "translate("+ pos +")";
			};
		})
		.styleTween("text-anchor", function(d){
			this._current = this._current || d;
			var interpolate = d3.interpolate(this._current, d);
			this._current = interpolate(0);
			return function(t) {
				var d2 = interpolate(t);
				return midAngle(d2) < Math.PI ? "start":"end";
			};
		});

	text.exit()
		.remove();

	/* ------- SLICE TO TEXT POLYLINES -------*/

	var polyline = svg.select(".lines").selectAll("polyline")
		.data(pie(data), key);
	
	polyline.enter()
		.append("polyline");

	polyline.transition().duration(1000)
		.attrTween("points", function(d){
			this._current = this._current || d;
			var interpolate = d3.interpolate(this._current, d);
			this._current = interpolate(0);
			return function(t) {
				var d2 = interpolate(t);
				var pos = outerArc.centroid(d2);
				pos[0] = radius * 0.95 * (midAngle(d2) < Math.PI ? 1 : -1);
				return [arc.centroid(d2), outerArc.centroid(d2), pos];
			};			
		});
	
	polyline.exit()
		.remove();
};}