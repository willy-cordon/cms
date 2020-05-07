<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
    require_once 'Classes/PHPExcel.php';
    
    $peradmin = (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
    //verificamos is es administrador
    if($peradmin!=1){
		header('Location: ../index');
    }
    
    $conn= sql_conectar();//Apertura de Conexion
    
    $query="SELECT P.PERCODIGO AS REG,P.PERNOMBRE AS NOMBRE,P.PERAPELLI AS APELLIDO,P.PERCOMPAN AS COMPANIA,P.PERCORREO AS CORREO,P.PERCIUDAD AS CIUDAD,
                P.PERESTADO AS ESTADO, S.PAIDESCRI AS PAIS,P.PERCODPOS AS CODIGOPOSTAL,P.PERTELEFO AS TELEFONO,P.PERDIRECC AS DIRECCION, P.PERCARGO AS CARGO,
                P.PERUSUACC AS USUARIOACCESO,T.PERTIPDESESP AS TIPO, C.PERCLADES AS CLASE,P.PERADMIN AS ESADMIN,
                P.PERURLWEB AS SITIOWEB,P.PEREMPDES AS DESCRIPCIONEMPRESA,
                P.PERUSADIS AS USADISPONIBILIDAD,P.PERUSAREU AS USAREUNIONES,
                P.PERCOMENT AS COMENTARIOREGISTRACION,-- M.MESNUMERO AS MESANUMERO,
                P.PERPARNOM1 AS PART1NOMBRE,P.PERPARAPE1 AS PART1APELLIDO,P.PERPARCARG1 AS PART1CARGO,
                P.PERPARNOM2 AS PART2NOMBRE,P.PERPARAPE2 AS PART2APELLIDO,P.PERPARCARG2 AS PART2CARGO,
                P.PERPARNOM3 AS PART3NOMBRE,P.PERPARAPE3 AS PART3APELLIDO,P.PERPARCARG3 AS PART3CARGO,
                M.MESNUMERO, 
                CASE
                    WHEN P.ESTCODIGO=1 THEN 'ACTIVO'
                    WHEN P.ESTCODIGO=9 THEN 'MAIL SIN CONFIRMAR'
                    WHEN P.ESTCODIGO=8 THEN 'SIN LIBERAR'
                    WHEN P.ESTCODIGO=3 THEN 'ELIMINADO'
                END AS PERFILESTADO,
                (   SELECT CAST(LIST(SECT.SECDESCRI)  AS VARCHAR(10000))
                    FROM PER_SECT PS
                    LEFT OUTER JOIN SEC_MAEST SECT ON SECT.SECCODIGO=PS.SECCODIGO
                    WHERE PS.PERCODIGO=P.PERCODIGO) AS SECTORES,
                (   SELECT CAST(LIST(SSECT.SECSUBDES)  AS VARCHAR(10000))
                    FROM PER_SSEC PSS
                    LEFT OUTER JOIN SEC_SUB SSECT ON SSECT.SECSUBCOD=PSS.SECSUBCOD
                    WHERE PSS.PERCODIGO=P.PERCODIGO) AS SUBSECTORES,
                (   SELECT CAST(LIST(CAT.CATDESCRI)   AS VARCHAR(10000))
                    FROM PER_CATE PC
                    LEFT OUTER JOIN CAT_MAEST CAT ON CAT.CATCODIGO=PC.CATCODIGO
                    WHERE PC.PERCODIGO=P.PERCODIGO) AS CATEGORIAS,
                (   SELECT CAST(LIST(SCAT.CATSUBDES)  AS VARCHAR(10000))
                    FROM PER_SCAT PSC
                    LEFT OUTER JOIN CAT_SUB SCAT ON SCAT.CATSUBCOD=PSC.CATSUBCOD
                    WHERE PSC.PERCODIGO=P.PERCODIGO) AS SUBCATEGORIAS,
                (SELECT COUNT(*)
                FROM REU_CABE R
                WHERE (R.PERCODDST=P.PERCODIGO OR R.PERCODSOL=P.PERCODIGO) AND R.REUESTADO=2 AND COALESCE(R.AGEREG,0)=0) AS REUNIONESCONFIRMADAS,
                (SELECT COUNT(*)
                FROM REU_CABE R
                WHERE (R.PERCODSOL=P.PERCODIGO) AND R.REUESTADO=1 AND COALESCE(R.AGEREG,0)=0) AS REUNIONESENVIADAS,
                (SELECT COUNT(*)
                FROM REU_CABE R
                WHERE (R.PERCODDST=P.PERCODIGO) AND R.REUESTADO=1 AND COALESCE(R.AGEREG,0)=0) AS REUNIONESRECIBIDAS,
                (SELECT COUNT(*)
                FROM REU_CABE R
                WHERE (R.PERCODDST=P.PERCODIGO OR R.PERCODSOL=P.PERCODIGO) AND R.REUESTADO<>3 AND COALESCE(R.AGEREG,0)=0) AS REUNIONESTOTALES
            FROM PER_MAEST P
            LEFT OUTER JOIN PER_TIPO T ON T.PERTIPO=P.PERTIPO
            LEFT OUTER JOIN PER_CLASE C ON C.PERCLASE=P.PERCLASE
            LEFT OUTER JOIN TBL_PAIS S ON S.PAICODIGO=P.PAICODIGO
            LEFT OUTER JOIN MES_MAEST M ON M.MESCODIGO=P.MESCODIGO
            ORDER BY P.PERNOMBRE,P.PERAPELLI, P.PERCOMPAN";
    $Table = sql_query($query,$conn);


    $objPHPExcel = new PHPExcel();
// Agregamos las columnas con sus nombres respectivos

    $objPHPExcel->getActiveSheet()
                ->setCellValue('A1', 'REG')
                ->setCellValue('B1', 'NOMBRE')
                ->setCellValue('C1', 'APELLIDO')
                ->setCellValue('D1', 'COMPANIA')
                ->setCellValue('E1', 'CORREO')
                ->setCellValue('F1', 'CIUDAD')
                ->setCellValue('G1', 'ESTADO')
                ->setCellValue('H1', 'PAIS')
                ->setCellValue('I1', 'CODIGOPOSTAL')
                ->setCellValue('J1', 'TELEFONO')
                ->setCellValue('K1', 'DIRECCION')
                ->setCellValue('L1', 'CARGO')
                ->setCellValue('M1', 'USUARIOACCESO')
                ->setCellValue('N1', 'TIPO')
                ->setCellValue('O1', 'CLASE')
                ->setCellValue('P1', 'ESADMIN')
                ->setCellValue('Q1', 'SITIOWEB')
                ->setCellValue('R1', 'DESCRIPCIONEMPRESA')
                ->setCellValue('S1', 'USADISPONIBILIDAD')
                ->setCellValue('T1', 'USAREUNIONES')
                ->setCellValue('U1', 'COMENTARIOREGISTRACION')
                ->setCellValue('V1', 'PART1NOMBRE')
                ->setCellValue('W1', 'PART1APELLIDO')
                ->setCellValue('X1', 'PART1CARGO')
                ->setCellValue('Y1', 'PART2NOMBRE')
                ->setCellValue('Z1', 'PART2APELLIDO')
                ->setCellValue('AA1', 'PART2CARGO')
                ->setCellValue('AB1', 'PART3NOMBRE')
                ->setCellValue('AC1', 'PART3APELLIDO')
                ->setCellValue('AD1', 'PART3CARGO')
                ->setCellValue('AE1', 'MESNUMERO')
                ->setCellValue('AF1', 'PERFILESTADO')
                ->setCellValue('AG1', 'SECTORES')
                ->setCellValue('AH1', 'SUBSECTORES')
                ->setCellValue('AI1', 'CATEGORIAS')
                ->setCellValue('AJ1', 'SUBCATEGORIAS')
                ->setCellValue('AK1', 'REUNIONESCONFIRMADAS')
                ->setCellValue('AL1', 'REUNIONESENVIADAS')
                ->setCellValue('AM1', 'REUNIONESRECIBIDAS')
                ->setCellValue('AN1', 'REUNIONESTOTALES');
            
            //Titulo de la tabla en negrita
            $objPHPExcel->getActiveSheet()->getStyle('A1:AN1')->getFont()->setBold(true);

            //Recorremos los datos           
            for($i=0; $i<$Table->Rows_Count; $i++){
                    $row = $Table->Rows[$i];
                    $ii = $i+2;
                    $reg 	= trim($row['REG']);
                    $nombre	= trim($row['NOMBRE']);
                    $apellido	= trim($row['APELLIDO']);
                    $compania = trim($row['COMPANIA']);
                    $correo = trim($row['CORREO']);
                    $ciudad = trim($row['CIUDAD']);
                    $estado = trim($row['ESTADO']);
                    $pais = trim($row['PAIS']);
                    $codigopostal = trim($row['CODIGOPOSTAL']);
                    $telefono = trim($row['TELEFONO']);
                    $direccion = trim($row['DIRECCION']);
                    $cargo = trim($row['CARGO']);
                    $usuarioacceso = trim($row['USUARIOACCESO']);
                    $tipo = trim($row['TIPO']);
                    $clase = trim($row['CLASE']);
                    $esadmin = trim($row['ESADMIN']);
                    $sitioweb = trim($row['SITIOWEB']);
                    $descripcionempresa = trim($row['DESCRIPCIONEMPRESA']);
                    $usadisponibilidad = trim($row['USADISPONIBILIDAD']);
                    $usareuniones = trim($row['USAREUNIONES']);
                    $comentarioregistracion = trim($row['COMENTARIOREGISTRACION']);
                    $part1nombre = trim($row['PART1NOMBRE']);
                    $part1apellido = trim($row['PART1APELLIDO']);
                    $part1cargo = trim($row['PART1CARGO']);
                    $part2nombre = trim($row['PART2NOMBRE']);
                    $part2apellido = trim($row['PART2APELLIDO']);
                    $part2cargo = trim($row['PART2CARGO']);
                    $part3nombre = trim($row['PART3NOMBRE']);
                    $part3apellido = trim($row['PART3APELLIDO']);
                    $part3cargo = trim($row['PART3CARGO']);
                    
                    $mesnumero = trim($row['MESNUMERO']);
                    $perfilestado = trim($row['PERFILESTADO']);
                    $sectores = trim($row['SECTORES']);
                    $subsectores = trim($row['SUBSECTORES']);
                    $categorias = trim($row['CATEGORIAS']);
                    $subcategorias = trim($row['SUBCATEGORIAS']);
                    $reunionesconfirmadas = trim($row['REUNIONESCONFIRMADAS']);
                    $reunionesenviadas = trim($row['REUNIONESENVIADAS']);
                    $reunionesrecibidas = trim($row['REUNIONESRECIBIDAS']);
                    $reunionestotales = trim($row['REUNIONESTOTALES']);
                    
                    //Asignamos a cada celda un valor
                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$ii, $reg);
                    $objPHPExcel->getActiveSheet()->setCellValue('B'.$ii, $nombre);
                    $objPHPExcel->getActiveSheet()->setCellValue('C'.$ii, $apellido);
                    $objPHPExcel->getActiveSheet()->setCellValue('D'.$ii, $compania);
                    $objPHPExcel->getActiveSheet()->setCellValue('E'.$ii, $correo);
                    $objPHPExcel->getActiveSheet()->setCellValue('F'.$ii, $ciudad);
                    $objPHPExcel->getActiveSheet()->setCellValue('G'.$ii, $estado);
                    $objPHPExcel->getActiveSheet()->setCellValue('H'.$ii, $pais);
                    $objPHPExcel->getActiveSheet()->setCellValue('I'.$ii, $codigopostal);
                    $objPHPExcel->getActiveSheet()->setCellValue('J'.$ii, $telefono);
                    $objPHPExcel->getActiveSheet()->setCellValue('K'.$ii, $direccion);
                    $objPHPExcel->getActiveSheet()->setCellValue('L'.$ii, $cargo);
                    $objPHPExcel->getActiveSheet()->setCellValue('M'.$ii, $usuarioacceso);
                    $objPHPExcel->getActiveSheet()->setCellValue('N'.$ii, $tipo);
                    $objPHPExcel->getActiveSheet()->setCellValue('O'.$ii, $clase);
                    $objPHPExcel->getActiveSheet()->setCellValue('P'.$ii, $esadmin);

                    $objPHPExcel->getActiveSheet()->setCellValue('Q'.$ii, $sitioweb);
                    $objPHPExcel->getActiveSheet()->setCellValue('R'.$ii, $descripcionempresa);
                    $objPHPExcel->getActiveSheet()->setCellValue('S'.$ii, $usadisponibilidad);
                    $objPHPExcel->getActiveSheet()->setCellValue('T'.$ii, $usareuniones);
                    $objPHPExcel->getActiveSheet()->setCellValue('U'.$ii, $comentarioregistracion);
                    $objPHPExcel->getActiveSheet()->setCellValue('V'.$ii, $part1nombre);
                    $objPHPExcel->getActiveSheet()->setCellValue('W'.$ii, $part1apellido);
                    $objPHPExcel->getActiveSheet()->setCellValue('X'.$ii, $part1cargo);
                    $objPHPExcel->getActiveSheet()->setCellValue('Y'.$ii, $part2nombre);
                    $objPHPExcel->getActiveSheet()->setCellValue('Z'.$ii, $part2apellido);
                    $objPHPExcel->getActiveSheet()->setCellValue('AA'.$ii, $part2cargo);
                    $objPHPExcel->getActiveSheet()->setCellValue('AB'.$ii, $part3nombre);
                    $objPHPExcel->getActiveSheet()->setCellValue('AC'.$ii, $part3apellido);
                    $objPHPExcel->getActiveSheet()->setCellValue('AD'.$ii, $part3cargo);
                    $objPHPExcel->getActiveSheet()->setCellValue('AE'.$ii, $mesnumero);
                    $objPHPExcel->getActiveSheet()->setCellValue('AF'.$ii, $perfilestado);
                    $objPHPExcel->getActiveSheet()->setCellValue('AG'.$ii, $sectores);
                    $objPHPExcel->getActiveSheet()->setCellValue('AH'.$ii, $subsectores);
                    $objPHPExcel->getActiveSheet()->setCellValue('AI'.$ii, $categorias);
                    $objPHPExcel->getActiveSheet()->setCellValue('AJ'.$ii, $subcategorias);
                    $objPHPExcel->getActiveSheet()->setCellValue('AK'.$ii, $reunionesconfirmadas);
                    $objPHPExcel->getActiveSheet()->setCellValue('AL'.$ii, $reunionesenviadas);
                    $objPHPExcel->getActiveSheet()->setCellValue('AM'.$ii, $reunionesrecibidas);
                    $objPHPExcel->getActiveSheet()->setCellValue('AN'.$ii, $reunionestotales);
        
            }


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="ExportarPerfiles.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//Descarga de archivo
$objWriter->save('php://output');


sql_close($conn);
?>