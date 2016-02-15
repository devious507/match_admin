<?php

require_once("PHPExcel/Classes/PHPExcel.php");

$sheet = new PHPExcel();

$sheet->getActiveSheet()->setCellValue('A1','Last Name');
$sheet->getActiveSheet()->setCellValue('B1','First Name');
$sheet->getActiveSheet()->setCellValue('C1','Middle Name');
$sheet->getActiveSheet()->setCellValue('D1','Gender');
$sheet->getActiveSheet()->setCellValue('E1','Birthdate');
$sheet->getActiveSheet()->setCellValue('F1','Relay');
$sheet->getActiveSheet()->setCellValue('G1','Firing Point');
$sheet->getActiveSheet()->setCellValue('H1','Organization');
$sheet->getActiveSheet()->setCellValue('I1','Address');
$sheet->getActiveSheet()->setCellValue('J1','Address Cont.');
$sheet->getActiveSheet()->setCellValue('K1','City');
$sheet->getActiveSheet()->setCellValue('L1','State');
$sheet->getActiveSheet()->setCellValue('M1','Zip');
$sheet->getActiveSheet()->setCellValue('N1','Country');
$sheet->getActiveSheet()->setCellValue('O1','Email');
$sheet->getActiveSheet()->setCellValue('P1','Email');
$sheet->getActiveSheet()->setCellValue('Q1','Phone');


$row=2;
for($relay=1; $relay <= 3; $relay++) {
	for($fp=1; $fp<=12; $fp++) {
		$sheet->getActiveSheet()->setCellValue("F".$row,$relay);
		$sheet->getActiveSheet()->setCellValue("G".$row,$fp);
		$row++;
	}
}
$writer = PHPExcel_IOFactory::createWriter($sheet,"Excel2007");
$writer->save("generatedTest.xlsx");


