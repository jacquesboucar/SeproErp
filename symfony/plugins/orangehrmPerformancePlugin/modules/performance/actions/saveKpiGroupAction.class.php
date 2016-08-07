<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of saveKpiAction
 *
 * @author nadeera
 */
class saveKpiGroupAction extends basePeformanceAction {

    public $kpiGroupSaveForm;

    
    public function preExecute() {
       $this->_checkAuthentication();
    }
    
    /**
     *
     * @return \DefineKpiForm 
     */
    public function getKpiGroupSaveForm() {
        if ($this->kpiGroupSaveForm == null) {
            return new KpiGroupForm();
        } else {
            return $this->kpiSaveFor;
        }
    }

    /**
     *
     * @param \DefineKpiForm $kpiSaveForm 
     */
    public function setKpiGroupSaveForm($kpiGroupSaveForm) {
        $this->kpiGroupSaveForm = $kpiGroupSaveForm;
    }

    public function execute( $request) {

        $request->setParameter('initialActionName', 'searchKpi');
        $form = $this->getKpiGroupSaveForm();

        if ($request->isMethod('post')) {
            $form->bind($request->getParameter($form->getName()));
            if ($form->isValid()) {
                try {
                    $form->saveForm();
                    $this->getUser()->setFlash('success', __(TopLevelMessages::SAVE_SUCCESS));
                    //$this->redirect('performance/searchKpi');
                } catch (LeaveAllocationServiceException $e) {
                    $this->templateMessage = array('WARNING', __($e->getMessage()));
                }
            }
        // } else {
        //     $form->loadFormData($request->getParameter('hdnEditId'));
       }
        $this->form = $form;
    }
    
    protected function _checkAuthentication($request = null) {
        $user = $this->getUser()->getAttribute('user');
        if (!($user->isAdmin())) {
            $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
        }
    }
}
