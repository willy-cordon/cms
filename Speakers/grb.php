<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';

//--------------------------------------------------------------------------------------------------------------
$pathimagenes = '../spkimg/';
if (!file_exists($pathimagenes)) {
	mkdir($pathimagenes);
}

//--------------------------------------------------------------------------------------------------------------
$errcod = 0;
$errmsg = '';
$err 	= 'SQLACCEPT';

//--------------------------------------------------------------------------------------------------------------
$conn = sql_conectar(); //Apertura de Conexion
$trans	= sql_begin_trans($conn);

//Control de Datos
$spkreg 		= (isset($_POST['spkreg']))	  	? trim($_POST['spkreg']) 	: 0;
$spktitulo 		= (isset($_POST['spktitulo']))	? trim($_POST['spktitulo']) : '';
$spkdescri 		= (isset($_POST['spkdescri'])) 	? trim($_POST['spkdescri']) : '';
$spkpos 		= (isset($_POST['spkpos']))		? trim($_POST['spkpos']) 	: '';
$spkempres 		= (isset($_POST['spkempres']))	? trim($_POST['spkempres']) : '';
$spkcargo 		= (isset($_POST['spkcargo']))	? trim($_POST['spkcargo']) 	: '';
$spkalf 		= (isset($_POST['spkoption']))	? trim($_POST['spkoption']) 	: '';
$spklinked 		= (isset($_POST['spklinked']))	? trim($_POST['spklinked']) 	: '';

$estcodigo 		= 1;

if ($spkreg == '') {
	$spkreg = 0;
}

if ($errcod == 2) {
	echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';
	exit;
}

//--------------------------------------------------------------------------------------------------------------
$spkreg			= VarNullBD($spkreg, 'N');
$spktitulo		= VarNullBD($spktitulo, 'S');
$spkdescri      = VarNullBD($spkdescri, 'S');
$spkpos         = VarNullBD($spkpos, 'N');
$estcodigo		= VarNullBD($estcodigo, 'N');
$spkempres      = VarNullBD($spkempres, 'S');
$spkcargo      	= VarNullBD($spkcargo, 'S');
$spklinked      	= VarNullBD($spklinked, 'S');


if ($spkalf > 0) {
	$spkpos = 0;
}
if ($spkreg == 0) {
	//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
	//Genero un ID 
	$query 		= 'SELECT GEN_ID(G_SPEAKERS,1) AS ID FROM RDB$DATABASE';
	$TblId		= sql_query($query, $conn, $trans);
	$RowId		= $TblId->Rows[0];
	$spkreg 	= trim($RowId['ID']);
	//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

	$query = " 	INSERT INTO SPK_MAEST (SPKREG,SPKNOMBRE,SPKDESCRI,SPKPOS,ESTCODIGO,SPKEMPRES,SPKCARGO,SPKLINKED)
					VALUES ($spkreg,$spktitulo,$spkdescri,$spkpos,$estcodigo,$spkempres,$spkcargo, $spklinked) ";
} else {
	$query = " 	UPDATE SPK_MAEST SET 
					SPKREG=$spkreg,SPKNOMBRE=$spktitulo,SPKDESCRI=$spkdescri, SPKPOS=$spkpos, ESTCODIGO=$estcodigo,
					SPKEMPRES=$spkempres,SPKCARGO=$spkcargo, SPKLINKED=$spklinked 
					WHERE SPKREG=$spkreg ";
}
//logerror($query);
//die();
$err = sql_execute($query, $conn, $trans);
date_default_timezone_set('UTC');
//--------------------------------------------------------------------------------------------------------------
if (isset($_FILES['spkimg'])) {

	$ext 	= pathinfo($_FILES['spkimg']['name'], PATHINFO_EXTENSION);
	//$name 	= 'SPKIMAGEN'.date(mktime(0, 0, 0, 7, 1, 2000)).'.'.$ext;
	$name 	= 'SPKIMAGEN' . date('His') . rand(100, 200) . '.' . $ext;  //$file['name'];

	if (!file_exists($pathimagenes . $spkreg)) {
		mkdir($pathimagenes . $spkreg);
	}
	if (file_exists($pathimagenes . $spkreg . '/' . $name)) {
		unlink($pathimagenes . $spkreg . '/' . $name);
	}
	move_uploaded_file($_FILES['spkimg']['tmp_name'], $pathimagenes . $spkreg . '/' . $name);

	$_SESSION[GLBAPPPORT . 'SPKIMG'] =  $pathimagenes . $spkreg . '/' . $name; //Actualizo la variable de Session del AVATAR

	$query = "	UPDATE SPK_MAEST SET SPKIMG='$name' WHERE SPKREG=$spkreg ";

	//-------------Redimension de imagen----------------------------------//
	$imagen_optimizada = redimensionar_imagen($name, $pathimagenes . $spkreg . '/' . $name, 200, 200);

	//Guardado de imagen
	imagepng($imagen_optimizada, $pathimagenes . $spkreg . '/' . $name);

	$err = sql_execute($query, $conn, $trans);
}
//--------------------------------------------------------------------------------------------------------------
if ($err == 'SQLACCEPT' && $errcod == 0) {
	sql_commit_trans($trans);
	$errcod = 0;
	$errmsg = 'Guardado correctamente!';
} else {
	sql_rollback_trans($trans);
	$errcod = 2;
	$errmsg = ($errmsg == '') ? 'Guardado correctamente!' : $errmsg;
}
//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';

?>	
