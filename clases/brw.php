<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('brw.html');
	
	//Diccionario de idiomas
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	
	$fltdescri = (isset($_POST['fltdescri']))? trim($_POST['fltdescri']):'';
	
	$where = '';
	if($fltdescri!=''){
		$where .= " AND PERCLADES CONTAINING '$fltdescri' ";
	}
	
	$conn= sql_conectar();//Apertura de Conexion
	
	$query = "	SELECT PERCLASE, PERCLADES , PERTIPO
				FROM PER_CLASE
				WHERE ESTCODIGO <> 3
				ORDER BY PERCLADES ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){

		$row = $Table->Rows[$i];
		$perclase 	= trim($row['PERCLASE']);
		$perclades 	= trim($row['PERCLADES']);
		$pertipo  	= trim($row['PERTIPO']);

		/* ----------------- SECTION TRAIGO LOS TIPOS DE LAS CLASES ----------------- */

		$querytipo = "SELECT PERTIPO, PERTIPDESESP FROM PER_TIPO WHERE ESTCODIGO<>3 AND PERTIPO = $pertipo ";
		$Tabletipo = sql_query($querytipo,$conn);
		$row = $Tabletipo->Rows[0];
		$pertipo = $row['PERTIPDESESP'];

		/* ------------------------------- END SECTION ------------------------------ */

		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('perclase'	, $perclase);
		$tmpl->setVariable('perclades'	, $perclades);
		$tmpl->setVariable('pertipo'	, $pertipo);
		$tmpl->parse('browser');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
