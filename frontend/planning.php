<?php
	$pageTitle = "Planning";
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
		                <div class="col-xs-12">
		                	<div class="row">
		                		<div class="col-sm-6 add-new-goal">
		                			<?php
		                				include_once('saveGoal.php');
		                			?>
		                		</div>
		                		<div class="col-sm-6 sort-goals-funs">
		                			<div class="settigns">
										<div class="show-sort-settings"><h2><i class="fa fa-cogs" aria-hidden="true"></i></h2></div>
										<!-- Single button -->
										<div class="btn-group amhidden sortby-settings">
											<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												Sort By <span class="caret"></span>
											</button>
											<ul class="dropdown-menu">
												<li><a href="#" class="sortby priority" data-sortby="priority">Priority</a></li>
												<li><a href="#" class="sortby status" data-sortby="Status">Status</a></li>
												<li><a href="#" class="sortby ltoh" data-sortby="ltoh">Low to High</a></li>
												<li><a href="#" class="sortby htol" data-sortby="htol">High to Log</a></li>
											</ul>
										</div>
									</div>
		                		</div>
		                	</div>
	                		<div class="customer-goals-list border-top">
	                			<?php
	                				echo listGoalsPlanning('304fd2e19f1c14fe3345cca788e4e83d');
								?>
	                		</div>
		                </div>
		            </div>
		        </div>
		    </div>
		    <!-- /#page-content-wrapper -->
	    </div>
		<?php
			include_once("footer.php");
		?>
	</body>
</html>