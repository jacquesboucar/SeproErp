<?php

abstract class AbstractTrainingAllocationService extends BaseService {

    protected $trainingRequestService;
    //protected $leaveTypeService;
    //protected $leavePeriodService;
    protected $employeeService;
    // protected $workWeekService;
    // protected $holidayService;
    // protected $overlapLeave;
    // private   $workScheduleService;
    // protected $workflowService;
    protected $userRoleManager;
        
    
    /**
     * 
     * Saves Leave Request and Sends Notification
     * @param TrainingParameterObject $leaveAssignmentData 
     */
    protected abstract function saveTrainingRequest(TrainingParameterObject $trainingAssignmentData);
    
    
    /**
     * Get Logger instance. Creates if not already created.
     *
     * @return Logger
     */
    protected abstract function getLogger();
    
    /**
     *
     * @return TrainingRequestService
     */
    public function getTrainingRequestService() {
        if (!($this->trainingRequestService instanceof TrainingRequestService)) {
            $this->trainingRequestService = new TrainingRequestService();
        }
        return $this->trainingRequestService;
    }

    /**
     *
     * @param TrainingRequestService $service 
     */
    public function setTrainingRequestService(TrainingRequestService $service) {
        $this->trainingRequestService = $service;
    }

    
    public function getEmployeeService() {
        if (!($this->employeeService instanceof EmployeeService)) {
            $this->employeeService = new EmployeeService();
        }
        return $this->employeeService;
    }

    /**
     *
     * @param EmployeeService $service 
     */
    public function setEmployeeService(EmployeeService $service) {
        $this->employeeService = $service;
    }

   
    public function getUserRoleManager() {
        if (!($this->userRoleManager instanceof AbstractUserRoleManager)) {
            $this->userRoleManager = UserRoleManagerFactory::getUserRoleManager();
        }
        return $this->userRoleManager;
    }

    /**
     * Set user role manager instance
     * @param AbstractUserRoleManager $userRoleManager
     */
    public function setUserRoleManager(AbstractUserRoleManager $userRoleManager) {
        $this->userRoleManager = $userRoleManager;
    }    
    
    
    /**
     *
     * @param TrainingParameterObject $leaveAssignmentData
     * @return LeaveRequest 
     */
    protected function generateTrainingRequest(TrainingParameterObject $trainingAssignmentData) {

//        $leavePeriodId = null;
//
//        $leavePeriod = $this->getLeavePeriodService()->getLeavePeriod(strtotime($leaveAssignmentData->getFromDate()));
//        if (!is_null($leavePeriod) && ($leavePeriod instanceof LeavePeriod)) {
//            $leavePeriodId = $leavePeriod->getLeavePeriodId();
//        }

        $trainingRequest = new TrainingRequest();

       // print_r($trainingRequest);
       // die();
        //$leaveRequest->setLeaveTypeId($leaveAssignmentData->getLeaveType());
        
        //$leaveRequest->setLeavePeriodId($leavePeriodId);
        $trainingRequest->setCoutFormation($trainingAssignmentData->getCout());
        $trainingRequest->setDateApplied('2016-07-15');
        $trainingRequest->setEmpNumber($trainingAssignmentData->getEmployeeNumber());
        $trainingRequest->setDescription($trainingAssignmentData->getDescription());
       // $trainingRequest->setTitle($trainingAssignmentData->getTitle());
        return $trainingRequest;
    }
}
   