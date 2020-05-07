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
	$codclase 		    = (isset($_POST['codclase'])) ? trim($_POST['codclase']) : '';

	$visibilidad 		= (isset($_POST['visibilidad'])) ? trim($_POST['visibilidad']) : '';
	
	$pertipoperm 		= (isset($_POST['pertipoperm'])) ? trim($_POST['pertipoperm']) : '';
    $pertipdest         = (isset($_POST['pertipdst'])) ? trim($_POST['pertipdst']) : '';
	$clase 		        = (isset($_POST['clase'])) ? trim($_POST['clase']) : '';
     //logerror('GRB '."PERTIPO: ".$pertipo." CLASE: ".$clase." VISIBILIDAD: ".$visibilidad." CODPERTIPOPERM: ".$pertipoperm." PERTIPO SELECCIONADO: ".$pertipdest);
	$estcodigo 		= 1;
    
  logerror('CODCLASE: '.$codclase);
	/* ---------------------- END SECTION CONTROL DE DATOS ---------------------- */

	// if ($idTipos == '') {
	// 	$errcod = 2;
	// 	$errmsg = 'Seleccione a que tipo de perfiles puede ver';
	// }
	// if ($pertipo == '') {
	// 	$pertipo = 0;
	// }
	// if ($pertipdesesp == '') {
	// 	$errcod = 2;
	// 	$errmsg = 'Falta el nombre de sector';
	// }
	// if ($errcod == 2) {
	// 	echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';
	// 	exit;
    // }
    
    $pertipo			= VarNullBD($pertipo        , 'N');
	$pertipdest     	= VarNullBD($pertipdest     , 'N');
	$clase		        = VarNullBD($clase          , 'N');
	$visibilidad		= VarNullBD($visibilidad    , 'S');
	$pertipoperm		= VarNullBD($pertipoperm    , 'N');
	$codclase			= VarNullBD($codclase   	, 'N');

	if ($pertipoperm == 0) {
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_PERTIPPERM,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query, $conn, $trans);
		$RowId		= $TblId->Rows[0];
		$pertipoperm 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

		$query = " 	INSERT INTO PER_TIPO_PERM(PERTIPOPERM,PERTIPO,PERTIPCLA,PERTIPDST,PERCLADST)
						VALUES($pertipoperm,$pertipo,$codclase,$pertipdest,$clase) ";

		
	 } else {
		$query = " 	UPDATE PER_TIPO_PERM SET 
						PERTIPO=$pertipo,PERTIPCLA=$codclase, PERTIPDST=$pertipdest,PERCLADST=$clase
						WHERE PERTIPOPERM=$pertipoperm";
	 }
	 
 
	$err = sql_execute($query, $conn, $trans);


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
