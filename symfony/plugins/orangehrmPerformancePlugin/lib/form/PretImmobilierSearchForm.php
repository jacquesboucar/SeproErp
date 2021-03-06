<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * Description of KpiSearchForm
 *
 * @author nadeera
 */

class PretImmobilierSearchForm extends BasePefromanceSearchForm {

    public function configure() {

        $this->setWidgets($this->getFormWidgets());
        $this->setValidators($this->getFormValidators());

        $this->getWidgetSchema()->setNameFormat('pretimmobilierSearchForm[%s]');
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
            'employee' => new ohrmWidgetEmployeeNameAutoFill(),
            'objet' => new sfWidgetFormInputText(),
            'montant' => new sfWidgetFormInputText(),
        );
        return $widgets;
    }

    /**
     *
     * @return array
     */
    protected function getFormValidators() {

        $validators = array(
            'employee' => new ohrmValidatorEmployeeNameAutoFill(),
            'objet' => new sfValidatorString(array('required' => false)),
            'montant' => new sfValidatorNumber(array('required' => false))
        );
        return $validators;
    }

    /**
     *
     * @return array
     */
    protected function getFormLabels() {
        $labels = array(
            'employee' => __('Employee'),
            'objet' => __('Objet Pret Immobilier'),
            'montant' => __('Montant')
        );
        return $labels;
    }

    /**
     *
     * @return type 
     */
    public function searchPretImmobilier($page) {
        $employe= $this->getValue('employee');
        $serachParams ['objet'] =  $this->getValue('objet');
        $serachParams ['montant'] =  $this->getValue('montant');
        $serachParams ['employeeNumber'] =  $employe['empId'];
        $serachParams ['page'] =  $page;
        $serachParams ['limit'] =  sfConfig::get('app_items_per_page');

        return $this->getPretImmobilierService()->searchPretImmobilier($serachParams);
    }

    public function getPretImmobilierCount(){
        $employe= $this->getValue('employee');
        $serachParams ['objet'] =  $this->getValue('objet');
        $serachParams ['montant'] =  $this->getValue('montant');
        $serachParams ['employeeNumber'] =  $employe['empId'];
        $serachParams['limit'] = null;

        return $this->getPretImmobilierService()->getPretImmobilierCount($serachParams);
    }
}