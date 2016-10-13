<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * Description of KpiSearchForm
 *
 * @author nadeera
 */

class MyVehiculeSearchForm extends BasePefromanceSearchForm {

    public function configure() {

        $this->setWidgets($this->getFormWidgets());
        $this->setValidators($this->getFormValidators());

        $this->getWidgetSchema()->setNameFormat('myvehiculeSearchForm[%s]');
        $this->getWidgetSchema()->setLabels($this->getFormLabels());
    }

    /**
     *
     * @return array
     */
    public function getStylesheets() {
        $styleSheets = parent::getStylesheets();
        $styleSheets[plugin_web_path('orangehrmPerformancePlugin','css/kpiSearchSuccess.css')] = 'all';
        return $styleSheets;
    }

    /**
     *
     * @return array
     */
    protected function getFormWidgets() {
        $widgets = array(
            'dateapplied' => new ohrmWidgetDatePicker(array(), array('id' => 'dateapplied')),
            'marque' => new sfWidgetFormInputText(),
            'employeeNumber' => new sfWidgetFormInputHidden(),
        );
        return $widgets;
    }

    /**
     *
     * @return array
     */
    protected function getFormValidators() {

        $validators = array(
            'dateapplied' => new sfValidatorString(array('required' => false)),
            'marque' => new sfValidatorString(array('required' => false)),
            'employeeNumber' => new sfValidatorString(array('required' => false))
        );
        return $validators;
    }

    /**
     *
     * @return array
     */
    protected function getFormLabels() {
        $labels = array(
            'dateapplied' => __('Date Soumission'),
            'marque' => __('Marque')
        );
        return $labels;
    }

    /**
     *
     * @return type 
     */
    public function searchVehicule($page) {
        $serachParams ['dateapplied'] =  $this->getValue('dateapplied');
        $serachParams ['marque'] =  $this->getValue('marque');
        $serachParams ['employeeNumber'] =  $this->getValue('employeeNumber');
        $serachParams ['page'] =  $page;
        $serachParams ['limit'] =  sfConfig::get('app_items_per_page');

        return $this->getVehiculeService()->searchVehicule($serachParams);
    }

    public function getVehiculeCount(){
        $serachParams ['dateapplied'] =  $this->getValue('dateapplied');
        $serachParams ['marque'] =  $this->getValue('marque');;
        $serachParams ['employeeNumber'] =  $this->getValue('employeeNumber');
        $serachParams['limit'] = null;

        return $this->getVehiculeService()->getVehiculeCount($serachParams);
    }
}