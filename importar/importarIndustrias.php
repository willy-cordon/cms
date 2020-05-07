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
$err = '';
$conn = sql_conectar();
$trans  = sql_begin_trans($conn);
$errmsg = '';


$industriaError = array(
  "tipo" => 'industria',
  "errmsg" => "",
  "errcod" => 1,
  "industrias" => [],


);




$tmpfname = $_FILES["file"]["tmp_name"];
$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
$excelObj = $excelReader->load($tmpfname);
$worksheet = $excelObj->getSheet(0);
$lastRow = $worksheet->getHighestRow();


//  echo $worksheet->getCell('B'. 2)->getValue();


for ($row = 2; $row <=  $lastRow; $row++) {



  $PERINDDESESP   =   $worksheet->getCell('A' . $row)->getValue();
  $PERINDDESING   =   $worksheet->getCell('B' . $row)->getValue();


  $PERINDDESESP   =   VarNullBD($PERINDDESESP,  'S');
  $PERINDDESING   =   VarNullBD($PERINDDESING,  'S');

  $query2 = "SELECT PERINDDESESP FROM PER_IND WHERE PERINDDESESP = $PERINDDESESP";
  $Table = sql_query($query2, $conn, $trans);
  $rows = $Table->Rows_Count;

  if ($rows == -1) {
    if ($PERINDDESESP   != 'NULL') {

      $query1     = 'SELECT GEN_ID(GEN_PER_IND,1) AS ID FROM RDB$DATABASE';
      $TblId    = sql_query($query1, $conn,$trans);
      $RowId    = $TblId->Rows[0];
      $setCodigo   = trim($RowId['ID']);


      $query = " 	INSERT INTO PER_IND(PERINDCOD,PERINDDESESP,PERINDDESING)
  
                VALUES($setCodigo,$PERINDDESESP,$PERINDDESING) ";
      $err = sql_execute($query, $conn,$trans);
      // sql_commit_trans($trans);

      $industria = array(

        'nombre' => $PERINDDESESP,
        'check' => 1,
        'log' =>  'N/A',
        'fila' => $row
  
      );
  
      array_push($industriaError['industrias'], $industria);
    }else {
      $industria = array(

        'nombre' => 'N/A',
        'check' => 0,
        'log' =>  'Verificar Vacios',
        'fila' => $row
  
      );
  
      array_push($industriaError['industrias'], $industria);
      $errcod = 1;
    }



    //
  } else {
    //ANCHOR EXISTENTE
    $industria = array(

      'nombre' => $PERINDDESESP,
      'check' => 0,
      'log' =>  'Ya existe Industria',
      'fila' => $row

    );

    array_push($industriaError['industrias'], $industria);
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
$industriaError['errcod'] = $errcod;
$industriaError['errmsg'] = $errmsg;

echo json_encode($industriaError, JSON_PRETTY_PRINT);

sql_close($conn);
?>