<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('brwmenu.html');
	
	//Diccionario de idiomas
	$conn= sql_conectar();//Apertura de Conexion
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$pertipo = (isset($_POST['pertipo']))? trim($_POST['pertipo']) : '';


	
	$tmpl->setVariable('pertipoBrw',$pertipo);
	

	/* ---------------------------- ANCHOR SET TITULO --------------------------- */
	$querytitulo = "SELECT PERTIPDESESP, PERTIPDESING FROM PER_TIPO WHERE PERTIPO = $pertipo";
	$TableTitulo =  sql_query($querytitulo,$conn); 
	$row =$TableTitulo->Rows[0];
	$pertipdesesp = trim($row['PERTIPDESESP']);
	$tmpl->setVariable('pertipdesesp',$pertipdesesp);

	/* ------------------------------------ - ----------------------------------- */


	/* - ANCHOR TRAIGO TODOS LOS ID´S DE MENU QUE CONTIENE EL TIPO SELECCIONADO - */

	$query  = "SELECT PERTIPO, MENCODIGO FROM  PER_TIPO_MENU WHERE PERTIPO = $pertipo";

	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$pertipo 	= trim($row['PERTIPO']);
		$mencodigo 	= trim($row['MENCODIGO']);

		/* ----------------- ANCHOR TRAIGO TODOS LOS NOMBRE DE MENU ----------------- */

		$querymenu	= "SELECT MENDESCRI, MENCODIGO FROM MEN_MAEST WHERE MENCODIGO = $mencodigo";
		$TableMenu	= sql_query($querymenu,$conn);
		$rowmenu	= $TableMenu->Rows[0];
		$mendescri	= $rowmenu['MENDESCRI'];
		$mencodigo 	= $rowmenu['MENCODIGO'];
		
		$tmpl->setCurrentBlock('menu');
		$tmpl->setVariable('pertipo'	, $pertipo);
		$tmpl->setVariable('mendescri'	, $mendescri);
		$tmpl->setVariable('mencodigo'	, $mencodigo);
		$tmpl->parse('menu');
		//logerror($mencodigo);
		
	}

	/* ------------------------------------ - ----------------------------------- */
	

	
	
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
