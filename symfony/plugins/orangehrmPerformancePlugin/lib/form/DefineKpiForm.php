<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * Description of DefineKpiForm
 *
 * @author nadeera
 */

class DefineKpiForm extends BasePefromanceSearchForm {

    public $jobassign = array();
    public $jobavailable = array();
    
    public function configure() {
        $groupe = $this->getKpiGroupListAsArray();
        $kpi_id = $this->getOption('kpiId');

        if(!empty($kpi_id)){
            $kpi = $this->getKpiService()->searchKpi(array('id' => $kpi_id));
            $jobtitleassign = $this->getJobTitleAssign($kpi->getKpiIndicators());
            $jobtitleavailable = $this->getJobTitleAvailable($kpi->getKpiIndicators());
        }else{
            $jobtitleassign = null;
            $jobtitleavailable = $this->getJobTitleListAsArray();
        }
        //var_dump($test);die;
       //var_dump($jobtitleassign, $jobtitleavailable);die;
        $this->setWidgets(array(
            'id' => new sfWidgetFormInputHidden(),
            'kpi_group' => new sfWidgetFormSelect(array('choices' => $groupe)),
            'keyPerformanceIndicators' => new sfWidgetFormInput(array(), array('class' => 'formInputText')),
            'minRating' => new sfWidgetFormInput(array(), array('class' => 'formInputText')),
            'maxRating' => new sfWidgetFormInput(array(), array('class' => 'formInputText')),
            'availableJob' => new sfWidgetFormSelectMany(array('choices' => $jobtitleavailable)),
            'assignedJob' => new sfWidgetFormSelectMany(array('choices' => $jobtitleassign)),
            'delai' => new sfWidgetFormInputText(),
            'objectif'  => new sfWidgetFormInputText(),
            'mode_calcul'  => new sfWidgetFormInputText(),


        ));
        $this->setValidators(array(
            'id' => new sfValidatorString(array('required' => false)),
            'kpi_group' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($groupe))),
            'keyPerformanceIndicators' => new sfValidatorString(array('required' => true)),
            'minRating' => new sfValidatorString(array('required' => false)),
            'maxRating' => new sfValidatorString(array('required' => false)),
            'availableJob' => new sfValidatorPass(),
            'assignedJob' => new sfValidatorPass(array('required' => true)),
            'makeDefault' => new sfValidatorString(array('required' => false)),
            'delai' => new sfValidatorString(array('required' => false)),
            'objectif' => new sfValidatorString(array('required' => false)),
            'mode_calcul' => new sfValidatorString(array('required' => false)),
        ));

        $this->setDefault('minRating', 1);
        $this->getWidgetSchema()->setNameFormat('defineKpi360[%s]');
        $this->getWidgetSchema()->setLabels($this->getFormLabels());
    }

    /**
     *
     * @return array
     */
    public function getStylesheets() {
        $styleSheets = parent::getStylesheets();
        $styleSheets[plugin_web_path('orangehrmPerformancePlugin','css/defineKpiSuccess.css')] = 'all';
        return $styleSheets;
    }

    /**
     *
     * @return array 
     */
    public function getJobTitleListAsArray() {
        foreach ($this->getJobService()->getJobTitleList() as $job) {
            $jobTitles [$job->getId()] = $job->getJobTitleName();
        }
        return $jobTitles;
    }

    /**
     * @param $indicateur
     * @return array
     */
    public function getJobTitleAssign($indicateur){

       foreach ($this->getKpiService()->searchKpiByJobTitleAndIndicator(array('indicateur' => $indicateur)) as $kpi) {
            $jobTitles [$kpi->getId()] = $kpi->getJobTitleCode();
       }
       foreach ($this->getJobService()->getJobTitleList() as $job) {
            if(in_array($job, $jobTitles)){
                $this->jobassign [$job->getId()] = $job->getJobTitleName();
            }

       }

       return  $this->jobassign;
    }

    /**
     * @param $indicateur
     * @return array
     */
    public function getJobTitleAvailable($indicateur){

        foreach ($this->getKpiService()->searchKpiByJobTitleAndIndicator(array('indicateur' => $indicateur)) as $kpi) {
            $jobTitles [$kpi->getId()] = $kpi->getJobTitleCode();
        }
        foreach ($this->getJobService()->getJobTitleList() as $job) {
            if(!in_array($job, $jobTitles)){
                $this->jobavailable [$job->getId()] = $job->getJobTitleName();
            }

        }

        return  $this->jobavailable;
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
     *
     * @return array
     */
    protected function getFormLabels() {
       
        $requiredMarker = '&nbsp;<span class="required">*</span>';
        $labels = array(
            'kpi_type' =>  __('Type') . $requiredMarker,
            'keyPerformanceIndicators' => __('Key Performance Indicator') . $requiredMarker,
            'minRating' => __('Minimum Rating'). $requiredMarker,
            'maxRating' => __('Poids'). $requiredMarker,
            'delai' => __("Périodicité"),
            'objectif' => __('Objectifs'),
            'mode_calcul' => __('Mode de Calcul'),
           // 'makeDefault' => __('Make Default Scale')
        );
        return $labels;
    }

    public function saveForm() {
        $values = $this->getValues();

        foreach ($values['assignedJob'] as $jobcode) {
          $kpi = new Kpi();
           // $kpi_old = $this->getKpiService()->searchKpiByJobTitle($jobcode);
           // var_dump($kpi_old);die;
          if ($values['id'] > 0) {

            $kpi = $this->getKpiService()->searchKpi(array('id' => $values['id']));
          }

          $kpi->setKpiGroup($values['kpi_group']);
          $kpi->setJobTitleCode($jobcode);
          $kpi->setKpiIndicators($values['keyPerformanceIndicators']);
        
          $kpi->setDelai($values['delai']);
          $kpi->setObjectif($values['objectif']);
          $kpi->setModeCalcul($values['mode_calcul']);
          $job = $kpi->setJobTitleCode($jobcode);
          if( strlen( $values['minRating']) >0 ){
            $kpi->setMinRating($values['minRating']);
          }
          if($values['maxRating']){
             $kpi->setMaxRating($values['maxRating']); 
          }
        
          if ($values['makeDefault'] == 'on') {
            $kpi->setDefaultKpi(1);
          } else {
            $kpi->setDefaultKpi(null);
          }
          $this->getKpiService()->saveKpi($kpi);
        }  
    }

    /**
     *
     * @param integer $kpiId 
     */
    public function loadFormData($kpiId) {

        if ($kpiId > 0) {
            $kpi = $this->getKpiService()->searchKpi(array('id' => $kpiId));
            $this->setDefault('id', $kpi->getId());
            $this->setDefault('kpi_group',$kpi->getKpiGroup());
            $this->setDefault('jobTitleCode', $kpi->getJobTitleCode());
            $this->setDefault('keyPerformanceIndicators', $kpi->getKpiIndicators());
            $this->setDefault('minRating', 1);
            $this->setDefault('maxRating', $kpi->getMaxRating());
            $this->setDefault('delai', $kpi->getDelai());
            $this->setDefault('objectif',$kpi->getObjectif());
            $this->setDefault('mode_calcul',$kpi->getModeCalcul());
            $this->setDefault('makeDefault', $kpi->getDefaultKpi());
            
        } else {
            
            $parameters ['isDefault'] = 1;
            $kpi = $this->getKpiService()->searchKpi($parameters);
            
            if(sizeof($kpi)>0){
                $kpi = $kpi->getFirst();
                $this->setDefault('minRating', 1);
                $this->setDefault('maxRating', $kpi->getMaxRating());
            }           
        }
    }
}