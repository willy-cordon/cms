<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('mst.html');
//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------
$expreg = (isset($_POST['expreg'])) ? trim($_POST['expreg']) : 0;
$estcodigo = 1; //Activo por defecto el estado
$pathPlano = '../app/';

$pathimagenes = '../expimg/';
$imgAvatarNull = '../app-assets/img/avatar.png';
$tmpl->setVariable('expavatar', $imgAvatarNull);
$tmpl->setVariable('imgAvatarNull', $imgAvatarNull);
//--------------------------------------------------------------------------------------------------------------
$conn = sql_conectar(); //Apertura de Conexion
$percodexp = 0;

//Busco el parametro del nombre del plano
$query  = "	SELECT PARVALOR FROM PAR_MAEST WHERE PARCODIGO='NameImagePlano' ";
$Table 	= sql_query($query, $conn);
$tmpl->setVariable('urlpathplano', $pathPlano . $Table->Rows[0]['PARVALOR']);


$query = "	SELECT *
				FROM EXP_MAEST 
				WHERE EXPREG=$expreg  ";

$Table = sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$expreg 	= trim($row['EXPREG']);
	$expnombre 	= trim($row['EXPNOMBRE']);
	$expweb 	= trim($row['EXPWEB']);
	$expmail 	= trim($row['EXPMAIL']);
	$expstand 	= trim($row['EXPSTAND']);
	$exprubros 	= trim($row['EXPRUBROS']);
	$expposx 	= trim($row['EXPPOSX']);
	$expposy 	= trim($row['EXPPOSY']);
	$estcodigo 	= trim($row['ESTCODIGO']);
	$expavatar 	= trim($row['EXPAVATAR']);
	$expcatego 	= trim($row['EXPCATEGO']);
	$percodexp 	= trim($row['PERCODIGO']);
	$exppos 	= trim($row['EXPPOS']);

	//NUEVOSDATOS
	$explinded	 	= trim($row['EXPLINKED']);
	$expfac 		= trim($row['EXPFAC']);
	$exptwi 		= trim($row['EXPTWI']);
	$expdireccion 	= trim($row['EXPDIRECCION']);
	$exptelefo 		= trim($row['EXPTELEFO']);
	$expbanimg 		= trim($row['EXPBANIMG']);
	$expyoutub 		= trim($row['EXPYOUTUB']);
	$exptitulo1 	= trim($row['EXPTITULO1']);
	$exptitulo2 	= trim($row['EXPTITULO2']);
	$exptitulo3 	= trim($row['EXPTITULO3']);
	$exptxt1 		= trim($row['EXPTXT1']);
	$exptxt2 		= trim($row['EXPTXT2']);
	$exptxt3 		= trim($row['EXPTXT3']);
	$expvid1 		= trim($row['EXPVID1']);
	$expvid2		= trim($row['EXPVID2']);
	$expvid3 		= trim($row['EXPVID3']);
	$expbanimg1 	= trim($row['EXPBANIMG1']);
	$expbanimg2 	= trim($row['EXPBANIMG2']);
	$expbanimg3 	= trim($row['EXPBANIMG3']);
	$expimg1 	= trim($row['EXPIMG1']);
	$expimg2 	= trim($row['EXPIMG2']);
	$expimg3 	= trim($row['EXPIMG3']);



	if ($expavatar == '') {
		$expavatar = $imgAvatarNull;
	} else {
		$expavatar = $pathimagenes . $expreg . '/' . $expavatar;
		$expbanimg = $pathimagenes . $expreg . '/' . $expbanimg;
	}

	//Asignamos los datos para cargar desde el templatee
	$tmpl->setCurrentBlock('browser');
	$tmpl->setVariable('expreg', $expreg);
	$tmpl->setVariable('expnombre', $expnombre);
	$tmpl->setVariable('expweb', $expweb);
	$tmpl->setVariable('expmail', $expmail);
	$tmpl->setVariable('expstand', $expstand);
	$tmpl->setVariable('exprubros', $exprubros);
	$tmpl->setVariable('expposx', $expposx);
	$tmpl->setVariable('expposy', $expposy);
	$tmpl->setVariable('estcodigo', $estcodigo);
	$tmpl->setVariable('expavatar', $expavatar);
	$tmpl->setVariable('expcatego', $expcatego);
	$tmpl->setVariable('orden', $exppos);
	$tmpl->setVariable('explinked', $explinded);
	$tmpl->setVariable('expfac', $expfac);
	$tmpl->setVariable('exptwi', $exptwi);
	$tmpl->setVariable('expdireccion', $expdireccion);
	$tmpl->setVariable('exptelefo', $exptelefo);
	$tmpl->setVariable('expbanimg', $expbanimg);
	$tmpl->setVariable('expyoutub', $expyoutub);
	$tmpl->setVariable('exptitulo1', $exptitulo1);
	$tmpl->setVariable('exptitulo2', $exptitulo2);
	$tmpl->setVariable('exptitulo3', $exptitulo3);
	$tmpl->setVariable('exptxt1', $exptxt1);
	$tmpl->setVariable('exptxt2', $exptxt2);
	$tmpl->setVariable('exptxt3', $exptxt3);
	$tmpl->setVariable('expvid1', $expvid1);
	$tmpl->setVariable('expvid2', $expvid2);
	$tmpl->setVariable('expvid3', $expvid3);
	$tmpl->parse('browser');
}

//Seleccionamos los rubros
$query = "	SELECT PERCOMPAN,PERNOMBRE,PERAPELLI,PERCODIGO
				FROM PER_MAEST 
				WHERE ESTCODIGO=1
				ORDER BY PERCOMPAN ";
$Table = sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$percod 	= trim($row['PERCODIGO']);
	$pernombre	= trim($row['PERNOMBRE']);
	$perapelli	= trim($row['PERAPELLI']);
	$percompan	= trim($row['PERCOMPAN']);


	$tmpl->setCurrentBlock('perfiles');
	$tmpl->setVariable('percodigo', $percod);
	$tmpl->setVariable('pernombre', $pernombre);
	$tmpl->setVariable('perapelli', $perapelli);
	$tmpl->setVariable('percompan', $percompan);
	if ($percodexp == $percod) {
		$tmpl->setVariable('persel', 'selected');
	}
	$tmpl->parse('perfiles');
}


//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();

?>	
