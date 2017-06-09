<?php
	$pageTitle = "Dashboard";
?>
<!DOCTYPE html>
<html>
<?php
	include_once("head.php");
?>
	<body>
		<div id="wrapper">
			<?php
				include_once("header.php");
			?>
		    <!-- Page Content -->
		    <div id="page-content-wrapper">
		        <button type="button" class="hamburger is-closed" data-toggle="offcanvas">
		            <span class="hamb-top"></span>
					<span class="hamb-middle"></span>
					<span class="hamb-bottom"></span>
		        </button>
		        <div class="container">
		            <div class="row">
		                <div class="col-lg-6">
							<div id="donutchart"></div>
		                </div>
		                <div class="col-lg-6">
		                </div>
		            </div>
		            <div class="row">
		                <div class="col-lg-6">
							<div id="dual_x_div"></div>
		                </div>
		                <div class="col-lg-6">
		                </div>
		            </div>
		        </div>
		    </div>
		    <!-- /#page-content-wrapper -->
	    </div>
	    <script type="text/javascript">
	    	/* doughnut chart*/
			google.charts.load("current", {packages:["corechart"]});
			google.charts.setOnLoadCallback(drawChart);
			function drawChart() {
				var data = google.visualization.arrayToDataTable([
					['Task', 'Hours per Day'],
					['Work',     11],
					['Eat',      2],
					['Commute',  2],
					['Watch TV', 2],
					['Sleep',    7]
				]);
				var options = {
					title: 'My Daily Activities',
					pieHole: 0.4,
				};
				var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
				chart.draw(data, options);
			}
			/* bar chart */
			google.charts.load('current', {'packages':['bar']});
			google.charts.setOnLoadCallback(drawStuff);

			function drawStuff() {
				var data = new google.visualization.arrayToDataTable([
					['Galaxy', 'Distance', 'Brightness'],
					['Canis Major Dwarf', 8000, 23.3],
					['Sagittarius Dwarf', 24000, 4.5],
					['Ursa Major II Dwarf', 30000, 14.3],
					['Lg. Magellanic Cloud', 50000, 0.9],
					['Bootes I', 60000, 13.1]
				]);

				var options = {
					width: 600,
					chart: {
						title: 'Nearby galaxies',
						subtitle: 'distance on the left, brightness on the right'
					},
					bars: 'vertical', // Required for Material Bar Charts.
					series: {
						0: { axis: 'distance' }, // Bind series 0 to an axis named 'distance'.
						1: { axis: 'brightness' } // Bind series 1 to an axis named 'brightness'.
					},
					axes: {
						x: {
							distance: {label: 'parsecs'}, // Bottom x-axis.
							brightness: {side: 'top', label: 'apparent magnitude'} // Top x-axis.
						}
					}
				};

				var chart = new google.charts.Bar(document.getElementById('dual_x_div'));
				chart.draw(data, options);
			};
		</script>
	</body>
</html>