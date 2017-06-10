<?php
	$pageTitle = "Dashboard";
?>
<!DOCTYPE html>
<html>
<?php
	include_once("head.php");
?>
	<body>
		<div class="container-fluid">
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
			                <div class="col-xs-12 col-sm-6 border-right">
			                	<h2>Spending By Category</h2>
								<div id="donutchart"></div>
			                </div>
			                <div class="col-xs-12 col-sm-6">
			                	<div class="row">
			               			<div class="col-xs-12">
					                	<h2>Budgets</h2>
										<div class="progress">
											<div class="progress-bar" role="progressbar" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100" style="width: 15%;">
												15%
											</div>
										</div>
									</div>
								</div>
			                	<div class="row">
			               			<div class="col-xs-12 border-top">
					                	<h2>Accounts</h2>
					                	<div class="accountsList">
					                		<div class="account-group">
					                			<span class="accuont-title">Credit</span>
					                			<span class="accuont-amount">10,000</span>
					                		</div>
					                		<div class="account-group">
					                			<span class="accuont-title">Savings</span>
					                			<span class="accuont-amount">10,000</span>
					                		</div>
					                		<div class="account-group">
					                			<span class="accuont-title">Loan</span>
					                			<span class="accuont-amount">10,000</span>
					                		</div>
					                	</div>
									</div>
								</div>
			                </div>
			            </div>
			            <div class="row">
			                <div class="col-xs-12 col-sm-6 border-right border-top">
			                	<h2>Performance</h2>
								<div id="chart_div"></div>
			                </div>
			                <div class="col-xs-12 col-sm-6">
			                	<div class="row">
					                <div class="col-xs-12 border-top">
					                	<h2>Activities</h2>
					                	<div class="activities-alerts">
						                	<div class="alert alert-info" role="alert">
												<strong><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Warning!</strong> 7 Accounts need your attention
											</div>
						                	<div class="alert alert-warning" role="alert">
												<strong><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Warning!</strong> In the past 30 days you spent 123123. Usually you spend 100
											</div>
										</div>
					                </div>
								</div>
			                	<div class="row">
					                <div class="col-xs-12 border-top">
					                	<h2>Top Spending Categories</h2>
					                	<div class="spendings-block"><i class="fa fa-suitcase" aria-hidden="true"></i></div>
					                	<div class="spendings-block"><i class="fa fa-car" aria-hidden="true"></i></div>
					                	<div class="spendings-block"><i class="fa fa-bolt" aria-hidden="true"></i></div>
					                	<div class="spendings-block"><i class="fa fa-cutlery" aria-hidden="true"></i></div>
					                	<div class="spendings-block"><i class="fa fa-shopping-bag" aria-hidden="true"></i></div>
					                </div>
								</div>
			                </div>
		                </div>
		            </div>
		        </div>
		    </div>
		    <!-- /#page-content-wrapper -->
	    </div>
	    <script type="text/javascript">
	    	/* Donut chart*/
			google.charts.load("current", {packages:["corechart"]});
			google.charts.setOnLoadCallback(drawDonutChart);
			function drawDonutChart() {
				var data = google.visualization.arrayToDataTable([
					['Spending Categories', 'Amount'],
					['Shopping',     11],
					['Travel',      2],
					['Commute',  2],
					['Bills', 2],
					['Other',    7]
				]);
				var options = {
					chartArea:{left:0,top:10,width:'100%',height:'100%'},
					fontSize: 11,
					width: 500,
					height: 300,
					//title: 'Spending Categories',
					pieHole: 0.6,
					colors:['#c00','blue','green','orange','magenta']
				};
				var chart = new google.visualization.PieChart(document.getElementById('donutchart'));

				// The select handler. Call the chart's getSelection() method
				function selectHandler() {
					var selectedItem = chart.getSelection()[0];
					if (selectedItem) {
						console.log(selectedItem);
						var value = data.getValue(selectedItem.row, 0);
						console.log('The user selected ' + value);
					}
				}
  				google.visualization.events.addListener(chart, 'select', selectHandler);

				chart.draw(data, options);
			}
			/* bar chart */
			google.charts.load('current', {'packages':['bar']});
			google.charts.setOnLoadCallback(drawBarChart);

			function drawBarChart() {
				var data = google.visualization.arrayToDataTable([
					['Month', 'Income', 'Expenses', 'Profit'],
					['Jan', 1000, 400, 200],
					['Feb', 1000, 400, 200],
					['Mar', 1000, 400, 200],
					['Apr', 1000, 400, 200],
					['May', 1000, 400, 200]
				]);

				var options = {
					/*chart: {
						title: 'Company Performance',
						subtitle: 'Income, Expenses, and Profit: 2014-2017',
					},*/
					bars: 'vertical', // Required for Material Bar Charts.
					hAxis: {format: 'decimal'},
					height: 300,
					colors: ['#1b9e77', '#d95f02', '#7570b3']
				};

				var chart = new google.charts.Bar(document.getElementById('chart_div'));
				chart.draw(data, options);
			}
		</script>
	</body>
</html>