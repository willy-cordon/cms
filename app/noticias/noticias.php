
<?

	//--------------------------------------------------------------------------------------------------------------
	include ('../../func/zglobals.php');
	include ('../../func/sigma.php');	
	include ('../../func/zdatabase.php');
    include ('../../func/zfvarias.php');
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('noticias.html');
	

	
	$conn= sql_conectar();//Apertura de Conexion
	
	
	$pathimg = '../../avimg';
	

	
	//Traigo todas las noticias
	$query="SELECT AVIREG, AVITITULO, AVIDESCRIP, AVIIMAGEN, AVIURL 
			FROM AVI_MAEST
			WHERE ESTCODIGO=1
			ORDER BY AVIREG DESC";
	$Table=sql_query($query,$conn);
	
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$avireg 	= trim($row['AVIREG']);
		$avititulo 	= trim($row['AVITITULO']);
		$avidescrip = trim($row['AVIDESCRIP']);
		$aviurl     = trim($row['AVIURL']);
		$aviimagen  = trim($row['AVIIMAGEN']);
		
		$tmpl->setCurrentBlock('noticias');
		$tmpl->setVariable('avireg'	, $avireg);
		$tmpl->setVariable('avititulo'	, $avititulo);
		$tmpl->setVariable('avidescrip'	, $avidescrip);
		$tmpl->setvariable('aviurl', $aviurl);
		$tmpl->setVariable('aviimagen'	, $pathimg.'/'.$avireg.'/'.$aviimagen);
		$tmpl->parse('noticias');
	}
	
	$tmpl->show();
	
?>	
