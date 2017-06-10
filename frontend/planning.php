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
		                			Sorting stuff
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
	</body>
</html>