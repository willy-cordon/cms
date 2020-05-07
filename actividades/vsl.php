<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('vsl.html');
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodlog = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$agereg = (isset($_POST['agereg']))? trim($_POST['agereg']) : 0;
	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	$query="SELECT AGEREG,AGEFCH,AGETITULO,AGEDESCRI,AGEHORINI,AGEHORFIN,AGELUGAR,ESTCODIGO
			FROM AGE_MAEST
			WHERE AGEREG=$agereg ";

	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$agereg 	= trim($row['AGEREG']);
		$agetitulo 	= trim($row['AGETITULO']);
		$agedescri 	= trim($row['AGEDESCRI']);
		$agelugar   = trim($row['AGELUGAR']);
		$agefch     = BDConvFch($row['AGEFCH']);
		$agehorini  = substr(trim($row['AGEHORINI']),0,5);
		$agehorfin  = substr(trim($row['AGEHORFIN']),0,5);

		$tmpl->setVariable('agereg'	, $agereg);
		$tmpl->setVariable('agetitulo'	, $agetitulo);
		$tmpl->setVariable('agedescri'	, $agedescri);
		$tmpl->setvariable('agelugar', $agelugar);
		$tmpl->setVariable('agefch'	, $agefch);
		$tmpl->setVariable('agehorini'	, $agehorini);
		$tmpl->setVariable('agehorfin'	, $agehorfin);
		$tmpl->setVariable('estcodigo'	, $estcodigo);
	}
		
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
