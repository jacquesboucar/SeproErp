<?php

/**

 */
class viewMyTrainingListAction extends viewTrainingListAction {    
    
    protected function getMode() {
       
        $mode = TrainingListForm::MODE_MY_TRAINING_LIST;
        return $mode;
    }
    
    protected function isEssMode() {
       
        return true;
    }
    
    protected function getPermissions(){
        return $this->getDataGroupPermissions('training_list', true);
    }

    // protected function getCommentPermissions(){
    //     return $this->getDataGroupPermissions('leave_list_comments', true);
    // }    
}