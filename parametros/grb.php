<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
	//--------------------------------------------------------------------------------------------------------------
	$pathimagenes = '../app/';
	if (!file_exists($pathimagenes)) {
		mkdir($pathimagenes);	   				
	}
	
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';

	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$parreg 		= (isset($_POST['parreg']))	  	? trim($_POST['parreg']) 	: 0;
	$parcodigo 		= (isset($_POST['parcodigo']))	? trim($_POST['parcodigo']) : '';
	$parorden 		= (isset($_POST['parorden'])) 	? trim($_POST['parorden']) : '';
	$partipo 		= (isset($_POST['partipo'])) 	? trim($_POST['partipo']) : '';
	$pertipo 		= (isset($_POST['pertipo'])) 	? trim($_POST['pertipo']) : 0;
	
	if($parreg=='') $parreg=0;
	
	if($errcod==2){
		echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
		exit;
	}
	
	//--------------------------------------------------------------------------------------------------------------
	$parreg			= VarNullBD($parreg			, 'N');
	$parcodigo		= VarNullBD($parcodigo		, 'S');
	$parorden       = VarNullBD($parorden		, 'N');
	$partipo		= VarNullBD($partipo		, 'S');
	
	if($parreg==0){
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT MAX(PARREG) AS ID FROM PAR_MAEST';
		$TblId		= sql_query($query,$conn,$trans);
		$RowId		= $TblId->Rows[0];			
		$parreg 	= trim($RowId['ID'])+1;
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$query = " 	INSERT INTO PAR_MAEST (PARREG,PARCODIGO,PARVALOR,PARORDEN,PARTIPO,PERTIPO)
					VALUES ($parreg,$parcodigo,'',$parorden,$partipo,$pertipo) ";
	}else{
		$query = " 	UPDATE PAR_MAEST SET 
					PARCODIGO=$parcodigo,PARORDEN=$parorden, PARTIPO=$partipo, PERTIPO=$pertipo
					WHERE PARREG=$parreg ";
	}
	$err = sql_execute($query,$conn,$trans);	
	date_default_timezone_set('UTC');
	//--------------------------------------------------------------------------------------------------------------
	if(isset($_FILES['parvalor'])){
		$ext 	= pathinfo($_FILES['parvalor']['name'], PATHINFO_EXTENSION);
		$name 	= $_FILES['parvalor']['name'];
		$name	= str_replace('.'.$ext,'',$name);
		$name 	= $name.'_'.date(mktime(0, 0, 0, 7, 1, 2000)).'.'.$ext;
		
		if(file_exists($pathimagenes.'/'.$name)){
			unlink($pathimagenes.'/'.$name);
		}
		move_uploaded_file($_FILES['parvalor']['tmp_name'], $pathimagenes.'/'.$name);
		 
		$query = "	UPDATE PAR_MAEST SET PARVALOR='$name' WHERE PARREG=$parreg ";
		$err = sql_execute($query,$conn,$trans);
	}
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = 'Guardado correctamente!';      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? 'Guardado correctamente!' : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
