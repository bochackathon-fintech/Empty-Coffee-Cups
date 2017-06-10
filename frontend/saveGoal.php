<div class="add-new-goal-title">
	<h2>Set A New Goal</h2>
</div>
<form id="setgoals-form" class="setgoals-form" action="manager/ajaxForms.php" method="POST">
	<!-- action for form! -->
	<input type="hidden" id="action" name="action" value="setgoal">

	<input type="hidden" id="customer_id" name="customer_id" value="304fd2e19f1c14fe3345cca788e4e83d">
	<input type="hidden" id="priority" name="priority" value="0">
	<input type="hidden" id="saved" name="saved" value="1">
	<input type="hidden" id="accountid" name="accountid" value="abc123">

	<div class="form-group">
		<input type="text" class="form-control" id="goal_name" name="goal_name" placeholder="Goal Name">
	</div>
	<div class="form-group">
		<input type="text" class="form-control" id="goal_value" name="goal_value" placeholder="Goal Value">
	</div>
	<div class="form-group">
		<input type="text" class="form-control" id="goal_date" name="goal_date" placeholder="Goal Date">
	</div>
	<button type="submit" class="btn btn-primary signin-but">Create Goal</button>
</form>