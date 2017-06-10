<?php

function talkToWatson($params) {
	//
	$curl = curl_init();
	$postJson = json_encode($params);

	curl_setopt_array($curl, array(
	    CURLOPT_POST => true,
	    CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
	    CURLOPT_URL => "https://watson-api-explorer.mybluemix.net/conversation/api/v1/workspaces/71b4b73e-ea58-44e3-b3f1-9373753b5ecd/message?version=2017-05-26",
	    CURLOPT_USERPWD => "7eb7b16d-f4eb-445c-8d9b-edea1af7f275:x6uCkhOgwTQw",
	    CURLOPT_POSTFIELDS => $postJson,
	    CURLOPT_RETURNTRANSFER => true,
	    CURLOPT_SSL_VERIFYPEER => true
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);

	if ($err) {
	    echo "cURL Error #:" . $err;
	} else {
		return $response;
	}
}

