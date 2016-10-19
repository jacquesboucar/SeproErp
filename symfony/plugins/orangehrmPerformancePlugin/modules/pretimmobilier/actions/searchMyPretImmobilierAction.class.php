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
class searchMyPretImmobilierAction extends basePeformanceAction {
    
    public $pretimmobilierSearchForm;
    
    /**
     *
     * @return KpiSearchForm
     */
    public function getPretImmobilierSearchService() {
        if ($this->pretimmobilierSearchForm == null) {
            return new PretImmobilierService();
        } else {
            return $this->pretimmobilierSearchForm;
        }
    }
    
    public function getPageNumber() {
        return $this->pageNumber;
    }
    public function setPageNumber($pageNumber) {
        $this->pageNumber = $pageNumber;
    }

    public function execute($request) {


        $page = $request->getParameter('hdnAction') == 'search' ? 1 : $request->getParameter('pageNo', 1);

        $serachParams ['employeeNumber'] = $this->getUser()->getEmployeeNumber();
        $serachParams['limit'] = sfConfig::get('app_items_per_page');
        $serachParams['page'] = $page;

        $pretimmobilierList = $this->getPretImmobilierSearchService()->searchPretImmobilier($serachParams);
        $pretimmobilierListCount = $this->getPretImmobilierSearchService()->getPretImmobilierCount();
        $this->setListComponent($pretimmobilierList, $pretimmobilierListCount);
    }

    /**
     *
     * @param Doctrine_Collection $kpiList 
     */
    protected function setListComponent($pretimmobilierList, $pretimmobilierListCount) {
        
        $pageNumber = $this->getPageNumber();

        $configurationFactory = $this->getListConfigurationFactory();
        ohrmListComponent::setActivePlugin('orangehrmPerformancePlugin');
        ohrmListComponent::setConfigurationFactory($configurationFactory);
        $list = ohrmListComponent::setListData($pretimmobilierList);
        ohrmListComponent::setPageNumber($pageNumber);
        $numRecords = $pretimmobilierListCount;
        ohrmListComponent::setItemsPerPage(sfConfig::get('app_items_per_page'));
        ohrmListComponent::setNumberOfRecords($numRecords);
    }

    /**
     *
     * @return \KpiListConfigurationFactory 
     */
    protected function getListConfigurationFactory() {
        return new MyPretImmobilierListConfigurationFactory();
    }

}
