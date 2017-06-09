<?php
	$pageTitle = "BankBase";
?>
<!DOCTYPE html>
<html>
<?php
	include_once("head.php");
?>
	<body>
		<div class="row">
			<div class="col-xs-12 text-center appTitle">
				<h2>bankbase</h2>
			</div>
			<div class="col-xs-12 text-center signin-pageTitle">
				<h3>Sign In</h3>
			</div>
			<div class="col-xs-4 col-xs-offset-4">
				<?php
					include_once("loginForm.php");
				?>
			</div>
		</div>
	</body>
</html>