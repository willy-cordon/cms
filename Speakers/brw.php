<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('brw.html');

//--------------------------------------------------------------------------------------------------------------
$percodigo = (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$pernombre = (isset($_SESSION[GLBAPPPORT . 'PERNOMBRE'])) ? trim($_SESSION[GLBAPPPORT . 'PERNOMBRE']) : '';

// $fltdescri = (isset($_POST['fltdescri']))? trim($_POST['fltdescri']):'';
// //Filtro de busqueda por titulo
// $where = '';
// if($fltdescri!=''){
// 	$where .= " AND AVITITULO CONTAINING '$fltdescri' ";
// }

$conn = sql_conectar(); //Apertura de Conexion

$query = "	SELECT SPKREG, SPKNOMBRE, SPKPOS, SPKDESCRI, ESTCODIGO
				FROM SPK_MAEST
				WHERE ESTCODIGO<>3
				ORDER BY SPKPOS ASC";



//logerror($query);
$Table = sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$spkreg 	= trim($row['SPKREG']);
	$spktitulo 	= trim($row['SPKNOMBRE']);
	$spkdescri  = trim($row['SPKDESCRI']);
	$spkpos     = trim($row['SPKPOS']);
	//$aviimagen  = trim($row['AVIIMAGEN']);

	$tmpl->setCurrentBlock('browser');
	$tmpl->setVariable('spkreg', $spkreg);
	$tmpl->setVariable('spktitulo', $spktitulo);
	$tmpl->setVariable('spkdescri', $spkdescri);
	$tmpl->setvariable('spkpos', $spkpos);

	//$tmpl->setvariable('aviimagen',$aviimagen);
	$tmpl->parse('browser');
}
//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();

?>	
