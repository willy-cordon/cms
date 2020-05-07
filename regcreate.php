<?
	if(!isset($_SESSION))  session_start();
	// include($_SERVER["DOCUMENT_ROOT"].'/webcoordinador/func/zglobals.php'); //DEV
	include($_SERVER["DOCUMENT_ROOT"].'/func/zglobals.php'); //PRD
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	
	
	
	
	/*
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$perusuacc = (isset($_POST['perusuacc']))? trim($_POST['perusuacc']) : '';
	$perpasacc = (isset($_POST['perpasacc']))? trim($_POST['perpasacc']) : '';
	
	$conn= sql_conectar();//Apertura de Conexion
	
	if($percodigo == ''){
		if($perusuacc=='' || $perpasacc==''){
			header('Location: login');
			exit;
		}else{
			//$perpasacc = md5('BenVido'.$perpasacc.'PassAcceso'.$perusuacc);
			//$perpasacc = md5(substr($perpasacc,1,10).'BenVidO'.substr($perpasacc,5,8));
			
			$query = " 	SELECT PERCODIGO,PERNOMBRE,PERAPELLI,PERCORREO,PERUSUACC,PERPASACC
						FROM PER_MAEST 
						WHERE PERUSUACC='$perusuacc' AND PERPASACC='$perpasacc' ";
			
			$Table = sql_query($query,$conn);
			if($Table->Rows_Count>0){
				$row= $Table->Rows[0];
				$percodigo = trim($row['PERCODIGO']);
				$_SESSION[GLBAPPPORT.'PERCODIGO'] = $percodigo;
				$_SESSION[GLBAPPPORT.'PERNOMBRE'] = trim($row['PERNOMBRE']);
				$_SESSION[GLBAPPPORT.'PERAPELLI'] = trim($row['PERAPELLI']);
				$_SESSION[GLBAPPPORT.'PERCORREO'] = strtoupper(trim($row['PERCORREO']));
				$_SESSION[GLBAPPPORT.'PERUSUACC'] = strtoupper(trim($row['PERUSUACC']));
				$_SESSION[GLBAPPPORT.'PERPASACC'] = trim($row['PERPASACC']);
			}else{
				header('Location: login');
				exit;
			}
		}
	}
	//--------------------------------------------------------------------------------------------------------------
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$perapelli = (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
	$perusuacc = (isset($_SESSION[GLBAPPPORT.'PERUSUACC']))? trim($_SESSION[GLBAPPPORT.'PERUSUACC']) : '';
	$perpasacc = (isset($_SESSION[GLBAPPPORT.'PERCORREO']))? trim($_SESSION[GLBAPPPORT.'PERCORREO']) : '';
	$tmpl->setVariable('pernombre'	, $pernombre	);
	$tmpl->setVariable('perapelli'	, $perapelli	);
	$tmpl->setVariable('perusuacc'	, $perusuacc	);
	$tmpl->setVariable('perpasacc'	, $perpasacc	);
	
	$tmpl->show();
	*/
?>	
