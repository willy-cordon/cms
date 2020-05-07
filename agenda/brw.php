<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('brw.html');
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	
	$fltdescri = (isset($_POST['fltdescri']))? trim($_POST['fltdescri']):'';
	
	$where = '';
	if($fltdescri!=''){
		$where .= " AND AGETITULO CONTAINING '$fltdescri' ";
	}
	
	$conn= sql_conectar();//Apertura de Conexion
	//Seleccionamos los datos que se mostrarar en el brw
	$query = "SELECT A.AGEREG, SUBSTRING(A.AGETITULO FROM 1 FOR 20) AS AGETITULO, SUBSTRING(A.AGEDESCRI FROM 1 FOR 20) AS AGEDESCRI, A.AGELUGAR, A.AGEFCH, A.AGEHORINI, A.AGEHORFIN, A.ESTCODIGO
				FROM AGE_MAEST A
				WHERE A.ESTCODIGO<>3 $where
				ORDER BY A.AGEHORINI";
			
	//logerror($query);	
	//echo($agedescri);		
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$agereg 	= trim($row['AGEREG']);
		$agetitulo 	= trim($row['AGETITULO']);
		$agedescri = trim($row['AGEDESCRI']);
		$agelugar     = trim($row['AGELUGAR']);
		$agefch     = BDConvFch($row['AGEFCH']);
		$agehorini     = substr(trim($row['AGEHORINI']),0,5);
		$agehorfin     = substr(trim($row['AGEHORFIN']),0,5);
		
		
		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('agereg'	, $agereg);
		$tmpl->setVariable('agetitulo'	, $agetitulo);
		$tmpl->setVariable('agedescri'	, $agedescri);
		$tmpl->setvariable('agelugar', $agelugar);
		$tmpl->setVariable('agefch'	, $agefch);
		$tmpl->setVariable('agehorini'	, $agehorini);
		$tmpl->setVariable('agehorfin'	, $agehorfin);
		$tmpl->parse('browser');

		
	}
	//$agehorini=date("h:i");
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
