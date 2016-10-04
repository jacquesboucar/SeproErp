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
            return new AddVehiculeForm();
        } else {
            return $this->vehiculeForm;
        }
    }


    /**
     * @param $vehiculeForm
     */
    public function setVehiculeForm($vehiculeForm) {
        $this->vehiculeForm = $vehiculeForm;
    }

    public function execute( $request) {

        $request->setParameter('initialActionName', 'saveVehicule');
        $form = $this->getVehiculeForm();
        $user = $this->getUser()->getAttribute('user');

        if ($request->isMethod('post')) {
            $form->bind($request->getParameter($form->getName()),$request->getFiles($form->getName()));
            if ($form->isValid()) {
                try {
                    $form->saveForm();
                    $this->getUser()->setFlash('success', __(TopLevelMessages::SAVE_SUCCESS));
                    $this->redirect('vehicule/saveVehicule');
                } catch (LeaveAllocationServiceException $e) {
                    $this->templateMessage = array('WARNING', __($e->getMessage()));
                }
            }
        } else {
            if(empty($form->loadFormData($request->getParameter('hdnEditId')))){

                if(!$user->isAdmin()|| !$user->isSupervisor()){
                    unset($form['file']);
                    unset($form['valider']);
                }
            }
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
