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
class searchVehiculeAction extends basePeformanceAction {

    public $vehiculeSearchForm;
    private $pageNumber;

    public function getPageNumber() {
        return $this->pageNumber;
    }

    public function setPageNumber($pageNumber) {
        $this->pageNumber = $pageNumber;
    }

    public function preExecute() {
        $this->_checkAuthentication();
    }

    /**
     *
     * @return KpiSearchForm
     */
    public function getVehiculeSearchForm() {
        if ($this->vehiculeSearchForm == null) {
            return new VehiculeSearchForm();
        } else {
            return $this->vehiculeSearchForm;
        }
    }
    
     /**
     *
     * @return \KpiService 
     */
    public function getVehiculeService() {

        if ($this->vehiculeService == null) {
            return new VehiculeService();
        } else {
            return $this->vehiculeService;
        }
    }

    /**
     *
     * @param KpiSearchForm $kpiSearchForm 
     */
    public function setVehiculeSearchForm($vehiculeSearchForm) {
        $this->vehiculeSearchForm = $vehiculeSearchForm;
    }

    public function execute($request) {

        $form = $this->getVehiculeSearchForm();
        $page = $request->getParameter('hdnAction') == 'search' ? 1 : $request->getParameter('pageNo', 1);

        $this->setPageNumber($page);

        if ($request->isMethod('post')) {
            $form->bind($request->getParameter($form->getName()));
            if ($form->isValid()) {
                try {
                    
                } catch (LeaveAllocationServiceException $e) {
                    $this->templateMessage = array('WARNING', __($e->getMessage()));
                }
            }
        }

        $message = $this->getUser()->getFlash('templateMessage');
        $this->messageType = (isset($message[0])) ? strtolower($message[0]) : "";
        $this->message = (isset($message[1])) ? $message[1] : "";


        if ($this->getUser()->hasFlash('templateMessage')) {
            $this->templateMessage = $this->getUser()->getFlash('templateMessage');
            $this->getUser()->setFlash('templateMessage', array());
        }

        $kpiList = $form->searchVehicule($page);
        $kpiListCount = $form->getVehiculeCount();
        $this->setListComponent($kpiList, $kpiListCount);
        $this->form = $form;
    }

    /**
     *
     * @param Doctrine_Collection $kpiList 
     */
    protected function setListComponent($kpiList, $kpiListCount) {

        $pageNumber = $this->getPageNumber();

        $configurationFactory = $this->getListConfigurationFactory();
        ohrmListComponent::setActivePlugin('orangehrmPerformancePlugin');
        ohrmListComponent::setConfigurationFactory($configurationFactory);
        $list = ohrmListComponent::setListData($kpiList);
        ohrmListComponent::setPageNumber($pageNumber);
        $numRecords = $kpiListCount;
        ohrmListComponent::setItemsPerPage(sfConfig::get('app_items_per_page'));
        ohrmListComponent::setNumberOfRecords($numRecords);
    }

    /**
     *
     * @return \KpiListConfigurationFactory 
     */
    protected function getListConfigurationFactory() {
        return new VehiculeListConfigurationFactory();
    }
    
    protected function _checkAuthentication($request = null) {
        $user = $this->getUser()->getAttribute('user');
        if (!($user->isAdmin())) {
            $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
        }
    }

}
