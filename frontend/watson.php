 <!DOCTYPE html>
 <html>
 <head>
     <title>PHP Starter Application</title>
     <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
     <link rel="stylesheet" href="style.css" />
 </head>
 <body>
 
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
     curl_setopt($curl, CURLOPT_USERPWD, "USERNAME:PASSWORD");
     curl_setopt($curl, CURLOPT_URL, "https://gateway.watsonplatform.net/language-identification-beta/api");
     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
 
     $result = curl_exec($curl);
     
     curl_close($curl);
     
     $decoded = json_decode($result, true);
     
     return $decoded;
 }
 // define variables and set to empty values
 $textLID = "";
 $textLIDErr = "";
 $textLang = "";
 
 if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    if (empty($_POST["textLID"])) {
      $textLIDErr = "Text is required (at least 3 words)";
    } else {
      $textLID = test_input($_POST["textLID"]);
    }
 }
 
 $textLang = testLangID($textLID);
 
 function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
 }
 ?>
 
     <table>
         <tr>
             <td style='width: 30%;'><img class = 'newappIcon' src='images/newapp-icon.png'>
             </td>
             <td>
                 <h2>Watson Language Identification</h2>
                 <p><span class="error">* required field.</span></p>
                 <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                     Enter text to identify language: <textarea name="textLID" rows="5" cols="40"><?php echo $textLID;?></textarea>
                       
                     <span class="error">* <?php echo $textLIDErr;?></span>
                       
  
                     <input type="submit" name="submit" value="Submit">
                 </form>
             
                 <?php
                 echo "<h2>Text language: </h2>";
                 echo $textLang["lang"];
                 ?>
             </td>
         </tr>
     </table>
 </body>
 </html>
   