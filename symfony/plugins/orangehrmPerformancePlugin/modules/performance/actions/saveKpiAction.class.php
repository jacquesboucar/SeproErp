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
class saveKpiAction extends basePeformanceAction {

    public $kpiSaveForm;

    
    public function preExecute() {
       $this->_checkAuthentication();
    }
    
    /**
     *
     * @return \DefineKpiForm 
     */
    public function getKpiSaveForm($parameters) {
        if ($this->kpiSaveForm == null) {
            return new DefineKpiForm(array(), $parameters, null);
        }
    }

    /**
     *
     * @param \DefineKpiForm $kpiSaveForm 
     */
    public function setKpiSaveForm(sfForm $kpiSaveForm) {
        if (is_null($this->kpiSaveForm)) {
            $this->kpiSaveForm = $kpiSaveForm;
        }
    }

    public function execute( $request) {

        $request->setParameter('initialActionName', 'searchKpi');
        $kpiId = $request->getParameter('hdnEditId');

        $form = $this->getKpiSaveForm(array('kpiId' => $kpiId));

        if ($request->isMethod('post')) {
            $form->bind($request->getParameter($form->getName()));

            if ($form->isValid()) {
                try {
                    $form->saveForm();
                    $this->getUser()->setFlash('success', __(TopLevelMessages::SAVE_SUCCESS));
                    $this->redirect('performance/searchKpi');
                } catch (LeaveAllocationServiceException $e) {
                    $this->templateMessage = array('WARNING', __($e->getMessage()));
                }
            }
        } else {
            $form->loadFormData($request->getParameter('hdnEditId'));
            //var_dump($form);die();
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
