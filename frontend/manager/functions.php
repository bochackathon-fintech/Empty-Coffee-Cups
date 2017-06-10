<?php

function pageTitle() {
	global $title;
	return $title;
}

function getBaseUrl() {
	return "localhost/hackathon/";
}

/* customers functions */
function getAllCustomers() {
	// Get cURL resource
	$curl = curl_init();
	// Set some options - we are passing in a useragent too here
	curl_setopt_array($curl, array(
	    CURLOPT_RETURNTRANSFER => 1,
	    CURLOPT_URL => 'https://hackathon-be.mybluemix.net/customers',
	    CURLOPT_USERAGENT => 'BankBase BOC Hackathon Request'
	));
	// Send the request & save response to $resp
	$resp = curl_exec($curl);
	// Close request to clear up some resources
	curl_close($curl);

	$resp_decode = json_decode($resp);
	echo "<pre>";
	print_r($resp_decode);
	echo "</pre>";
}
function getCustomer($custId) {
	// Get cURL resource
	$curl = curl_init();
	// Set some options - we are passing in a useragent too here
	curl_setopt_array($curl, array(
	    CURLOPT_RETURNTRANSFER => 1,
	    CURLOPT_URL => "https://hackathon-be.mybluemix.net/customer/$custId",
	    CURLOPT_USERAGENT => 'BankBase BOC Hackathon Request'
	));
	// Send the request & save response to $resp
	$resp = curl_exec($curl);
	// Close request to clear up some resources
	curl_close($curl);

	$resp_decode = json_decode($resp);
	return $resp_decode;

	/* an example of usage */
	// $customer = getCustomer('304fd2e19f1c14fe3345cca788e4e83d');
	// //var_dump($customer);
	// $customerGoals = $customer[0]->goals;
	// foreach ($customerGoals as $key => $goal) {
	// 	echo "$goal->value $goal->name $goal->date $goal->accountid";
	// 	echo "<br>";
	// }

}
/* Todo */
function setCustomer() {
	
}
/* Todo */
function deleteCustomer() {

}

/* goals functions */
function getGoals($custId) {
	// Get cURL resource
	$curl = curl_init();
	// Set some options - we are passing in a useragent too here
	curl_setopt_array($curl, array(
	    CURLOPT_RETURNTRANSFER => 1,
	    CURLOPT_URL => "https://hackathon-be.mybluemix.net/customer/$custId/goals",
	    // CURLOPT_URL => "192.168.88.202:8080/customer/$custId/goals",
	    CURLOPT_USERAGENT => 'BankBase BOC Hackathon Request'
	));
	// Send the request & save response to $resp
	$resp = curl_exec($curl);
	// Close request to clear up some resources
	curl_close($curl);

	$resp_decode = json_decode($resp);
	return $resp_decode;
	/* an example of usage */
	// $customerGoals = getGoals('304fd2e19f1c14fe3345cca788e4e83d');
	// echo "<pre>";
	// print_r($customerGoals);
	// echo "</pre>";
}
function setGoals($custId,  $name, $value, $saved, $gdate, $accountid, $priority) {
	// {"name":"Auto","value":"12500","saved":"1","date":"16-08-2018","accountid":"abc123","priority":"0"}
	// { "value": 0, "saved": 0, "name": "string", "date": "string", "priority": 0, "accountid": "string" }
	// {"value":"12500","saved":"1","name":"Auto","date":"16-08-2018","priority":"0","accountid":"abc123"}
	$goal_details = array(
	        'value' => $value,
	        'saved' => $saved,
	        'name' => $name,
	        'date' => $gdate,
	        'priority' => $priority,
	        'accountid' => $accountid
	    );
	$goal_details_json = json_encode($goal_details);
	// Get cURL resource
	$curl = curl_init();
	// Set some options - we are passing in a useragent too here
	//curl -X POST --header 'Content-Type: text/plain' --header 'Accept: application/json' 'https://hackathon-be.mybluemix.net/customer/304fd2e19f1c14fe3345cca788e4e83d/goals?name=AutoSwagger&value=12500&saved=3000&date=18-06-2018&accountid=abc123&priority=4'
	curl_setopt_array($curl, array(
	    CURLOPT_RETURNTRANSFER => 1,
	    CURLOPT_URL => "https://hackathon-be.mybluemix.net/customer/$custId/goals?name=$name&value=$value&saved=$saved&date=$gdate&accountid=$accountid&priority=$priority",
	    CURLOPT_USERAGENT => 'BankBase BOC Hackathon Request',
	    // CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($goal_details_json)),
	    CURLOPT_HTTPHEADER, array("Content-Type: text/plain; charset=utf-8","Accept:application/json"),
	    CURLOPT_POST => 1,
	    CURLOPT_POSTFIELDS => array('goal'=> $goal_details_json)
	));
	// Send the request & save response to $resp
	$resp = curl_exec($curl);
	// Close request to clear up some resources
	curl_close($curl);
}
/* Todo */
function deleteGoals() {

}
function listGoalsPlanning($custId) {
	$goals_list = '';

	$customerGoals = getGoals($custId)[0];
		//[0] => stdClass Object
        // (
        //     [value] => 600
        //     [saved] => 600
        //     [name] => Laptop
        //     [date] => 20-06-2017
        //     [priority] => 1
        //     [accountid] => abc123
        // )
	$goalProgres = array();
	foreach ($customerGoals as $key => $goal) {
		$tmpObj = (array) $goal;
		if(empty($tmpObj)) {
			$goals_list .= "<br>empty! $key<br>";
			continue;
		}
		//everything ok continue!
		$percentage = ($goal->saved/$goal->value) * 100;
		$progress_bar_color = "progress-bar-warning";
		if($percentage > 50) {
			$progress_bar_color = "progress-bar-success";
		}
		//echo "$goal->value $goal->saved $percentage%<br>";
		$goalProgres[] = '<div class="goal-group-details" data-sortp="'.$goal->priority.'">
							<span class="goal-name">Goal: '.$goal->name.'</span>
							<span class="goal-setdate">Target Date: '.$goal->date.'</span>
							<div class="goal-completion-percent">'.$percentage.'% Complete</div>
							<div class="progress">
								<div class="progress-bar '.$progress_bar_color.' progress-bar-striped" role="progressbar" aria-valuenow="'.$percentage.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$percentage.'%">
									<span class="sr-only">'.$percentage.'% Complete (success)</span>
								</div>
							</div>
						</div>';
	}
	$goals_list .= implode('', $goalProgres);
	return $goals_list;
}