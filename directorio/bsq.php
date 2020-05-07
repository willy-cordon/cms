<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('bsq.html');
	DDIdioma($tmpl);
	
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$perapelli = (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
	$perusuacc = (isset($_SESSION[GLBAPPPORT.'PERUSUACC']))? trim($_SESSION[GLBAPPPORT.'PERUSUACC']) : '';
	$perpasacc = (isset($_SESSION[GLBAPPPORT.'PERCORREO']))? trim($_SESSION[GLBAPPPORT.'PERCORREO']) : '';
	$peradmin = (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
	$peravatar = (isset($_SESSION[GLBAPPPORT.'PERAVATAR']))? trim($_SESSION[GLBAPPPORT.'PERAVATAR']) : '';
	$btnsectores 		= (isset($_SESSION[GLBAPPPORT.'SECTORES']))? trim($_SESSION[GLBAPPPORT.'SECTORES']) : '';
	$btnsubsectores 	= (isset($_SESSION[GLBAPPPORT.'SUBSECTORES']))? trim($_SESSION[GLBAPPPORT.'SUBSECTORES']) : '';
	$btncategorias 		= (isset($_SESSION[GLBAPPPORT.'CATEGORIAS']))? trim($_SESSION[GLBAPPPORT.'CATEGORIAS']) : '';
	$btnsubcategorias 	= (isset($_SESSION[GLBAPPPORT.'SUBCATEGORIAS']))? trim($_SESSION[GLBAPPPORT.'SUBCATEGORIAS']) : '';
	$tmpl->setVariable('pernombre'	, $pernombre	);
	$tmpl->setVariable('perapelli'	, $perapelli	);
	$tmpl->setVariable('perusuacc'	, $perusuacc	);
	$tmpl->setVariable('perpasacc'	, $perpasacc	);
	$tmpl->setVariable('peravatar'	, $peravatar	);
	
	//Nombre del Evento
	$tmpl->setVariable('SisNombreEvento', $_SESSION['PARAMETROS']['SisNombreEvento']);
	
	if($peradmin!=1) $tmpl->setVariable('viewadmin'	, 'none'	);
	if($btnsectores!=1) $tmpl->setVariable('btnsectores'	, 'display:;'	);
	if($btnsubsectores!=1) $tmpl->setVariable('btnsubsectores'	, 'display:;'	);
	if($btncategorias!=1) $tmpl->setVariable('btncategorias'	, 'display:none;'	);
	if($btnsubcategorias!=1) $tmpl->setVariable('btnsubcategorias'	, 'display:none;'	);
	
	//--------------------------------------------------------------------------------------------------------------
	$fltrecomendado = (isset($_GET['R']))? 1 : 0; //Filtro de Recomendados
	$tmpl->setVariable('fltrecomendado'	, $fltrecomendado	);
	if($fltrecomendado==1){
		$tmpl->setVariable('activerecomendados'	, 'class="active"'	);
	}else{
		$tmpl->setVariable('activedirectorio'	, 'class="active"'	);
	}
	$fltfavoritos = (isset($_GET['F']))? 1 : 0; //Filtro de Favoritos
	$tmpl->setVariable('fltfavoritos'	, $fltfavoritos	);
	//--------------------------------------------------------------------------------------------------------------
	
//Habilito las opciones del Menu
if(json_decode($_SESSION['PARAMETROS']['MenuActividades']) == false){
	$tmpl->setVariable('ParamMenuActividades'	, 'display:;'	);
}
if(json_decode($_SESSION['PARAMETROS']['MenuAgenda']) == false){
	$tmpl->setVariable('ParamMenuAgenda'	, 'display:;'	);
}
if(json_decode($_SESSION['PARAMETROS']['MenuMensajes']) == false){
	$tmpl->setVariable('ParamMenuMensajes'	, 'display:;'	);
}
if(json_decode($_SESSION['PARAMETROS']['MenuNoticias']) == false){
	$tmpl->setVariable('ParamMenuNoticias'	, 'display:;'	);
}
if(json_decode($_SESSION['PARAMETROS']['MenuExportar']) == false){
	$tmpl->setVariable('ParamMenuExportar'	, 'display:;'	);
}
if(json_decode($_SESSION['PARAMETROS']['MenuEncuesta']) == false){
	$tmpl->setVariable('ParamMenuEncuesta'	, 'display:none;'	);
}
	
	$conn= sql_conectar();//Apertura de Conexion
	
	$query = "	SELECT PERCODIGO,PERNOMBRE
				FROM PER_MAEST 
				WHERE PERCODIGO=$percodigo
				ORDER BY PERNOMBRE ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$percodigo 	= trim($row['PERCODIGO']);
		$pernombre	= trim($row['PERNOMBRE']);
		
		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('percodigo'	, $percodigo);
		$tmpl->setVariable('pernombre'	, $pernombre);		
		$tmpl->parse('browser');
	}
	
	//Cargo los Sectores

	$campo = ($IdiomView=='ING')? 'S.SECDESING' : 'S.SECDESCRI';
	logerror($campo);	
	$query = "	SELECT SECCODIGO, $campo AS SECDESCRI
				FROM SEC_MAEST S
				WHERE ESTCODIGO<>3 ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$seccodigo = trim($row['SECCODIGO']);
		$secdescri = trim($row['SECDESCRI']);
		
		$tmpl->setCurrentBlock('sectores');
		$tmpl->setVariable('seccodigo'	, $seccodigo	);
		$tmpl->setVariable('secdescri'	, $secdescri	);
		$tmpl->parse('sectores');
	}
	
	//Cargo los SubSectores
	$campo = ($IdiomView=='ING')? 'SB.SECSUBDESING' : 'SB.SECSUBDES';
	$query = "	SELECT SECSUBCOD, $campo AS SECSUBDES
				FROM SEC_SUB SB
				WHERE ESTCODIGO<>3 ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$secsubcod = trim($row['SECSUBCOD']);
		$secsubdes = trim($row['SECSUBDES']);
		
		$tmpl->setCurrentBlock('subsectores');
		$tmpl->setVariable('secsubcod'	, $secsubcod	);
		$tmpl->setVariable('secsubdes'	, $secsubdes	);
		$tmpl->parse('subsectores');
	}
	
	//Cargo los Categorias
	$campo = ($IdiomView=='ING')? 'C.CATDESING' : 'C.CATDESCRI';
	$query = "	SELECT CATCODIGO, $campo AS CATDESCRI
				FROM CAT_MAEST C
				WHERE ESTCODIGO<>3 ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$catcodigo = trim($row['CATCODIGO']);
		$catdescri = trim($row['CATDESCRI']);
		
		$tmpl->setCurrentBlock('categoria');
		$tmpl->setVariable('catcodigo'	, $catcodigo	);
		$tmpl->setVariable('catdescri'	, $catdescri	);
		$tmpl->parse('categoria');
	}
	
	//Cargo los SubCategorias
	$campo = ($IdiomView=='ING')? 'CS.CATSUBDESING' : 'CS.CATSUBDES';
	$query = "	SELECT CATSUBCOD, $campo AS CATSUBDES
				FROM CAT_SUB CS
				WHERE ESTCODIGO<>3 ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$catsubcod = trim($row['CATSUBCOD']);
		$catsubdes = trim($row['CATSUBDES']);
		
		$tmpl->setCurrentBlock('subcategoria');
		$tmpl->setVariable('catsubcod'	, $catsubcod	);
		$tmpl->setVariable('catsubdes'	, $catsubdes	);
		$tmpl->parse('subcategoria');
	}
	
	//--------------------------------------------------------------------------------------------------------------
	//Tipo de Perfiles
	$query = "	SELECT PERTIPO,PERTIPDES$IdiomView AS PERTIPDES
				FROM PER_TIPO			
				ORDER BY PERTIPO ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$pertipo 	= trim($row['PERTIPO']);
		$pertipdes	= trim($row['PERTIPDES']);
		
		$tmpl->setCurrentBlock('pertipos');
		$tmpl->setVariable('pertipo'	, $pertipo 		);
		$tmpl->setVariable('pertipdes'	, $pertipdes	);		
		$tmpl->parse('pertipos');
	}
	//--------------------------------------------------------------------------------------------------------------
	//Reuniones solicitadas y pendientes
	$query = "	SELECT COUNT(*) AS CANTIDAD
				FROM REU_CABE R
				LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=R.PERCODSOL
				WHERE R.PERCODSOL=$percodigo AND R.REUESTADO=1 ";
	$Table = sql_query($query,$conn);
	$row = $Table->Rows[0];
	$cantEnviados= trim($row['CANTIDAD']);
	if($cantEnviados==0)	$cantEnviados='';
	
	//Reuniones recibidas y pendientes
	$query = "	SELECT COUNT(*) AS CANTIDAD
				FROM REU_CABE R
				LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=R.PERCODSOL
				WHERE  R.PERCODDST=$percodigo AND R.REUESTADO=1  ";
	$Table = sql_query($query,$conn);
	$row = $Table->Rows[0];
	$cantRecibidos= trim($row['CANTIDAD']);
	if($cantRecibidos==0)	$cantRecibidos='';
	
	$tmpl->setVariable('cantEnviados'	, $cantEnviados);
	$tmpl->setVariable('cantRecibidos'	, $cantRecibidos);
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
