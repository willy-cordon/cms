<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('brwprofile.html');
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

	//DATOS DE CONTACTO
	$expreg 		= trim($row['EXPREG']);
	$expnombre 		= trim($row['EXPNOMBRE']);
	$expdireccion 	= trim($row['EXPDIRECCION']);
	$exptelefo 		= trim($row['EXPTELEFO']);
	$expmail 		= trim($row['EXPMAIL']);
	$expweb 		= trim($row['EXPWEB']);
	$explinked 		= trim($row['EXPLINKED']);
	$expfac 		= trim($row['EXPFAC']);
	$exptwi 		= trim($row['EXPTWI']);
	$expyoutub 		= trim($row['EXPYOUTUB']);

	//Avatar
	$expavatar 	= trim($row['EXPAVATAR']);

	//categoria
	$expcatego 	= trim($row['EXPCATEGO']);

	//sobre la empresa
	$exprubros 	= trim($row['EXPRUBROS']);

	//datos del evento
	$expstand 	= trim($row['EXPSTAND']);
	$expposx 	= trim($row['EXPPOSX']);
	$expposy 	= trim($row['EXPPOSY']);
	$estcodigo 	= trim($row['ESTCODIGO']);
	$percodexp 	= trim($row['PERCODIGO']);
	$exppos 	= trim($row['EXPPOS']);

	//BANNER
	$expbanimg1 	= trim($row['EXPBANIMG1']);
	//imagenes
	$expimg1 	= trim($row['EXPIMG1']);
	$expimg2 	= trim($row['EXPIMG2']);
	$expimg3 	= trim($row['EXPIMG3']);

	//TEXTO
	$exptitulo1 	= trim($row['EXPTITULO1']);
	$exptitulo2		= trim($row['EXPTITULO2']);
	$exptitulo3 	= trim($row['EXPTITULO3']);
	$exptxt1 		= trim($row['EXPTXT1']);
	$exptxt2		= trim($row['EXPTXT2']);
	$exptxt3 		= trim($row['EXPTXT3']);

	//video
	$expvid1 		= trim($row['EXPVID1']);
	$expvid2 		= trim($row['EXPVID2']);
	$expvid3 		= trim($row['EXPVID3']);


	//



	if ($expavatar == '') {
		$expavatar = $imgAvatarNull;
	} else {
		$expavatar = $pathimagenes . $expreg . '/' . $expavatar;
	}

	//Asignamos los datos para cargar desde el templatee
	$tmpl->setCurrentBlock('browser');
	$tmpl->setVariable('expreg', $expreg);
	$tmpl->setVariable('expstand', $expstand);
	$tmpl->setVariable('exprubros', $exprubros);
	$tmpl->setVariable('expposx', $expposx);
	$tmpl->setVariable('expposy', $expposy);
	$tmpl->setVariable('estcodigo', $estcodigo);
	$tmpl->setVariable('expavatar', $expavatar);
	$tmpl->setVariable('expcatego', $expcatego);
	$tmpl->setVariable('orden', $exppos);
	$tmpl->setVariable('expbanimg1', $pathimagenes . $expreg . '/' . $expbanimg1);

	//contacto
	$tmpl->setVariable('expnombre', $expnombre);
	$tmpl->setVariable('expdireccion', $expdireccion);
	$tmpl->setVariable('exptelefo', $exptelefo);
	$tmpl->setVariable('expweb', $expweb);
	$tmpl->setVariable('expmail', $expmail);
	$tmpl->setVariable('explinked', $explinked);
	$tmpl->setVariable('exptwi', $exptwi);
	$tmpl->setVariable('expfac', $expfac);
	$tmpl->setVariable('expyoutub', $expyoutub);

	//video
	$tmpl->setVariable('expvid1', $expvid1);
	$tmpl->setVariable('expvid2', $expvid2);
	$tmpl->setVariable('expvid3', $expvid3);


	//imagenes
	$tmpl->setVariable('expimg1', $pathimagenes . $expreg . '/' . $expimg1);
	$tmpl->setVariable('expimg2', $pathimagenes . $expreg . '/' .  $expimg2);
	$tmpl->setVariable('expimg3', $pathimagenes . $expreg . '/' . $expimg3);





	//texto
	$tmpl->setVariable('exptitulo1', $exptitulo1);
	$tmpl->setVariable('exptitulo2', $exptitulo2);
	$tmpl->setVariable('exptitulo3', $exptitulo3);
	$tmpl->setVariable('exptxt1', $exptxt1);
	$tmpl->setVariable('exptxt2', $exptxt2);
	$tmpl->setVariable('exptxt3', $exptxt3);


	$tmpl->parse('browser');
}




//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();

?>	
