<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('clasificar.html');

	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	$btnsectores 		= (isset($_SESSION[GLBAPPPORT.'SECTORES']))? trim($_SESSION[GLBAPPPORT.'SECTORES']) : '';
	$btnsubsectores 	= (isset($_SESSION[GLBAPPPORT.'SUBSECTORES']))? trim($_SESSION[GLBAPPPORT.'SUBSECTORES']) : '';
	$btncategorias 		= (isset($_SESSION[GLBAPPPORT.'CATEGORIAS']))? trim($_SESSION[GLBAPPPORT.'CATEGORIAS']) : '';
	$btnsubcategorias 	= (isset($_SESSION[GLBAPPPORT.'SUBCATEGORIAS']))? trim($_SESSION[GLBAPPPORT.'SUBCATEGORIAS']) : '';
	
	if($btnsectores!=1) $tmpl->setVariable('btnsectores'	, 'display:none;'	);
	if($btnsubsectores!=1) $tmpl->setVariable('btnsubsectores'	, 'display:none;'	);
	if($btncategorias!=1) $tmpl->setVariable('btncategorias'	, 'display:none;'	);
	if($btnsubcategorias!=1) $tmpl->setVariable('btnsubcategorias'	, 'display:none;'	);
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_POST['percodigo']))? trim($_POST['percodigo']) : 0;
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	//Data Sectores 
	$sectores = '';
	$subsectores = '';
	$categorias = '';
	$subcategorias = '';
	
	if(isset($_POST['dataClasificar'])){
		if(isset($_POST['dataClasificar']['sectores'])){
			foreach($_POST['dataClasificar']['sectores'] as $ind => $data){
				$sectores .= $data['seccodigo'].','; 
			}
			if(trim($sectores)==',') $sectores='';
		}
		
		//Data Subsectores
		if(isset($_POST['dataClasificar']['subsectores'])){
			foreach($_POST['dataClasificar']['subsectores'] as $ind => $data){
				$subsectores .= $data['secsubcod'].','; 
			}
			if(trim($subsectores)==',') $subsectores='';
		}
		
		//Data Categorias
		if(isset($_POST['dataClasificar']['categorias'])){
			foreach($_POST['dataClasificar']['categorias'] as $ind => $data){
				$categorias .= $data['catcodigo'].','; 
			}
			if(trim($categorias)==',') $categorias='';
		}
	
		//Data Subcategorias
		if(isset($_POST['dataClasificar']['subcategorias'])){
			foreach($_POST['dataClasificar']['subcategorias'] as $ind => $data){
				$subcategorias .= $data['catsubcod'].','; 
			}
			if(trim($subcategorias)==',') $subcategorias='';
		}
	}
	//--------------------------------------------------------------------------------------------------------------
	//Cargo los Sectores 
	$query = "	SELECT SECCODIGO,SECDESCRI,SECDESING
				FROM SEC_MAEST
				WHERE ESTCODIGO<>3 ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$seccodigo = trim($row['SECCODIGO']);
		$secdescri = trim($row['SECDESCRI']);
		$secdesing = trim($row['SECDESING']);
		
		$tmpl->setCurrentBlock('sectores');
		$tmpl->setVariable('seccodigo'	, $seccodigo	);
		$tmpl->setVariable('secdescri'	, $secdescri	);
		$tmpl->setVariable('secdesing'	, $secdesing	);
		
		if(strpos($sectores,$seccodigo.',')!==false){
			$tmpl->setVariable('secchecked'	, 'checked'	);
		}
		
		$tmpl->parse('sectores');
	}
	
	//Cargo los Subsectores 
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
		$tmpl->setVariable('seccod'			, $seccod		);
		
		if(strpos($subsectores,$secsubcod.',')!==false){
			$tmpl->setVariable('secsubchecked'	, 'checked'	);
		}
		
		$tmpl->parse('subsectores');
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
	
	//Cargo las Subcategorias
	$query = "	SELECT CATSUBCOD,CATSUBDES,CATSUBDESING,CATCODIGO
				FROM CAT_SUB
				WHERE CATCODIGO IN ($categorias 0) AND ESTCODIGO<>3 ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$catsubcod 		= trim($row['CATSUBCOD']);
		$catsubdes 		= trim($row['CATSUBDES']);
		$catsubdesing 	= trim($row['CATSUBDESING']);
		$catcod 		= trim($row['CATCODIGO']);
		
		$tmpl->setCurrentBlock('subcategorias');
		$tmpl->setVariable('catsubcod'		, $catsubcod	);
		$tmpl->setVariable('catsubdes'		, $catsubdes	);
		$tmpl->setVariable('catsubdesing'	, $catsubdesing	);
		$tmpl->setVariable('catcod'			, $catcod	);
		
		if(strpos($subcategorias,$catsubcod.',')!==false){
			$tmpl->setVariable('catsubchecked'	, 'checked'	);
		}
		
		$tmpl->parse('subcategorias');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
