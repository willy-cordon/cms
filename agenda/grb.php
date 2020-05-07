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
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$agereg 		= (isset($_POST['agereg']))? trim($_POST['agereg']) : 0;
	$agetitulo 		= (isset($_POST['agetitulo']))? trim($_POST['agetitulo']) : '';
	$agedescri 		= (isset($_POST['agedescri']))? trim($_POST['agedescri']) : '';
	$agelugar 		= (isset($_POST['agelugar']))? trim($_POST['agelugar']) : '';
	$agefch 		= (isset($_POST['agefch']))? trim($_POST['agefch']) : '';
	$agehorini 		= (isset($_POST['agehorini']))? trim($_POST['agehorini']) : 0;
	$agehorfin		= (isset($_POST['agehorfin']))? trim($_POST['agehorfin']) : 0;
	$spkreg			= (isset($_POST['speakers']))? trim($_POST['speakers']) : 0;
	$ageprehab		= (isset($_POST['ageprehab']))? trim($_POST['ageprehab']) : 'N';
	
	$estcodigo 		= 1;
	//$fecha= BDConvFch($row['FECHA']);
	if($agereg==''){
		$agereg=0;
	}
	
	if($errcod==2){
		echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
		exit;
	}
	//--------------------------------------------------------------------------------------------------------------
	$agereg			= VarNullBD($agereg			, 'N');
	$agetitulo		= VarNullBD($agetitulo		, 'S');
	$agedescri      = VarNullBD($agedescri		, 'S');
	$agelugar       = VarNullBD($agelugar		, 'S');
	$agehorini		= VarNullBD($agehorini		, 'S');
	$agehorfin		= VarNullBD($agehorfin		, 'S');
	$estcodigo		= VarNullBD($estcodigo		, 'N');
	$ageprehab		= VarNullBD($ageprehab		, 'S');
	$spkreg			= VarNullBD($spkreg			, 'S');

	//fecha 2019-03-24 -> mm/dd/yyyy BD 
	$agefch = substr($agefch,5,2).'/'.substr($agefch,8,2).'/'.substr($agefch,0,4);
	
	if($agereg==0){
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_AGENDA,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn,$trans);
		$RowId		= $TblId->Rows[0];			
		$agereg 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$query = " 	INSERT INTO AGE_MAEST (AGEREG, AGETITULO, AGEDESCRI, AGELUGAR, AGEFCH, AGEHORINI, AGEHORFIN, ESTCODIGO, SPKREG, AGEPREHAB)
					VALUES  ($agereg, $agetitulo, $agedescri, $agelugar, '$agefch', $agehorini, $agehorfin,$estcodigo, $spkreg, $ageprehab ) ";
					//$agefch	= substr($agefch,6,4).'-'.substr($agefch,3,2).'-'.substr($agefch,0,2);
					logerror($query);
					
	}else{
		$query = " UPDATE AGE_MAEST SET 
					 AGETITULO=$agetitulo, AGEDESCRI=$agedescri, AGELUGAR=$agelugar, AGEFCH='$agefch',AGEHORINI=$agehorini, AGEHORFIN=$agehorfin, 
					 ESTCODIGO=$estcodigo, SPKREG=$spkreg, AGEPREHAB=$ageprehab
					WHERE AGEREG=$agereg ";
	}
	
	$err = sql_execute($query,$conn,$trans);	
	
		
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = TrMessage('Guardado correctamente!');      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? 'Guardado correctamente!' : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
