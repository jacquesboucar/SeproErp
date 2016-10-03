<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


class savePretImmobilierAction extends basePeformanceAction {

    public $pretimmobilierForm;


    /**
     *
     */
    public function preExecute() {
       $this->_checkAuthentication();
    }


    /**
     * @return AddPretImmobilierForm
     */
    public function getPretImmobilierForm() {
        if ($this->pretimmobilierForm == null) {
            return new AddPretImmobilierForm();
        } else {
            return $this->pretimmobilierForm;
        }
    }


    /**
     * @param $pretimmobilierForm
     */
    public function setPretImmobilierForm($pretimmobilierForm) {
        $this->pretimmobilierForm = $pretimmobilierForm;
    }

    /**
     * @param sfRequest $request
     */
    public function execute($request) {

        $request->setParameter('initialActionName', 'savePretImmobilier');
        $form = $this->getPretImmobilierForm();
        $user = $this->getUser()->getAttribute('user');
        $empNumber = $this->getUser()->getAttribute('auth.empNumber');

        if ($request->isMethod('post')) {
            $form->bind($request->getParameter($form->getName()));
            if ($form->isValid()) {
                try {
                    $form->saveForm();
                    $this->getUser()->setFlash('success', __(TopLevelMessages::SAVE_SUCCESS));
                    $this->redirect('pretimmobilier/savePretImmobilier');
                } catch (LeaveAllocationServiceException $e) {
                    $this->templateMessage = array('WARNING', __($e->getMessage()));
                }
            }
        } else {
            $form->loadFormData($request->getParameter('hdnEditId'));
        }
        $this->form = $form;
    }

    /**
     * @param null $request
     */
    protected function _checkAuthentication($request = null) {
        $user = $this->getUser()->getAttribute('user');
        if (!($user->isAdmin())) {
            $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
        }
    }

}
