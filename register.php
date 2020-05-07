<?
	if(!isset($_SESSION))  session_start();
	// include($_SERVER["DOCUMENT_ROOT"].'/webcoordinador/func/zglobals.php'); //DEV
	include($_SERVER["DOCUMENT_ROOT"].'/func/zglobals.php'); //PRD
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/class.phpmailer.php';
	require_once GLBRutaFUNC.'/class.smtp.php';
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('register.html');
	
	$conn= sql_conectar();//Apertura de Conexion
	
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
		
		/*if($paicodigo==$paicod){
			$tmpl->setVariable('paiselected', 'selected' );
		}*/
		
		$tmpl->parseCurrentBlock();
	}
	//--------------------------------------------------------------------------------------------------------------
	
	sql_close($conn);
	
	
	//--------------------------------------------------------------------------------------------------------------
	$tmpl->show();
	
?>	
