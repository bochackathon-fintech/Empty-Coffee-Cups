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
	    CURLOPT_USERAGENT => 'Codular Sample cURL Request'
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
	    CURLOPT_USERAGENT => 'Codular Sample cURL Request'
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
function setCustomer() {
	
}
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
	    CURLOPT_USERAGENT => 'Codular Sample cURL Request'
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
function setGoals($custId) {
	// Get cURL resource
	$curl = curl_init();
	// Set some options - we are passing in a useragent too here
	curl_setopt_array($curl, array(
	    CURLOPT_RETURNTRANSFER => 1,
	    CURLOPT_URL => "https://hackathon-be.mybluemix.net/customer/$custId/goals",
	    CURLOPT_USERAGENT => 'Codular Sample cURL Request',
	    CURLOPT_POST => 1,
	    CURLOPT_POSTFIELDS => array(
	        name => 'value',
	        value => 'value2',
	        saved => 'value2',
	        date => 'value2',
	        accountid => 'value2',
	        priority => 'value2'
	    )
	));
	// Send the request & save response to $resp
	$resp = curl_exec($curl);
	// Close request to clear up some resources
	curl_close($curl);
}
function deleteGoals() {

}