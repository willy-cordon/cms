<?
	function sendIOSMessage($texto, $devicetoken) {
		
		$tPort = 2195;
		
		$tHost = 'gateway.sandbox.push.apple.com';
		$tCert = $_SERVER["DOCUMENT_ROOT"].'/webcoordinador/func/dev.pem';
		
		//$tHost = 'gateway.push.apple.com';
		//$tCert = $_SERVER["DOCUMENT_ROOT"].'/func/prd.pem';
		
		
		// Provide the Private Key Passphrase (alternatively you can keep this secrete
		// and enter the key manually on the terminal -> remove relevant line from code).
		// Replace XXXXX with your Passphrase
		$tPassphrase = 'games09A';
		// Provide the Device Identifier (Ensure that the Identifier does not have spaces in it).
		// Replace this token with the token of the iOS device that is to receive the notification.
		//$tToken = 'b3d7a96d5bfc73f96d5bfc73f96d5bfc73f7a06c3b0101296d5bfc73f38311b4';
		$tToken = $devicetoken;
		//0a32cbcc8464ec05ac3389429813119b6febca1cd567939b2f54892cd1dcb134
		// The message that is to appear on the dialog.
		$tAlert = 'You have a LiveCode APNS Message';
		// The Badge Number for the Application Icon (integer >=0).
		$tBadge = 1;
		// Audible Notification Option.
		$tSound = 'default';
		// The content that is returned by the LiveCode "pushNotificationReceived" message.
		$tPayload = 'APNS Message Handled by LiveCode';
		// Create the message content that is to be sent to the device.
		$tBody['aps'] = array (
			'alert' => $texto,
			'badge' => $tBadge,
			'sound' => $tSound,
		);
		
		$tBody ['payload'] = $tPayload;
		// Encode the body to JSON.
		$tBody = json_encode ($tBody);
		// Create the Socket Stream.
		$tContext = stream_context_create ();
		
		stream_context_set_option ($tContext, 'ssl', 'local_cert', $tCert);
		
		// Remove this line if you would like to enter the Private Key Passphrase manually.
		stream_context_set_option ($tContext, 'ssl', 'passphrase', $tPassphrase);
		
		// Open the Connection to the APNS Server.
		$tSocket = stream_socket_client ('ssl://'.$tHost.':'.$tPort, $error, $errstr, 30, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $tContext);
		
		// Check if we were able to open a socket.
		if (!$tSocket){
			logerror("APNS Connection Failed: $error $errstr" );
			exit ("APNS Connection Failed: $error $errstr" . PHP_EOL);
		}
		
		// Build the Binary Notification.
		$tMsg = chr (0) . chr (0) . chr (32) . pack ('H*', $tToken) . pack ('n', strlen ($tBody)) . $tBody;
		
		// Send the Notification to the Server.
		$tResult = fwrite ($tSocket, $tMsg, strlen ($tMsg));
		
		if ($tResult){
			echo 'Delivered Message to APNS' . PHP_EOL;
			logerror('Delivered Message to APNS');
		}
		else{
			logerror('Could not Deliver Message to APNS');
			echo 'Could not Deliver Message to APNS' . PHP_EOL;
		}
		
		// Close the Connection to the Server.
		fclose ($tSocket);
		
	}
	
?>	
