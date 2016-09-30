<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


class saveVehiculeAction extends basePeformanceAction {

    public $vehiculeForm;


    /**
     *
     */
    public function preExecute() {
       $this->_checkAuthentication();
    }



    public function getVehiculeForm() {
        if ($this->vehiculeForm == null) {
            return new AddTrainingForm();
        } else {
            return $this->vehiculeForm;
        }
    }


    /**
     * @param $vehiculeForm
     */
    public function setVehiculeForm($vfdddfdfds) {
        $this->vehiculeForm = $vehiculeForm;
    }

    public function execute( $request) {

        $request->setParameter('initialActionName', 'searchKpi');
        $form = $this->getVehiculeForm();

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
