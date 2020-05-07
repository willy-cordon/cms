<?
	//--------------------------------------------------------------------------------------------------------------
	include ('../../func/zglobals.php');
	include ('../../func/sigma.php');	
	include ('../../func/zdatabase.php');
    include ('../../func/zfvarias.php');
    
    $tmpl= new HTML_Template_Sigma();
    	
	$tmpl->loadTemplateFile('mesaayuda.html');
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	//30d24455a207b990cebe990cebe0f0d57133273530d24455a24f666d8fb393ee3c5be901
	//http://localhost/webcoordinador/app/mesaAyuda/mesaAyuda.php?P=30d24455a207b990cebe990cebe0f0d57133273530d24455a24f666d8fb393ee3c5be901{PERCODIGO}
	$valcode = substr(md5('BenvidoSistemasAccesoClientes'),0,10).substr(md5('ExpoagroBenvidoSistemas'),5,10).substr(md5('ExpoagroBenvidoSistemas'),8,20).md5('BenvidoSistemasAccesoClientes');
	$percodigo = (isset($_GET['P']))? $_GET['P'] : '';
	
	if($percodigo!=''){
		$percodigo = str_replace($valcode,'',$percodigo);
		//--------------------------------------------------------------------------------------------------------------
		$conn= sql_conectar();//Apertura de Conexion
		
		$query = "	SELECT PERCODIGO
					FROM PER_MAEST 
					WHERE ESTCODIGO=1 AND PERCODIGO=$percodigo ";
		
		$Table = sql_query($query,$conn);
		
		if($Table->Rows_Count>0){
			$tmpl->setVariable('percodigo'		, $percodigo 		);
		}else{
			header('location: ../../login');
		}
		//--------------------------------------------------------------------------------------------------------------
		sql_close($conn);
		$tmpl->show();
	}else{
		header('location: ../../login');
	}
?>	
