# twfish01
<?php
 $json_str = file_get_contents('php://input'); //接收REQUEST的BODY
 $json_obj = json_decode($json_str); //轉JSON格式
$myfile = fopen("log.txt","w+") or die("Unable to open file!"); //設定一個log.txt 用來印訊息
 fwrite($myfile, "\xEF\xBB\xBF".$json_str); //在字串前加入\xEF\xBB\xBF轉成utf8格式
 fclose($myfile);
 //產生回傳給line server的格式
 $sender_userid = $json_obj->events[0]->source->userId;
 $sender_txt = $json_obj->events[0]->message->text;
 $sender_replyToken = $json_obj->events[0]->replyToken;
 $line_server_url = 'https://api.line.me/v2/bot/message/push';
 //用sender_txt來分辨要發何種訊息
 $objID = $json_obj->events[0]->message->id;
			$url = 'https://api.line.me/v2/bot/message/'.$objID.'/content';
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Authorization: Bearer /qwGugx145+ArJNNIOsltn/IxSyYPgh2x0gZHlTVi6uHvJwG4pHxpv+j2DcDeS0XFiT/mv9rfWCgTXbHf5cKeK1P6Bxt4dcoi0TGumShf0RYrxHbXYAzaXxqTZrzuDb7U3PGadttskbH27O+Ph/3bgdB04t89/1O/w1cDnyilFU='
			));
				
			$json_content = curl_exec($ch);
			curl_close($ch);
$imagefile = fopen($objID.".jpeg", "w+") or die("Unable to open file!"); //設定一個log.txt，用來印訊息
			fwrite($imagefile, $json_content); 
			fclose($imagefile);
$header[] = "Content-Type: application/json";
			$post_data = array (
				"requests" => array (
						array (
							"image" => array (
								"source" => array (
									"imageUri" => "http://139.59.123.8/chtChatBot/twfish01/".$objID.".jpeg"
								)
							),
							"features" => array (
								array (
									"type" => "TEXT_DETECTION",
									"maxResults" => 1
								)
							)
						)
					)
			);
			$ch = curl_init('https://vision.googleapis.com/v1/images:annotate?key=AIzaSyCiyGiCfjzzPR1JS8PrAxcsQWHdbycVwmg');                                                                      
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));                                                                  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);                                                                                                   
			$result = json_decode(curl_exec($ch));
			$result_ary = mb_split("\n",$result -> responses[0] -> fullTextAnnotation -> text);
			$ans_txt = "這張發票沒用了，你又製造了一張垃圾";
			foreach ($result_ary as $val) {
				if($val == "AG-26272435"){
					$ans_txt = "恭喜您中獎啦，快分紅!!";
				}
			}
			$response = array (
				"to" => $sender_userid,
				"messages" => array (
					array (
						"type" => "text",
						"text" => $result -> responses[0] -> fullTextAnnotation -> text
					)
				)
			);
 //回傳給line server
 $header[] = "Content-Type: application/json";
 $header[] = "Authorization: Bearer /qwGugx145+ArJNNIOsltn/IxSyYPgh2x0gZHlTVi6uHvJwG4pHxpv+j2DcDeS0XFiT/mv9rfWCgTXbHf5cKeK1P6Bxt4dcoi0TGumShf0RYrxHbXYAzaXxqTZrzuDb7U3PGadttskbH27O+Ph/3bgdB04t89/1O/w1cDnyilFU=";
 $ch = curl_init($line_server_url);                                                                      
 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
 curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));                                                                  
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
 curl_setopt($ch, CURLOPT_HTTPHEADER, $header);                                                                                                   
 $result = curl_exec($ch);
 curl_close($ch); 
?>
