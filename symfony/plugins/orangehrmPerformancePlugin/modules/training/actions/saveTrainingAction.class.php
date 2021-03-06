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
class saveTrainingAction extends basePeformanceAction {

    public $trainingForm;

    /**
     *
     * @return \DefineKpiForm 
     */
    public function getTrainingForm() {
        if ($this->trainingForm == null) {
            return new AddTrainingForm();
        } else {
            return $this->trainingForm;
        }
    }

    /**
     *
     * @param \DefineKpiForm $kpiSaveForm 
     */
    public function setTrainingForm($trainingForm) {
        $this->trainingForm = $trainingForm;
    }

    public function execute( $request) {

        $request->setParameter('initialActionName', 'searchKpi');
        $user = $this->getUser()->getAttribute('user');
        $form = $this->getTrainingForm();
        if ($request->isMethod('post')) {
            $form->bind($request->getParameter($form->getName()),$request->getFiles($form->getName()));
            if ($form->isValid()) {
                try {
                    $form->saveForm();
                    $this->getUser()->setFlash('success', __(TopLevelMessages::SAVE_SUCCESS));

                    $this->redirect('training/saveTraining');
                } catch (LeaveAllocationServiceException $e) {
                    $this->templateMessage = array('WARNING', __($e->getMessage()));
                }
            }
        } else {
            $form->loadFormData($request->getParameter('hdnEditId'));
        }
        $this->form = $form;
    }

}
