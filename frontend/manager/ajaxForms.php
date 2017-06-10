<?php
	include_once("functions.php");
	
	$output = 'test';
	$action = $_POST['action'];		//what to do what to do..
	$custId = $_POST['customer_id'];

	switch ($action) {
	    case 'setgoal':
	    print_r($_POST);
	    	$output = 'Setting New Goal...';

	    	$name = $_POST['goal_name'];
	    	$value = $_POST['goal_value'];
	    	$saved = $_POST['saved'];
	    	$gdate = $_POST['goal_date'];
	    	$accountid = $_POST['accountid'];
	    	$priority = $_POST['priority'];
	    	setGoals($custId, $name, $value, $saved, $gdate, $accountid, $priority);
	        break;

	    case 'updateGoalsList':
	    	$output = 'Listing Goals...';
	    	$output = listGoalsPlanning($custId);
	        break;

	    case 'deleteGoal':
			$goal_name = $_POST['goal_name'];
	    	deleteGoal($custId,$goal_name);
	        break;

	    case 'setCustomer':
	        break;
	}

	echo $output;
?>