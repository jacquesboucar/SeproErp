<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * Description of DefineKpiForm
 *
 * @author nadeera
 */
require_once sfConfig::get('sf_root_dir').'/apps/orangehrm/lib/model/core/Service/EmailService.php';
class AddTrainingForm extends BasePefromanceSearchForm {
    
    public $trainingService;

    /**
     *
     * @return \TrainingService 
     */
    public function getTrainingService() {

            return new TrainingService();
    }

    public function getEmployeeService() {

            return new EMployeeService();
    }
    public function getEmailService() {

            return new EmailService();
    }
    public function getSystemUserService() {

            return new SystemUserService();
    }

    public function configure() {

        $type = array('En cours'=>'En cours','Valider'=>'Valider', 'Rejetter'=>'Rejetter');
        $this->setWidgets(array(
            'id' => new sfWidgetFormInputHidden(),
            'employee' => new ohrmWidgetEmployeeNameAutoFill(),
            'cout' => new sfWidgetFormInputText(),
            'titre' => new sfWidgetFormInputText(),
            'description' => new sfWidgetFormTextarea(array(), array('rows' => '3', 'cols' => '30')),
            'valider' => new sfWidgetFormSelect(array('choices' => $type), array('class' => 'formSelect')),
            'fileformation' => new sfWidgetFormInputFileEditable(array('edit_mode'=>false,'with_delete' => false,'file_src' => '')),
            'file' => new sfWidgetFormInputFileEditable(array('edit_mode'=>false,'with_delete' => false,'file_src' => ''))

        ));
        $this->setValidators(array(
            'id' => new sfValidatorString(array('required' => false)),
            'employee' => new ohrmValidatorEmployeeNameAutoFill(),
            'cout' => new sfValidatorString(array('max_length' => 255)),
            'titre' => new sfValidatorString(array('max_length' => 255)),             
            'description' => new sfValidatorString(array('required' => false, 'trim' => true, 'max_length' => 2000)),
            'valider' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($type))),
            'fileformation' =>  new sfValidatorFile(array('max_size' => 1024000,'required' => false)),
            'file' =>  new sfValidatorFile(array('max_size' => 1024000,'required' => false))
        ));
        $this->getWidgetSchema()->setNameFormat('addTraining[%s]');
        $this->getWidgetSchema()->setLabels($this->getFormLabels());
    }



   

    /**
     *
     * @return array
     */
    protected function getFormLabels() {
       
        $requiredMarker = '&nbsp;<span class="required">*</span>';
        $labels = array(
            'titre' => __('Titre'),
            'employee' => __('Employee'),
            'cout' => __('Coût'),
            'description' => __('Description'),
            'valider' => __('Valider'),
            'fileformation' => __('Fiche Formation'),
            'file' => __('Téléversement')
        );
        return $labels;
    }

    public function saveForm() {
        // Get logged user employee Id
        $user = sfContext::getInstance()->getUser();
        $values = $this->getValues();
        $file = $this->getValue('file');
        $fileform = $this->getValue('fileformation');

        $training = new Training();

        if ($values['id']>0){
            $training = $this->getTrainingService()->getTrainingById($values['id']);
            $loggedInEmpNumber=$training->getEmpNumber();
        }elseif ($values['employee']['empId']==''){
            $loggedInEmpNumber = $user->getAttribute('auth.empNumber');
        }else{
            $loggedInEmpNumber = $values['employee']['empId'];
        }
        $ficheformation = $this->getFileTraitement($fileform);
        $ficheteleversement = $this->getFileTraitement($file);

        $employee = $this->getEmployeeService()->getEmployee($loggedInEmpNumber);
        $Emails = $this->ListEmail($loggedInEmpNumber);
        $messageAdmin = $this->MessageAdmin($values['id'], $training);
        $messageEmployee = $this->MessageEmploye($values['id'], $training);

        $training->setTitle($values['titre']);
        $training->setCoutFormation($values['cout']);
        $training->setDescription($values['description']);
        $training->setDateApplied(date('Y-m-d H:i:s'));
        $training->setValider($values['valider']);

        $training->setFormFilecontent($ficheformation['tempname']);
        $training->setFormFiletype($ficheformation['type']);
        $training->setFormFilesize($ficheformation['size']);
        $training->setFormFilename($ficheformation['originalname']);

        $training->setFilecontent($ficheteleversement['tempname']);
        $training->setFiletype($ficheteleversement['type']);
        $training->setFilesize($ficheteleversement['size']);
        $training->setFilename($ficheteleversement['originalname']);

        $training->setEmpNumber($loggedInEmpNumber);
        $this->getTrainingService()->saveTraining($training);
        $this->getEmailService()->sendEmailTraining($Emails,$employee, $messageAdmin, $messageEmployee);
          
    }

    /**
     *
     * @param integer $kpiId 
     */
    public function loadFormData($trainingId) {

        if ($trainingId > 0) {
            $training = $this->getTrainingService()->getTrainingById(array('id' => $trainingId));
            $this->setDefault('id', $training->getId());
            $this->setDefault('cout', $training->getCoutFormation());
            $this->setDefault('employee', array('empName' => $training->getEmployee()->getFullName(), 'empId' => $training->getEmployee()->getEmpNumber()));
            $this->setDefault('description', $training->getDescription());
            $this->setDefault('titre', $training->getTitle());
            $this->setDefault('date_applied', set_datepicker_date_format($training->getDateApplied()));
            $this->setDefault('valider', $training->getValider());
            $this->setDefault('file', $training->getFilename());
            $this->setDefault('fileformation', $training->getFormFilename());

            
        }
    }
    /**
     *
     * @return type 
     */
    public function searchTraining($page) {
        
        $serachParams ['title'] =  $this->getValue('titre');
        $serachParams ['page'] =  $page;
        $serachParams ['limit'] =  sfConfig::get('app_items_per_page');        

        return $this->getTrainingService()->searchTraining($serachParams);
    }
    
    public function getTrainingCount(){
        $serachParams ['title'] =  $this->getValue('titre');
        $serachParams['limit'] = null;
        
        return $this->getTrainingService()->getTrainingCount($serachParams);
    }

    public function ListEmail($EmpNumber){

        $emplist = $this->getEmployeeService()->getSupervisorIdListBySubordinateId($EmpNumber);
        $Listemails=array();

        foreach ($emplist as $emp){
            $Listemails[] = $this->getEmployeeService()->getEmployee($emp)->getEmpWorkEmail();
        }

        //$Listemails[] = $this->getEmployeeService()->getEmployee($EmpNumber)->getEmpWorkEmail();

        foreach ($this->getSystemUserService()->getEmployeesByUserRole('Admin') as $a){
            $Listemails[] = $a['emp_work_email'];
        }

        return $Listemails;
    }

    public function MessageAdmin($id, $training){
        if($id>0){
            if($training['valider'] == 'En cours'){
                $message = "Notification Formation \n 
                        Votre demande de formation a ete modifie \n Merci. \n Ceci est une notification automatique";
            }else{
                $message = "Notification Formation \n 
                            Votre demande de formation ".$training['title']." a ete ".$training['valider']." par ".$training['empNumber']
                           ." \n Merci. \n Ceci est une notification automatique";
            }

        }else{
            $message = "Notification Formation \n Une nouvelle demande de formation  a ete creee \n Merci de vous connecter \n Ceci est une notfication automatique";
        }
        return $message;
    }
    public function MessageEmploye($id, $training){
        if($id>0){
            if($training['valider'] == 'En cours'){
                $message = "Votre demande de formation a ete modifier. Merci de vous connecter!";
            }else{
                $message = "Notification formation \n".
                    "Votre demande de formation ".$training['title']." a ete ".$training['valider']." par ".$training['empNumber']
                           ." \n Merci de vous connecter \n Ceci est une notification automatique";
            }
        }else{
            $message = "Notification Formation \n Vous avez effectue une demande de formation \n Merci de vous connecter \n Ceci est une notification automatique";
        }
        return $message;
    }

    public function getFileTraitement($fichier){
        $f = array();

        if(!empty($fichier))
        {
            $f['type']=$fichier->getType();
            $f['originalname']=$fichier->getOriginalName();
            $f['size']=$fichier->getSize();
            $f['tempname']=file_get_contents($fichier->getTempName());
        }else{
            $f['type']=null;
            $f['originalname']=null;
            $f['size']=null;
            $f['tempname']=null;
        }

        return $f;
    }
}