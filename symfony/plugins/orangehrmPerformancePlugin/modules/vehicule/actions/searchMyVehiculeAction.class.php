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
class searchMyVehiculeAction extends basePeformanceAction {
    
    public $vehiculeService;
    
    /**
     *
     * @return KpiSearchForm
     */
    public function getVehiculeSearchService() {
        if ($this->vehiculeService == null) {
            return new VehiculeService();
        } else {
            return $this->vehiculeService;
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

        $page = $request->getParameter('hdnAction') == 'search' ? 1 : $request->getParameter('pageNo', 1);

        $serachParams ['employeeNumber'] = $this->getUser()->getEmployeeNumber();
        $serachParams['limit'] = sfConfig::get('app_items_per_page');
        $serachParams['page'] = $page;

        $vehiculeList = $this->getVehiculeSearchService()->searchVehicule($serachParams);
        $vehiculeListCount = $this->getVehiculeSearchService()->getVehiculeCount();
        $this->setListComponent($vehiculeList, $vehiculeListCount);
    }

    /**
     *
     * @param Doctrine_Collection $kpiList 
     */
    protected function setListComponent($vehiculeList, $vehiculeListCount) {
        
        $pageNumber = $this->getPageNumber();

        $configurationFactory = $this->getListConfigurationFactory();
        ohrmListComponent::setActivePlugin('orangehrmPerformancePlugin');
        ohrmListComponent::setConfigurationFactory($configurationFactory);
        $list = ohrmListComponent::setListData($vehiculeList);
        ohrmListComponent::setPageNumber($pageNumber);
        $numRecords = $vehiculeListCount;
        ohrmListComponent::setItemsPerPage(sfConfig::get('app_items_per_page'));
        ohrmListComponent::setNumberOfRecords($numRecords);
    }

    /**
     *
     * @return \KpiListConfigurationFactory 
     */
    protected function getListConfigurationFactory() {
        return new MyVehiculeListConfigurationFactory();
    }
    
    protected function _checkAuthentication($request = null) {
        $user = $this->getUser()->getAttribute('user');
        if (!($user->isAdmin())) {
            $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
        }
    }

}
