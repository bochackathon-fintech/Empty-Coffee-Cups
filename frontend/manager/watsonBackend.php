<?php
	include_once("watsonFunctions.php");
	include_once("functions.php");
	
	$output = 'test';
	$action = $_POST['action'];		//what to do what to do..

	// $context = array('saveamount' => 10, 'username' => '', 'balanceamount' => 1500, 'transferamount' => 10);
	$params = array(//'context' => $context, 
					'alternate_intents' => true
				);

	switch ($action) {
	    case 'sendMessage':
			//what to do what to do..
			//update json params
			if(isset($_POST['json_resp'])) {
				$json_resp = $_POST['json_resp'];
				$json_resp = json_decode($json_resp, true);
				unset($json_resp['output']);
				unset($json_resp['intents']);
				unset($json_resp['input']);
				$params['context'] = $json_resp['context'];
			}
			
			$username = getBOCCustomer();
			$params['context']['customername'] = $username;

			$message = $_POST['message'];		//what to do what to do..
			$input = array('text' => $message);
			/*$system = array('dialog_stack' => array('dialog_node' => 'root'),
						'dialog_turn_counter' => 1,
						'dialog_request_counter' => 1,
						'_node_output_map' => array('Anything else' => array('0','1','2','3'),
													'Welcome' => 0)
					);*/

			// $conversation_id = '';
			// if(isset($_POST['conversation_id'])) {
			// 	$conversation_id = $_POST['conversation_id'];
			// 	$context['conversation_id'] = $conversation_id;
			// }
			$params['input'] = $input;
			// $params['system'] = $system;

			$output = talkToWatson($params);
	        break;
	}

	echo $output;
?>