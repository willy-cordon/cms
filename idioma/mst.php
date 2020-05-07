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
	$iditraitm = (isset($_POST['iditraitm']))? trim($_POST['iditraitm']) : 0;
	$estcodigo = 1; //Activo por defecto
	$secdescri = '';
	
	
	

	logerror("MST:".$iditraitm);
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	
	if($iditraitm!=0){
		$query = "	SELECT IDICODIGO, IDITRAESP, IDITRAVAL, IDITRAITM
		FROM IDI_TRAD WHERE IDITRAITM = $iditraitm";

		logerror($query);
		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			
			$iditraitm = trim($row['IDITRAITM']);
			$iditraesp = trim($row['IDITRAESP']);
			$iditraval = trim($row['IDITRAVAL']);
			$idicodigomst = trim($row['IDICODIGO']);

			
		
			
			
			$tmpl->setVariable('iditraesp'	, $iditraesp);
			$tmpl->setVariable('iditraval'	, $iditraval);
			
			
		}



		
		
	}else{
		$idicodigomst = '';
	}

	$queryidioma = "SELECT * FROM IDI_MAEST";
	$Tableidioma = sql_query($queryidioma,$conn);
	for($i=0; $i<$Tableidioma->Rows_Count; $i++){
		
		$row = $Tableidioma->Rows[$i];
		$idicodigo 	= trim($row['IDICODIGO']);
		$ididescri 	= trim($row['IDIDESCRI']);
		
		$tmpl->setCurrentBlock('idioma');
		$tmpl->setVariable('idicodigo',$idicodigo);

		$tmpl->setVariable('ididescri',$ididescri);

		if($idicodigo == $idicodigomst){
			$tmpl->setVariable('selected', 'selected');
		}

		$tmpl->parse('idioma');
	}
	
	$tmpl->setVariable('iditraitm'	, $iditraitm);
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
