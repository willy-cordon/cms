<?
	function sendFCMMessage($data,$target){
	   //FCM API end-point
	   $url = 'https://fcm.googleapis.com/fcm/send';
	   //$server_key = 'AIzaSyDKnoteMgrRH6eMBJG2Uphdyk2VVo6q2fU';	//CMS PERU com.nextar.cms
	   $server_key = 'AIzaSyBNHSCh4gDjY5jWag1S95qZWBuK_t0JVVw';	//CMS com.nextar.cmsbrasil

	   $fields = array();
	   $fields['data'] = $data;
	   if(is_array($target)){   		
	      	$fields['registration_ids'] = $target;
	   }else{
		  $fields['to'] = $target;
	   }
	   logerror(json_encode($fields));
		//header with content_type api key
	   $headers = array(
			'Content-Type:application/json',
			'Authorization:key='.$server_key
	   );
	   //CURL request to route notification to FCM connection server (provided by Google)			
	   $ch = curl_init();
	   curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,300);
	   curl_setopt($ch, CURLOPT_URL, $url);
	   curl_setopt($ch, CURLOPT_POST, true);
	   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	   curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
	   $result = curl_exec($ch);
	   if ($result === FALSE) {
			//die('Oops! FCM Send Error: ' . curl_error($ch));
	   		//logerror('ERROR FCM: '.curl_error($ch));
	   }
	   //logerror('FRM RESULT: '.$result);
	   curl_close($ch);  
		
	}
	
?>	
