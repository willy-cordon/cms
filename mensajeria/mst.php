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
	$msgreg = (isset($_POST['msgreg']))? trim($_POST['msgreg']) : 0;
	$msgestado = 1; //Activo por defecto
	$secdescri = '';
	
	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	if($msgreg!=0){
		$query = "SELECT MSGREG, MSGFCHREG, MSGTITULO, MSGDESCRI, MSGESTADO,MSGDATE,MSGTIME,MSGPER,MSGIDIOMA
					FROM MSG_CABE
					WHERE MSGREG=$msgreg";

		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$msgreg = trim($row['MSGREG']);
			$msgfchreg = trim($row['MSGFCHREG']);
			$msgtitulo = trim($row['MSGTITULO']);
			$msgdescri = trim($row['MSGDESCRI']);
			$msgestasdo = trim($row['MSGESTADO']);
			$msgdate 	= trim($row['MSGDATE']);
			$msgtime 	= trim($row['MSGTIME']);
			$msgper 	= trim($row['MSGPER']);
			$msgidioma 	= trim($row['MSGIDIOMA']);

			$tmpl->setVariable('msgreg'	, $msgreg	);
			$tmpl->setVariable('msgfchreg'	, $msgfchreg	);
			$tmpl->setVariable('msgtitulo'	, $msgtitulo	);
			$tmpl->setVariable('msgdescri'	, $msgdescri	);
			$tmpl->setVariable('msgestado'	, $msgestado	);
			$tmpl->setVariable('msgdate'	, $msgdate	);
			$tmpl->setVariable('msgtime'	, $msgtime	);

			$tmpl->setVariable('msgper'.$msgper	, 'Selected'	);
			$tmpl->setVariable('msgidioma'.$msgidioma	, 'Selected'	);
			
		}
	}
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
