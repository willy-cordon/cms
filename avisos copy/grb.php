<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
	//--------------------------------------------------------------------------------------------------------------
	$pathimagenes = '../avimg/';
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
	$avireg 		= (isset($_POST['avireg']))? trim($_POST['avireg']) : 0;
	$avititulo 		= (isset($_POST['avititulo']))? trim($_POST['avititulo']) : '';
	$avidescrip 		= (isset($_POST['avidescrip']))? trim($_POST['avidescrip']) : '';
	$aviurl 		= (isset($_POST['aviurl']))? trim($_POST['aviurl']) : '';
	
	$estcodigo 		= 1;
	
	if($avireg==''){
		$avireg=0;
	}
	
	if($errcod==2){
		echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
		exit;
	}
	//--------------------------------------------------------------------------------------------------------------
	$avireg		= VarNullBD($avireg		, 'N');
	$avititulo		= VarNullBD($avititulo		, 'S');
	$avidescrip         =VarNullBD($avidescrip, 'S');
	$aviurl         =VarNullBD($aviurl, 'S');
	$estcodigo		= VarNullBD($estcodigo		, 'N');
	
	if($avireg==0){
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_AVISOS,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn,$trans);
		$RowId		= $TblId->Rows[0];			
		$avireg 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$query = " 	INSERT INTO AVI_MAEST (AVIREG,AVITITULO,AVIDESCRIP,AVIURL,ESTCODIGO)
					VALUES ($avireg,$avititulo,$avidescrip,$aviurl,$estcodigo) ";
	}else{
		$query = " 	UPDATE AVI_MAEST SET 
					AVIREG=$avireg,AVITITULO=$avititulo,AVIDESCRIP=$avidescrip, AVIURL=$aviurl, ESTCODIGO=$estcodigo
					WHERE AVIREG=$avireg ";
	}
	//logerror($query);
	//die();
	$err = sql_execute($query,$conn,$trans);	
	date_default_timezone_set('UTC');
	//--------------------------------------------------------------------------------------------------------------
	if(isset($_FILES['aviimagen'])){
		
		$ext 	= pathinfo($_FILES['aviimagen']['name'], PATHINFO_EXTENSION);
		$name 	= 'AVIMAGEN'.date(mktime(0, 0, 0, 7, 1, 2000)).'.'.$ext;
		
		if (!file_exists($pathimagenes.$avireg)) {
			mkdir($pathimagenes.$avireg);	   				
		}
		if(file_exists($pathimagenes.$avireg.'/'.$name)){
			unlink($pathimagenes.$avireg.'/'.$name);
		}
		move_uploaded_file( $_FILES['aviimagen']['tmp_name'], $pathimagenes.$avireg.'/'.$name);
		 
		$_SESSION[GLBAPPPORT.'AVIIMAGEN'] =  $pathimagenes.$avireg.'/'.$name; //Actualizo la variable de Session del AVATAR
		
		$query = "	UPDATE AVI_MAEST SET AVIIMAGEN='$name' WHERE AVIREG=$avireg ";
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
