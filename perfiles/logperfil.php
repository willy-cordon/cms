<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod 	= 0;
	$errmsg 	= '';
	$err 		= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	//Control de Datos
	$peradminlog 	= (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
	$percodigo 		= (isset($_POST['percodigo']))? trim($_POST['percodigo']) : 0;
	$pathimagenes = '../perimg/';
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = VarNullBD($percodigo , 'N');
	
	if($percodigo!=0 && $peradminlog==1){
		$query = " 	SELECT P.PERCODIGO,P.PERNOMBRE,P.PERAPELLI,P.PERCORREO,P.PERUSUACC,P.PERPASACC,P.ESTCODIGO,P.PERADMIN,P.PERAVATAR,
							P.PERUSADIS,P.PERUSAREU,P.PERUSAMSG,P.PERTIPO,P.PERCLASE,P.PERCOMPAN,P.PERIDIOMA,
							T.TIMREG,T.TIMDESCRI,T.TIMOFFSET
					FROM PER_MAEST P
					LEFT OUTER JOIN TIM_ZONE T ON T.TIMREG=P.TIMREG
					WHERE P.PERCODIGO=$percodigo ";
		
		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$percodigo = trim($row['PERCODIGO']);
			$estcodigo = trim($row['ESTCODIGO']);
			
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
			$_SESSION[GLBAPPPORT.'PERCOMPAN'] 	= trim($row['PERCOMPAN']);
			$_SESSION[GLBAPPPORT.'PERIDIOMA' ]  = trim($row['PERIDIOMA']);
			$_SESSION[GLBAPPPORT.'TIMREG'] 		= trim($row['TIMREG']);;
			$_SESSION[GLBAPPPORT.'TIMDESCRI'] 	= trim($row['TIMDESCRI']);;
			$_SESSION[GLBAPPPORT.'TIMOFFSET'] 	= trim($row['TIMOFFSET']);;
			
			if(trim($row['PERAVATAR'])!=''){
				$_SESSION[GLBAPPPORT.'PERAVATAR'] =  $pathimagenes.$percodigo.'/'.trim($row['PERAVATAR']);
			}else{
				$_SESSION[GLBAPPPORT.'PERAVATAR'] =  '../app-assets/img/avatar.png';
			}
		}
	}
	
	//--------------------------------------------------------------------------------------------------------------
	
	$errcod = 0;
	$errmsg = TrMessage('Login iniciado!');      
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
