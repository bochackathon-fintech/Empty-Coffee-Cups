 <?php
 function testLangID($data) {
     $curl = curl_init();
     
     $post_args = array(
         'txt' => $data,
         'sid' => 'lid-generic',
         'rt' => 'json' 
     );
     
     curl_setopt($curl, CURLOPT_POST, true);
     curl_setopt($curl, CURLOPT_POSTFIELDS, $post_args);
     curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
     curl_setopt($curl, CURLOPT_USERPWD, "7eb7b16d-f4eb-445c-8d9b-edea1af7f275:x6uCkhOgwTQw");
     curl_setopt($curl, CURLOPT_URL, "https://watson-api-explorer.mybluemix.net/conversation/api/v1/workspaces/71b4b73e-ea58-44e3-b3f1-9373753b5ecd/message?version=2017-05-26");
     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
 
     $result = curl_exec($curl);
     
     curl_close($curl);
     
     $decoded = json_decode($result, true);
     
     print_r($decoded);
 }