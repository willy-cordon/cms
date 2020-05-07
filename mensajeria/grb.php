<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$msgreg 		= (isset($_POST['msgreg']))? trim($_POST['msgreg']) : 0;
	$msgtitulo 		= (isset($_POST['msgtitulo']))? trim($_POST['msgtitulo']) : '';
	$msgdescri 		= (isset($_POST['msgdescri']))? trim($_POST['msgdescri']) : '';
	$msgestado 		= 1;


	$msgdate		= (isset($_POST['msgdate']))? trim($_POST['msgdate']) : '';
	$msgtime		= (isset($_POST['msgtime']))? trim($_POST['msgtime']) : '';
	$msgper		= (isset($_POST['msgper']))? trim($_POST['msgper']) : '';
	$msgidioma		= (isset($_POST['msgidioma']))? trim($_POST['msgidioma']) : '';
	$msgsend		= (isset($_POST['msgsend']))? trim($_POST['msgsend']) : '';
	
	if($msgreg==''){
		$msgreg=0;
	}
	if($msgtitulo==''){
		$errcod=2;
		$errmsg='Falta el titulo';

	}
	

	if($errcod==2){
		echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
		exit;
	}
	//--------------------------------------------------------------------------------------------------------------
	$msgreg		= VarNullBD($msgreg		, 'N');
	$msgtitulo		= VarNullBD($msgtitulo		, 'S');
	$msgdescri		= VarNullBD($msgdescri		, 'S');
	$msgestado		= VarNullBD($msgestado		, 'N');
	$msgdate		= VarNullBD($msgdate		, 'S');
	$msgtime		= VarNullBD($msgtime		, 'S');
	$msgper			= VarNullBD($msgper			, 'N');
	$msgidioma		= VarNullBD($msgidioma		, 'S');
	$msgsend		= VarNullBD($msgsend		, 'N');
	if($msgreg==0){
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_MSG,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn,$trans);
		$RowId		= $TblId->Rows[0];			
		$msgreg 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$query = " 	INSERT INTO MSG_CABE(MSGREG,MSGFCHREG,MSGTITULO,MSGDESCRI,MSGESTADO,MSGTIME,MSGDATE,MSGPER,MSGIDIOMA,MSGSEND)
					VALUES($msgreg,CURRENT_TIMESTAMP,$msgtitulo,$msgdescri,$msgestado,$msgtime,$msgdate,$msgper,$msgidioma,$msgsend) ";
					logerror($query);
	}else{
		$query = " 	UPDATE MSG_CABE SET 
					MSGREG=$msgreg, MSGTITULO=$msgtitulo, MSGDESCRI=$msgdescri, MSGESTADO=$msgestado,
					MSGTIME=$msgtime,	  MSGDATE=$msgdate,
					MSGPER=$msgper,		  MSGIDIOMA=$msgidioma,
					MSGSEND=$msgsend
					WHERE MSGREG=$msgreg ";
		logerror($query);
	}
	$err = sql_execute($query,$conn,$trans);
	logerror($err);	
	
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
