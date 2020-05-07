<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC . '/sigma.php';
	require_once GLBRutaFUNC . '/zdatabase.php';
	require_once GLBRutaFUNC . '/zfvarias.php';
	require_once GLBRutaFUNC . '/idioma.php'; //Idioma	

	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn = sql_conectar(); //Apertura de Conexion
	$trans	= sql_begin_trans($conn);   

	/* ------------------------- SECTION  CONTROL DE DATOS ------------------------ */

	$pertipo 			= (isset($_POST['pertipo'])) ? trim($_POST['pertipo']) : 0;
	$pertipdesesp 		= (isset($_POST['pertipdesesp'])) ? trim($_POST['pertipdesesp']) : '';
	$pertipdesing 		= (isset($_POST['pertipdesing'])) ? trim($_POST['pertipdesing']) : '';
	$perusacha 			= (isset($_POST['perusacha'])) ? trim($_POST['perusacha']) : '';
	$perusareu 			= (isset($_POST['perusareu'])) ? trim($_POST['perusareu']) : '';
	
	
	$estcodigo 		= 1;


	if ($pertipo == '') {
		$pertipo = 0;
	}
	if ($pertipdesesp == '') {
		$errcod = 2;
		$errmsg = 'Falta el nombre de sector';
	}
	if ($errcod == 2) {
		echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';
		exit;
	}

	/* -------------------- END SECTION VALIDACION DE ERRORES ------------------- */

	$pertipo			= VarNullBD($pertipo, 'N');
	$pertipdesesp		= VarNullBD($pertipdesesp, 'S');
	$pertipdesing		= VarNullBD($pertipdesing, 'S');
	$perusacha			= VarNullBD($perusacha, 'S');
	$perusareu			= VarNullBD($perusareu, 'S');

	if ($pertipo == 0) {
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_PERTIPO,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query, $conn, $trans);
		$RowId		= $TblId->Rows[0];
		$pertipo 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

		$query = " 	INSERT INTO PER_TIPO(PERTIPO,PERTIPDESESP,PERTIPDESING,ESTCODIGO,PERUSACHA,PERUSAREU)
						VALUES($pertipo,$pertipdesesp,$pertipdesing,$estcodigo,$perusareu,$perusacha) ";

		
	} else {
		$query = " 	UPDATE PER_TIPO SET 
						pertipo=$pertipo, pertipdesesp=$pertipdesesp,pertipdesing=$pertipdesing,ESTCODIGO=$estcodigo,PERUSACHA=$perusacha,PERUSAREU=$perusareu
						WHERE PERTIPO=$pertipo";
	}

	$err = sql_execute($query, $conn, $trans);
	logerror('QUERY TEST: ' .$query);
	
	if ($err == 'SQLACCEPT' && $errcod == 0) {
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = TrMessage('Guardado correctamente!');
	} else {
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg == '') ? TrMessage('Guardado correctamente!') : $errmsg;
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);
	echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';

?>	
