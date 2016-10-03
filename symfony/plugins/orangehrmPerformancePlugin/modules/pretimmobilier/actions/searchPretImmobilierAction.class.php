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
class searchPretImmobilierAction extends basePeformanceAction {
    
    public $vehiculeSearchForm;
    
    /**
     *
     * @return KpiSearchForm
     */
    public function getVehiculeSearchForm() {
        if ($this->vehiculeSearchForm == null) {
            return new AddVehiculeForm();
        } else {
            return $this->vehiculeSearchForm;
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

        $form = $this->getVehiculeSearchForm();
        $page = $request->getParameter('hdnAction') == 'search' ? 1 : $request->getParameter('pageNo', 1);

        $this->setPageNumber($page);

        $vehiculeList = $form->searchVehicule($page);
        $vehiculeListCount = $form->getVehiculeCount();
        $this->setListComponent($vehiculeList, $vehiculeListCount);
        $this->form = $form;
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
        return new KpiListConfigurationFactory();
    }
    
    protected function _checkAuthentication($request = null) {
        $user = $this->getUser()->getAttribute('user');
        if (!($user->isAdmin())) {
            $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
        }
    }

}
