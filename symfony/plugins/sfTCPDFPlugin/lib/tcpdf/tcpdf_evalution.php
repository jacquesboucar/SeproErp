<?php
require_once sfConfig::get('sf_root_dir').'/plugins/sfTCPDFPlugin/lib/tcpdf/tcpdf.php';
class FICHE extends TCPDF {

  //Page header
  public function Header() {
    // Logo
    $image_file = K_PATH_IMAGES.'logosablux.jpg';
    $this->Image($image_file, 17, 0, 45, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    // Set font
    $this->SetFont('helvetica', 'B', 20);
    // Title
    $this->SetXY(50, 15);
    $this->Cell(0, 15, 'Systeme de Management de la qualité', 0, false, 'C', 0, '', 0, false, 'C', 'C');
  }
}
