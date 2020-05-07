<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';		
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('mst.html');
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$spkreg = (isset($_POST['spkreg']))? trim($_POST['spkreg']) : 0;
	$estcodigo = 1; //Activo por defecto
	$spktitulo = '';
	$spkpp = '';
	
	$imgnull = '../app-assets/img/pages/sativa.png';
	$tmpl->setVariable('imgnull', $imgnull );
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	if($spkreg!=0){
		$query = "	SELECT SPKREG, SPKNOMBRE, SPKDESCRI, SPKIMG, SPKPOS, ESTCODIGO,SPKEMPRES,SPKCARGO,SPKLINKED
					FROM SPK_MAEST
					WHERE SPKREG=$spkreg";
		
		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$spkreg 	= trim($row['SPKREG']);
			$spktitulo 	= trim($row['SPKNOMBRE']);
			$spkdescri 	= trim($row['SPKDESCRI']);
			$spkimg  	= trim($row['SPKIMG']);
			$spkpos 	= trim($row['SPKPOS']);
			$estcodigo 	= trim($row['ESTCODIGO']);
			$spkempres 	= trim($row['SPKEMPRES']);
			$spkcargo 	= trim($row['SPKCARGO']);
			$spklinked 	= trim($row['SPKLINKED']);
			
			$tmpl->setVariable('spkreg'		, $spkreg		);
			$tmpl->setVariable('spktitulo'	, $spktitulo	);
			$tmpl->setVariable('spkdescri'	, $spkdescri	);
			$tmpl->setVariable('spkpos'		, $spkpos		);
			$tmpl->setVariable('spkimg'		, $spkimg		);
			$tmpl->setVariable('estcodigo'	, $estcodigo	);
			$tmpl->setVariable('spkempres'	, $spkempres	);
			$tmpl->setVariable('spkcargo'	, $spkcargo		);
			$tmpl->setVariable('spklinked'	, $spklinked	);

			if($spkpp==$spkpos){
				$tmpl->setVariable('poselected', 'selected' );
			}
		}
	}
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
