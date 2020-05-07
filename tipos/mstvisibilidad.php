<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/idioma.php'; //Idioma	

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('mstvisibilidad.html');
//Diccionario de idiomas
DDIdioma($tmpl);
//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------
$percodigo = (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$pernombre = (isset($_SESSION[GLBAPPPORT . 'PERNOMBRE'])) ? trim($_SESSION[GLBAPPPORT . 'PERNOMBRE']) : '';
$conn = sql_conectar(); //Apertura de Conexion
//Diccionario de idiomas


/* ----------- ANCHOR CLAVES PRIMARIAS PARA LA TABLA PERTIPO PERM ----------- */
$pertipo 			= (isset($_POST['pertipo']))	? trim($_POST['pertipo']) : '';
$pertipoori 		= (isset($_POST['pertipoori']))	? trim($_POST['pertipoori']) : '';
$perclaseori 		= (isset($_POST['perclaseori'])) ? trim($_POST['perclaseori']) : '';
$pertipoperm 		= (isset($_POST['pertipoperm'])) ? trim($_POST['pertipoperm']) : '';
$perclase 			= (isset($_POST['perclase']))	? trim($_POST['perclase']) : '';
$pertipodst 		= (isset($_POST['pertipodst']))	? trim($_POST['pertipodst']) : '';
$codclase 			= (isset($_POST['codclase']))	? trim($_POST['codclase']) : '';

logerror('PERCLASEORI: ' . $pertipoori);
$tmpl->setVariable('perclaseori', $perclaseori);
$tmpl->setVariable('codclase', $codclase);;

/* ------------------------------------ X ----------------------------------- */

/* ---------------------------- ANCHOR SET TITULO --------------------------- */

if ($pertipo != 0) {
$querytitulo = "SELECT PERTIPDESESP, PERTIPDESING FROM PER_TIPO WHERE PERTIPO = $pertipo";
$agregar = '';
}else{
$querytitulo = "SELECT PERTIPDESESP, PERTIPDESING FROM PER_TIPO WHERE PERTIPO = $pertipoori";
$agregar = 'Agregar';
}

$TableTitulo =  sql_query($querytitulo, $conn);
$row = $TableTitulo->Rows[0];
$pertipdesesp = trim($row['PERTIPDESESP']);
$tmpl->setVariable('pertipotittle', "Maestro de visibilidad para ". $pertipdesesp);
$TableTitulo =  sql_query($querytitulo, $conn);
$row = $TableTitulo->Rows[0];
$pertipdesesp = trim($row['PERTIPDESESP']);

$tmpl->setVariable('pertipotittle', $agregar. " Maestro de visibilidad para ". $pertipdesesp);
/* ----------------------------------- - ----------------------------------- */

//logerror('BRW VISIBILIDAD final '.$pertipo.' PERMCOD: '.$pertipoperm) ;

/* ------------- ANCHOR TIPOS DE PERFILES ------------ */


if ($pertipo == 0) {

	$queryclase = "SELECT PERCLASE, PERCLADES  FROM PER_CLASE WHERE PERTIPO = $pertipoori";
} else {
	$queryclase = "SELECT PERCLASE, PERCLADES  FROM PER_CLASE WHERE PERTIPO = $pertipo";
}

$Tableclase = sql_query($queryclase, $conn);

$tmpl->setCurrentBlock('clases');


for ($y = 0; $y < $Tableclase->Rows_Count; $y++) {

	$rowclase = $Tableclase->Rows[$y];
	$perclasenew 		= trim($rowclase['PERCLASE']);
	$perclades 	= trim($rowclase['PERCLADES']);


	if ($perclasenew == $codclase) {
		$tmpl->setVariable('checked', 'selected');
	}

	$tmpl->setVariable('perclases', $perclasenew);

	$tmpl->setVariable('percladess', $perclades);





	$tmpl->parse('clases');
}


if ($pertipo != 0) {
	$tmpl->setVariable('pertipoo', $pertipo);
	$tmpl->setVariable('pertipoperm', $pertipoperm);
	$query = "SELECT PERTIPO, PERTIPDESESP  FROM PER_TIPO";

	$Table = sql_query($query, $conn);

	$tmpl->setCurrentBlock('tipo');


	for ($i = 0; $i < $Table->Rows_Count; $i++) {

		$row = $Table->Rows[$i];
		$petiponew 	= trim($row['PERTIPO']);
		$pertipdesesp 	= trim($row['PERTIPDESESP']);



		$tmpl->setVariable('pertiposelected', $petiponew);

		$tmpl->setVariable('pertipdesesp', $pertipdesesp);


		if ($pertipodst == $petiponew) {
			$tmpl->setVariable('checked', 'selected');
		}


		$tmpl->parse('tipo');
	}

	/* ------------------------------------ x ----------------------------------- */


	/* ------------- ANCHOR TIPOS CLASES LIGADOS AL TIPO DE PERFIL ------------ */



	$queryclase = "SELECT PERCLASE, PERCLADES  FROM PER_CLASE WHERE PERTIPO = $pertipodst";

	$Tableclase = sql_query($queryclase, $conn);

	$tmpl->setCurrentBlock('clase');


	for ($y = 0; $y < $Tableclase->Rows_Count; $y++) {

		$rowclase = $Tableclase->Rows[$y];
		$perclasenew 		= trim($rowclase['PERCLASE']);
		$perclades 	= trim($rowclase['PERCLADES']);

		if ($perclase == $perclasenew) {
			$tmpl->setVariable('checked', 'selected');
		}

		$tmpl->setVariable('perclase', $perclasenew);

		$tmpl->setVariable('perclades', $perclades);





		$tmpl->parse('clase');
	}
} else {
	$tmpl->setVariable('pertipoo', $pertipoori);
	logerror('ADASDAS  ' . $pertipoori . $pertipo);

	$query = "SELECT PERTIPO, PERTIPDESESP  FROM PER_TIPO";

	$Table = sql_query($query, $conn);

	$tmpl->setCurrentBlock('tipo');


	for ($i = 0; $i < $Table->Rows_Count; $i++) {

		$row = $Table->Rows[$i];
		$petiponew 	= trim($row['PERTIPO']);
		$pertipdesesp 	= trim($row['PERTIPDESESP']);



		$tmpl->setVariable('pertiposelected', $petiponew);

		$tmpl->setVariable('pertipdesesp', $pertipdesesp);





		$tmpl->parse('tipo');
	}
}

//--------------------------------------------------------------------------------------------------------------
sql_close($conn);

$tmpl->show();
//logerror('MST VISIBILIDAD '.$pertipo);
?>	
