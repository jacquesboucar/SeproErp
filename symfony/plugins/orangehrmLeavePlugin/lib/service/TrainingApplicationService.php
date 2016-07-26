<?php

/**
 * Leave Application Service
 * 
 * Functionalities related to leave applying.
 * 
 * @package leave
 * @todo Add license 
 */

class TrainingApplicationService extends AbstractTrainingAllocationService {

    // protected $leaveEntitlementService;
    // protected $dispatcher;
    protected $logger;
    //protected $applyWorkflowItem = null;
    
    
    /**
     * Creates a new leave application
     * 
     * @param TrainingParameterObject $leaveAssignmentData
     * @return boolean True if leave request is saved else false
     * @throws LeaveAllocationServiceException When leave request length exceeds work shift length. 
     * 
     * @todo Add LeaveParameterObject to the API
     */
    public function applyTraining(TrainingParameterObject $leaveAssignmentData) {

        // if ($this->hasOverlapLeave($leaveAssignmentData)) {
        //     return false;
        // }
                
        // if ($this->applyMoreThanAllowedForADay($leaveAssignmentData)) {
        //     throw new LeaveAllocationServiceException('Failed to Submit: Work Shift Length Exceeded');
        // }
        
        return $this->saveTrainingRequest($leaveAssignmentData);        
    }

    /**
     * Saves Leave Request and Sends Email Notification
     * 
     * @param TrainingParameterObject $leaveAssignmentData 
     * @return boolean True if leave request is saved else false
     * @throws LeaveAllocationServiceException
     * 
     * @todo Don't catch general Exception. Catch specific one.
     */
    protected function saveTrainingRequest(TrainingParameterObject $leaveAssignmentData) {
        $trainingRequest = $this->generateTrainingRequest($leaveAssignmentData);
        $user = sfContext::getInstance()->getUser();
        $loggedInUserId = $user->getAttribute('auth.userId');
        $loggedInEmpNumber = $user->getAttribute('auth.empNumber');
        //print_r($trainingRequest);
        $trainingRequest = $this->getTrainingRequestService()->saveTrainingRequest($trainingRequest);
        return $trainingRequest;

  
    }

   
    /**
     *
     * @return Employee
     * @todo Remove the use of session
     */
    public function getLoggedInEmployee() {
        $employee = $this->getEmployeeService()->getEmployee($_SESSION['empNumber']);
        return $employee;
    }
    
    /**
     * Get Logger instance. Creates if not already created.
     *
     * @return Logger
     */
    protected function getLogger() {
        if (is_null($this->logger)) {
            $this->logger = Logger::getLogger('training.TrainingApplicationService');
        }

        return($this->logger);
    }     

}
