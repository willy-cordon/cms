<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma

	$tmpl= new HTML_Template_Sigma();
	$tmpl->loadTemplateFile('brwPreg.html');

	//Diccionario de idiomas
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$encreg = (isset($_POST['encreg']))? trim($_POST['encreg']) : 0;
	//logerror($encreg);
	//$fltdescri = (isset($_POST['fltdescri']))? trim($_POST['fltdescri']):'';

	// $where = '';
	// if($fltdescri!=''){
	// 	$where .= " AND ENCTITULO CONTAINING '$fltdescri' ";
	// }

	$conn= sql_conectar();//Apertura de Conexion

	
	$query="SELECT ENCREG,ENCPREITM,ENCPREGUN,ENCPRETIP,ENCPREVAL,ENCPREORD FROM ENC_PREG WHERE ENCREG=$encreg AND ESTCODIGO<>3 ORDER BY ENCPREORD ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		
		$encpregun 	= trim($row['ENCPREGUN']);
		$encpreitm 	= trim($row['ENCPREITM']);
		//logerror($encpreitm);
		$encpretip  = trim($row['ENCPRETIP']);
		$encpreval  = trim($row['ENCPREVAL']);
		$encpreord  = trim($row['ENCPREORD']);

		switch($encpretip){
			case 1: $encpretipdes='Libre'; break;
			case 2: $encpretipdes='Tabulado'; break;
			case 3: $encpretipdes='Clasificado'; break;
		}

		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('encpregun'		, $encpregun);
		$tmpl->setVariable('encpreitm'		, $encpreitm);
		$tmpl->setVariable('encpretip'		, $encpretip);
		$tmpl->setVariable('encpretipdes'	, $encpretipdes);
		$tmpl->setVariable('encpreval'		, $encpreval);
		$tmpl->setVariable('encpreord'		, $encpreord);
		$tmpl->parse('browser');
	}
	//Enviamos el cpdogp
	$tmpl->setVariable('encreg'	, $encreg);
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);
	$tmpl->show();

?>
