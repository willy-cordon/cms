<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('dvsubsectores.html');
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	$sectores 		= '';
	$subsectores 	= '';
	
	if(isset($_POST['sectores'])){
		foreach($_POST['sectores'] as $ind => $data){
			$sectores .= $data['seccodigo'].','; 
		}
		if(trim($sectores)==',') $sectores='';
	}
	
	if(isset($_POST['subsectores'])){
		foreach($_POST['subsectores'] as $ind => $data){
			$subsectores .= $data['secsubcod'].','; 
		}
		if(trim($subsectores)==',') $subsectores='';
	}
	
	//Cargo los Subsectores segun los sectores seleccionados
	$query = "	SELECT SECSUBCOD,SECSUBDES,SECSUBDESING,SECCODIGO
				FROM SEC_SUB
				WHERE SECCODIGO IN ($sectores 0) AND ESTCODIGO<>3 ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$secsubcod 		= trim($row['SECSUBCOD']);
		$secsubdes 		= trim($row['SECSUBDES']);
		$secsubdesing 	= trim($row['SECSUBDESING']);
		$seccod 		= trim($row['SECCODIGO']);
		
		$tmpl->setCurrentBlock('subsectores');
		$tmpl->setVariable('secsubcod'		, $secsubcod	);
		$tmpl->setVariable('secsubdes'		, $secsubdes	);
		$tmpl->setVariable('secsubdesing'	, $secsubdesing	);
		$tmpl->setVariable('seccod'			, $seccod	);
		
		if(strpos($subsectores,$secsubcod.',')!==false){
			$tmpl->setVariable('secsubchecked'	, 'checked'	);
		}
		
		$tmpl->parse('subsectores');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
