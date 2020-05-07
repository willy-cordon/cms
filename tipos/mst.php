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
	$pertipo = (isset($_POST['pertipo'])) ? trim($_POST['pertipo']) : 0;
	$estcodigo = 1; //Activo por defecto
	$pertipodesesp = '';


	//--------------------------------------------------------------------------------------------------------------
	$conn = sql_conectar(); //Apertura de Conexion

	if ($pertipo != 0) {
		$query = "	SELECT PERTIPO, PERTIPDESESP, PERTIPDESING,PERUSACHA,PERUSAREU
							FROM PER_TIPO
							WHERE PERTIPO=$pertipo";

		$Table = sql_query($query, $conn);
		if ($Table->Rows_Count > 0) {
			$row = $Table->Rows[0];


			$pertipo = trim($row['PERTIPO']);
			$pertipodesesp = trim($row['PERTIPDESESP']);
			$pertipodeing = trim($row['PERTIPDESING']);
			$perusacha    = trim($row['PERUSACHA']);
			$perusareu    = trim($row['PERUSAREU']);


			$tmpl->setVariable('pertipo', $pertipo);
			$tmpl->setVariable('pertipodesesp', $pertipodesesp);
			$tmpl->setVariable('pertipodeing', $pertipodeing);
			$tmpl->setVariable('perusacha' . $perusacha, 'selected');
			$tmpl->setVariable('perusareu' . $perusareu, 'selected');
		}
	}

	/* ------------------------------------ * ----------------------------------- */

	$querytipos				=	"SELECT PERTIPO, PERTIPDESESP
								FROM PER_TIPO
								WHERE ESTCODIGO<>3 
								ORDER BY PERTIPDESESP ";

	/* ------------------------------------ * ----------------------------------- */

	$queryvisibilidad  = 	"SELECT PERTIPDST, PERCLADST
								FROM PER_TIPO_PERM
								WHERE PERTIPO =$pertipo";

	/* ------------------------------------ * ----------------------------------- */

	$queryclase 	  =  	"SELECT PERCLASE, PERCLADES, PERTIPO 
								FROM PER_CLASE
								WHERE ESTCODIGO <> 3";

	/* ------------------------------------ * ----------------------------------- */
	
	$Table = sql_query($querytipos, $conn);

	$Tablevisibilidad = sql_query($queryvisibilidad, $conn);
	
	
	
	/* ------------------------------------ * ----------------------------------- */

	for ($i = 0; $i < $Table->Rows_Count; $i++) {
		$row = $Table->Rows[$i];
		$pertipoadd 	= trim($row['PERTIPO']);
		$pertipodesespadd 	= trim($row['PERTIPDESESP']);

		$queryclase 	  =  	"SELECT PERCLASE, PERCLADES, PERTIPO 
		FROM PER_CLASE
		WHERE ESTCODIGO <> 3 AND PERTIPO = $pertipoadd  ";

		$TableClase = sql_query($queryclase, $conn);
		

	

		$tmpl->setCurrentBlock('tipo');

		 for ($f = 0; $f < $Tablevisibilidad->Rows_Count; $f++) {

		 	$rowvisibilidad = $Tablevisibilidad->Rows[$f];

		 	if ($rowvisibilidad['PERTIPDST'] ==$pertipoadd) {
		 		$tmpl->setVariable('checked', 'selected');
		 	}
		 }


	

		$tmpl->setVariable('pertipoadd', $pertipoadd);
		
		$tmpl->setVariable('pertipodesespadd', $pertipodesespadd);
		
		$tmpl->parse('tipo');
		
		/* ------------------------- SECTION SI ESTA VISIBLE ------------------------ */

	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);
	$tmpl->show();

?>	
