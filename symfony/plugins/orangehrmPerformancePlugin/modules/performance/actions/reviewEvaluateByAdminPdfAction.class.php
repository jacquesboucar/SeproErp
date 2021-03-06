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
    $finalcomment = $this->getReview($review_id)->getFinalComment();
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
            $existe_group[$key] = $kpigroup;
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
      <td colspan="2" rowspan="2"  bgcolor="#ffffff" color="#770a82" style="font-size: 10px;font-weight:bold;text-align:center;"> 
      <img src="../../symfony/web/webres_55a775cf9fcff8.50052456/themes/default/images/login/logosablux.png" alt="SeproRH">
      </td>
      <td colspan="14" rowspan="1" border="0" bgcolor="#ffffff" color="#770a82" style="font-size: 16px;font-weight:bold;text-align:center;">  SYSTÈME DE MANAGEMENT DE LA QUALITE TABLEAU DE BORD DU SMQ </td>
      <td colspan="3" rowspan="2" bgcolor="#ffffff" color="#770a82" > Page: $curPage sur $nbPage  </td>
    </tr>
    <tr>
      <td colspan="7" border="0" bgcolor="#ffffff" color="#770a82" style="font-size: 12px;font-weight:bold;" > Employé : $employe_name  </td>
      <td colspan="7" border="0" bgcolor="#ffffff" color="#770a82" style="font-size: 12px;font-weight:bold;" > Responsable Evaluation : $reviewer_first_name $reviewer_last_name  </td>
    </tr>
EOD;

    foreach ($existe_group as $key => $ex_group) {
$html .= <<<EOD
      <tr>
        <th colspan="19" style="font-weight:bold;text-align:center;font-size: 12px;"><b> $ex_group </b></th>
      </tr><tr>
       
        <th style="width:115px;"><b> INDICATEURS </b></th>
        
        <th style="width:73px;"><b> PERIODICITE </b></th>
        
        <th style="width:50px;"><b> CIBLE </b></th>
       <th style="width:50px;"><b> Janvier </b></th>
        <th style="width:50px;"><b> Fevrier </b></th>
        <th style="width:50px;"><b> Mars </b></th>
        <th style="width:50px;"><b> Avril </b></th>
        <th style="width:50px;"><b> Mai </b></th>
        <th style="width:50px;"><b> Juin </b></th>
        <th style="width:50px;"><b> Juillet </b></th>
        <th style="width:50px;"><b> Aout </b></th>
        <th style="width:50px;"><b> Septembre </b></th>
        <th style="width:50px;"><b> Octobre </b></th>
        <th style="width:50px;"><b> Novembre </b></th>
        <th style="width:50px;"><b> Decembre </b></th>
        <th style="width:50px;"><b> Note finale </b></th>
        <th style="width:50px;"><b> Taux atteint </b></th>
      </tr>

EOD;

    foreach ($rating as $value) {

      $reviewer_id = $value->getReviewerId();
      $reviewer = $this->getPerformanceReviewService()->getReviewerById($reviewer_id);
      $reviewer_group = $reviewer->getReviewerGroupId();
      if ($reviewer_group == 1 && $value->getKpi()->getKpiGroup() == $key) {
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
        $taux_atteind = round((double)(($value->getTauxAtteint()/$value->getValeurCible())*100));
        $comment = $value->getComment();



$html .= <<<EOD

    <tr>
      
      <td style="width:115px;"> $kpi </td>
      
      
      <td style="width:73px;"> $periodicite </td>
      <td style="width:50px;"> $cible </td>
      <td style="width:50px;"> $mois1 </td>
      <td style="width:50px;"> $mois2 </td>
      <td style="width:50px;"> $mois3 </td>
      <td style="width:50px;"> $mois4 </td>
      <td style="width:50px;"> $mois5 </td>
      <td style="width:50px;"> $mois6 </td>
      <td style="width:50px;"> $mois7 </td>
      <td style="width:50px;"> $mois8 </td>
      <td style="width:50px;"> $mois9 </td>
      <td style="width:50px;"> $mois10 </td>
      <td style="width:50px;"> $mois11 </td>
      <td style="width:50px;"> $mois12 </td>
      <td style="width:50px;"> $note_final </td>
      <td style="width:50px;"> $taux_atteind %</td>
    </tr>


EOD;

}
}
}

