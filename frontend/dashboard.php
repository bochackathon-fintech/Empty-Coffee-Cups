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
											<?php
												//get goals percentage
												$customerGoals = getGoals('304fd2e19f1c14fe3345cca788e4e83d')[0];
												// print_r($customerGoals);
												$overallPercentage = 0;
												foreach ($customerGoals as $key => $goal) {
													$overallPercentage += ($goal->saved/$goal->value);
												}
												$overallPercentage = $overallPercentage * 100;
												$overallPercentage = number_format($overallPercentage, 2, '.', ',');
											?>
											<div class="progress-bar goal-progress-bar-dashboard" role="progressbar" aria-valuenow="<?php echo $overallPercentage; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $overallPercentage; ?>%;">
												<?php echo $overallPercentage . "%"; ?>
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
					                			<span class="accuont-amount">7,423</span>
					                		</div>
					                		<div class="account-group">
					                			<span class="accuont-title">Savings</span>
					                			<span class="accuont-amount">4,531</span>
					                		</div>
					                		<div class="account-group">
					                			<span class="accuont-title">Loan</span>
					                			<span class="accuont-amount">12,846</span>
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
												<strong><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Warning!</strong> 1 Account(s) need your attention <span class="expand-warnings-dropdown"><i class="fa fa-plus-square" aria-hidden="true"></i></span>

											</div>
											<div class="warnings-dropdown-wrapper amhidden">
							                	<div class="alert alert-warning" role="alert">
													<strong><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Warning!</strong> In the past 30 days you spent €864. Usually you spend around €500.
												</div>
											</div>
										</div>
					                </div>
								</div>
			                	<div class="row">
					                <div class="col-xs-12 border-top">
					                	<h2>Top Spending Categories</h2>
					                	<div class="spendings-block"><i class="fa fa-bolt" aria-hidden="true"></i> 4,147</div>
					                	<div class="spendings-block"><i class="fa fa-car" aria-hidden="true"></i> 3,889</div>
					                	<div class="spendings-block"><i class="fa fa-cutlery" aria-hidden="true"></i> 2,641</div>
					                	<div class="spendings-block"><i class="fa fa-suitcase" aria-hidden="true"></i> 2,345</div>
					                	<div class="spendings-block"><i class="fa fa-shopping-bag" aria-hidden="true"></i> 871</div>
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
					['Other',     11],
					['Travel',      4],
					['Bills',  8],
					['Household', 5],
					['Commute', 6],
					['Shopping',    3]
				]);
				var options = {
					backgroundColor: '#fafafa',
					chartArea:{left:0,top:10,width:'100%',height:'100%'},
					fontSize: 11,
					width: 500,
					height: 300,
					//title: 'Spending Categories',
					pieHole: 0.6,
					colors:['#c00','#20455a','#ccc','orange','#1b9e77', "green"]
				};
				var chart = new google.visualization.PieChart(document.getElementById('donutchart'));

				// The select handler. Call the chart's getSelection() method
				/*function selectHandler() {
					var selectedItem = chart.getSelection()[0];
					if (selectedItem) {
						console.log(selectedItem);
						var value = data.getValue(selectedItem.row, 0);
						console.log('The user selected ' + value);
					}
				}
  				google.visualization.events.addListener(chart, 'select', selectHandler);*/

				chart.draw(data, options);
			}
			/* bar chart */
			google.charts.load('current', {'packages':['bar']});
			google.charts.setOnLoadCallback(drawBarChart);

			function drawBarChart() {
				var data = google.visualization.arrayToDataTable([
					['Month', 'Income', 'Expenses', 'Savings'],
					['Jan', 1600, 1400, 100],
					['Feb', 1600, 900, 250],
					['Mar', 1600, 457, 350],
					['Apr', 1600, 665, 300],
					['May', 1600, 864, 280]
				]);

				var options = {
					/*chart: {
						title: 'Company Performance',
						subtitle: 'Income, Expenses, and Profit: 2014-2017',
					},*/
					backgroundColor: '#fafafa',
					bars: 'vertical', // Required for Material Bar Charts.
					hAxis: {format: 'decimal'},
					height: 300,
					colors: ['#1b9e77', '#d95f02', '#7570b3']
				};

				var chart = new google.charts.Bar(document.getElementById('chart_div'));
				chart.draw(data, options);
			}
		</script>
		<?php
			include_once("footer.php");
		?>
	</body>
</html>