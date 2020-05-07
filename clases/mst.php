<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/idioma.php'; //Idioma	

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('mst.html');

//Diccionario de idiomas
DDIdioma($tmpl);
//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------
$perclase = (isset($_POST['perclase'])) ? trim($_POST['perclase']) : 0;
$estcodigo = 1; //Activo por defecto
$secdescri = '';
$pertipocla= '';

//--------------------------------------------------------------------------------------------------------------
$conn = sql_conectar(); //Apertura de Conexion

if ($perclase != 0) {
	$query = "	SELECT PERCLASE, PERCLADES,PERTIPO
					FROM PER_CLASE
					WHERE PERCLASE =$perclase";




	$Table = sql_query($query, $conn);
	if ($Table->Rows_Count > 0) {
		$row = $Table->Rows[0];
		$perclades = trim($row['PERCLADES']);
		$perclase = trim($row['PERCLASE']);
		$pertipocla = trim($row['PERTIPO']);


		$tmpl->setVariable('perclades', $perclades);
		$tmpl->setVariable('perclase', $perclase);
	}
}

$querytipo = "SELECT PERTIPO, PERTIPDESESP FROM PER_TIPO WHERE ESTCODIGO <> 3";
$Tabletipo = sql_query($querytipo, $conn);


for ($i = 0; $i < $Tabletipo->Rows_Count; $i++) {
	$row = $Tabletipo->Rows[$i];
	$pertipdesesp = $row['PERTIPDESESP'];
	$pertipo = $row['PERTIPO'];


	$tmpl->setCurrentBlock('tipo');

	if ($pertipo == $pertipocla) {

		$tmpl->setVariable('tiposelected', 'selected');
	}
	$tmpl->setVariable('pertipdesesp', $pertipdesesp);
	$tmpl->setVariable('pertipo', $pertipo);
	$tmpl->parse('tipo');
}

//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();

?>	
