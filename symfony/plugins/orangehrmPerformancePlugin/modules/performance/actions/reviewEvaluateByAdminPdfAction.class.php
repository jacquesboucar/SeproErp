<?php

/**
 * ViewJobDetailsAction
 */
require_once sfConfig::get('sf_root_dir').'/plugins/sfTCPDFPlugin/lib/tcpdf/tcpdf_evalution.php';
require_once sfConfig::get('sf_root_dir').'/plugins/orangehrmPerformancePlugin/lib/form/BasePefromanceSearchForm.php';

class reviewEvaluateByAdminPdfAction extends basePeformanceAction {

   /**
     *
     * @return \PerformanceReviewService
     */
    public function getPerformanceReviewService() {
        if ($this->performanceReviewService == null) {
            return new PerformanceReviewService();
        } else {
            return $this->performanceReviewService;
        }
    }

    /**
     *
     * @return type
     */
    public function getReview($id) {

        $parameters ['id'] = $id;
        $review = $this->getPerformanceReviewService()->searchReview($parameters, 'piority');
        // print_r($review);
        // exit();
        return $review;
    }

    /**
     *
     * @return \KpiService
     */
    public function getKpiGroupService() {

        if ($this->kpiGroupService == null) {
            return new KpiGroupService();
        } else {
            return $this->kpiGroupService;
        }
    }

