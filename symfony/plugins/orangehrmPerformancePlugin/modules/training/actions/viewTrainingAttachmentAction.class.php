<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of viewCandidateAttachmentAction
 *
 * @author orangehrm
 */
class viewTrainingAttachmentAction extends sfAction {

    /**
     *
     * @return <type> 
     */
    public function getTrainingService() {
        if (is_null($this->trainingService)) {
            $this->trainingService = new TrainingService();
        }
        return $this->trainingService;
    }

    /**
     *
     * @param <type> $request
     * @return <type> 
     */
    public function execute($request) {

        // this should probably be kept in session?
        $request->getParameter('hdnEditId');

        $attachment= $this->getTrainingService()->getTrainingById($request->getParameter('hdnEditId'));

        $response = $this->getResponse();

        if (!empty($attachment)) {
            $contents = $attachment->getFilecontent();
            $contentType = $attachment->getFiletype();
            $fileName = $attachment->getFilename();
            $fileLength = $attachment->getFilesize();

            $response->setHttpHeader('Pragma', 'public');

            $response->setHttpHeader('Expires', '0');
            $response->setHttpHeader("Cache-Control", "must-revalidate, post-check=0, pre-check=0, max-age=0");
            $response->setHttpHeader("Cache-Control", "private", false);
            $response->setHttpHeader("Content-Type", $contentType);
            $response->setHttpHeader("Content-Disposition", 'attachment; filename="' . $fileName . '";');
            $response->setHttpHeader("Content-Transfer-Encoding", "binary");
            $response->setHttpHeader("Content-Length", $fileLength);

            $response->setContent($contents);
            $response->send();
        } else {
            $response->setStatusCode(404, 'This attachment does not exist');
        }

        return sfView::NONE;
    }

}

