<?
	if(!isset($_SESSION))  session_start();
	//--------------------------------------------------------------------------------------------------------------
	include ('../func/zglobals.php');
	include ('../func/sigma.php');	
	include ('../func/zdatabase.php');
    include ('../func/zfvarias.php');
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('miperfilapp.html');
	
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	//30d24455a207b990cebe990cebe0f0d57133273530d24455a24f666d8fb393ee3c5be901
	//http://localhost/webcoordinador/perfiles/miperfilapp.php?P=30d24455a207b990cebe990cebe0f0d57133273530d24455a24f666d8fb393ee3c5be901MXx8MTIzNDU2fHxkYW5pZWw=
		
	//Nombre del Evento
	$tmpl->setVariable('SisNombreEvento', $_SESSION['PARAMETROS']['SisNombreEvento']);
	
	$valcode = substr(md5('BenvidoSistemasAccesoClientes'),0,10).substr(md5('ExpoagroBenvidoSistemas'),5,10).substr(md5('ExpoagroBenvidoSistemas'),8,20).md5('BenvidoSistemasAccesoClientes');
	$datainfo = (isset($_GET['P']))? trim($_GET['P']) : '';
	$pathimagenes = '../perimg/';
	
	if($datainfo!=''){
		$datainfo = str_replace($valcode,'',$datainfo);
		$datainfo = base64_decode($datainfo);
		
		$vdata = explode('||',$datainfo);
		$percodigo = $vdata[0];//CODIGO DE PERFIL
		$perpasacc = $vdata[1];//PASSWORD DE ACCESO
		$perusuacc = $vdata[2];//USUARIO DE ACCESO
		
		$perpasacc = md5('BenVido'.$perpasacc.'PassAcceso'.$perusuacc);
		$perpasacc = 'B#SD'.md5(substr($perpasacc,1,10).'BenVidO'.substr($perpasacc,5,8)).'E##$F';
		
		$conn= sql_conectar();//Apertura de Conexion
		
		//Busco si existe el perfil
		$query = "	SELECT PERCODIGO,PERNOMBRE,PERAPELLI,PERCORREO,PERUSUACC,PERPASACC,ESTCODIGO,PERADMIN,PERAVATAR,
							PERUSADIS,PERUSAREU,PERUSAMSG,PERTIPO,PERCLASE
					FROM PER_MAEST
					WHERE PERCODIGO=$percodigo AND PERUSUACC='$perusuacc' AND PERPASACC='$perpasacc' ";
		$Table = sql_query($query,$conn);
		
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$percodigo = trim($row['PERCODIGO']);
			$estcodigo = trim($row['ESTCODIGO']);
			
			$tmpl->setVariable('percodigo'	, $percodigo);
			
			$_SESSION[GLBAPPPORT.'PERCODIGO'] 	= $percodigo;
			$_SESSION[GLBAPPPORT.'PERNOMBRE'] 	= trim($row['PERNOMBRE']);
			$_SESSION[GLBAPPPORT.'PERAPELLI'] 	= trim($row['PERAPELLI']);
			$_SESSION[GLBAPPPORT.'PERCORREO'] 	= strtoupper(trim($row['PERCORREO']));
			$_SESSION[GLBAPPPORT.'PERUSUACC'] 	= strtoupper(trim($row['PERUSUACC']));
			$_SESSION[GLBAPPPORT.'PERPASACC'] 	= trim($row['PERPASACC']);
			$_SESSION[GLBAPPPORT.'PERADMIN'] 	= trim($row['PERADMIN']);
			$_SESSION[GLBAPPPORT.'PERUSADIS'] 	= trim($row['PERUSADIS']);
			$_SESSION[GLBAPPPORT.'PERUSAREU'] 	= trim($row['PERUSAREU']);
			$_SESSION[GLBAPPPORT.'PERUSAMSG'] 	= trim($row['PERUSAMSG']);
			$_SESSION[GLBAPPPORT.'PERTIPO'] 	= trim($row['PERTIPO']);
			$_SESSION[GLBAPPPORT.'PERCLASE'] 	= trim($row['PERCLASE']);
			
			if(trim($row['PERAVATAR'])!=''){
				$_SESSION[GLBAPPPORT.'PERAVATAR'] =  $pathimagenes.$percodigo.'/'.trim($row['PERAVATAR']);
			}else{
				$_SESSION[GLBAPPPORT.'PERAVATAR'] =  '../app-assets/img/avatar.png';
			}
			
			//Busco los parametro de clasificacion
			$query = "	SELECT 1
						FROM SEC_MAEST
						WHERE ESTCODIGO<>3 ";
			$Table = sql_query($query,$conn);
			if($Table->Rows_Count>0){
				$_SESSION[GLBAPPPORT.'SECTORES'] = 1; 
			}else{
				$_SESSION[GLBAPPPORT.'SECTORES'] = 0; 
			}
			
			$query = "	SELECT 1
						FROM SEC_SUB
						WHERE ESTCODIGO<>3 ";
			$Table = sql_query($query,$conn);
			if($Table->Rows_Count>0){
				$_SESSION[GLBAPPPORT.'SUBSECTORES'] = 1; 
			}else{
				$_SESSION[GLBAPPPORT.'SUBSECTORES'] = 0; 
			}
			
			$query = "	SELECT 1
						FROM CAT_MAEST
						WHERE ESTCODIGO<>3 ";
			$Table = sql_query($query,$conn);
			if($Table->Rows_Count>0){
				$_SESSION[GLBAPPPORT.'CATEGORIAS'] = 1; 
			}else{
				$_SESSION[GLBAPPPORT.'CATEGORIAS'] = 0; 
			}
			
			$query = "	SELECT 1
						FROM CAT_SUB
						WHERE ESTCODIGO<>3 ";
			$Table = sql_query($query,$conn);
			if($Table->Rows_Count>0){
				$_SESSION[GLBAPPPORT.'SUBCATEGORIAS'] = 1; 
			}else{
				$_SESSION[GLBAPPPORT.'SUBCATEGORIAS'] = 0; 
			}
			
			
		}else{
	
		}
		
		sql_close($conn);	
	}else{
	
	}
	
	//--------------------------------------------------------------------------------------------------------------
	$tmpl->show();
	
?>	
