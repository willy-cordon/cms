<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('mst.html');

	//Diccionario de idiomas
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$catcodigo = (isset($_POST['catcodigo']))? trim($_POST['catcodigo']) : 0;
	$estcodigo = 1; //Activo por defecto
	$catdescri = '';
	$secsubcod = 0;
	$seccodigo = 0;
	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	if($catcodigo!=0){
		$query = "	SELECT CM.CATCODIGO, CM.CATDESCRI,CM.CATDESING, CM.ESTCODIGO, CM.SECSUBCOD, SB.SECCODIGO
					FROM CAT_MAEST CM
					LEFT OUTER JOIN SEC_SUB SB ON SB.SECSUBCOD = CM.SECSUBCOD
					WHERE CM.CATCODIGO=$catcodigo";

		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$catcodigo = trim($row['CATCODIGO']);
			$catdescri = trim($row['CATDESCRI']);
			$catdesing = trim($row['CATDESING']);
			$estcodigo = trim($row['ESTCODIGO']);
			$secsubcod = trim($row['SECSUBCOD']);
			$seccodigo = trim($row['SECCODIGO']);
			
			$tmpl->setVariable('catcodigo'	, $catcodigo	);
			$tmpl->setVariable('catdescri'	, $catdescri	);
			$tmpl->setVariable('catdesing'	, $catdesing	);
			$tmpl->setVariable('estcodigo'	, $estcodigo	);
			$tmpl->setVariable('seccodigo'	, $seccodigo	);
			$tmpl->setVariable('secsubcod'	, $secsubcod	);
		}
	}
	
	//--------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------
	//Listado de Sectores
	$query = "	SELECT SECCODIGO,SECDESCRI
				FROM SEC_MAEST
				WHERE ESTCODIGO <>3 
				ORDER BY SECCODIGO ";
	
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count;$i++){
		$row= $Table->Rows[$i];
		$seccod = trim($row['SECCODIGO']);
		$secdes = trim($row['SECDESCRI']);
		
		$tmpl->setCurrentBlock('sectores');
		$tmpl->setVariable('seccodigo'	, $seccod 	);
		$tmpl->setVariable('secdescri'	, $secdes 	);
		
		if($seccod==$seccodigo){
			$tmpl->setVariable('secselected', 'selected' );
		}
		
		$tmpl->parseCurrentBlock();
	}
	//--------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------
	//Listado de Subsectores
	
	$query = "	SELECT SECSUBCOD,SECSUBDES
				FROM SEC_SUB
				WHERE SECCODIGO=$seccodigo
				ORDER BY SECSUBCOD ";
	
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count;$i++){
		$row= $Table->Rows[$i];
		$secscod = trim($row['SECSUBCOD']);
		$secsdes = trim($row['SECSUBDES']);
		
		$tmpl->setCurrentBlock('subsector');
		$tmpl->setVariable('secsubcod'	, $secscod 	);
		$tmpl->setVariable('secsubdes'	, $secsdes 	);
				
		if($secsubcod==$secscod){
			$tmpl->setVariable('secsubselected', 'selected' );
		}
		
		$tmpl->parseCurrentBlock();
	}
	//--------------------------------------------------------------------------------------------------------------
	
	
	sql_close($conn);	
	$tmpl->show();
	
?>	
