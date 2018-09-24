<head>
  	<script>
 	window.onload = function () {

		var array = <?php echo json_encode($_SESSION["r_array"]); ?>;
		var title = <?php echo json_encode($_SESSION["title"]);?>;
		var type = <?php echo json_encode($_SESSION["type"]);?>;

		var element = [];

		for( var i in array) {

			element.push ({
				y: array[i],
				indexLabel: i 
			})

		}

   		var chart = new CanvasJS.Chart('chartContainer',
	    	{
	          	title: {
		        	text: title,
			},
			data: [ 
				{ type: type,

					dataPoints: element 
				}
			]
		});

		chart.render();
		}
	   </script>
</head>

<div id="chartContainer" style="height: <?php echo $_SESSION["graphicheight"];?>; width: 100%;"></div>
<script src="canvasjs/canvasjs.min.js"></script>
