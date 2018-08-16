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
		url : base_url('lib/drug_gene_network_json.php', false, false, false, {drug:$_GET['drug']}),
		dataType : 'json'
	})
	.done(function(data, textStatus, jqXHR) {
		drawnetd('#dg_network_draw',data);
	});
	
function drawnetd(id,graph){
  var width = 443,
	  height = 300;
  
  var svg = d4.select(id).append("svg")
	  .attr("width", width)
	  .attr("height", height);
  
  var color = d4.scaleOrdinal(d4.schemeCategory20);

  var simulation = d4.forceSimulation()
	  .force("link", d4.forceLink().id(function(d) { return d.id; }))
	  .force("charge", d4.forceManyBody())
	  .force("center", d4.forceCenter(width / 2, height / 2));

  var link = svg.append("g")
      .attr("class", "links")
	  .selectAll("line")
	  .data(graph.links)
	  .enter().append("line")
      .attr("stroke-width", function(d) { return Math.sqrt(d.value); });

  var node = svg.append("g")
      .attr("class", "nodes")
	  .selectAll("circle")
	  .data(graph.nodes)
	  .enter().append("circle")
      .attr("r", 5)
      .attr("fill", function(d) { return color(d.group); })
      .call(d4.drag()
          .on("start", dragstarted)
          .on("drag", dragged)
          .on("end", dragended));

  node.append("title")
      .text(function(d) { return d.id; });

  simulation
      .nodes(graph.nodes)
      .on("tick", ticked);

  simulation.force("link")
      .links(graph.links);

  function ticked() {
    link
        .attr("x1", function(d) { return d.source.x; })
        .attr("y1", function(d) { return d.source.y; })
        .attr("x2", function(d) { return d.target.x; })
        .attr("y2", function(d) { return d.target.y; });

    node
        .attr("cx", function(d) { return d.x; })
        .attr("cy", function(d) { return d.y; });
		
  }
  function dragstarted(d) {
	if (!d4.event.active) simulation.alphaTarget(0.3).restart();
	d.fx = d.x;
	d.fy = d.y;
  }
  
  function dragged(d) {
	d.fx = d4.event.x;
	d.fy = d4.event.y;
  }
  
  function dragended(d) {
	if (!d4.event.active) simulation.alphaTarget(0);
	d.fx = null;
	d.fy = null;
  }
}





