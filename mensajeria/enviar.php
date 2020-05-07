<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('enviar.html');
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodlog = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	//$msgreg = (isset($_SESSION[GLBAPPPORT.'MSGREG']))? trim($_SESSION[GLBAPPPORT.'MSGREG']) : '';
	$msgreg = (isset($_POST['msgreg']))? trim($_POST['msgreg']) : 0;
	$tipoenvio = (isset($_POST['tipoenvio']))? trim($_POST['tipoenvio']) : 0;
	
	
	
	
	$conn= sql_conectar();//Apertura de ConexionMSG

	$query="SELECT MSGREG, MSGTITULO, MSGDESCRI FROM MSG_CABE WHERE MSGREG=$msgreg";
	//logerror($query);
	$Table = sql_query($query,$conn);
	$row = $Table->Rows[0];
	$msgreg 	= trim($row['MSGREG']);
	$msgtitulo= trim($row['MSGTITULO']);
	//$msgdescri = trim($row['MSGDESCRI']);

	$tmpl->setVariable('msgreg'	, $msgreg);
	$tmpl->setVariable('msgtitulo'	, $msgtitulo);
	
	//TIpo de envio (1:Correo, 2:Notificacion)
	if($tipoenvio==1){
		$tmpl->setVariable('tipoenvio'	, 'Envio por Correo');
		$tmpl->setVariable('functionguardar'	, 'guardarMaestroCorreo');
	}else if($tipoenvio==2){
		$tmpl->setVariable('tipoenvio'	, 'Envio por Notificaciones');
		$tmpl->setVariable('functionguardar'	, 'guardarMaestroNotif');
	}

	
	//-----------------------------------------Filtro por tipo---------------------------------------------------------------------
	 $query = "SELECT PERTIPO,PERTIPDESESP AS PERTIPDES
	 			FROM PER_TIPO			
	 			ORDER BY PERTIPO";

	 $Table = sql_query($query,$conn);
	 for($i=0; $i<$Table->Rows_Count; $i++){
	 	$row = $Table->Rows[$i];
	 	$pertipcod 	= trim($row['PERTIPO']);
	 	$pertipdes	= trim($row['PERTIPDES']);
	
	 	$tmpl->setCurrentBlock('pertipos');
	 		$tmpl->setVariable('pertipcod'	, $pertipcod);
	 		$tmpl->setVariable('pertipdes'	, $pertipdes);
	 	$tmpl->parse('pertipos');
	
	 }

	//---------Filtro por clase ---------------------//
<<<<<<< .mine
	$query="SELECT PERCLASE,PERCLADES FROM PER_CLASE ORDER BY PERCLASE";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$perclacod 	= trim($row['PERCLASE']);
		$perclades	= trim($row['PERCLADES']);
		
		$tmpl->setCurrentBlock('perclase');
			$tmpl->setVariable('perclacod'	, $perclacod);
			$tmpl->setVariable('perclades'	, $perclades);
		$tmpl->parse('perclase');
	
	}
||||||| .r781
	//$query="SELECT PERCLASE,PERCLADES FROM PER_CLASE ORDER BY PERCLASE";
	//$Table = sql_query($query,$conn);
	//for($i=0; $i<$Table->Rows_Count; $i++){
	//	$row = $Table->Rows[$i];
	//	$perclacod 	= trim($row['PERCLASE']);
	//	$perclades	= trim($row['PERCLADES']);
	//	
	//	$tmpl->setCurrentBlock('perclase');
	//		$tmpl->setVariable('perclacod'	, $perclacod);
	//		$tmpl->setVariable('perclades'	, $perclades);
	//	$tmpl->parse('perclase');
	//
	//}
=======
	$query="SELECT C.PERCLASE,T.PERTIPDESESP||'-'||C.PERCLADES AS PERCLADES
			FROM PER_CLASE C 
			LEFT OUTER JOIN PER_TIPO T ON T.PERTIPO=C.PERTIPO
			ORDER BY T.PERTIPO,C.PERCLASE";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$perclacod 	= trim($row['PERCLASE']);
		$perclades	= trim($row['PERCLADES']);
		
		$tmpl->setCurrentBlock('perclases');
			$tmpl->setVariable('perclacod'	, $perclacod);
			$tmpl->setVariable('perclades'	, $perclades);
		$tmpl->parse('perclases');
	
	}
>>>>>>> .r788

	//---------Filtro por Idioma ---------------------//
	$query="SELECT PERIDIOMA FROM PER_MAEST ";
	$Table = sql_query($query,$conn);

	$row = $Table->Rows[0];
	$peridioma 	= trim($row['PERIDIOMA']);
	/**
	 * Condicional if Idioma
	 * 
	 * @if(idiomaSeleccionado)
	 */
	if ($peridioma == 'ing') {
		$tmpl->setVariable('idioma', 'selected' );
	}else{
		$tmpl->setVariable('idioma1', 'selected' );
	}
	sql_close($conn);	
	$tmpl->show();
	
	
	
	
?>	
