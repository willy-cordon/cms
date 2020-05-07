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
	
	
	$fltdescri = (isset($_POST['fltdescri']))? trim($_POST['fltdescri']):'';
	//Filtro de busqueda por titulo
	$where = '';
	if($fltdescri!=''){
		$where .= " AND CUPTITULO CONTAINING '$fltdescri' ";
	}
	
	$conn= sql_conectar();//Apertura de Conexion
	
	$query = "	SELECT CUPCOD, CUPTITULO, CUPDESCRI, CUPIMG, CUPTIPO, CUPCLASE, ESTCODIGO
				FROM CUP_MAEST
				WHERE ESTCODIGO<>3 $where
				ORDER BY CUPTITULO ";
				
	//logerror($query);			
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$cupcod 	= trim($row['CUPCOD']);
		$cuptitulo 	= trim($row['CUPTITULO']);
		$cupdescri  = trim($row['CUPDESCRI']);
		$cupimg     = trim($row['CUPIMG']);
		$cuptipo  	= trim($row['CUPTIPO']);
		$cupclase 	= trim($row['CUPCLASE']);
		$estcodigo 	= trim($row['ESTCODIGO']);


		//$aviimagen  = trim($row['AVIIMAGEN']);
		
		$tmpl->setCurrentBlock('browser');
		
		$tmpl->setVariable('cupcod'	, $cupcod);
		$tmpl->setVariable('cuptitulo'	, $cuptitulo);
		$tmpl->setVariable('cupdescri'	, $cupdescri);
		$tmpl->setvariable('cupimg', $cupimg);
		$tmpl->setVariable('cuptipo', $cuptipo);
		$tmpl->setVariable('cupclase', $cupclase);

		
		//$tmpl->setvariable('aviimagen',$aviimagen);
		$tmpl->parse('browser');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
