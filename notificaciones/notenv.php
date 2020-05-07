<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	

	
			
    $percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
    $pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
    $perapelli = (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
    $percompan = (isset($_SESSION[GLBAPPPORT.'PERCOMPAN']))? trim($_SESSION[GLBAPPPORT.'PERCOMPAN']) : '';
       

    
    $JSTable	= '{"notificaciones": [ '; // nombre de la variable json
    $conn= sql_conectar();
	//Busco las notificaciones pendientes
	$query = "SELECT N.NOTREG, N.NOTTITULO, P.PERNOMBRE, P.PERAPELLI, P.PERCOMPAN,N.NOTCODIGO
				FROM NOT_CABE N
				LEFT OUTER JOIN REU_CABE R ON R.REUREG = N.REUREG 
				LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO = N.PERCODORI
				WHERE N.PERCODDST=$percodigo AND NOTESTADO=1 
				ORDER BY N.NOTFCHREG DESC ";
				
	$Table = sql_query($query,$conn);
	#Recorremos
	for($i=0; $i < $Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$notreg    = trim($row['NOTREG']); 
		$nottitulo	= DDStrIdioma($row['NOTTITULO']);
		$pernombre = trim($row['PERNOMBRE']);
		$perapelli = trim($row ['PERAPELLI']);
		$percompan = trim($row ['PERCOMPAN']);
		$notcodigo = trim($row ['NOTCODIGO']);
	
		$JSTable .= '{	"notreg":"'.$notreg.'",
						"nottitulo":"'.$nottitulo.'",
						"pernombre":"'.$pernombre.'",
						"perapelli":"'.$perapelli.'",
						"percompan":"'.$percompan.'",
						"notcodigo":"'.$notcodigo.'",
						"notestado":"1"
						},';
	}
   
	//Busco las notificaciones leidas, solo 20
	$query = "SELECT FIRST 20 N.NOTREG, N.NOTTITULO, P.PERNOMBRE, P.PERAPELLI, P.PERCOMPAN,N.NOTCODIGO
				FROM NOT_CABE N
				LEFT OUTER JOIN REU_CABE R ON R.REUREG = N.REUREG 
				LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO = N.PERCODORI
				WHERE N.PERCODDST=$percodigo AND NOTESTADO=2
				ORDER BY N.NOTFCHREG DESC ";
				
	$Table = sql_query($query,$conn);
	#Recorremos
	for($i=0; $i < $Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$notreg    = trim($row['NOTREG']); 
		$nottitulo	= DDStrIdioma($row['NOTTITULO']);
		
		$pernombre = trim($row['PERNOMBRE']);
		$perapelli = trim($row ['PERAPELLI']);
		$percompan = trim($row ['PERCOMPAN']);
		$notcodigo = trim($row ['NOTCODIGO']);
	
		$JSTable .= '{	"notreg":"'.$notreg.'",
						"nottitulo":"'.$nottitulo.'",
						"pernombre":"'.$pernombre.'",
						"perapelli":"'.$perapelli.'",
						"percompan":"'.$percompan.'",
						"notcodigo":"'.$notcodigo.'",
						"notestado":"2"
						},';
	}
	
	//if($Table->Rows_Count >0){ //Si hay registros
	$JSTable	= substr($JSTable, 0, strlen($JSTable)-1);
	//}
			
            
    $JSTable.=']}';
    echo $JSTable;
	
    sql_close($conn);
        
?>	
