<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod 	= 0;
	$errmsg 	= '';
	$err 		= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$percodlog = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$agereg = (isset($_POST['agereg']))? trim($_POST['agereg']) : 0;
	//--------------------------------------------------------------------------------------------------------------
	$agereg = VarNullBD($agereg , 'N');
	
	if($agereg!=0){
		//Busco datos del evento
		$query="SELECT AGEREG,AGEFCH,AGETITULO,AGEDESCRI,AGEHORINI,AGEHORFIN,AGELUGAR,ESTCODIGO
				FROM AGE_MAEST
				WHERE AGEREG=$agereg ";

		$Table = sql_query($query,$conn);
		$row = $Table->Rows[0];
		$agereg 	= trim($row['AGEREG']);
		$agefch 	= trim($row['AGEFCH']);
		$agehorini 	= trim($row['AGEHORINI']);
		
		//Agendo el evneto
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_REUNIONES,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn,$trans);
		$RowId		= $TblId->Rows[0];			
		$reureg 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		
		$query = "	INSERT INTO REU_CABE (REUREG, REUFCHREG, PERCODSOL, PERCODDST, REUESTADO, REUFECHA, REUHORA, REUFCHCAN,AGEREG) 
					VALUES ($reureg, CURRENT_TIMESTAMP, $percodlog, $percodlog, 2, '$agefch', '$agehorini', NULL, $agereg);";
		$err = sql_execute($query,$conn,$trans);
	}
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = 'Agenda agregada!';      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? 'Error al agendar!' : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
