<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of deleteKpiAction
 *
 * @author nadeera
 */
class deletePretImmobilierAction extends basePeformanceAction {

    public $pretimmobilierSearchForm;

    public function preExecute() {
        $this->_checkAuthentication();
    }

    /**
     *
     * @return KpiSearchForm
     */
    public function getPretImmobilierSearchForm() {
        if ($this->pretimmobilierSearchForm == null) {
            return new PretImmobilierSearchForm();
        } else {
            return $this->pretimmobilierSearchForm;
        }
    }

    /**
     *
     * @param KpiSearchForm $kpiSearchForm 
     */
    public function setPretImmobilierSearchForm($pretimmobilierSearchForm) {
        $this->pretimmobilierSearchForm = $pretimmobilierSearchForm;
    }

    public function execute($request) {

        $form = new DefaultListForm();
        $form->bind($request->getParameter($form->getName()));

        if ($form->isValid()) {
            $rowsToBeDeleted = $request->getParameter('chkSelectRow');

            if (sizeof($rowsToBeDeleted) > 0) {
                $this->getPretimmobilierService()->softDeletePretImmobilier($rowsToBeDeleted);
                $this->getUser()->setFlash('success', __(TopLevelMessages::DELETE_SUCCESS));
            } else {
                $this->getUser()->setFlash('error', __(TopLevelMessages::SELECT_RECORDS));
            }
        }
        $this->redirect('pretimmobilier/searchPretImmobilier');
    }

    protected function _checkAuthentication($request = null) {
        $user = $this->getUser()->getAttribute('user');
        if (!($user->isAdmin())) {
            $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
        }
    }

}
