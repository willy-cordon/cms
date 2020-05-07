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
    
        $query="SELECT R.REUREG, R.REUFCHREG, 
						R.PERCODSOL,R.PERCODDST,
						R.REUESTADO,R.REUFECHA,R.REUHORA,R.REUFCHCAN,
						R.AGEREG,
						PS.PERCOMPAN AS SOLEMPRESA,PS.PERNOMBRE AS SOLNOMBRE,PS.PERAPELLI AS SOLAPELLI,PS.PERCORREO AS SOLCORREO,
						PD.PERCOMPAN AS DSTEMPRESA,PD.PERNOMBRE AS DSTNOMBRE,PD.PERAPELLI AS DSTAPELLI,PD.PERCORREO AS DSTCORREO,
						M.MESNUMERO AS MESA
            FROM REU_CABE R
            LEFT OUTER JOIN PER_MAEST PS ON R.PERCODSOL=PS.PERCODIGO
            LEFT OUTER JOIN PER_MAEST PD ON R.PERCODDST=PD.PERCODIGO
			LEFT OUTER JOIN MES_DISP D ON D.REUREG=R.REUREG 
            LEFT OUTER JOIN MES_MAEST M ON M.MESCODIGO=D.MESCODIGO
            ORDER BY R.REUFCHREG   
            ";
    $Table = sql_query($query,$conn);
    logerror($query);
    $objPHPExcel = new PHPExcel();
// Agregamos las columnas con sus nombres respectivos

    $objPHPExcel->getActiveSheet()
                ->setCellValue('A1', 'Reg.')
                ->setCellValue('B1', 'Fecha Registro')
                ->setCellValue('C1', 'Empresa Solicitante')
				->setCellValue('D1', 'Nombre Soliticante')
				->setCellValue('E1', 'Apellido Soliticante')
				->setCellValue('F1', 'Correo Soliticante')
                ->setCellValue('G1', 'Empresa Destino')
				->setCellValue('H1', 'Nombre Destino')
				->setCellValue('I1', 'Apellido Destino')
				->setCellValue('J1', 'Correo Destino')
                ->setCellValue('K1', 'Estado')
                ->setCellValue('L1', 'Fecha')
                ->setCellValue('M1', 'Hora')
                ->setCellValue('N1', 'Fecha Cancelacion')
                ->setCellValue('O1', 'Mesa')
               ;
            
            //Titulo de la tabla en negrita
            $objPHPExcel->getActiveSheet()->getStyle('A1:O1')->getFont()->setBold(true);

            //Recorremos los datos           
            for($i=0; $i<$Table->Rows_Count; $i++){
                    $row = $Table->Rows[$i];
                    $ii = $i+2;
                    $reureg 	= trim($row['REUREG']);
                    $reufchreg	= trim($row['REUFCHREG']);
                    $solempresa	= trim($row['SOLEMPRESA']);
					$solnombre	= trim($row['SOLNOMBRE']);
					$solapelli	= trim($row['SOLAPELLI']);
					$solcorreo	= trim($row['SOLCORREO']);
                    $dstempresa	= trim($row['DSTEMPRESA']);
					$dstnombre	= trim($row['DSTNOMBRE']);
					$dstapelli	= trim($row['DSTAPELLI']);
					$dstcorreo	= trim($row['DSTCORREO']);
                    $reuestado 	= trim($row['REUESTADO']);
                    $reufecha 	= trim($row['REUFECHA']);
                    $reuhora 	= trim($row['REUHORA']);
                    $reufchcan 	= trim($row['REUFCHCAN']);
                    $mesa 		= trim($row['MESA']);
                    
					switch($reuestado){
						case 1: $reuestado='PENDIENTE'; break;
						case 2: $reuestado='CONFIRMADA'; break;
						case 3: $reuestado='CANCELADA'; break;
					}					
					
                    //Asignamos a cada celda un valor
                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$ii, $reureg);
                    $objPHPExcel->getActiveSheet()->setCellValue('B'.$ii, $reufchreg);
                    $objPHPExcel->getActiveSheet()->setCellValue('C'.$ii, $solempresa);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$ii, $solnombre);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$ii, $solapelli);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$ii, $solcorreo);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$ii, $dstempresa);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$ii, $dstnombre);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$ii, $dstapelli);
                    $objPHPExcel->getActiveSheet()->setCellValue('J'.$ii, $dstcorreo);
                    $objPHPExcel->getActiveSheet()->setCellValue('K'.$ii, $reuestado);
                    $objPHPExcel->getActiveSheet()->setCellValue('L'.$ii, $reufecha);
                    $objPHPExcel->getActiveSheet()->setCellValue('M'.$ii, $reuhora);
                    $objPHPExcel->getActiveSheet()->setCellValue('N'.$ii, $reufchcan);
                    $objPHPExcel->getActiveSheet()->setCellValue('O'.$ii, $mesa);
                    
            }


    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ExportarReuniones.xlsx"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    //Descarga de archivo
    $objWriter->save('php://output');


    sql_close($conn);
    ?>