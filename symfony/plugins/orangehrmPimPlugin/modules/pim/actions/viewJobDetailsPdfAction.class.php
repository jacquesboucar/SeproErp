<?php

/**
 * ViewJobDetailsAction
 */
require_once sfConfig::get('sf_root_dir').'/plugins/sfTCPDFPlugin/lib/tcpdf/tcpdf_fiche.php';
class viewJobDetailsPdfAction extends basePimAction {

	  public function getJobTitleService() {
        if (is_null($this->jobTitleService)) {
            $this->jobTitleService = new JobTitleService();
            $this->jobTitleService->setJobTitleDao(new JobTitleDao());
        }
        return $this->jobTitleService;
    }
    private function _getJobTitles($jobTitleId) {

        $jobTitleList = $this->getJobTitleService()->getJobTitleList("", "", false);
        //$choices = array('' => '-- ' . __('Select') . ' --');

        foreach ($jobTitleList as $job) {
            if (($job->getId() == $jobTitleId)) {
                $name = $job->getJobTitleName();
            }
        }
        return $name;
    }

    private function _getEEOCategories($id) {
        $jobService = new JobCategoryService();
        $categories = $jobService->getJobCategoryList();
        foreach ($categories as $category) {
          if ($category->getId() == $id) {
            $name = $category->getName();
          }
        }
        return $name;
    }

    private function _getLocations($id) {
        $locationService = new LocationService();
        $locations = $locationService->getLocationList();
        foreach ($locations as $location) {
            if ($location->id == $id) {
              $name = $location->name;
            }
        }
        return $name;
    }

    private function _getEmpStatuses($id) {
        $empStatusService = new EmploymentStatusService();
        $statuses = $empStatusService->getEmploymentStatusList();
        foreach ($statuses as $status) {
            if ($status->getId() == $id) {
              $name = $status->getName();
            }
        }
        return $name;
    }




	public function execute($request) {
		$emp_number = $request->getParameter('empNumber');
		$employee = $this->getEmployeeService()->getEmployee($emp_number);
		$name_first = $employee->getFirstName();
		$name_last = $employee->getLastName();
		$name_middle = $employee->getMiddleName();
		$cat = $employee->getEeoCatCode();
		$cat = $this->_getEEOCategories($cat);
		$job_id = $employee->getJobTitleCode();
		$job_title =  $this->_getJobTitles($job_id);
		$sigle = $employee->getSigle();
		$entite = $employee->getEntite();
		$version = $employee->getVersion();
		$locationList = $employee->locations;
		$dir_id = $locationList[0]->id;
		$direction = $this->_getLocations($dir_id);
		$supervisors = $this->getEmployeeService()->getSupervisorList();
		$supfist_name = $supervisors[0]->firstName;
		$suplast_name = $supervisors[0]->lastName;
		$matricule = $employee->getEmployeeId();
		$status = $employee->getEmpStatus();
		$status = $this->_getEmpStatuses($status);
		$mission = $employee->getMission();
		$relation = $employee->getRelation();
		$formation = $employee->getFormation();
		$experience = $employee->getExp();
		$competence = $employee->getCompetence();
		$remplacant = $employee->getRemplacant();
		$activite = $employee->getActivite();
		// create new PDF document
       $pdf = new FICHE(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

      // set document information
       $pdf->SetCreator(PDF_CREATOR);
       $pdf->SetAuthor('Sablux');
       $pdf->SetTitle('Fiche de description de poste');
       $pdf->SetSubject('Fiche de poste : '. $name_first .' '.$name_last);
       $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

       // set default header data
       $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
       // $pdf->setFooterData(array(0,64,0), array(0,64,128));

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
$pdf->SetFont('Helvetica', '', 12, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
// $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

// Set some content to print
$html = <<<EOD

<table border="0.5" cellspacing="0" cellpadding="4">
  <tr bgcolor="#770a82" color="#ffffff">
    <td><strong>Intitule du poste</strong></td><td><strong>$job_title </strong> </td>
  </tr><tr bgcolor="#cccccc" color="#000000">
    <td><strong>Sigle </strong></td><td><strong>$sigle </strong> </td>
  </tr><tr bgcolor="#cccccc" color="#000000">
    <td><strong>Intitule du poste</strong></td><td>$job_title</td>
  </tr><tr bgcolor="#cccccc" color="#000000">
    <td><strong>Prénom et Nom du titulaire</strong></td><td>$name_first  $name_middle $name_last </td>
  </tr><tr bgcolor="#cccccc" color="#000000">
    <td><strong>Département</strong></td><td>$entite</td>
  </tr><tr bgcolor="#cccccc" color="#000000">
    <td><strong>Remplaçant habilité</strong></td><td>$remplacant</td>
  </tr><tr bgcolor="#cccccc" color="#000000">
    <td><strong>Société </strong></td><td>$direction</td>
  </tr><tr bgcolor="#cccccc" color="#000000">
    <td><strong>Matricule</strong></td><td>$matricule</td>
  </tr><tr bgcolor="#cccccc" color="#000000">
    <td><strong>Version</strong></td><td>$version</td>
  </tr><tr bgcolor="#cccccc" color="#000000">
    <td><strong>Categorie</strong></td><td>$cat</td>
  </tr><tr bgcolor="#cccccc" color="#000000">
    <td><strong>Type de contrat</strong></td><td>$status</td>
  </tr>
</table>

<br><br><br>
<table border="0.5" cellspacing="0" cellpadding="5">
  <tr>
    <td>
      <br>
      <b><u>Mission</u></b><br>
      $mission
    </td>
  </tr>

  <tr>
    <td>
      <br>
      <b><u>Activités principales</u></b><br>
      $activite
    </td>
  </tr>
</table>
<br><br>

<table border="0.5" cellspacing="0" cellpadding="5">
  <tr>
    <td>
      <b><u>Relations fonctionnelles</u></b>
    </td>

    <td>
      $relation
    </td>
  </tr>

  <tr>
    <td>
      <b><u>Formations souhaitées</u></b>
    </td>

    <td>$formation</td>
  </tr>

  <tr>
    <td>
      <b><u>Expériences souhaitée</u></b>
    </td>

    <td>$experience</td>
  </tr>

  <tr>
    <td colspan="2">
      <b><u>Compétences et aptitudes requises</u></b><br>
      $competence
    </td>
  </tr>
</table>

<br><br><br><br><br>

<b><u>Signature supérieure hiérarchique</u></b>

<br><br><br><br><br><br><br>

<b><u>Signature titulaire (mention lu et approuvé)</u></b>

<br><br><br><br><br><br><br>


EOD;

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('fiche_'.$name_first.'_'.$name_last, 'I');

	}
}
