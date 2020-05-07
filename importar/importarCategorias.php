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


$categoriaError = array(
  "tipo" => 'categoria',
  "errmsg" => "",
  "errcod" => 1,
  "categorias" => [],


);





//  echo $worksheet->getCell('B'. 2)->getValue();


for ($row = 2; $row <=  $lastRow; $row++) {



  $CATDESCRI   =   $worksheet->getCell('A' . $row)->getValue();
  $ESTCODIGO   =   $worksheet->getCell('B' . $row)->getValue();
  $SECSUBCOD   =   $worksheet->getCell('C' . $row)->getValue();
  $CATDESING   =   $worksheet->getCell('C' . $row)->getValue();

  $CATDESCRI   =   VarNullBD($CATDESCRI,  'S');
  $ESTCODIGO   =   VarNullBD($ESTCODIGO,  'N');
  $SECSUBCOD   =   VarNullBD($SECSUBCOD,  'N');
  $CATDESING   =   VarNullBD($SECSUBCOD,  'S');


  $query2 = "SELECT CATDESCRI FROM CAT_MAEST WHERE CATDESCRI = $CATDESCRI";

  $Table = sql_query($query2, $conn, $trans);
  $rows = $Table->Rows_Count;


  if ($rows == -1) {
    if ($CATDESCRI != 'NULL') {

      $query1     = 'SELECT GEN_ID(GEN_CAT,1) AS ID FROM RDB$DATABASE';
      $TblId    = sql_query($query1, $conn,$trans);
      $RowId    = $TblId->Rows[0];
      $setCodigo   = trim($RowId['ID']);


      $query = " 	INSERT INTO CAT_MAEST(CATCODIGO,CATDESCRI,ESTCODIGO,SECSUBCOD,CATDESING)
  
                VALUES($setCodigo,$CATDESCRI,$ESTCODIGO,$SECSUBCOD,$CATDESING) ";




      $err = sql_execute($query, $conn,$trans);
      // sql_commit_trans($trans);
      $categoria = array(

        'nombre' => $CATDESCRI,
        'check' => 1,
        'log' =>  'N/A',
        'fila' => $row

      );

      array_push($categoriaError['categorias'], $categoria);
    } else {

      $categoria = array(

        'nombre' => 'N/A',
        'check' => 0,
        'log' =>  'Revisar Campos Vacios',
        'fila' => $row

      );

      array_push($categoriaError['categorias'], $categoria);
    }


    //

  } else {
    $categoria = array(

      'nombre' => $CATDESCRI,
      'check' => 0,
      'log' =>  'Categoria Existente',
      'fila' => $row

    );

    array_push($categoriaError['categorias'], $categoria);
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
$categoriaError['errcod'] = $errcod;
$categoriaError['errmsg'] = $errmsg;

echo json_encode($categoriaError, JSON_PRETTY_PRINT);

sql_close($conn);
?>