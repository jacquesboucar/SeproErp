<?php

/**
 * ViewJobDetailsAction
 */
require_once sfConfig::get('sf_root_dir').'/plugins/sfTCPDFPlugin/lib/tcpdf/tcpdf.php';
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
       $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

      // set document information
       $pdf->SetCreator(PDF_CREATOR);
       $pdf->SetAuthor('Sablux');
       $pdf->SetTitle('sablux fiche');
       $pdf->SetSubject('fiche de poste');
       $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

       // set default header data
       $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
       $pdf->setFooterData(array(0,64,0), array(0,64,128));

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
$pdf->SetFont('dejavusans', '', 14, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

// Set some content to print
$html = <<<EOD
<strong>Intitule du poste   </strong>$job_title<br>
<strong>Sigle </strong> $sigle<br>
<strong>Prénom et Nom du titulaire   </strong>$name_first  $name_middle $name_last<br>
<strong>Département   </strong> $entite<br>
<strong>Remplaçant habilité </strong>$remplacant<br>
<strong>Société </strong> $direction<br>
<strong>Matricule </strong> $matricule<br>
<strong>Version </strong>$version<br>
<strong>Categorie </strong>$cat<br>
<strong>Type de contrat </strong>$status<br>
<h3>Mission</h3>$mission<br>
<h3>Activités principales</h3>$activite<br><br>
<strong>Relations fonctionnelles  </strong> $relation<br>
<strong>Formations souhaitées  </strong>$formation<br>
<strong>Expériences souhaitée  </strong>$experience<br> 
<h3>Compétences et aptitudes requises  </h3>$competence<br>
EOD;

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output();

	}
}			