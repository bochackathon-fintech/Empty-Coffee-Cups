<?php
	$pageTitle = "BankBase";
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
		                <?php
							$customer = getCustomer('304fd2e19f1c14fe3345cca788e4e83d');
							echo "<pre>";
							var_dump($customer);
							echo "</pre>";
		                ?>
		                </div>
		            </div>
		        </div>
		    </div>
		    <!-- /#page-content-wrapper -->
	    </div>
	</body>
</html>