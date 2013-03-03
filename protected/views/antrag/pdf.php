<?php
/**
 * @var Antrag $model
 * @var Sprache $sprache
 * @var AntragController $this
 */

// Muss am Anfang stehen, ansonsten zerhaut's die Zeilenumbrüche; irgendwas mit dem internen Encoding
$absae = $model->getParagraphs();


// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$initiator = null;
$unterstuetzer = array();
foreach ($model->antragUnterstuetzer as $unt) {
	if ($unt->rolle == IUnterstuetzer::$ROLLE_INITIATOR) $initiator = $unt->unterstuetzer;
	if ($unt->rolle == IUnterstuetzer::$ROLLE_UNTERSTUETZER) $unterstuetzer[] = $unt->unterstuetzer;
}

// set document information
$pdf->SetCreator(PDF_CREATOR);
if ($initiator) $pdf->SetAuthor($initiator->name);
$pdf->SetTitle($sprache->get("Antrag") . " " . $model->revision_name . ": " . $model->name);
$pdf->SetSubject($sprache->get("Antrag") . " " . $model->revision_name . ": " . $model->name);
//$pdf->SetSubject($model->name);
//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 006', PDF_HEADER_STRING);

// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

//set margins
$pdf->SetMargins(25, 40, 25);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// ---------------------------------------------------------

$this->widget("AntragPDFWidget", array(
	"sprache" => $sprache,
	"antrag" => $model,
	"pdf" => $pdf,
	"initiator" => $initiator
));



//Close and output PDF document
$pdf->Output('Antrag_' . $model->revision_name . '.pdf', 'I');
