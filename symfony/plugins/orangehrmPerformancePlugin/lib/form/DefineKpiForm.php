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

    
    public function configure() {
        //$type = array('' => '-- ' . __('Select') . ' --');
        $type[] = array('Indicateur de Performance', 'Indicateur de Pilotage');
        $groupe = array('Certification ISO 9001', 'Etudes et marketing', 'Outils et évolutions SI', 'Reporting et analyse');
        // $this->setWidgets($this->getFormWidgets());
        // $this->setValidators($this->getFormValidators());

        $this->setWidgets(array(
            'id' => new sfWidgetFormInputHidden(),
            'kpi_type' => new sfWidgetFormSelect(array('choices' => $type)),
            'kpi_group' => new sfWidgetFormSelect(array('choices' => $groupe)),
            'jobTitleCode' => new sfWidgetFormChoice(array('choices' => $this->getJobTitleListAsArrayWithSelectOption()), array('class' => 'formSelect')),  
            'keyPerformanceIndicators' => new sfWidgetFormInput(array(), array('class' => 'formInputText')),
            'minRating' => new sfWidgetFormInput(array(), array('class' => 'formInputText')),
            'maxRating' => new sfWidgetFormInput(array(), array('class' => 'formInputText')),
           // 'makeDefault' => new sfWidgetFormInputCheckbox(array(), array('class' => 'formCheckbox')),
            'delai' => new sfWidgetFormInputText(),
            'valeur_cible'  => new sfWidgetFormInputText()
        ));
        $this->setValidators(array(
            'id' => new sfValidatorString(array('required' => false)),
            'kpi_type' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($type))),
            'kpi_group' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($groupe))),
            'jobTitleCode' => new sfValidatorString(array('required' => false)),
            'keyPerformanceIndicators' => new sfValidatorString(array('required' => true)),
            'minRating' => new sfValidatorString(array('required' => false)),
            'maxRating' => new sfValidatorString(array('required' => false)),
            'makeDefault' => new sfValidatorString(array('required' => false)),
            'delai' => new sfValidatorString(array('required' => false)),
            'valeur_cible' => new sfValidatorString(array('required' => true)),
        ));


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
     *
     * @return array
     */
    // protected function getFormWidgets() {
    //     $type = array('indperf' => 'Indicateur de Performance', 'indpil' => 'Indicateur de Pilotage');
    //     $groupe = array('cert' => 'Certification ISO 9001', 'etude' => 'Etudes et marketing', 'outil' => 'Outils et évolutions SI', 'report' => 'Reporting et analyse');
    //     $widgets = array(
    //         'id' => new sfWidgetFormInputHidden(),
    //         'type' => new sfWidgetFormSelect(array('choices' => $type)),
    //         'group' => new sfWidgetFormSelect(array('choices' => $groupe)),
    //         'jobTitleCode' => new sfWidgetFormChoice(array('choices' => $this->getJobTitleListAsArrayWithSelectOption()), array('class' => 'formSelect')),  
    //         'keyPerformanceIndicators' => new sfWidgetFormInput(array(), array('class' => 'formInputText')),
    //         'minRating' => new sfWidgetFormInput(array(), array('class' => 'formInputText')),
    //         'maxRating' => new sfWidgetFormInput(array(), array('class' => 'formInputText')),
    //        // 'makeDefault' => new sfWidgetFormInputCheckbox(array(), array('class' => 'formCheckbox')),
    //         'delai' => new sfWidgetFormInputText(),
    //         'valeur_cible'  => new sfWidgetFormInputText()
                     
    //     );
        
    //     return $widgets;
    // }

    /**
     *
     * @return array
     */
    // protected function getFormValidators() {
    //     $type = array('indperf' => 'Indicateur de Performance', 'indpil' => 'Indicateur de Pilotage');
    //     $groupe = array('cert' => 'Certification ISO 9001', 'etude' => 'Etudes et marketing', 'outil' => 'Outils et évolutions SI', 'report' => 'Reporting et analyse');
    //     $validators = array(
    //         'id' => new sfValidatorString(array('required' => false)),
    //         'type' => new sfValidatorString(array('required' => true)),
    //         'group' => new sfValidatorString(array('required' => true)),
    //         'jobTitleCode' => new sfValidatorString(array('required' => false)),
    //         'keyPerformanceIndicators' => new sfValidatorString(array('required' => true)),
    //         'minRating' => new sfValidatorString(array('required' => false)),
    //         'maxRating' => new sfValidatorString(array('required' => false)),
    //         'makeDefault' => new sfValidatorString(array('required' => false)),
    //         'delai' => new sfValidatorString(array('required' => false)),
    //         'valeur_cible' => new sfValidatorString(array('required' => true)),
    //     );
    //     return $validators;
    // }

    /**
     *
     * @return array
     */
    protected function getFormLabels() {
       
        $requiredMarker = '&nbsp;<span class="required">*</span>';
        $labels = array(
            'kpi_type' =>  __('Type') . $requiredMarker,
            'kpi_group' =>  __('Groupe') . $requiredMarker,
            'jobTitleCode' => __('Job Title') . $requiredMarker,
            'keyPerformanceIndicators' => __('Key Performance Indicator') . $requiredMarker,
            'minRating' => __('Minimum Rating'). $requiredMarker,
            'maxRating' => __('Poids'). $requiredMarker,
            'delai' => __('Delai'),
            'valeur_cible' => __('Valeur cible'). $requiredMarker,
           // 'makeDefault' => __('Make Default Scale')
        );
        return $labels;
    }

    public function saveForm() {
        $values = $this->getValues();
        $kpi = new Kpi();
        if ($values['id'] > 0) {
            $kpi = $this->getKpiService()->searchKpi(array('id' => $values['id']));
        }
        $kpi->setKpiGroup($values['kpi_group']);
        $kpi->setJobTitleCode($values['jobTitleCode']);
        $kpi->setKpiIndicators($values['keyPerformanceIndicators']);
        
        $kpi->setDelai($values['delai']);
        $kpi->setValeurCible($values['valeur_cible']);
        $kpi->setKpiType($values['kpi_type']);
        $job = $kpi->setJobTitleCode($values['jobTitleCode']);
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

    /**
     *
     * @param integer $kpiId 
     */
    public function loadFormData($kpiId) {

        if ($kpiId > 0) {
            $kpi = $this->getKpiService()->searchKpi(array('id' => $kpiId));
            $this->setDefault('id', $kpi->getId());
            $this->setDefault('jobTitleCode', $kpi->getJobTitleCode());
            $this->setDefault('keyPerformanceIndicators', $kpi->getKpiIndicators());
            $this->setDefault('minRating', 1);
            $this->setDefault('maxRating', $kpi->getMaxRating());
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