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
class deleteVehiculeAction extends basePeformanceAction {

    public $vehiculeSearchForm;

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
     * @param KpiSearchForm $kpiSearchForm 
     */
    public function setVehiculeSearchForm($vehiculeSearchForm) {
        $this->vehiculeSearchForm = $vehiculeSearchForm;
    }

    public function execute($request) {

        $form = new DefaultListForm();
        $form->bind($request->getParameter($form->getName()));

        if ($form->isValid()) {
            $rowsToBeDeleted = $request->getParameter('chkSelectRow');

            if (sizeof($rowsToBeDeleted) > 0) {
                $this->getVehiculeService()->softDeleteVehicule($rowsToBeDeleted);
                $this->getUser()->setFlash('success', __(TopLevelMessages::DELETE_SUCCESS));
            } else {
                $this->getUser()->setFlash('error', __(TopLevelMessages::SELECT_RECORDS));
            }
        }
        $this->redirect('training/searchTraining');
    }

    protected function _checkAuthentication($request = null) {
        $user = $this->getUser()->getAttribute('user');
        if (!($user->isAdmin())) {
            $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
        }
    }

}
