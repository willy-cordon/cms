<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------


	$pathimagenes = '../pitimg/';
	if (!file_exists($pathimagenes)) {
		mkdir($pathimagenes);	   				
	}

/* ------------------------------------ x ----------------------------------- */

	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$pitcodigo 		= (isset($_POST['pitcodigo']))? trim($_POST['pitcodigo']) : 0;
	$pitnombre 		= (isset($_POST['pitnombre']))? trim($_POST['pitnombre']) : '';
	$pitdes 		= (isset($_POST['pitdes']))? trim($_POST['pitdes']) : '';
	$percodigo 		= (isset($_POST['percodigo']))? trim($_POST['percodigo']) : '';
	$estcodigo 		= 1;
	
	if($pitcodigo==''){
		$pitcodigo=0;
	}
	if($pitnombre==''){
		$errcod=2;
		$errmsg='Falta el nombre';
	}	
	if($pitdes==''){
		$errcod=2;
		$errmsg='Falta el descripcion';
	}
	if($percodigo==''){
		$errcod=2;
		$errmsg='Falta el perfil';
	}
	if($errcod==2){
		echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
		exit;
	}
	//--------------------------------------------------------------------------------------------------------------
	$pitcodigo		= VarNullBD($pitcodigo		, 'N');
	$pitnombre		= VarNullBD($pitnombre		, 'S');
	$pitdes			= VarNullBD($pitdes			, 'S');
	$percodigo		= VarNullBD($percodigo		, 'N');
	
	logerror("ENTRO");
	if($pitcodigo==0){
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(GEN_PIT,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn,$trans);
		$RowId		= $TblId->Rows[0];			
		$pitcodigo 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$query = " 	INSERT INTO PIT_MAEST(PITCODIGO,PITNOMBRE,PITDES,PITCON, PERCODIGO)
					VALUES($pitcodigo,$pitnombre,$pitdes,0,$percodigo) ";
	}else{
		$query = " 	UPDATE PIT_MAEST SET 
					PITCODIGO=$pitcodigo, PITNOMBRE=$pitnombre,PITDES=$pitdes, PERCODIGO = $percodigo
					WHERE PITCODIGO=$pitcodigo ";
	}
	$err = sql_execute($query,$conn,$trans);	
	
	date_default_timezone_set('UTC');
	
	if(isset($_FILES['pitimg'])){
		$ext 	= pathinfo($_FILES['pitimg']['name'], PATHINFO_EXTENSION);
		$name 	= 'PITIMG'.date(mktime(0, 0, 0, 7, 1, 2000)).'.'.$ext;
		
		if (!file_exists($pathimagenes.$pitcodigo)) {
			mkdir($pathimagenes.$pitcodigo);	   				
		}
		if(file_exists($pathimagenes.$pitcodigo.'/'.$name)){
			unlink($pathimagenes.$pitcodigo.'/'.$name);
		}
		move_uploaded_file( $_FILES['pitimg']['tmp_name'], $pathimagenes.$pitcodigo.'/'.$name);

		$imagen_optimizada = redimensionar_imagen($name,$pathimagenes.$pitcodigo.'/'.$name,200,200);
	
		//Guardado de imagen
		imagepng($imagen_optimizada, $pathimagenes.$pitcodigo.'/'.$name);
		
		 
		$_SESSION[GLBAPPPORT.'pitmg'] =  $pathimagenes.$pitcodigo.'/'.$name; //Actualizo la variable de Session del AVATAR
		
		$query = "	UPDATE PIT_MAEST SET PITIMG='$name' WHERE PITCODIGO=$pitcodigo ";
		$err = sql_execute($query,$conn,$trans);
	}
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = TrMessage('Guardado correctamente!');      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? TrMessage('Guardado correctamente!') : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
