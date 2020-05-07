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

$sectorError = array(
  "tipo" => 'sector',
  "errmsg" => "",
  "errcod" => 1,
  "sectores" => [],


);


$tmpfname = $_FILES["file"]["tmp_name"];
$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
$excelObj = $excelReader->load($tmpfname);
$worksheet = $excelObj->getSheet(0);
$lastRow = $worksheet->getHighestRow();




//  echo $worksheet->getCell('B'. 2)->getValue();


for ($row = 2; $row <=  $lastRow; $row++) {


 //SECTOR 
  $SECDESCRI   =   $worksheet->getCell('A' . $row)->getValue();
  $ESTCODIGO   =   $worksheet->getCell('B' . $row)->getValue();
  $SECDESING   =   $worksheet->getCell('C' . $row)->getValue();

  //SUBSECTOR


  //CATEGORIAS

  $SECDESCRI   =   VarNullBD($SECDESCRI,  'S');
  $ESTCODIGO   =   VarNullBD($ESTCODIGO,  'N');
  $SECDESING   =   VarNullBD($SECDESING,  'S');

  $query2 = "SELECT SECDESCRI FROM SEC_MAEST WHERE SECDESCRI = $SECDESCRI";
  $Table = sql_query($query2, $conn, $trans);
  $rows = $Table->Rows_Count;
  if ($rows == -1) {


    if ($SECDESCRI != 'NULL') {

      $query1     = 'SELECT GEN_ID(G_SECTOR,1) AS ID FROM RDB$DATABASE';
      $TblId    = sql_query($query1, $conn, $trans);
      $RowId    = $TblId->Rows[0];
      $setCodigo   = trim($RowId['ID']);


      $query = " 	INSERT INTO SEC_MAEST(SECCODIGO,SECDESCRI,ESTCODIGO,SECDESING)
  
                VALUES($setCodigo,$SECDESCRI,$ESTCODIGO,$SECDESING) ";

      $err = sql_execute($query, $conn, $trans);

      $sector = array(

        'nombre' => $SECDESCRI,
        'check' => 1,
        'log' =>  'N/A',
        'fila' => $row

      );

      array_push($sectorError['sectores'], $sector);
    } else {
      //ANCHOR  NULO
      $sector = array(

        'nombre' => 'N/A',
        'check' => 0,
        'log' =>  'Revisar vacios',
        'fila' => $row

      );

      array_push($sectorError['sectores'], $sector);
      $errcod = 1;
    }



    // sql_commit_trans($trans);

    //
  } else {
    //ANCHOR EXISTENTE


    $sector = array(

      'nombre' => $SECDESCRI,
      'check' => 0,
      'log' =>  'Ya existe sector',
      'fila' => $row

    );

    array_push($sectorError['sectores'], $sector);
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

$sectorError['errcod'] = $errcod;
$sectorError['errmsg'] = $errmsg;

echo json_encode($sectorError, JSON_PRETTY_PRINT);



sql_close($conn);
?>