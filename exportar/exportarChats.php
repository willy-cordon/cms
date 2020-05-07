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
    
        $query="SELECT DISTINCT PS.PERCOMPAN AS SOLEMPRESA,PS.PERNOMBRE AS SOLNOMBRE,PS.PERAPELLI AS SOLAPELLI,PS.PERCORREO AS SOLCORREO,
						PD.PERCOMPAN AS DSTEMPRESA,PD.PERNOMBRE AS DSTNOMBRE,PD.PERAPELLI AS DSTAPELLI,PD.PERCORREO AS DSTCORREO
				FROM TBL_CHAT C
				LEFT OUTER JOIN PER_MAEST PS ON PS.PERCODIGO=C.PERCODIGO
				LEFT OUTER JOIN PER_MAEST PD ON PD.PERCODIGO=C.PERCODDST
				WHERE PS.PERCODIGO IS NOT NULL AND PD.PERCODIGO IS NOT NULL
				ORDER BY PS.PERCOMPAN,PD.PERCOMPAN ";
    $Table = sql_query($query,$conn);
    logerror($query);
    $objPHPExcel = new PHPExcel();
// Agregamos las columnas con sus nombres respectivos

    $objPHPExcel->getActiveSheet()
                ->setCellValue('A1', 'Empresa Solicitante')
				->setCellValue('B1', 'Nombre Soliticante')
				->setCellValue('C1', 'Apellido Soliticante')
				->setCellValue('D1', 'Correo Soliticante')
                ->setCellValue('E1', 'Empresa Destino')
				->setCellValue('F1', 'Nombre Destino')
				->setCellValue('G1', 'Apellido Destino')
				->setCellValue('H1', 'Correo Destino');
            
            //Titulo de la tabla en negrita
            $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);

            //Recorremos los datos           
            for($i=0; $i<$Table->Rows_Count; $i++){
                    $row = $Table->Rows[$i];
                    $ii = $i+2;
                    $solempresa	= trim($row['SOLEMPRESA']);
					$solnombre	= trim($row['SOLNOMBRE']);
					$solapelli	= trim($row['SOLAPELLI']);
					$solcorreo	= trim($row['SOLCORREO']);
                    $dstempresa	= trim($row['DSTEMPRESA']);
					$dstnombre	= trim($row['DSTNOMBRE']);
					$dstapelli	= trim($row['DSTAPELLI']);
					$dstcorreo	= trim($row['DSTCORREO']);
                    
                    //Asignamos a cada celda un valor
                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$ii, $solempresa);
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$ii, $solnombre);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$ii, $solapelli);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$ii, $solcorreo);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$ii, $dstempresa);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$ii, $dstnombre);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$ii, $dstapelli);
                    $objPHPExcel->getActiveSheet()->setCellValue('H'.$ii, $dstcorreo);
                    
            }


    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ExportarConversaciones.xlsx"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    //Descarga de archivo
    $objWriter->save('php://output');


    sql_close($conn);
    ?>