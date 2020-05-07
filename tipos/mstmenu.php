<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/idioma.php'; //Idioma	

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('mstmenu.html');

$conn = sql_conectar(); //Apertura de Conexion

//Diccionario de idiomas
DDIdioma($tmpl);
//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------
$pertipo = (isset($_POST['pertipo'])) ? trim($_POST['pertipo']) : 0;

$tmpl->setVariable('pertipo', $pertipo);
$estcodigo = 1; //Activo por defecto
$pertipodesesp = '';


//--------------------------------------------------------------------------------------------------------------

/* ---------------------------- ANCHOR SET TITULO --------------------------- */
$querytitulo = "SELECT PERTIPDESESP, PERTIPDESING FROM PER_TIPO WHERE PERTIPO = $pertipo";
$TableTitulo =  sql_query($querytitulo, $conn);
$row = $TableTitulo->Rows[0];
$pertipdesesp = trim($row['PERTIPDESESP']);
$tmpl->setVariable('pertipdesesp', $pertipdesesp);

/* ------------------------------------ - ----------------------------------- */


$query = "SELECT MENCODIGO, MENDESCRI FROM MEN_MAEST ORDER BY MENCODIGO ";

$Table = sql_query($query, $conn);

for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$mencodigo 	= trim($row['MENCODIGO']);
	$mendescri 	= trim($row['MENDESCRI']);

	$tmpl->setCurrentBlock('browser');
	$tmpl->setVariable('mencodigo', $mencodigo);
	$tmpl->setVariable('mendescri', $mendescri);
	$tmpl->parse('browser');
}








//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();

?>	
