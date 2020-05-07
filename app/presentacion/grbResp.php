<?
	if(!isset($_SESSION))  session_start();
	//--------------------------------------------------------------------------------------------------------------
	include ('../../func/zglobals.php');
	include ('../../func/sigma.php');	
	include ('../../func/zdatabase.php');
    include ('../../func/zfvarias.php');

	$valcode = substr(md5('OnLifeAccesoAppMobile'),0,10).substr(md5('MobileApp'),5,10).substr(md5('oNlIFEApplication'),8,20).md5('OnLifeAccesoApplication');
	
	$datainfo 	= (isset($_POST['datainfo']))? trim($_POST['datainfo']) : '';
	if($datainfo!=''){
		$datainfo = str_replace($valcode,'',$datainfo);
		$datainfo = base64_decode($datainfo);
		
		$vdata = explode('||',$datainfo);
		$percodigo = $vdata[0];//CODIGO DE PERFIL
		
		$encreg 	= (isset($_POST['encreg']))? trim($_POST['encreg']) : 0;
		$errcod		= 0;
		$errmsg		= '';
		$err 		= 'ERROR';
		//--------------------------------------------------------------------------------------------------------------
		$conn= sql_conectar();//Apertura de Conexion
		$trans	= sql_begin_trans($conn);
		
		foreach($_POST['preguntas'] as $index => $data){
			$encpreitm = trim($data['encpreitm']);
			$encvalres = trim($data['encvalres']);
			
			
			$query = "	INSERT INTO ENC_RESP (REUREG, ENCREG, ENCPREITM, PERCODIGO, ENCVALRES, ESTCODIGO) 
						VALUES ($percodigo, $encreg, $encpreitm, $percodigo, '$encvalres', 1)";
			$err = sql_execute($query,$conn,$trans);

			//Actualizo los datos de el perfil que respondio

			$queryU="UPDATE PER_MAEST SET ENCRES=2 WHERE PERCODIGO=$percodigo ";
			$err1=sql_execute($queryU,$conn,$trans);
		}
		if($err == 'SQLACCEPT' &&$err1 == 'SQLACCEPT'  && $errcod==0 ){
			sql_commit_trans($trans);
			$errcod = 0;
			$errmsg = 'Guardado correctamente!';      
		}else{            
			sql_rollback_trans($trans);
			$errcod = 2;
			$errmsg = ($errmsg=='')? 'Guardado correctamente!' : $errmsg;
		}	
		
		sql_close($conn);	
		echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	}
