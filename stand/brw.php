<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/idioma.php'; //Idioma	


$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('brw.html');
//Diccionario de idiomas
DDIdioma($tmpl);


//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------
$percodlog 	= (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$pernombre 	= (isset($_SESSION[GLBAPPPORT . 'PERNOMBRE'])) ? trim($_SESSION[GLBAPPPORT . 'PERNOMBRE']) : '';
$perapelli 	= (isset($_SESSION[GLBAPPPORT . 'PERAPELLI'])) ? trim($_SESSION[GLBAPPPORT . 'PERAPELLI']) : '';
$perusuacc 	= (isset($_SESSION[GLBAPPPORT . 'PERUSUACC'])) ? trim($_SESSION[GLBAPPPORT . 'PERUSUACC']) : '';
$percorreo 	= (isset($_SESSION[GLBAPPPORT . 'PERCORREO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCORREO']) : '';
$perusareu 	= (isset($_SESSION[GLBAPPPORT . 'PERUSAREU'])) ? trim($_SESSION[GLBAPPPORT . 'PERUSAREU']) : '';
$pertipolog = (isset($_SESSION[GLBAPPPORT . 'PERTIPO'])) ? trim($_SESSION[GLBAPPPORT . 'PERTIPO']) 	  : '';
$perclaselog = (isset($_SESSION[GLBAPPPORT . 'PERCLASE'])) ? trim($_SESSION[GLBAPPPORT . 'PERCLASE'])   : '';

$pathimagenes = '../expimg/';
$imgAvatarNull = '../app-assets/img/avatar.png';



$conn = sql_conectar(); //Apertura de Conexion

$query = "SELECT *
FROM EXP_MAEST
WHERE ESTCODIGO<>3";
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
	$expcatego 	= trim($row['EXPCATEGO']);
	$expavatar 	= trim($row['EXPAVATAR']);
	$expbanimg1 = trim($row['EXPBANIMG1']);
	$expbanimg2 = trim($row['EXPBANIMG2']);

	if ($expavatar == '') {
		$expavatar = $imgAvatarNull;
	} else {
		$expavatar = $pathimagenes . $expreg . '/' . $expavatar;
	}




	switch ($expcatego) {
		case 'Gold':
			logerror('oro');
			$tmpl->setCurrentBlock('oro');
			$tmpl->setVariable('exprego', $expreg);
			$tmpl->setVariable('expnombreo', $expnombre);
			$tmpl->setVariable('expwebo', $expweb);
			$tmpl->setVariable('expmailo', $expmail);
			$tmpl->setVariable('exprubroso', $exprubros);
			$tmpl->setVariable('expposxo', $expposx);
			$tmpl->setVariable('expposyo', $expposy);
			$tmpl->setVariable('expcategoo', $expcatego);
			$tmpl->setVariable('expavataro', $expavatar);
			$tmpl->setVariable('expbanimg1o', '../expimg/' . $expreg . '/' . $expbanimg1);
			$tmpl->setVariable('expbanimg2o', '../expimg/' . $expreg . '/' . $expbanimg2);
			$tmpl->parse('oro');
			break;

		case 'Silver':
			logerror('silver');
			$tmpl->setCurrentBlock('silver');
			$tmpl->setVariable('expregs', $expreg);
			$tmpl->setVariable('expnombres', $expnombre);
			$tmpl->setVariable('expwebs', $expweb);
			$tmpl->setVariable('expmails', $expmail);
			$tmpl->setVariable('exprubross', $exprubros);
			$tmpl->setVariable('expposxs', $expposx);
			$tmpl->setVariable('expposys', $expposy);
			$tmpl->setVariable('expcategos', $expcatego);
			$tmpl->setVariable('expavatars', $expavatar);
			$tmpl->setVariable('expbanimg1s', '../expimg/' . $expreg . '/' . $expbanimg1);
			$tmpl->setVariable('expbanimg2s', '../expimg/' . $expreg . '/' . $expbanimg2);

			$tmpl->parse('silver');
			break;


		case 'Bronze':
			logerror('bronze');
			$tmpl->setCurrentBlock('bronze');
			$tmpl->setVariable('expregb', $expreg);
			$tmpl->setVariable('expnombreb', $expnombre);
			$tmpl->setVariable('expwebb', $expweb);
			$tmpl->setVariable('expmailb', $expmail);
			$tmpl->setVariable('exprubrosb', $exprubros);
			$tmpl->setVariable('expposxb', $expposx);
			$tmpl->setVariable('expposyb', $expposy);
			$tmpl->setVariable('expcategob', $expcatego);
			$tmpl->setVariable('expavatarb', $expavatar);
			$tmpl->setVariable('expbanimg1b', '../expimg/' . $expreg . '/' . $expbanimg1);
			$tmpl->setVariable('expbanimg2b', '../expimg/' . $expreg . '/' . $expbanimg2);

			$tmpl->parse('bronze');
			break;
		default:

			break;
	}
}


sql_close($conn);
$tmpl->show();

?>	




