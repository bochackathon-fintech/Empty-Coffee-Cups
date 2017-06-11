<?php

function pageTitle() {
	global $title;
	return $title;
}

function getBaseUrl() {
	return "localhost/hackathon/";
}

/* Mixalis API  Functions */
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
function deleteGoal($custId,$goal_name) {
	$goal_details = array(
	        'custId' => $custId,
	        'goal_name' => $goal_name
	    );
	$goal_details_json = json_encode($goal_details);
	// Get cURL resource
	$curl = curl_init();
	curl_setopt_array($curl, array(
	    CURLOPT_RETURNTRANSFER => 1,
	    CURLOPT_CUSTOMREQUEST => 'DELETE',
	    CURLOPT_URL => "https://hackathon-be.mybluemix.net/customer/$custId/goals?name=$goal_name",
	    CURLOPT_USERAGENT => 'BankBase BOC Hackathon Request',
	    CURLOPT_HTTPHEADER, array("Content-Type: text/plain; charset=utf-8","Accept:application/json"),
	    CURLOPT_POST => 1,
	    CURLOPT_POSTFIELDS => array('goal'=> $goal_details_json)
	));
	// Send the request & save response to $resp
	$resp = curl_exec($curl);
	// Close request to clear up some resources
	curl_close($curl);
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
		if($percentage > 90) {
			$progress_bar_color = "progress-bar-success";
		}
		//echo "$goal->value $goal->saved $percentage%<br>";
		$goalProgres[] = '<div class="goal-group-details border-bottom" data-sortp="'.$goal->priority.'" data-sortstats="'.$percentage.'">
							<div class="deleteGoal">
								<i class="fa fa-trash" aria-hidden="true"></i>
								<input type="hidden" class="goal_name" value="'.$goal->name.'" />
							</div>
							<div class="span-dets-wrapper">
								<span class="goal-name "></span><span class="span-inner-val">'.$goal->name.':</span>
								<span class="goal-name span-inner-title">Progress:</span><span class="span-inner-val">€'.number_format($goal->saved, 2, '.', ',').',</span>
								<span class="goal-setdate span-inner-title">Target Date:</span><span class="span-inner-val">'.$goal->date.'</span>
							</div>
							<div class="goal-amount span-inner-title">Required Amount:<span class="span-inner-val">€'.$goal->value.'</div>
							<div class="goal-completion-percent">'.number_format($percentage, 2, '.', ',').'% Complete</div>
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

/*BOC API Functions */
//Todo:
function getTransactions() {
	// Get cURL resource
	$curl = curl_init();
	// Set some options - we are passing in a useragent too here
	curl_setopt_array($curl, array(
	    CURLOPT_RETURNTRANSFER => 1,
	    CURLOPT_URL => "https://hackathon-be.mybluemix.net/customer/304fd2e19f1c14fe3345cca788e4e83d/amexprofitability",
	    CURLOPT_USERAGENT => 'BankBase BOC Hackathon Request'
	));
	// Send the request & save response to $resp
	$resp = curl_exec($curl);
	// Close request to clear up some resources
	curl_close($curl);

	$resp_decode = json_decode($resp);
	return $resp_decode;
}
//Todo:
function getAccounts() {
	//accId  bda8eb884efcea209b2a6240
	//viewId 5710bba5d42604e4072d1e92

	// Get cURL resource
	$curl = curl_init();
	// Set some options - we are passing in a useragent too here
	curl_setopt_array($curl, array(
	    CURLOPT_RETURNTRANSFER => 1,
	    CURLOPT_URL => "http://api.bocapi.net/v1/api/banks/bda8eb884efcef7082792d45/accounts?subscription-key=f1817e51b3fb4d2ca3fc279d0df3a061",
	    // CURLOPT_URL => "192.168.88.202:8080/customer/$custId/goals",
	    CURLOPT_USERAGENT => 'BankBase BOC Hackathon Request'
	));
	// Send the request & save response to $resp
	$resp = curl_exec($curl);
	// Close request to clear up some resources
	curl_close($curl);

	$resp_decode = json_decode($resp);
	return $resp_decode;
	
}
//Todo:
function getBanks() {
	//bankId bda8eb884efcef7082792d45
	// Get cURL resource
	$curl = curl_init();
	// Set some options - we are passing in a useragent too here
	curl_setopt_array($curl, array(
	    CURLOPT_RETURNTRANSFER => 1,
	    CURLOPT_URL => "http://api.bocapi.net/v1/api/banks/bda8eb884efcef7082792d45/customer?subscription-key=f1817e51b3fb4d2ca3fc279d0df3a061",
	    // CURLOPT_URL => "192.168.88.202:8080/customer/$custId/goals",
	    CURLOPT_USERAGENT => 'BankBase BOC Hackathon Request'
	));
	// Send the request & save response to $resp
	$resp = curl_exec($curl);
	// Close request to clear up some resources
	curl_close($curl);

	$resp_decode = json_decode($resp);
	return $resp_decode;
}
//Todo:
function getPrivateAccounts() {
	//bankId bda8eb884efcef7082792d45
	// Get cURL resource
	$curl = curl_init();
	// Set some options - we are passing in a useragent too here
	curl_setopt_array($curl, array(
		CURLOPT_HTTPHEADER => array(
							    'Auth-Provider-Name: 01460900080600',
							    'Auth-ID: 123456789',
							    'Ocp-Apim-Subscription-Key: f1817e51b3fb4d2ca3fc279d0df3a061'
							    ),
	    CURLOPT_RETURNTRANSFER => 1,
	    CURLOPT_URL => "http://api.bocapi.net/v1/api/banks/bda8eb884efcef7082792d45/accounts/private?subscription-key=f1817e51b3fb4d2ca3fc279d0df3a061",
	    // CURLOPT_URL => "192.168.88.202:8080/customer/$custId/goals",
	    CURLOPT_USERAGENT => 'BankBase BOC Hackathon Request'
	));
	// Send the request & save response to $resp
	$resp = curl_exec($curl);
	// Close request to clear up some resources
	curl_close($curl);

	$resp_decode = json_decode($resp);
	return $resp_decode;
}
//Todo:
function getBOCCustomer() {
	return 'Alex';
	// Get cURL resource
	$curl = curl_init();
	// Set some options - we are passing in a useragent too here
	curl_setopt_array($curl, array(
		CURLOPT_HTTPHEADER => array(
							    'Auth-Provider-Name: 01460900080600',
							    'Auth-ID: 123456789',
							    'Ocp-Apim-Subscription-Key: f1817e51b3fb4d2ca3fc279d0df3a061'
							    ),
	    CURLOPT_RETURNTRANSFER => 1,
	    CURLOPT_URL => "http://api.bocapi.net/v1/api/banks/bda8eb884efcef7082792d45/customers/",
	    // CURLOPT_URL => "192.168.88.202:8080/customer/$custId/goals",
	    CURLOPT_USERAGENT => 'BankBase BOC Hackathon Request'
	));
	// Send the request & save response to $resp
	$resp = curl_exec($curl);
	// Close request to clear up some resources
	curl_close($curl);

	$resp_decode = json_decode($resp);
	return $resp_decode;
}

//Primary key f1817e51b3fb4d2ca3fc279d0df3a061
//Auth-Provider-Name
//01460900080600

function getBOCAccount($accountid = 'a746637b91b19a261a67d8bd') {
	//bankid bda8eb884efcef7082792d45
	//accountid a746637b91b19a261a67d8bd
	//viewid 5710bba5d42604e4072d1e92
	//
	// Get cURL resource
	$curl = curl_init();
	// Set some options - we are passing in a useragent too here
	curl_setopt_array($curl, array(
		CURLOPT_HTTPHEADER => array(
							    'Auth-Provider-Name: 01460900080600',
							    'Auth-ID: 123456789',
							    'Ocp-Apim-Subscription-Key: f1817e51b3fb4d2ca3fc279d0df3a061'
							    ),
	    CURLOPT_RETURNTRANSFER => 1,
	    CURLOPT_URL => "http://api.bocapi.net/v1/api/banks/bda8eb884efcef7082792d45/accounts/$accountid/5710bba5d42604e4072d1e92/account",
	    // CURLOPT_URL => "192.168.88.202:8080/customer/$custId/goals",
	    CURLOPT_USERAGENT => 'BankBase BOC Hackathon Request'
	));
	// Send the request & save response to $resp
	$resp = curl_exec($curl);
	// Close request to clear up some resources
	curl_close($curl);

	$resp_decode = json_decode($resp);
	return $resp_decode;
}