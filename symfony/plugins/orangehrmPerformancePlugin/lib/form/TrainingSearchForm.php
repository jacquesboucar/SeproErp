<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * Description of KpiSearchForm
 *
 * @author nadeera
 */

class TrainingSearchForm extends BasePefromanceSearchForm {

    public function configure() {

        $this->setWidgets($this->getFormWidgets());
        $this->setValidators($this->getFormValidators());

        $this->getWidgetSchema()->setNameFormat('trainingSearchForm[%s]');
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
            'coutformation' => new sfWidgetFormInputText(),
            'title' => new sfWidgetFormInputText(),
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
            'coutformation' => new sfValidatorString(array('required' => false)),
            'title' => new sfValidatorString(array('required' => false))
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
            'coutformation' => __('Coût Formation'),
            'title' => __('Title')
        );
        return $labels;
    }

    /**
     *
     * @return type 
     */
    public function searchTraining($page) {
        $employe= $this->getValue('employee');
        $serachParams ['coutformation'] =  $this->getValue('coutformation');
        $serachParams ['employeeNumber'] =  $employe['empId'];
        $serachParams ['title'] =  $this->getValue('title');
        $serachParams ['page'] =  $page;
        $serachParams ['limit'] =  sfConfig::get('app_items_per_page');
        return $this->getTrainingService()->searchTraining($serachParams);
    }

    public function getTrainingCount(){
        $employe= $this->getValue('employee');
        $serachParams ['coutformation'] =  $this->getValue('coutformation');
        $serachParams ['employeeNumber'] =  $employe['empId'];
        $serachParams ['title'] =  $this->getValue('title');
        $serachParams['limit'] = null;
        return $this->getTrainingService()->getTrainingCount($serachParams);
    }
}