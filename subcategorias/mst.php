<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('mst.html');
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$catsubcod = (isset($_POST['catsubcod']))? trim($_POST['catsubcod']) : 0;
	$estcodigo = 1; //Activo por defecto
	$catsubdes = '';
	$catcodigo = 0;
	$secsubcod = 0;
	$seccodigo = 0;	
	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	if($catsubcod!=0){
		$query = "	SELECT CS.CATSUBCOD, CS.CATSUBDES,CS.CATSUBDESING, CS.ESTCODIGO, CM.CATCODIGO, SS.SECSUBCOD, SM.SECCODIGO 
					FROM CAT_SUB CS
					LEFT OUTER JOIN CAT_MAEST CM ON CM.CATCODIGO = CS.CATCODIGO
					LEFT OUTER JOIN SEC_SUB SS ON SS.SECSUBCOD = CM.SECSUBCOD
					LEFT OUTER JOIN SEC_MAEST SM ON SM.SECCODIGO = SS.SECCODIGO
					
					WHERE CS.CATSUBCOD=$catsubcod";

		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$catsubcod 		= trim($row['CATSUBCOD']);
			$catsubdes 		= trim($row['CATSUBDES']);			
			$catsubdesing 	= trim($row['CATSUBDESING']);
			$catcodigo 		= trim($row['CATCODIGO']);
			$estcodigo 		= trim($row['ESTCODIGO']);
			$secsubcod 		= trim($row['SECSUBCOD']);
			$seccodigo 		= trim($row['SECCODIGO']);
			
			$tmpl->setVariable('catsubcod'		, $catsubcod	);
			$tmpl->setVariable('catsubdes'		, $catsubdes	);
			$tmpl->setVariable('catsubdesing'	, $catsubdesing	);
			$tmpl->setVariable('catcodigo'		, $catcodigo	);
			$tmpl->setVariable('estcodigo'		, $estcodigo	);
			
		}
	}
	
	//--------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------
	//Listado de Sectores
	$query = "	SELECT SECCODIGO,SECDESCRI
				FROM SEC_MAEST
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
	
	//--------------------------------------------------------------------------------------------------------------
	//Listado de Catalogos
	$query = "	SELECT CATCODIGO,CATDESCRI
				FROM CAT_MAEST
				WHERE SECSUBCOD=$secsubcod
				ORDER BY CATCODIGO ";
	
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count;$i++){
		$row= $Table->Rows[$i];
		$ccodigo = trim($row['CATCODIGO']);
		$cdescri = trim($row['CATDESCRI']);
		
		$tmpl->setCurrentBlock('categoria');
		$tmpl->setVariable('catcodigo'	, $ccodigo 	);
		$tmpl->setVariable('catdescri'	, $cdescri 	);
				
		if($catcodigo==$ccodigo){
			$tmpl->setVariable('catselected', 'selected' );
		}
		
		$tmpl->parseCurrentBlock();
	}
	//--------------------------------------------------------------------------------------------------------------

	
	
	sql_close($conn);	
	$tmpl->show();
	
?>	
