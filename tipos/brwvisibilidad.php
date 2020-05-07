<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
    $tmpl->loadTemplateFile('brwvisibilidad.html');
    	//Diccionario de idiomas
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
    $conn= sql_conectar();//Apertura de Conexion
    //Diccionario de idiomas
    //logerror($_POST['pertipo']);
    
    $pertipo =(isset($_POST['pertipo'])) ? trim($_POST['pertipo']) : '';

  
    // $tmpl->setVariable('pertipo',$pertipo);
    

    
        /* --------------- ANCHOR  TIPO SELECCIONADO -------------- */

        $querytipori = "SELECT PERTIPO,PERTIPDESESP FROM PER_TIPO WHERE PERTIPO = $pertipo ";
        $Tabletipori = sql_query($querytipori,$conn);
        $rowtipori   = $Tabletipori->Rows[0];
        $pertiporides   = $rowtipori['PERTIPDESESP'];
        $pertipori   = $rowtipori['PERTIPO'];

        $tmpl->setVariable('pertipori',$pertipori);
        $tmpl->setVariable('pertiporides',$pertiporides);
        /* ------------------------------------ x ----------------------------------- */
    

    /* ------------- ANCHOR A QUIENES PUEDE VER EL TIPO SELECIONADO ------------- */
   

    $query = "SELECT PERTIPOPERM,PERTIPO,PERTIPCLA,PERTIPDST, PERCLADST  
                FROM PER_TIPO_PERM 
                WHERE PERTIPO = $pertipo  
                ORDER BY PERTIPOPERM";


  
    $Table = sql_query($query, $conn);
    
    $tmpl->setCurrentBlock('visibilidad');
    
    for ($i = 0; $i < $Table->Rows_Count; $i++) {
        
        $row = $Table->Rows[$i];
        $pertipdst 	= trim($row['PERTIPDST']);
        $percladst 	= trim($row['PERCLADST']);
        $pertipoperm= trim($row['PERTIPOPERM']);
        $pertiponew = trim($row['PERTIPO']);
        $perclasenew = trim($row['PERTIPCLA']);
       

        if($percladst  == ''){
            $percladst = 1;
        }

       
        /* ------------------- ANCHOR CLASES DEL TIPO SELECCIONADO ------------------ */

        $queryclaori = "SELECT PERCLASE,PERCLADES FROM PER_CLASE WHERE PERCLASE = $perclasenew ";
        $Talbeclaori = sql_query($queryclaori,$conn);
        $rowclaori   = $Talbeclaori->Rows[0];
        $perclaori   = $rowclaori['PERCLADES'];
        $codclase   = $rowclaori['PERCLASE'];
        $codclasee = $codclase;
        $tmpl->setVariable('perclaori',$perclaori);
        $tmpl->setVariable('codclase',$codclase);

        
        /* ------------------------------------ X ----------------------------------- */
        $querydst = "SELECT PERTIPO, PERTIPDESESP 
                        FROM PER_TIPO 
                        WHERE PERTIPO = $pertipdst";
         

        $queryclase = "SELECT PERCLASE, PERCLADES,PERTIPO 
                            FROM PER_CLASE 
                            WHERE PERCLASE =$percladst";

        $Tabledst = sql_query($querydst,$conn);
        $Tableclase = sql_query($queryclase,$conn);
        
        
        $rowdst = $Tabledst->Rows[0];
        $rowclase = $Tableclase->Rows[0];

        $pertipodst = $rowdst['PERTIPO'];

        $pertipdesesp = $rowdst['PERTIPDESESP'];
        $perclades = $rowclase['PERCLADES'];
        $perclase = $rowclase['PERCLASE'];
        
      
       
        $tmpl->setVariable('pertipdesesp',$pertipdesesp);
        $tmpl->setVariable('pertipodst',$pertipodst);
        $tmpl->setVariable('perclades',$perclades);
        $tmpl->setVariable('perclase',$perclase);

        $tmpl->setVariable('pertipoperm', $pertipoperm);
        $tmpl->setVariable('pertipo', $pertipo);

        $tmpl->parse('visibilidad');
        
      
    }
    
    

	//--------------------------------------------------------------------------------------------------------------
    sql_close($conn);	
    // $tmpl->setVariable('codclasee', $codclasee);

	$tmpl->show();
   
?>	
