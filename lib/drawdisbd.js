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

dJax = $.ajax({
		url : base_url("lib/drug_tissue_cancer.php", false, false, false, {drug:$_GET['drug']}),
		dataType : 'json'
	})
	.done(function(data, textStatus, jqXHR) {
		
		drawd('#drug_tissue_cancer',data);
	});

function drawd(id,da){
var width = 443,
	  height = 300;
  
  var svg = d4.select(id).append("svg")
	  .attr("width", width)
	  .attr("height", height)
	  .attr("font-family", "Chalkboard")
	  .attr("font-size", "10")
	  .attr("text-anchor", "middle");

var format = d4.format(",d");

var color = d4.scaleOrdinal(d4.schemeCategory20c);

var pack = d4.pack()
    .size([width, height])
    .padding(1.5);
  
  var root = d4.hierarchy({children: da})
      .sum(function(d) { return d.value; })
      .each(function(d) {
        if (id = d.data.id) {
          var id, i = id.lastIndexOf(".");
          d.id = id;
          d.package = id.slice(0, i);
          d.class = id.slice(i + 1);
		  d.classes = d.class;
        }
      });

  var node = svg.selectAll(".node")
    .data(pack(root).leaves())
    .enter().append("g")
      .attr("class", "node")
      .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });

  node.append("circle")
      .attr("id", function(d) { return d.id; })
      .attr("r", function(d) { return d.r; })
      .style("fill", function(d) { return color(d.package); });

  node.append("clipPath")
      .attr("id", function(d) { return "clip-" + d.id; })
    .append("use")
      .attr("xlink:href", function(d) { return "#" + d.id; });

  node.append("text")
      .attr("clip-path", function(d) { return "url(#clip-" + d.id + ")"; })
    .selectAll("tspan")
    .data(function(d) { return d.package.split(); })
    .enter().append("tspan")
      .attr("x", 0)
      .attr("y", function(d, i, nodes) { return 13 + (i - nodes.length / 2 - 0.5) * 10; })
      .text(function(d) { return d; });

  node.append("title")
      .text(function(d) { return "tissue: " + d.package + "\ncancer: " + d.class.split() + "\nmodel num: " + format(d.value); });

}