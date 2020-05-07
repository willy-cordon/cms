<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once 'Classes/PHPExcel/IOFactory.php';
require_once "Classes/PHPExcel.php";
$PERADMIN = (isset($_SESSION[GLBAPPPORT . 'PERADMIN'])) ? trim($_SESSION[GLBAPPPORT . 'PERADMIN']) : '';
//verificamos is es administrador
if ($PERADMIN != 1) {
    header('Location: ../index');
}


$errcod = 0;
$err ='';
$conn = sql_conectar();
$trans	= sql_begin_trans($conn);
$errmsg = '';

// $trans  = sql_begin_trans($conn);


$areaError = array(
    "tipo" => 'area',
    "errmsg" => "",
    "errcod" => 1,
    "areas" => [],
 
  
  );


$tmpfname = $_FILES["file"]["tmp_name"];
$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
$excelObj = $excelReader->load($tmpfname);
$worksheet = $excelObj->getSheet(0);
$lastRow = $worksheet->getHighestRow();


//  echo $worksheet->getCell('B'. 2)->getValue();


for ($row = 2; $row <=  $lastRow; $row++) {



    $PERAREDESESP   =   $worksheet->getCell('A' . $row)->getValue();
    $PERAREDESING   =   $worksheet->getCell('B' . $row)->getValue();


    $PERAREDESESP   =   VarNullBD($PERAREDESESP,  'S');
    $PERAREDESING   =   VarNullBD($PERAREDESING,  'S');

    $query2 = "SELECT PERAREDESESP FROM PER_AREA WHERE PERAREDESESP = $PERAREDESESP";
    $Table = sql_query($query2, $conn, $trans);
    $rows = $Table->Rows_Count;


    logerror($PERAREDESESP);

    if ($rows == -1) {

            if ($PERAREDESESP   != 'NULL') {

                $query1     = 'SELECT GEN_ID(GEN_PER_AREA,1) AS ID FROM RDB$DATABASE';
                $TblId    = sql_query($query1, $conn);
                $RowId    = $TblId->Rows[0];
                $setCodigo   = trim($RowId['ID']);


                $query = " 	INSERT INTO PER_AREA(PERARECOD,PERAREDESESP,PERAREDESING)
    
                    VALUES($setCodigo,$PERAREDESESP,$PERAREDESING) ";

                $err = sql_execute($query, $conn,$trans);

                $area = array(
           
                    'nombre' => $PERAREDESESP,
                    'check' => 1,
                    'log' =>  'N/A'
        
                  );
            
                  array_push($areaError['areas'], $area);
                  
               
            } else {

                //ANCHOR  NULO
                $area = array(
           
                    'nombre' => 'N/A',
                    'check' => 0,
                    'log' =>  'Revisar vacios',
                    'fila' => $row
        
                  );
            
                  array_push($areaError['areas'], $area);
                  $errcod = 1;
            }
        } else {
        //ANCHOR EXISTENTE

        
        $area = array(
           
            'nombre' => $PERAREDESESP,
            'check' => 0,
            'log' =>  'Ya existe area',
            'fila' => $row

          );
    
          array_push($areaError['areas'], $area);
          $errcod = 1;
    }
}



if ($err == 'SQLACCEPT' && $errcod == 0) {
    sql_commit_trans($trans);
    $errcod = 0;
    $errmsg =  'Improtacion Exitosa'      /*TrMessage('Permiso actualizado!')*/;
} else {
    sql_rollback_trans($trans);
    $errcod = 2;
    $errmsg = ($errmsg == '') ? 'Error al importar' /*TrMessage('Error al actualizar el permiso!')*/ : $errmsg;
}

//echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';
$areaError['errcod'] = $errcod;
$areaError['errmsg'] = $errmsg;

echo json_encode($areaError, JSON_PRETTY_PRINT);

sql_close($conn);
?>