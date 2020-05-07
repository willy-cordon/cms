<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('vslenc.html');
	
	//Diccionario de idiomas
	DDIdioma($tmpl);
	
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodlog = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	//--------------------------------------------------------------------------------------------------------------
	$reureg = (isset($_POST['reureg']))? trim($_POST['reureg']) : 0;
	
	$tmpl->setVariable('reureg'	, $reureg);
	
	$conn= sql_conectar();//Apertura de Conexion
	
	$query = "	SELECT EP.ENCREG, EC.ENCREG, EC.ENCDESCRI,EP.ENCPREITM, EP.ENCPREGUN, EP.ENCPRETIP, EP.ENCPREVAL
				FROM ENC_CABE EC
				LEFT OUTER JOIN ENC_PREG EP ON EP.ENCREG=EC.ENCREG
				WHERE EC.ESTCODIGO=1 AND EC.ENCPUBLIC='S' ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$encreg 	= trim($row['ENCREG']);
		$encpreitm 	= trim($row['ENCPREITM']);
		$encdescri 	= trim($row['ENCDESCRI']);
		$encpretip 	= trim($row['ENCPRETIP']);
		logerror($encpretip);
		$encpregun 	= trim($row['ENCPREGUN']);
		
		$encpreval 	= trim($row['ENCPREVAL']);
		
		$tmpl->setCurrentBlock('preguntas');
		
		if ($encpretip==1) {
			$tmpl->setVariable('pregtip1'	, 'display:visible');
			$tmpl->setVariable('pregtip2'	, 'display:none');
			$tmpl->setVariable('pregtip3'	, 'display:none');
		}
		if($encpretip==2){
			$tmpl->setVariable('pregtip1'	, 'display:none');
			$tmpl->setVariable('pregtip2'	, 'display:visible');
			$tmpl->setVariable('pregtip3'	, 'display:none');
			$vopciones= explode(",",$encpreval);
			
			foreach ($vopciones as $key => $value) {
				$tmpl->setCurrentBlock('preval');
				$tmpl->setVariable('encpreval'	, $value);
				$tmpl->parse('preval');
			}
		}
		if ($encpretip==3) {
			$tmpl->setVariable('pregtip1'	, 'display:none');
			$tmpl->setVariable('pregtip2'	, 'display:none');
			$tmpl->setVariable('pregtip3'	, 'display:visible');
			
			$tmpl->setCurrentBlock('jsclasificar');
			$tmpl->setVariable('encpreitmcla', $encpreitm);
			$tmpl->setVariable('encpreval'	, $encpreval);
			$tmpl->parse('jsclasificar');
			
		}
			
		
		$tmpl->setVariable('encreg'	, $encreg);
		$tmpl->setVariable('encdescri'	, $encdescri);
		
		$tmpl->setVariable('encpreitm'	, $encpreitm);
		$tmpl->setVariable('encpregun'	, $encpregun);
		
		$tmpl->parse('preguntas');
	}



	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	

?>	
