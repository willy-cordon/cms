<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('dvcategorias.html');
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	$subsectores 	= '';
	$categorias		= '';
	
	if(isset($_POST['subsectores'])){
		foreach($_POST['subsectores'] as $ind => $data){
			$subsectores .= $data['secsubcod'].','; 
		}
		if(trim($subsectores)==',') $subsectores='';
	}
	if(isset($_POST['categorias'])){
		foreach($_POST['categorias'] as $ind => $data){
			$categorias .= $data['catcodigo'].','; 
		}
		if(trim($categorias)==',') $categorias='';
	}
	
	//Cargo las Categorias
	$query = "	SELECT CATCODIGO,CATDESCRI,CATDESING,SECSUBCOD
				FROM CAT_MAEST
				WHERE SECSUBCOD IN ($subsectores 0) AND ESTCODIGO<>3 ";

	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$catcodigo 	= trim($row['CATCODIGO']);
		$catdescri 	= trim($row['CATDESCRI']);
		$catdesing 	= trim($row['CATDESING']);
		$secscod 	= trim($row['SECSUBCOD']);
		
		$tmpl->setCurrentBlock('categorias');
		$tmpl->setVariable('catcodigo'	, $catcodigo	);
		$tmpl->setVariable('catdescri'	, $catdescri	);
		$tmpl->setVariable('catdesing'	, $catdesing	);
		$tmpl->setVariable('secscod'	, $secscod		);
		
		if(strpos($categorias,$catcodigo.',')!==false){
			$tmpl->setVariable('catchecked'	, 'checked'	);
		}
		
		$tmpl->parse('categorias');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
