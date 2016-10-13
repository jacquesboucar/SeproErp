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
class deleteTrainingAction extends basePeformanceAction {

    public $trainingSearchForm;

    public function preExecute() {
        $this->_checkAuthentication();
    }

    /**
     *
     * @return KpiSearchForm
     */
    public function getTrainingSearchForm() {
        if ($this->trainingSearchForm == null) {
            return new TrainingSearchForm();
        } else {
            return $this->trainingSearchForm;
        }
    }

    /**
     *
     * @param KpiSearchForm $kpiSearchForm 
     */
    public function setTrainingSearchForm($trainingSearchForm) {
        $this->trainingSearchForm = $trainingSearchForm;
    }

    public function execute($request) {

        $form = new DefaultListForm();
        $form->bind($request->getParameter($form->getName()));

        if ($form->isValid()) {
            $rowsToBeDeleted = $request->getParameter('chkSelectRow');

            if (sizeof($rowsToBeDeleted) > 0) {
                $this->getTrainingService()->softDeleteTraining($rowsToBeDeleted);
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
