<?php

/**
 * ViewJobDetailsAction
 */
require_once sfConfig::get('sf_root_dir').'/plugins/sfTCPDFPlugin/lib/tcpdf/tcpdf_kpi.php';
require_once sfConfig::get('sf_root_dir').'/plugins/orangehrmPerformancePlugin/lib/form/BasePefromanceSearchForm.php';

/**
 * Description of searchKpiAction
 *
 * @author nadeera
 */
class searchKpiPdfAction extends basePeformanceAction {

	/**
     *
     * @return \KpiService
     */
    public function getKpiService() {

        if ($this->kpiService == null) {
            return new KpiService();
        } else {
            return $this->kpiService;
        }
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

    /**
     *
     * @param KpiSearchForm $kpiSearchForm
     */
    public function setKpiSearchForm($kpiSearchForm) {
        $this->kpiSearchForm = $kpiSearchForm;
    }

    /**
     * @return mixed
     */
    public function getKpiGroupListAsArray() {
        foreach ($this->getKpiGroupService()->getKpiGroupList() as $group) {
            $kpiGroup[$group->getId()] = $group->getKpiGroupName();
        }
        return $kpiGroup;
    }

	public function execute($request) {
       //$kpis = $this->getKpiService()->searchKpi($parameters = null);
       $kpis = $this->getKpiService()->getAllKpi();

      // create new PDF document
       $pdf = new FICHE(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

      // set document information
       $pdf->SetCreator(PDF_CREATOR);
       $pdf->SetAuthor('Sablux');
       $pdf->SetTitle("Dictionnaire des KPIs");
       $pdf->SetSubject('Dictionnaire des KPIs');
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
    $pdf->SetFont('Helvetica', '', 7, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
   $pdf->AddPage();

// set text shadow effect
// $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

$html = <<<EOD
  <br/><br/><br/>
  <table border="1" cellspacing="0" cellpadding="4" >
  <tr bgcolor="#770a82" color="#ffffff" style="font-weight:bold;text-align:center;font-size: 12px;"><td colspan="5"> Indicateur Clé de Performance</td></tr>
    <tr bgcolor="#770a82" color="#ffffff">
      <th> Kpi Groupe </th>
      <th> Indicateur Clé de Performance </th>
      <th> Objectifs  </th>
      <th> Mode Calcul </th>
      <th> Périodicité  </th>
    </tr>




EOD;
$existe_group = array();
$groupe =  $this->getKpiGroupListAsArray();
 foreach ($groupe as $key => $grp) {
   $existe_group[$key] = $key;
 }
foreach ($existe_group as $key => $grp)
{
    foreach ($kpis as $value)
    {

        $kpi = $value->getKpiIndicators();
        $id_group = $value->getKpiGroup();
        $group = $this->getKpiGroupService()->getKpiGroupById($id_group);
        $Kpigroup = $group->getKpiGroupName();
        if($grp==$id_group)
        {
            //$job_id = $value->getJobTitle();
            //$job_title = $this->_getJobTitles($job_id);
            ////$poids = $value->getMaxRating();
            $objectif = $value->getObjectif();
            $mode_calcul = $value->getModeCalcul();
            $periodicite = $value->getDelai();
            $kpitype = $value->getKpiType();
            if($kpitype == 'Performances'){
                $html .= <<<EOD
            <tr>
              <td> $Kpigroup </td>
              <td> $kpi </td>
              <td> $objectif </td>
              <td> $mode_calcul </td>
              <td> $periodicite </td>
            </tr>
EOD;
            }

        }


    }
}

        $html .= <<<EOD
  </table>
  <br/><br/><br/>
  <table border="1" cellspacing="0" cellpadding="4" >
  <tr bgcolor="#770a82" color="#ffffff" style="font-weight:bold;text-align:center;font-size: 12px;"><td colspan="5"> Indicateur Clé de Pilotage</td></tr>
    <tr bgcolor="#770a82" color="#ffffff">
      <th> Kpi Groupe </th>
      <th> Indicateur Clé de Pilotage </th>
      <th> Objectifs  </th>
      <th> Mode Calcul </th>
      <th> Périodicité  </th>
    </tr>
EOD;
foreach ($existe_group as $key => $grp)
{
    foreach ($kpis as $value)
    {

        $kpi = $value->getKpiIndicators();
        $id_group = $value->getKpiGroup();
        $group = $this->getKpiGroupService()->getKpiGroupById($id_group);
        $Kpigroup = $group->getKpiGroupName();
        if($grp==$id_group)
        {
            //$job_id = $value->getJobTitle();
            //$job_title = $this->_getJobTitles($job_id);
            ////$poids = $value->getMaxRating();
            $objectif = $value->getObjectif();
            $mode_calcul = $value->getModeCalcul();
            $periodicite = $value->getDelai();
            $kpitype = $value->getKpiType();
            if($kpitype == 'Pilotage'){
                $html .= <<<EOD
            <tr>
              <td> $Kpigroup </td>
              <td> $kpi </td>
              <td> $objectif </td>
              <td> $mode_calcul </td>
              <td> $periodicite </td>
            </tr>
EOD;
    }

}


}
}



EOD;


$html .= <<<EOD
  </table>
EOD;
// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);


// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('dictionnaire_kpi', 'I');

  }
}
