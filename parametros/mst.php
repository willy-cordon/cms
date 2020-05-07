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
	$parreg = (isset($_POST['parreg']))? trim($_POST['parreg']) : 0;
	
	$estcodigo 	= 1; //Activo por defecto
	$parcodigo 	= '';
	$parvalor 	= '';
	$parorden 	= '';
	$pertipo = '';
	$imgnull = '../app-assets/img/pages/sativa.png';
	$pathimg = '../app/';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	if($parreg!=0){
		$query = "	SELECT PARREG,PARCODIGO,PARVALOR,PARORDEN,PARTIPO,PERTIPO
					FROM PAR_MAEST
					WHERE PARREG=$parreg";
		
		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$parreg 	= trim($row['PARREG']);
			$parcodigo 	= trim($row['PARCODIGO']);
			$parvalor  	= trim($row['PARVALOR']);
			$parorden   = trim($row['PARORDEN']);
			$partipo   = trim($row['PARTIPO']);
			$pertipo   = trim($row['PERTIPO']);
			
			$tmpl->setVariable('parreg' 	, $parreg);
			$tmpl->setVariable('parcodigo' 	, $parcodigo);
			$tmpl->setVariable('parvalor'  	, $parvalor);
			$tmpl->setvariable('parorden'   , $parorden);
			if($parvalor!=''){
				$tmpl->setVariable('parimg'		, $pathimg.'/'.$parvalor );
			}else{
				$tmpl->setVariable('parimg', $imgnull );
			}
			$tmpl->setVariable('partipo' 	, $partipo);
			
			$tmpl->setVariable('parcodigo'.$parcodigo 	, 'selected');
			$tmpl->setVariable('partipo'.$partipo 	, 'selected');
			$tmpl->setvariable('orden'.$parorden   , 'selected');

		}
	}else{
		$tmpl->setVariable('parimg', $imgnull );
	}
	
	//--------------------------------------------------------------------------------------------------------------
	//Tipo de Perfiles
	$query = "SELECT PERTIPO,PERTIPDES$IdiomView AS PERTIPDES
				FROM PER_TIPO			
				ORDER BY PERTIPO";

	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$pertipcod 	= trim($row['PERTIPO']);
		$pertipdes	= trim($row['PERTIPDES']);
			
		$tmpl->setCurrentBlock('pertipos');
		$tmpl->setVariable('pertipcod'	, $pertipcod 		);
		$tmpl->setVariable('pertipdes'	, $pertipdes	);
		
		if($pertipo==$pertipcod){
			$tmpl->setVariable('pertipsel', 'selected' );
		}
		
		$tmpl->parse('pertipos');

	}
	//--------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------
	

	sql_close($conn);	
	$tmpl->show();
	
?>	
