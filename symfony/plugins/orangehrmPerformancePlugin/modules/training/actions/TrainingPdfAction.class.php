<?php

/**
 * ViewJobDetailsAction
 */
require_once sfConfig::get('sf_root_dir').'/plugins/sfTCPDFPlugin/lib/tcpdf/tcpdf_vehicule.php';
require_once sfConfig::get('sf_root_dir').'/plugins/orangehrmPerformancePlugin/lib/form/BasePefromanceSearchForm.php';

class TrainingPdfAction extends basePeformanceAction {

   /**
     *
     * @return \PerformanceReviewService
     */
    public function getTrainingService() {
        if ($this->trainingService == null) {
            return new TrainingService();
        } else {
            return $this->trainingService;
        }
    }

    /**
     *
     * @return type
     */
    public function getTrainingById($id) {

        $parameters ['id'] = $id;
        $training = $this->getTrainingService()->searchTraining($parameters);

        return $training;
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

    $training_id = $request->getParameter('id');
    $param = array('id' => $training_id);
    $training = $this->getTrainingService()->searchTraining($param);
    $employe_name = $training->getEmployee()->getFullName();
    $employe_lastname = $training->getEmployee()->getLastName();
    $employe_city = $training->getEmployee()->getCity();
    $employe_telephone = $training->getEmployee()->getEmpMobile();
    $employe_adresse = $training->getEmployee()->getStreet1();
    $description  = $training->getDescription();
    $valider  = $training->getValider();
    $dateapplied = set_datepicker_date_format($training->getDateApplied());

    $user = sfContext::getInstance()->getUser();
    $employee = $this->getEmployeeService()->getEmployee($user->getAttribute('auth.empNumber'));
    $supernom = $employee->getFullName();
    $superprenom = $employee->getLastName();
    $superadresse = $employee->getStreet1();
    $supermobile = $employee->getEmpMobile();
    $supercity = $employee->getCity();

    $date=  date("d-m-Y");

    // create new PDF document
       $pdf = new FICHE("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

      // set document information
       $pdf->SetCreator(PDF_CREATOR);
       $pdf->SetAuthor('Sablux');
       $pdf->SetTitle("FICHE DEMAMDE DE FORMATION");
       $pdf->SetSubject('FICHE DEMAMDE DE FORMATION');
       $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

       // set default header data
       $pdf->SetHeaderData(false);
       $pdf->setFooterData(false);

       $pdf->setPrintHeader(false);
       $pdf->setPrintFooter(false);

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

$curPage = $pdf->getAliasNumPage();
$nbPage = $pdf->getAliasNbPages();
$html = <<<EOD
  <table border="0.4" cellspacing="0" cellpadding="4">
    <tr>
      <td colspan="2"  bgcolor="#ffffff" color="#770a82" style="font-size: 20px;font-weight:bold;text-align:center;"></td>
      <td colspan="14" border="0" bgcolor="#770a82" color="#ffffff" style="font-size: 10px;font-weight:bold;text-align:center;">FICHE DEMAMDE DE FORMATION</td>
      <td colspan="3" bgcolor="#ffffff" color="#770a82" >$dateapplied</td>
    </tr>
</table>
EOD;


$html .= <<<EOD
      <fieldset style="text-align:center;" color="#ffffff">
        <legend>  Employe</legend>
        <br/>
                Nom & Prenom : $employe_lastname $employe_name <br/>
                Adresse : $employe_adresse / $employe_city <br/>
                Telephone : $employe_telephone <br/>
      </fieldset> <br/> <br/> <br/>
      <fieldset class="dotationleft">
        <legend> Responsable :</legend>
            Nom & Prenom : $supernom $superprenom <br/>
            Adresse : $superadresse / $supercity <br/>
            Telephone : $supermobile <br/>
        
      </fieldset>
      <br><br><br>
      <p>
        L’Employeur met à disposition du Salarié un véhicule de fonction de type , immatriculé  
        et propriété de Sablux à compter du 
        Le véhicule, objet de la présente clause, est attribué exclusivement pour l’exercice des fonctions du Salarié 
        et ne peut donc être utilisé que pendant le temps de travail
      </p>
      <p>
        Le Salarié restituera le véhicule, objet de la présente clause, dès la cessation effective de ses fonctions 
        et au plus tard le dernier jour du contrat de travail, quelle que soit la cause de la rupture de ce contrat.
      </p>
    <tr>
      <table>
      <tr>
        <th style="width:70px;"><b> Marque </b></th>
        <th style="width:70px;"><b> Energie </b></th>
        <th style="width:70px;"><b> Matricule vehicule </b></th>
        <th style="width:65px;"><b> Dotation_carburant  </b></th>
        <th style="width:65px;"><b> Description </b></th>
        <th style="width:35px;"><b> Nom et Prenom </b></th>
        <th style="width:35px;"><b> Date </b></th>
      </tr>
EOD;
$html .= <<<EOD

    <tr>
      <td style="width:70px;">  </td>
      <td style="width:70px;">  </td>
      <td style="width:70px;">  </td>
      <td style="width:65px;">  </td>
      <td style="width:65px;">  </td>
      <td style="width:35px;">  </td>
    </tr>
     <p>
     Je m'engage a restituer ce matériel au complet dés la première réquisition de la société Sablux et à n'utiliser ce matériel que dans le cadre des missions que j'effectue avec Sablux 
     </p>

EOD;


$html .= <<<EOD
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

