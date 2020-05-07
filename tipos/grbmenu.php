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
	$menu 				= (isset($_POST['menu'])) ? trim($_POST['menu']) : '';


	$querycheck = "SELECT * FROM PER_TIPO_MENU WHERE PERTIPO = $pertipo AND MENCODIGO = $menu";

	$Tablecheck = sql_query($querycheck,$conn,$trans);

	 $resultcheck = $Tablecheck->Rows_Count;
	 
	 $pertipo			= VarNullBD($pertipo, 'N');
	 $menu				= VarNullBD($menu, 'N');

	 if($resultcheck == -1){

		$queryinsert = "INSERT INTO PER_TIPO_MENU (PERTIPO, MENCODIGO) VALUES ($pertipo,$menu)";
		$err = sql_execute($queryinsert,$conn,$trans);
	 }else{
		 $errcod  = 1;
		 $errmsg = "Menu existente para ese tipo";
	 }	

	if ($err == 'SQLACCEPT' && $errcod == 0) {
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = TrMessage('Guardado correctamente!');
	} else {
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg == '') ? TrMessage('Guardado correctamente!') : $errmsg;
	}
	// //--------------------------------------------------------------------------------------------------------------
	sql_close($conn);
	echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';

?>	
