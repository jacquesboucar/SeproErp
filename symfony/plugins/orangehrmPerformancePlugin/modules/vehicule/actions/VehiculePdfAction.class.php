<?php

/**
 * ViewJobDetailsAction
 */
require_once sfConfig::get('sf_root_dir').'/plugins/sfTCPDFPlugin/lib/tcpdf/tcpdf_vehicule.php';
require_once sfConfig::get('sf_root_dir').'/plugins/orangehrmPerformancePlugin/lib/form/BasePefromanceSearchForm.php';

class VehiculePdfAction extends basePeformanceAction {

   /**
     *
     * @return \PerformanceReviewService
     */
    public function getVehiculeService() {
        if ($this->vehiculeService == null) {
            return new VehiculeService();
        } else {
            return $this->vehiculeService;
        }
    }

    /**
     *
     * @return type
     */
    public function getVehiculeById($id) {

        $parameters ['id'] = $id;
        $vehicule = $this->getVehiculeService()->searchVehicule($parameters);

        return $vehicule;
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

    $vehicule_id = $request->getParameter('id');
    $param = array('id' => $vehicule_id);
    $vehicule = $this->getVehiculeService()->searchVehicule($param);
        //var_dump($vehicule->getEmployee());die;
    $employe_name = $vehicule->getEmployee()->getFullName();
    $employe_lastname = $vehicule->getEmployee()->getLastName();
    $employe_city = $vehicule->getEmployee()->getCity();
    $employe_telephone = $vehicule->getEmployee()->getEmpMobile();
    $employe_adresse = $vehicule->getEmployee()->getStreet1();
    $marque = $vehicule->getMarque();
    $energie = $vehicule->getEnergie();
    $matricule_vehicule = $vehicule->getMatriculeVehicule();
    $dotation_carburant  = $vehicule->getDotationCarburant();
    $description  = $vehicule->getDescription();
    $valider  = $vehicule->getValider();
    $dateapplied = set_datepicker_date_format($vehicule->getDateApplied());

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
       $pdf->SetTitle("DOTATION DE VEHICULE");
       $pdf->SetSubject('DOTATION DE VEHICULE');
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
      <td colspan="14" border="0" bgcolor="#770a82" color="#ffffff" style="font-size: 10px;font-weight:bold;text-align:center;">FICHE DE DOTATION DE VEHICULE </td>
      <td colspan="3" bgcolor="#ffffff" color="#770a82" >$dateapplied</td>
    </tr>
</table>
EOD;


$html .= <<<EOD
      <fieldset style="text-align:center;" color="#ffffff">
        <legend>  Employe</legend>
        <br/>
                Nom & Prenom : $employe_lastname $employe_name <br/>
                Adresse : $superadresse / $employe_city <br/>
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
        L’Employeur met à disposition du Salarié un véhicule de fonction de type $marque, immatriculé $matricule_vehicule 
        et propriété de Sablux à compter du $date
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

    
      <td style="width:70px;"> $marque </td>
      <td style="width:70px;"> $energie </td>
      <td style="width:70px;"> $matricule_vehicule </td>
      <td style="width:65px;"> $description </td>
      <td style="width:65px;"> $dotation_carburant </td>
      <td style="width:35px;"> $dateapplied </td>
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
$pdf->Output('Evaluation_'.$employe_name);

  }
}

