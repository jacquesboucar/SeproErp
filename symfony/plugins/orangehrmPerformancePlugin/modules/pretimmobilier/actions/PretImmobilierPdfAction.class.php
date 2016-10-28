<?php

/**
 * ViewJobDetailsAction
 */
require_once sfConfig::get('sf_root_dir').'/plugins/sfTCPDFPlugin/lib/tcpdf/tcpdf_vehicule.php';
require_once sfConfig::get('sf_root_dir').'/plugins/orangehrmPerformancePlugin/lib/form/BasePefromanceSearchForm.php';

class PretImmobilierPdfAction extends basePeformanceAction {

   /**
     *
     * @return \PerformanceReviewService
     */
    public function getPretImmobilierService() {
        if ($this->pretimmobilierService == null) {
            return new PretImmobilierService();
        } else {
            return $this->pretimmobilierService;
        }
    }

    /**
     *
     * @return type
     */
    public function getPretImmobilierById($id) {

        $parameters ['id'] = $id;
        $pretimmobilier = $this->getPretImmobilierService()->searchPretImmobilier($parameters);

        return $pretimmobilier;
    }


    /**
     * Get EmployeeService
     * @returns EmployeeService
     */
    public function getEmployeeService() {
        if (is_null($this->employeeService)) {
            $this->employeeService = new EmployeeService();
            $this->employeeService->setEmployeeDao(new EmployeeDao());
        }
        return $this->employeeService;
    }

    public function execute($request) {

    $pretimmobilier_id = $request->getParameter('id');
    $param = array('id' => $pretimmobilier_id);
    $pretimmobilier = $this->getPretImmobilierService()->searchPretImmobilier($param);
    $employe_name = $pretimmobilier->getEmployee()->getFullName();
    $employe_lastname = $pretimmobilier->getEmployee()->getLastName();
    $employe_city = $pretimmobilier->getEmployee()->getCity();
    $employe_telephone = $pretimmobilier->getEmployee()->getEmpMobile();
    $employe_adresse = $pretimmobilier->getEmployee()->getStreet1();
    $montant = $pretimmobilier->getMontantPret();
    $objet = $pretimmobilier->getObjet();
    $nombremois = $pretimmobilier->getNombreMois();
    $dateaccord = $pretimmobilier->getDateAccord();
    $dateprelevement = $pretimmobilier->getDatePrelevement();
    $quotitesaisissable = $pretimmobilier->getQuotiteSaisissable();
    $description  = $pretimmobilier->getDescription();
    $valider  = $pretimmobilier->getValider();

    $user = sfContext::getInstance()->getUser();
    $employee = $this->getEmployeeService()->getEmployee($user->getAttribute('auth.empNumber'));
    $supernom = $employee->getFullName();
    $superprenom = $employee->getLastName();
    $superadresse = $employee->getStreet1();
    $supermobile = $employee->getEmpMobile();
    $supercity = $employee->getCity();
    $img = public_path('../../symfony/web/themes/default/images/logo.png');
    $date=  date("d-m-Y");

    // create new PDF document
       $pdf = new FICHE("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

      // set document information
       $pdf->SetCreator(PDF_CREATOR);
       $pdf->SetAuthor('Sablux');
       $pdf->SetTitle("FICHE PRET IMMOBILIER");
       $pdf->SetSubject('FICHE PRET IMMOBILIER');
       $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

       // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
       $pdf->setFooterData(false);

      // $pdf->setPrintHeader(false);
      // $pdf->setPrintFooter(false);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('Helvetica', '', 6, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();
//var_dump($img);die;
$curPage = $pdf->getAliasNumPage();
$nbPage = $pdf->getAliasNbPages();
$html = <<<EOD
  <table border="0.4" cellspacing="0" cellpadding="4">
    <tr>
      <td colspan="2"  bgcolor="#ffffff" color="#770a82" style="font-size: 20px;font-weight:bold;text-align:center;">
        <img src="../../symfony/web/webres_55a775cf9fcff8.50052456/themes/default/images/login/logo.png" alt="SeproRH">
      </td>
      <td colspan="14" border="0" bgcolor="#770a82" color="#ffffff" style="font-size: 10px;font-weight:bold;text-align:center;">FICHE DE PRET IMMOBILIER </td>
      <td colspan="2"  bgcolor="#ffffff" color="#770a82" style="font-size: 20px;font-weight:bold;text-align:center;">
        <img src="../../symfony/web/webres_55a775cf9fcff8.50052456/themes/default/images/login/logo.png" alt="SeproRH">
      </td>
    </tr>
</table>
<h3 style="text-align:right;">$date</h3>
EOD;


$html .= <<<EOD
          <br /><br />
         <table border="0.5" cellspacing="0" cellpadding="4">
              <tr bgcolor="#770a82" color="#ffffff">
                <td colspan="2"><strong>Responsable</strong></td>
              </tr>
              <tr bgcolor="#cccccc" color="#000000">
                <td><strong>Nom & Prenom</strong></td><td><strong>$supernom $superprenom</strong></td>
              </tr>
              <tr bgcolor="#cccccc" color="#000000">
                <td><strong>Adresse</strong></td><td>$superadresse / $supercity</td>
              </tr>
              <tr bgcolor="#cccccc" color="#000000">
                <td><strong>Téléphone</strong></td><td>$supermobile</td>
              </tr>
              <tr bgcolor="#770a82" color="#ffffff">
                <td colspan="2"><strong>Employe</strong></td>
              </tr>
              <tr bgcolor="#cccccc" color="#000000">
                <td><strong>Nom & Prenom </strong></td><td>$employe_lastname $employe_name</td>
              </tr>
              <tr bgcolor="#cccccc" color="#000000">
                <td><strong>Adresse</strong></td><td> $employe_adresse / $employe_city</td>
              </tr>
              <tr bgcolor="#cccccc" color="#000000">
                <td><strong>Téléphone</strong></td><td>$employe_telephone</td>
              </tr>
        </table>
      <br><br><br>
      <table border="0.4">
          <tr>
            <th><b> Motant du pret </b></th>
            <th><b> Objet  </b></th>
            <th><b> Nombre de mois  </b></th>
            <th><b> Date prelevement</b></th>
            <th><b> Quotite saisissable </b></th>
            <th><b> Description </b></th>
          </tr>
          <tr>
            <td> $montant </td>
            <td> $objet </td>
            <td> $nombremois </td>
            <td> $dateprelevement </td>
            <td> $quotitesaisissable </td>
            <td> $description </td>
          </tr>
      </table>
EOD;

$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
// Print text using writeHTMLCell()


// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('PretImmobilier_'.$employe_name);

  }
}

