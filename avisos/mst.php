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
	$avireg = (isset($_POST['avireg']))? trim($_POST['avireg']) : 0;
	$estcodigo = 1; //Activo por defecto
	$avititulo = '';
	
	$imgnull = '../app-assets/img/pages/sativa.png';
	$tmpl->setVariable('imgnull', $imgnull );
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	if($avireg!=0){
		$query = "	SELECT AVIREG, AVITITULO, AVIDESCRIP, AVIIMAGEN, AVIURL, ESTCODIGO
					FROM AVI_MAEST
					WHERE AVIREG=$avireg";
		
		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$avireg = trim($row['AVIREG']);
			$avititulo = trim($row['AVITITULO']);
			$avidescrip = trim($row['AVIDESCRIP']);
			$aviimagen  = trim($row['AVIIMAGEN']);
			$aviurl = trim($row['AVIURL']);
			
			$tmpl->setVariable('avireg'	, $avireg	);
			$tmpl->setVariable('avititulo'	, $avititulo	);
			$tmpl->setVariable('avidescrip'	, $avidescrip	);
			$tmpl->setVariable('estcodigo'	, $estcodigo	);
			$tmpl->setVariable('aviurl',$aviurl);
			$tmpl->setVariable('aviimagen', $aviimagen);
		}
	}
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
