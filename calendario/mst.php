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
	$percodigo = (isset($_POST['percodigo']))? trim($_POST['percodigo']) : 0;
	$perestcod = 1; //Activo por defecto
	$pernombre = '';
	$perapelli = '';
	$percorreo = '';
	$pertelefo = '';
	$perdirecc = '';
	$perciudad = '';
	$perestado = '';
	$paicodigo = '';
	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	if($percodigo!=0){
		$query = "	SELECT PERCODIGO,PERNOMBRE,PERAPELLI,PERCOMPAN
					FROM PER_MAEST 
					WHERE PERCODIGO=$percodigo ";
		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$percodigo 	= trim($row['PERCODIGO']);
			$pernombre 	= trim($row['PERNOMBRE']);
			$perapelli 	= trim($row['PERAPELLI']);
			$percompan 	= trim($row['PERCOMPAN']);
			
			$tmpl->setVariable('percodigo'	, $percodigo	);
			$tmpl->setVariable('pernombre'	, $pernombre	);
			$tmpl->setVariable('perapelli'	, $perapelli	);
			$tmpl->setVariable('percompan'	, $percompan	);
		}
	}
	
	//--------------------------------------------------------------------------------------------------------------
	//Listado de Paises
	$query = "	SELECT PAICODIGO,PAIDESCRI
				FROM TBL_PAIS
				ORDER BY PAIDESCRI ";
	
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count;$i++){
		$row= $Table->Rows[$i];
		$paicod = trim($row['PAICODIGO']);
		$paides = trim($row['PAIDESCRI']);
		
		$tmpl->setCurrentBlock('paises');
		$tmpl->setVariable('paicodigo'	, $paicod 	);
		$tmpl->setVariable('paidescri'	, $paides 	);
		
		if($paicodigo==$paicod){
			$tmpl->setVariable('paiselected', 'selected' );
		}
		
		$tmpl->parseCurrentBlock();
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
