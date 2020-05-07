<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('mst.html');

	//Diccionario de idiomas
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$pitcodigo = (isset($_POST['pitcodigo']))? trim($_POST['pitcodigo']) : 0;
	$estcodigo = 1; //Activo por defecto
	$secdescri = '';
	
	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	if($pitcodigo!=0){
		$query = "	SELECT PITCODIGO, PITNOMBRE,PITDES, PERCODIGO,PITCON, PITIMG
					FROM PIT_MAEST
					WHERE PITCODIGO=$pitcodigo";

		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$pitcodigo = trim($row['PITCODIGO']);
			$pitnombre = trim($row['PITNOMBRE']);
			$pitdes = trim($row['PITDES']);
			$percodigo = trim($row['PERCODIGO']);
			$pitcon = trim($row['PITCON']);
			$pitimg = trim($row['PITIMG']);
			
			$tmpl->setVariable('pitcodigo'	, $pitcodigo	);
			$tmpl->setVariable('pitnombre'	, $pitnombre	);
			$tmpl->setVariable('pitdes'		, $pitdes		);
			$tmpl->setVariable('percodigo'	, $percodigo	);
			$tmpl->setVariable('pitcon'		, $pitcon	);
			$tmpl->setVariable('pitimg'		,	"../pitimg/".$pitcodigo.'/'.$pitimg	);
			
		}
	}else{
	$percodigo = 0;
	}


	$query = "	SELECT PERCOMPAN,PERNOMBRE,PERAPELLI,PERCODIGO
	FROM PER_MAEST 
	ORDER BY PERCOMPAN ";			
$Table = sql_query($query,$conn);
for($i=0; $i<$Table->Rows_Count; $i++){
$row = $Table->Rows[$i];
$percod 	= trim($row['PERCODIGO']);
$pernombre	= trim($row['PERNOMBRE']);
$perapelli	= trim($row['PERAPELLI']);
$percompan	= trim($row['PERCOMPAN']);


$tmpl->setCurrentBlock('perfiles');
$tmpl->setVariable('percodigo'	, $percod 		);
$tmpl->setVariable('pernombre'	, $pernombre	);
$tmpl->setVariable('perapelli'	, $perapelli	);
$tmpl->setVariable('percompan'	, $percompan	);
if($percodigo==$percod){
$tmpl->setVariable('persel'		, 'selected' 	);
}
$tmpl->parse('perfiles');

}
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
