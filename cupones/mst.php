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
	$cupcod = (isset($_POST['cupcod']))? trim($_POST['cupcod']) : 0;

	$estcodigo = 1; //Activo por defecto
	$cuptitulo = '';
	$cuptipo = '';
	$cupclase = '';
	$imgnull = '../app-assets/img/pages/sativa.png';
	$tmpl->setVariable('imgnull', $imgnull );
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	if($cupcod!=0){
		$query = "	SELECT CUPCOD, CUPTITULO, CUPDESCRI, CUPIMG, CUPTIPO, CUPCLASE, ESTCODIGO
					FROM CUP_MAEST
					WHERE CUPCOD = $cupcod";
						
		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$cupcod 	= trim($row['CUPCOD']);
			$cuptitulo 	= trim($row['CUPTITULO']);
			$cupdescri 	= trim($row['CUPDESCRI']);
			$cupimg  	= trim($row['CUPIMG']);
			$cuptipo 	= trim($row['CUPTIPO']);
			$cupclase 	= trim($row['CUPCLASE']);
			
			$tmpl->setVariable('cupcod'		, $cupcod	);
			$tmpl->setVariable('cuptitulo'	, $cuptitulo	);
			$tmpl->setVariable('cupdescri'	, $cupdescri	);
			$tmpl->setVariable('estcodigo'	, $estcodigo	);
			
			$tmpl->setVariable('cupimg'		, $cupimg);
			$tmpl->setVariable('cuptipo'	, $cuptipo);
			$tmpl->setVariable('cupclase'	, $cupclase);		
		}
	}

	//Tipos de Cupones
	$query = "	SELECT CUPTIPO, CUPTIPDES,ESTCODIGO
				FROM CUP_TIPO
				WHERE ESTCODIGO<>3
				ORDER BY CUPTIPO ";
						
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$cuptipoSelected 	= trim($row['CUPTIPO']);
		$cuptipdes 		= trim($row['CUPTIPDES']);
		$estcodigo 		= trim($row['ESTCODIGO']);
		
		$tmpl->setCurrentBlock('tipo');
		$tmpl->setVariable('cuptipo'	, $cuptipoSelected);		
		$tmpl->setVariable('cuptipdes'	, $cuptipdes);

		if ($cuptipo == $cuptipoSelected) {			
			$tmpl->setVariable('cuptiposelect'	, 'selected');
		}
		$tmpl->parse('tipo');
	}

	//Clases de Cupones
	$query = "	SELECT CUPCLASE, CUPCLADES,ESTCODIGO
				FROM CUP_CLASE
				WHERE ESTCODIGO<>3
				ORDER BY CUPCLASE ";
			
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$cupclaseselected 	= trim($row['CUPCLASE']);
		$cupclades 			= trim($row['CUPCLADES']);
		$estcodigo 			= trim($row['ESTCODIGO']);

		$tmpl->setCurrentBlock('clase');		
		$tmpl->setVariable('cupclase'	, $cupclaseselected);
		$tmpl->setVariable('cupclades'	, $cupclades);
		if ($cupclase == $cupclaseselected) {
			$tmpl->setVariable('cupclaseselected'	, 'selected');
		}
		$tmpl->parse('clase');
	}

	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