$html .= <<<EOD
  </table><br/><br/><br/><p><h1><b>Commentaires des valeurs atteintes par mois suivant un indicateur</b></h1></p>
  <table border="0.4" cellspacing="0" cellpadding="4">
   
EOD;
// commentaire par mois
        foreach ($existe_group as $key => $ex_group) {
            $html .= <<<EOD
      <tr>
        <th colspan="13" style="font-weight:bold;text-align:center;font-size: 12px;"><b> $ex_group </b></th>
      </tr>
      <tr>
        <th style="width:100px;" colspan="2"><b> INDICATEURS </b></th>
        <th style="width:70px;"><b> Janvier </b></th>
        <th style="width:70px;"><b> Fevrier </b></th>
        <th style="width:70px;"><b> Mars </b></th>
        <th style="width:70px;"><b> Avril </b></th>
        <th style="width:70px;"><b> Mai </b></th>
        <th style="width:70px;"><b> Juin </b></th>
        <th style="width:70px;"><b> Juillet </b></th>
        <th style="width:70px;"><b> Aout </b></th>
        <th style="width:70px;"><b> Septembre </b></th>
        <th style="width:70px;"><b> Octobre </b></th>
        <th style="width:70px;"><b> Novembre </b></th>
        <th style="width:70px;"><b> Decembre </b></th>
      </tr>
EOD;

            foreach ($rating as $value) {

                $reviewer_id = $value->getReviewerId();
                $reviewer = $this->getPerformanceReviewService()->getReviewerById($reviewer_id);
                $reviewer_group = $reviewer->getReviewerGroupId();
                if ($reviewer_group == 1 && $value->getKpi()->getKpiGroup() == $key) {
                    $kpi = $value->getKpi()->getKpiIndicators();
                    $Commententaire = $this->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois1");
                    $Comment1 = $Commententaire['comment'];
                    $Commententaire = $this->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois2");
                    $Comment2 = $Commententaire['comment'];
                    $Commententaire = $this->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois3");
                    $Comment3 = $Commententaire['comment'];
                    $Commententaire = $this->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois4");
                    $Comment4 = $Commententaire['comment'];
                    $Commententaire = $this->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois5");
                    $Comment5 = $Commententaire['comment'];
                    $Commententaire = $this->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois6");
                    $Comment6 = $Commententaire['comment'];
                    $Commententaire = $this->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois7");
                    $Comment7 = $Commententaire['comment'];
                    $Commententaire = $this->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois8");
                    $Comment8 = $Commententaire['comment'];
                    $Commententaire = $this->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois9");
                    $Comment9 = $Commententaire['comment'];
                    $Commententaire = $this->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois10");
                    $Comment10 = $Commententaire['comment'];
                    $Commententaire = $this->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois11");
                    $Comment11 = $Commententaire['comment'];
                    $Commententaire = $this->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois12");
                    $Comment12 = $Commententaire['comment'];

$html .= <<<EOD

    <tr>
      <td style="width:100px;"> $kpi </td>
      <td style="width:70px;"> $Comment1 </td>
      <td style="width:70px;"> $Comment2 </td>
      <td style="width:70px;"> $Comment3 </td>
      <td style="width:70px;"> $Comment4 </td>
      <td style="width:70px;"> $Comment5 </td>
      <td style="width:70px;"> $Comment6 </td>
      <td style="width:70px;"> $Comment7 </td>
      <td style="width:70px;"> $Comment8 </td>
      <td style="width:70px;"> $Comment9 </td>
      <td style="width:70px;"> $Comment10 </td>
      <td style="width:70px;"> $Comment11 </td>
      <td style="width:70px;"> $Comment12 </td>
    </tr>


EOD;

                }
            }
        }

        $html .= <<<EOD
  </table>
EOD;
        ////fin commentaire par mois
$html .= <<<EOD
    <br/><br/>
    <table>
        <tr>
            <td><b>Commentaire Final : </b> <i>$finalcomment</i></td>
        </tr>
    <table>
EOD;

$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
// Print text using writeHTMLCell()


// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Evaluation_'.$employe_name);

  }
}

