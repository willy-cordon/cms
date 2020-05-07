<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('dvsubcategorias.html');
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	$categorias		= '';
	$subcategorias 	= '';
	
	if(isset($_POST['categorias'])){
		foreach($_POST['categorias'] as $ind => $data){
			$categorias .= $data['catcodigo'].','; 
		}
		if(trim($categorias)==',') $categorias='';
	}
	
	if(isset($_POST['subcategorias'])){
		foreach($_POST['subcategorias'] as $ind => $data){
			$subcategorias .= $data['catsubcod'].','; 
		}
		if(trim($subcategorias)==',') $subcategorias='';
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
		$tmpl->setVariable('catcod'			, $catcod		);
		
		if(strpos($subcategorias,$catsubcod.',')!==false){
			$tmpl->setVariable('catsubchecked'	, 'checked'	);
		}
		
		$tmpl->parse('subcategorias');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