    /**
     *
     * @return array
     */
    public function getKpiGroupListAsArray() {
        foreach ($this->getKpiGroupService()->getKpiGroupList() as $group) {
            $kpiGroup[$group->getId()] = $group->getKpiGroupName();
        }
        return $kpiGroup;
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

    $review_id = $request->getParameter('id');
    $param = array('reviewId' => $review_id);
    $rate = array();
    $rating = $this->getPerformanceReviewService()->searchReviewRating($param);
    $employe_name = $this->getReview($review_id)->getEmployee()->getFullName();
    $emp_job = $this->getReview($review_id)->getJobTitle()->getJobTitleName();
    $date_decheance = set_datepicker_date_format($this->getReview($review_id)->getDueDate());
    $exam_start_date = set_datepicker_date_format($this->getReview($review_id)->getWorkPeriodStart());
    $exam_end_date = set_datepicker_date_format($this->getReview($review_id)->getWorkPeriodEnd());
    $date_completed = set_datepicker_date_format($this->getReview($review_id)->getCompletedDate());

    // var_dump($this->getReview($review_id));


    foreach ($rating as $value) {
      $reviewer_id = $value->getReviewerId();
      $reviewer = $this->getPerformanceReviewService()->getReviewerById($reviewer_id);
      $reviewer_group = $reviewer->getReviewerGroupId();

      if ($reviewer_group == 1) {
        $emp_number = $reviewer->getEmployeeNumber();
      }
    }

    $employee = $this->getEmployeeService()->getEmployee($emp_number);
    $reviewer_first_name = $employee->getFirstName();
    $reviewer_last_name = $employee->getLastName();
    // create new PDF document
       $pdf = new FICHE("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

      // set document information
       $pdf->SetCreator(PDF_CREATOR);
       $pdf->SetAuthor('Sablux');
       $pdf->SetTitle("SYSTÈME DE MANAGEMENT DE LA QUALITE");
       $pdf->SetSubject('TABLEAU DE BORD DU SMQ');
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

// set text shadow effect
// $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

// Set some content to print
    //Get all group name and id the groupe id is saved on kpi.
    $groupe =  $this->getKpiGroupListAsArray();
    //Set an array which displayed already viewed groups.
    // var_dump($groupe);
    $existe_group = array();

    foreach ($rating as $value) {
      foreach ($groupe as $key => $group) {
        if ($value->getKpi()->getKpiGroup() == $key) {
          $kpigroup = $group;
          if(!in_array($kpigroup, $existe_group)) {
            $existe_group[] = $kpigroup;
          }
        }
      }
    }


    $gen_date = date("d/m/Y");

    // var_dump($existe_group);

$curPage = $pdf->getAliasNumPage();
$nbPage = $pdf->getAliasNbPages();
$html = <<<EOD
  <table border="0.4" cellspacing="0" cellpadding="4">
    <tr>
      <td colspan="2" rowspan="3"  bgcolor="#ffffff" color="#770a82" style="font-size: 20px;font-weight:bold;text-align:center;"> <br><br> SABLUX <br></td>
      <td colspan="14" rowspan="2" border="0" bgcolor="#770a82" color="#ffffff" style="font-size: 20px;font-weight:bold;text-align:center;">  SYSTÈME DE MANAGEMENT DE LA QUALITE <br> TABLEAU DE BORD DU SMQ </td>
      <td colspan="3" bgcolor="#ffffff" color="#770a82" > Page: $curPage sur $nbPage  </td>
    </tr>
    <tr>
      <td colspan="3" bgcolor="#ffffff" color="#770a82" > Date de création: $date_completed  </td>
    </tr>
    <tr>
      <td colspan="7" border="0" bgcolor="#770a82" color="#ffffff" style="font-size: 16px;font-weight:bold;text-align:center;" > Employé : $employe_name  </td>
      <td colspan="7" border="0" bgcolor="#770a82" color="#ffffff" style="font-size: 16px;font-weight:bold;text-align:center;" > Evaluateur : $reviewer_first_name $reviewer_last_name  </td>
      <td colspan="3" bgcolor="#ffffff" color="#770a82" > Code: </td>
    </tr>
EOD;

    foreach ($existe_group as $ex_group) {
$html .= <<<EOD
      <tr>
        <th colspan="19" style="font-weight:bold;text-align:center;font-size: 12px;"><b> $ex_group </b></th>
      </tr><tr>
        <th style="width:70px;"><b> Objectifs </b></th>
        <th style="width:70px;"><b> INDICATEURS </b></th>
        <th style="width:70px;"><b> MODE DE CALCUL </b></th>
        <th style="width:65px;"><b> PERIODICITE </b></th>
        <th style="width:65px;"><b> PILOTE </b></th>
        <th style="width:35px;"><b> CIBLE </b></th>
        <th style="width:35px;"><b> MOIS 1 </b></th>
        <th style="width:35px;"><b> MOIS 2 </b></th>
        <th style="width:35px;"><b> MOIS 3 </b></th>
        <th style="width:35px;"><b> MOIS 4 </b></th>
        <th style="width:35px;"><b> MOIS 5 </b></th>
        <th style="width:35px;"><b> MOIS 6 </b></th>
        <th style="width:35px;"><b> MOIS 7 </b></th>
        <th style="width:35px;"><b> MOIS 8 </b></th>
        <th style="width:35px;"><b> MOIS 9 </b></th>
        <th style="width:35px;"><b> MOIS 10 </b></th>
        <th style="width:35px;"><b> MOIS 11 </b></th>
        <th style="width:35px;"><b> MOIS 12 </b></th>
        <th style="width:35px;"><b> Note finale </b></th>
        <th style="width:35px;"><b> Taux atteint </b></th>
        <th style="width:73px;"><b> COMMENTAIRE </b></th>
      </tr><tr>
        <td colspan ="3"> </td>
        <td><b> Collecte </b></td>
        <td><b> Analyse </b></td>
        <td colspan = "14"> </td>
      </tr>

EOD;

    foreach ($rating as $value) {

      $reviewer_id = $value->getReviewerId();
      $reviewer = $this->getPerformanceReviewService()->getReviewerById($reviewer_id);
      $reviewer_group = $reviewer->getReviewerGroupId();
      if ($reviewer_group == 1) {
        $kpi = $value->getKpi()->getKpiIndicators();
        $objectifs = $value->getKpi()->getObjectif();
        $mode_calcul = $value->getKpi()->getModeCalcul();
        $periodicite = $value->getKpi()->getDelai();
        $poids = $value->getKpi()->getMaxRating();
        $cible = $value->getValeurCible();
        $mois1 = $value->getRating();
        $mois2 = $value->getMois2();
        $mois3 = $value->getMois3();
        $mois4 = $value->getMois4();
        $mois5 = $value->getMois5();
        $mois6 = $value->getMois6();
        $mois7 = $value->getMois7();
        $mois8 = $value->getMois8();
        $mois9 = $value->getMois9();
        $mois10 = $value->getMois10();
        $mois11 = $value->getMois11();
        $mois12 = $value->getMois12();
        $note_final = $value->getNote();
        $taux_atteind = $value->getTauxAtteint();
        $comment = $value->getComment();



$html .= <<<EOD

    <tr>
      <td style="width:70px;"> $objectifs </td>
      <td style="width:70px;"> $kpi </td>
      <td style="width:70px;"> $mode_calcul </td>
      <td style="width:65px;"> $periodicite </td>
      <td style="width:65px;"> $periodicite </td>
      <td style="width:35px;"> $cible </td>
      <td style="width:35px;"> $mois1 </td>
      <td style="width:35px;"> $mois2 </td>
      <td style="width:35px;"> $mois3 </td>
      <td style="width:35px;"> $mois4 </td>
      <td style="width:35px;"> $mois5 </td>
      <td style="width:35px;"> $mois6 </td>
      <td style="width:35px;"> $mois7 </td>
      <td style="width:35px;"> $mois8 </td>
      <td style="width:35px;"> $mois9 </td>
      <td style="width:35px;"> $mois10 </td>
      <td style="width:35px;"> $mois11 </td>
      <td style="width:35px;"> $mois12 </td>
      <td style="width:35px;"> $note_final </td>
      <td style="width:35px;"> $taux_atteind </td>
      <td style="width:73px;"> $comment </td>
    </tr>


EOD;

}
}
}

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

