<?php
if (!file_exists('WEB-INF/lib/tcpdf/'))
  die('TCPDF library is not found in WEB-INF/lib/tcpdf/');

require_once('WEB-INF/lib/tcpdf/tcpdf.php');



class ttPDF extends TCPDF {


  // SetPageWord - sets page word for footer.
  public function SetPageWord($pageWord) {
    $this->page_word = $pageWord;
  }

  // Page header.
  public function Header() {
    // Print logo, which is the only element of our custom header.
    $this->Image($this->image_file, 10, 10, '', '', '', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
  }

  // Page footer.
  public function Footer() {
    // Position at 15 mm from bottom.
    $this->SetY(-15);
    // Set font.
    $this->SetFont('freeserif', 'I', 8);
    // Print localized page number.
    $this->Cell(0, 10, $this->page_word.' '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
  }
}



$pdf = new ttPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set default margins.
$pdf->SetMargins(15, 27, 15);
$pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(10);


// Set auto page breaks.
$pdf->SetAutoPageBreak(true, 25);

// Add a page.
$pdf->AddPage();


// Set font for content.
$pdf->SetFont('helvetica', '', 12);


$html = $smarty->fetch('new_invoice.tpl');


// Write HTML content into the PDF.
$pdf->writeHTML($html, true, false, true, false, '');

// Output PDF to browser.
$pdf->Output('Invoice.pdf', 'I');