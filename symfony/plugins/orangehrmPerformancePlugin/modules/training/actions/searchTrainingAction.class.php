<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of searchKpiAction
 *
 * @author nadeera
 */
class searchTrainingAction extends basePeformanceAction {
    
    public $trainingSearchForm;
    
    /**
     *
     * @return KpiSearchForm
     */
    public function getTrainingSearchForm() {
        if ($this->trainingSearchForm == null) {
            return new AddTrainingForm();
        } else {
            return $this->trainingSearchForm;
        }
    }
    
    public function getPageNumber() {
        return $this->pageNumber;
    }
    public function setPageNumber($pageNumber) {
        $this->pageNumber = $pageNumber;
    }

    public function preExecute() {
        $this->_checkAuthentication();
    }

    public function execute($request) {

        $form = $this->getTrainingSearchForm();
        $page = $request->getParameter('hdnAction') == 'search' ? 1 : $request->getParameter('pageNo', 1);

        $this->setPageNumber($page);

        $trainingList = $form->searchTraining($page);
        $trainingListCount = $form->getTrainingCount();
        $this->setListComponent($trainingList, $trainingListCount);
        $this->form = $form;
    }

    /**
     *
     * @param Doctrine_Collection $kpiList 
     */
    protected function setListComponent($trainingList, $trainingListCount) {
        
        $pageNumber = $this->getPageNumber();

        $configurationFactory = $this->getListConfigurationFactory();
        ohrmListComponent::setActivePlugin('orangehrmPerformancePlugin');
        ohrmListComponent::setConfigurationFactory($configurationFactory);
        $list = ohrmListComponent::setListData($trainingList);
        ohrmListComponent::setPageNumber($pageNumber);
        $numRecords = $trainingListCount;
        ohrmListComponent::setItemsPerPage(sfConfig::get('app_items_per_page'));
        ohrmListComponent::setNumberOfRecords($numRecords);
    }

    /**
     *
     * @return \KpiListConfigurationFactory 
     */
    protected function getListConfigurationFactory() {
        return new KpiListConfigurationFactory();
    }
    
    protected function _checkAuthentication($request = null) {
        $user = $this->getUser()->getAttribute('user');
        if (!($user->isAdmin())) {
            $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
        }
    }

}
