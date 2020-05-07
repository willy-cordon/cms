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
// $trans  = sql_begin_trans($conn);


$tmpfname = $_FILES["file"]["tmp_name"];
$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
$excelObj = $excelReader->load($tmpfname);
$worksheet = $excelObj->getSheet(0);
$lastRow = $worksheet->getHighestRow();

$subSectorError = array(
  "tipo" => 'subsector',
  "errmsg" => "",
  "errcod" => 1,
  "subsectores" => [],


);
//  echo $worksheet->getCell('B'. 2)->getValue();


for ($row = 2; $row <=  $lastRow; $row++) {



  $SECSUBDES   =   $worksheet->getCell('A' . $row)->getValue();
  $ESTCODIGO   =   $worksheet->getCell('B' . $row)->getValue();
  $SECCODIGO  =   $worksheet->getCell('C' . $row)->getValue();
  $SECSUBDESING = $worksheet->getCell('D' . $row)->getValue();

  $SECSUBDES   =   VarNullBD($SECSUBDES,  'S');
  $ESTCODIGO   =   VarNullBD($ESTCODIGO,  'N');
  $SECCODIGO   =   VarNullBD($SECCODIGO,  'N');
  $SECSUBDESING   =   VarNullBD($SECSUBDESING,  'S');



  $query2 = "SELECT SECSUBDES FROM SEC_SUB WHERE SECSUBDES = $SECSUBDES";
  $Table = sql_query($query2, $conn, $trans);
  $rows = $Table->Rows_Count;

  if ($rows == -1) {


    if ($SECSUBDES != 'NULL') {

      $query1     = 'SELECT GEN_ID(G_SUBSECTOR,1) AS ID FROM RDB$DATABASE';
      $TblId    = sql_query($query1, $conn, $trans);
      $RowId    = $TblId->Rows[0];
      $setCodigo   = trim($RowId['ID']);


      $query = " 	INSERT INTO SEC_SUB(SECSUBCOD,SECSUBDES,ESTCODIGO,SECCODIGO,SECSUBDESING)
  
                VALUES($setCodigo,$SECSUBDES,$ESTCODIGO,$SECCODIGO,$SECSUBDESING) ";

      $err = sql_execute($query, $conn, $trans);

      $subSector = array(

        'nombre' => $SECSUBDES,
        'check' => 1,
        'log' =>  'N/A',
        'fila' => $row

      );

      array_push($subSectorError['subsectores'], $subSector);
    } else {
      //nulo
      $subSector = array(

        'nombre' => 'N/A',
        'check' => 0,
        'log' =>  'Revisar Vacios',
        'fila' => $row

      );

      array_push($subSectorError['subsectores'], $subSector);
      $errcod = 1;
    }
  } else {
    //EXISTENTE

    $subSector = array(

      'nombre' => $SECSUBDES,
      'check' => 0,
      'log' =>  'Ya existe Subsector',
      'fila' => $row

    );

    array_push($subSectorError['subsectores'], $subSector);
    $errcod = 1;
  }


  // sql_commit_trans($trans);

  //

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

$subSectorError['errcod'] = $errcod;
$subSectorError['errmsg'] = $errmsg;

echo json_encode($subSectorError, JSON_PRETTY_PRINT);



sql_close($conn);
?>