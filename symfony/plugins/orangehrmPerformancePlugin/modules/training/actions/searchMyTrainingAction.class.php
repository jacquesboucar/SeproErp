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
class searchMyTrainingAction extends basePeformanceAction {
    
    public $trainingService;
    
    /**
     *
     * @return KpiSearchForm
     */
    public function getTrainingSearchService() {
        if ($this->trainingService == null) {
            return new TrainingService();
        } else {
            return $this->trainingService;
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
        $this->_checkAuthentication($request);
        $page = $request->getParameter('hdnAction') == 'search' ? 1 : $request->getParameter('pageNo', 1);

        $serachParams ['employeeNumber'] = $this->getUser()->getEmployeeNumber();
        $serachParams['limit'] = sfConfig::get('app_items_per_page');
        $serachParams['page'] = $page;

        $trainingList = $this->getTrainingSearchService()->searchTraining($serachParams);
        $trainingListCount = $this->getTrainingSearchService()->getTrainingCount();
        $this->setListComponent($trainingList, $trainingListCount);
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
        return new MyTrainingListConfigurationFactory();
    }
    
    protected function _checkAuthentication($request = null) {
        $user = $this->getUser()->getAttribute('user');
        if (!($user->isAdmin())) {
            $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
        }
    }

}
