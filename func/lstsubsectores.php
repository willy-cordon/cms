<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
    $percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
    $pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
    $perapelli = (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
    $percompan = (isset($_SESSION[GLBAPPPORT.'PERCOMPAN']))? trim($_SESSION[GLBAPPPORT.'PERCOMPAN']) : '';
	
	$seccodigo = (isset($_POST['seccodigo']))? trim($_POST['seccodigo']) : 0;
       
    $JSTable	= '['; // nombre de la variable json
    
	if($seccodigo!=0){   
		$conn= sql_conectar();
		
		$query = "SELECT SECSUBCOD,SECSUBDES
					FROM SEC_SUB 
					WHERE ESTCODIGO=1 AND SECCODIGO=$seccodigo 
					ORDER BY SECSUBDES ";
					
		$Table = sql_query($query,$conn);
		for($i=0; $i < $Table->Rows_Count; $i++){
			$row= $Table->Rows[$i];
			$secsubcod	= trim($row['SECSUBCOD']); 
			$secsubdes	= trim($row['SECSUBDES']);
		
			$JSTable .= '{	"secsubcod":"'.$secsubcod.'",
							"secsubdes":"'.$secsubdes.'"
							},';
		}
	   
		if($Table->Rows_Count >0){ //Si hay registros
			$JSTable	= substr($JSTable, 0, strlen($JSTable)-1);
		}
		
		sql_close($conn);
	}
            
    $JSTable.=']';
    echo $JSTable;
	
?>	
