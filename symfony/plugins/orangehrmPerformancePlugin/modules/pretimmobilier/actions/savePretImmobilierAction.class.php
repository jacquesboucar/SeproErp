<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


class savePretImmobilierAction extends basePeformanceAction {

    public $pretimmobilierForm;
    private $allowedFileTypes = array(
        "docx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
        "doc" => "application/msword",
        "doc" => "application/x-msword",
        "doc" => "application/vnd.ms-office",
        "odt" => "application/vnd.oasis.opendocument.text",
        "pdf" => "application/pdf",
        "pdf" => "application/x-pdf",
        "rtf" => "application/rtf",
        "rtf" => "text/rtf",
        "txt" => "text/plain");


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


        if ($request->isMethod('post')) {

            $form->bind($request->getParameter($form->getName()),$request->getFiles($form->getName()));
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


}
