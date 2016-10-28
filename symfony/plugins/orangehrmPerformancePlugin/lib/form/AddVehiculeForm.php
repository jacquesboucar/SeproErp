<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * Description of DefineKpiForm
 *
 * @author nadeera
 */

class AddVehiculeForm extends BasePefromanceSearchForm {
    
    public $vehiculeService;

    /**
     *
     * @return \VehiculeService
     */
    public function getVehiculeService() {

            return new VehiculeService();
    }

    public function getEmailService() {

        return new EmailService();
    }
    public function getSystemUserService() {

        return new SystemUserService();
    }
    /**
     *
     */
    public function configure() {
        $type = array('En cours'=>'En cours','Valider'=>'Valider', 'Rejetter'=>'Rejetter');
        $this->setWidgets(array(
            'id' => new sfWidgetFormInputHidden(),
            'marque' => new sfWidgetFormInputText(),
            'employee' => new ohrmWidgetEmployeeNameAutoFill(),
            'energie' => new sfWidgetFormInputText(),
            'matricule_vehicule' => new sfWidgetFormInputText(),
            'dotation_carburant' => new sfWidgetFormInputText(),
            'description' => new sfWidgetFormTextarea(array(), array('rows' => '3', 'cols' => '30')),
            'valider' => new sfWidgetFormSelect(array('choices' => $type), array('class' => 'formSelect')),
            'file' => new sfWidgetFormInputFileEditable(array('edit_mode'=>false,'with_delete' => false,'file_src' => ''))
        ));
        $this->setValidators(array(
            'id' => new sfValidatorString(array('required' => false)),
            'marque' => new sfValidatorString(array('max_length' => 100)),
            'employee' => new ohrmValidatorEmployeeNameAutoFill(),
            'energie' => new sfValidatorString(array('max_length' => 100)),
            'matricule_vehicule' => new sfValidatorString(array('max_length' => 50)),
            'dotation_carburant' => new sfValidatorString(array('max_length' => 50)),
            'description' => new sfValidatorString(array('required' => false, 'trim' => true, 'max_length' => 2000)),
            'valider' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($type))),
            'file' =>  new sfValidatorFile(array('max_size' => 1024000,'required' => false))
        ));

        $this->getWidgetSchema()->setNameFormat('addVehicule[%s]');
        $this->getWidgetSchema()->setLabels($this->getFormLabels());
    }


    /**
     * @param $kpiId
     */
    public function loadFormData($vehiculeId) {

        if ($vehiculeId > 0) {
            $vehicule = $this->getVehiculeService()->getVehiculeById(array('id' => $vehiculeId));
           // var_dump($vehicule->getEmpNumber());die;
            $this->setDefault('id', $vehicule->getId());
            $this->setDefault('marque', $vehicule->getMarque());
            $this->setDefault('energie', $vehicule->getEnergie());
            $this->setDefault('employee',  array('empName' => $vehicule->getEmployee()->getFullName(), 'empId' => $vehicule->getEmployee()->getEmpNumber()));
            $this->setDefault('matricule_vehicule', $vehicule->getMatriculeVehicule());
            $this->setDefault('dotation_carburant', $vehicule->getDotationCarburant());
            $this->setDefault('description', $vehicule->getDescription());
            $this->setDefault('valider', $vehicule->getValider());
            $this->setDefault('file', $vehicule->getFilename());
            //var_dump($file);die;

        }
    }
   

    /**
     *
     * @return array
     */
    protected function getFormLabels() {
       
        $requiredMarker = '&nbsp;<span class="required">*</span>';
        $labels = array(
            'marque' => __('Marque'). $requiredMarker,
            'employee' => __('Employee'). $requiredMarker,
            'energie' => __('Energie'). $requiredMarker,
            'matricule_vehicule' => __('Matricule véhicule'). $requiredMarker,
            'dotation_carburant' => __('Dotation_carburant'). $requiredMarker,
            'description' => __('Description'),
            'valider' => __('Valider'),
            'file' => __('Téléversement')
        );
        return $labels;
    }

    /**
     *
     */
    public function saveForm() {
        // Get logged user employee Id
        $user = sfContext::getInstance()->getUser();
        $values = $this->getValues();
        $file = $this->getValue('file');
        $vehicule = new Vehicule();
        if($values['id']>0){
            $vehicule = $this->getVehiculeService()->getVehiculeById($values['id']);
            $loggedInEmpNumber = $vehicule->getEmpNumber();
        }elseif ($values['employee']['empId']==''){
            $loggedInEmpNumber = $user->getAttribute('auth.empNumber');
        }else{
            $loggedInEmpNumber = $values['employee']['empId'];
        }

        $employee = $this->getEmployeeService()->getEmployee($loggedInEmpNumber);
        $Emails = $this->ListEmail($loggedInEmpNumber);
        $messageAdmin = $this->MessageAdmin($values['id'], $vehicule);
        $messageEmployee = $this->MessageEmploye($values['id'], $vehicule);
        $ficheteleversement = $this->getFileTraitement($file);

        $vehicule->setMarque($values['marque']);
        $vehicule->setEnergie($values['energie']);
        $vehicule->setDotationCarburant($values['dotation_carburant']);
        $vehicule->setMatriculeVehicule($values['matricule_vehicule']);
        $vehicule->setValider($values['valider']);
        $vehicule->setDateApplied(date('Y-m-d H:i:s'));


        $vehicule->setFilecontent($ficheteleversement['tempname']);
        $vehicule->setFiletype($ficheteleversement['type']);
        $vehicule->setFilesize($ficheteleversement['size']);
        $vehicule->setFilename($ficheteleversement['originalname']);

        $vehicule->setDescription($values['description']);
        $vehicule->setEmpNumber($loggedInEmpNumber);

        $this->getVehiculeService()->saveVehicule($vehicule);
        $this->getEmailService()->sendEmailVehicule($Emails,$employee, $messageAdmin, $messageEmployee);
    }

    public function ListEmail($EmpNumber){

        $emplist = $this->getEmployeeService()->getSupervisorIdListBySubordinateId($EmpNumber);
        $Listemails=array();

        foreach ($emplist as $emp){
            $Listemails[] = $this->getEmployeeService()->getEmployee($emp)->getEmpWorkEmail();
        }

        foreach ($this->getSystemUserService()->getEmployeesByUserRole('Admin') as $a){
            $Listemails[] = $a['emp_work_email'];
        }

        return $Listemails;
    }

    public function MessageAdmin($id, $pretimmo){
        if($id>0){
            if($pretimmo['valider'] == 'En cours'){
                $message = "Notification Véhicule \n 
                            Votre demande de Véhicule a ete modifie \n Merci. \n Ceci est une notification automatique";
            }else{
                $message = "Notification Véhicule \n 
                            Votre demande de Véhicule ".$pretimmo['objet']." a ete ".$pretimmo['valider']." par ".$pretimmo['empNumber']
                            ." \n Merci. \n Ceci est une notification automatique";
            }

        }else{
            $message = "Notification Véhicule \n Une nouvelle demande de Véhicule  a ete creee \n Merci de vous connecter \n Ceci est une notfication automatique";
        }
        return $message;
    }
    public function MessageEmploye($id, $pretimmo){
        if($id>0){
            if($pretimmo['valider'] == 'En cours'){
                $message = "Votre demande de Véhicule a ete modifier. Merci de vous connecter!";
            }else{
                $message = "Notification Véhicule \n".
                           "Votre demande de Véhicule ".$pretimmo['objet']." a ete ".$pretimmo['valider']." par ".$pretimmo['empNumber']
                           ." \n Merci de vous connecter \n Ceci est une notification automatique";
            }
        }else{
            $message = "Notification Véhicule \n Vous avez effectue une demande de Véhicule \n Merci de vous connecter \n Ceci est une notification automatique";
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