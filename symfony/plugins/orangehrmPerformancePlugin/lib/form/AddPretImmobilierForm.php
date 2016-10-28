<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * Description of DefineKpiForm
 *
 * @author nadeera
 */

class AddPretImmobilierForm extends BasePefromanceSearchForm {
    
    public $pretimmobilierService;
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
     *
     * @return \TrainingService 
     */
    public function getPretImmobilierService() {

            return new PretImmobilierService();
    }

    public function getEmailService() {

        return new EmailService();
    }
    public function getSystemUserService() {

        return new SystemUserService();
    }

    public function configure() {
        $type = array('En cours' => 'En cours','Valider' => 'Valider', 'Rejetter' => 'Rejetter');
        $this->setWidgets(array(
            'id' => new sfWidgetFormInputHidden(),
            'montant_pret' => new sfWidgetFormInputText(),
            'objet' => new sfWidgetFormInputText(),
            'nombre_mois' => new sfWidgetFormInputText(),
            'date_accord' => new ohrmWidgetDatePicker(array(), array('id' => 'date_accord')),
            'date_prelevement' => new ohrmWidgetDatePicker(array(), array('id' => 'date_prelevement')),
            'quotite_saisissable' => new sfWidgetFormInputText(),
            'description' => new sfWidgetFormTextarea(array(), array('rows' => '3', 'cols' => '30')),
            'valider' => new sfWidgetFormSelect(array('choices' => $type), array('class' => 'formSelect')),
            'file' => new sfWidgetFormInputFileEditable(array('edit_mode'=>false,'with_delete' => false,'file_src' => '/themes/orange/pictures/'))
        ));

        $this->setValidators(array(
            'id' => new sfValidatorString(array('required' => false)),
            'montant_pret' => new sfValidatorNumber(),
            'objet' => new sfValidatorString(array('max_length' => 255)),
            'nombre_mois' => new sfValidatorNumber(),
            'date_accord' => new ohrmDateValidator(array('required' => false)),
            'date_prelevement' =>new ohrmDateValidator(array('required' => false)),
            'quotite_saisissable' => new sfValidatorNumber(array('required' => false)),
            'description' => new sfValidatorString(array('required' => false, 'trim' => true, 'max_length' => 2000)),
            'valider' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($type))),
            'file' =>  new sfValidatorFile(array('max_size' => 1024000,'required' => false))
        ));
        $this->getWidgetSchema()->setNameFormat('addPretImmobilier[%s]');
        $this->getWidgetSchema()->setLabels($this->getFormLabels());
    }



   

    /**
     *
     * @return array
     */
    protected function getFormLabels() {
       
        $requiredMarker = '&nbsp;<span class="required">*</span>';
        $labels = array(
            'montant_pret' => __('Montant du prêt').$requiredMarker,
            'objet' => __('Objet').$requiredMarker,
            'nombre_mois' => __('Nombre de mois').$requiredMarker,
            'date_accord' => __('Date accord'),
            'date_prelevement' => __('Date prélèvement'),
            'quotite_saisissable' => __('Quotité saisissable'),
            'description' => __('Description'),
            'valider' => __('Valider'),
            'file' => __('Téléversement')
        );
        return $labels;
    }

    public function saveForm() {

        // Get logged user employee Id
        $user = sfContext::getInstance()->getUser();
        $file = $this->getValue('file');
        $values = $this->getValues();
        $pretimmobilier = new PretImmobilier();
        if($values['id']>0){
            $pretimmobilier = $this->getPretImmobilierService()->getPretImmobilierById($values['id']);
            $loggedInEmpNumber=$pretimmobilier->getEmpNumber();
        }else{
            $loggedInEmpNumber=$user->getAttribute('auth.empNumber');
        }

        $employee = $this->getEmployeeService()->getEmployee($loggedInEmpNumber);
        $Emails = $this->ListEmail();
        $messageAdmin = $this->MessageAdmin($values['id'], $pretimmobilier);
        $messageEmployee = $this->MessageEmploye($values['id'], $pretimmobilier);

        $ficheteleversement = $this->getFileTraitement($file);

        $pretimmobilier->setMontantPret($values['montant_pret']);
        $pretimmobilier->setObjet($values['objet']);
        $pretimmobilier->setNombreMois($values['nombre_mois']);
        $pretimmobilier->setDateAccord($values['date_accord']);
        $pretimmobilier->setDatePrelevement($values['date_prelevement']);
        $pretimmobilier->setQuotiteSaisissable($values['quotite_saisissable']);
        $pretimmobilier->setValider($values['valider']);

        $pretimmobilier->setFilecontent($ficheteleversement['tempname']);
        $pretimmobilier->setFiletype($ficheteleversement['type']);
        $pretimmobilier->setFilesize($ficheteleversement['size']);
        $pretimmobilier->setFilename($ficheteleversement['originalname']);

        $pretimmobilier->setDescription($values['description']);
        $pretimmobilier->setEmpNumber($loggedInEmpNumber);

        $this->getPretImmobilierService()->savePretImmobilier($pretimmobilier);
        $this->getEmailService()->sendEmailPretImmobilier($Emails,$employee, $messageAdmin, $messageEmployee);
    }

    /**
     *
     * @param integer $kpiId 
     */
    public function loadFormData($kpiId) {

        if ($kpiId > 0) {

            $pretimmobilier = $this->getPretImmobilierService()->getPretImmobilierById(array('id' => $kpiId));$files=array($pretimmobilier->getFilecontent(), $pretimmobilier->getFiletype(), $pretimmobilier->getFilesize(), $pretimmobilier->getFilename());
            $this->setDefault('id', $pretimmobilier->getId());
            $this->setDefault('montant_pret', $pretimmobilier->getMontantPret());
            $this->setDefault('objet', $pretimmobilier->getObjet());
            $this->setDefault('nombre_mois', $pretimmobilier->getNombreMois());
            $this->setDefault('date_accord', set_datepicker_date_format($pretimmobilier->getDateAccord()));
            $this->setDefault('date_prelevement', set_datepicker_date_format($pretimmobilier->getDatePrelevement()));
            $this->setDefault('quotite_saisissable', $pretimmobilier->getQuotiteSaisissable());
            $this->setDefault('description', $pretimmobilier->getDescription());
            $this->setDefault('valider', $pretimmobilier->getValider());
            $this->setDefault('file', $pretimmobilier->getFilename());
        }
    }
    /**
     *
     * @return type 
     */
    public function searchPretImmobilier($page) {
        
        $serachParams ['objet'] =  $this->getValue('objet');
        $serachParams ['page'] =  $page;
        $serachParams ['limit'] =  sfConfig::get('app_items_per_page');        

        return $this->getPretImmobilierService()->searchPretImmobilier($serachParams);
    }
    
    public function getPretImmobilierCount(){
        $serachParams ['objet'] =  $this->getValue('objet');
        $serachParams['limit'] = null;
        
        return $this->getPretImmobilierService()->getPretImmobilierCount($serachParams);
    }


    public function ListEmail(){

        $Listemails=array();
        foreach ($this->getSystemUserService()->getEmployeesByUserRole('Admin') as $a){
            $Listemails[] = $a['emp_work_email'];
        }

        return $Listemails;
    }

    public function MessageAdmin($id, $pretimmo){
        if($id>0){
            if($pretimmo['valider'] == 'En cours'){
                $message = "Notification Prêt Immobilier \n 
                        Votre demande de Prêt Immobilier a ete modifie \n Merci. \n Ceci est une notification automatique";
            }else{
                $message = "Notification Prêt Immobilier \n 
                            Votre demande de Prêt Immobilier ".$pretimmo['objet']." a ete ".$pretimmo['valider']." par ".$pretimmo['empNumber']
                    ." \n Merci. \n Ceci est une notification automatique";
            }

        }else{
            $message = "Notification Prêt Immobilier \n Une nouvelle demande de Prêt Immobilier  a ete creee \n Merci de vous connecter \n Ceci est une notfication automatique";
        }
        return $message;
    }
    public function MessageEmploye($id, $pretimmo){
        if($id>0){
            if($pretimmo['valider'] == 'En cours'){
                $message = "Votre demande de Prêt Immobilier a ete modifier. Merci de vous connecter!";
            }else{
                $message = "Notification Prêt Immobilier \n".
                    "Votre demande de Prêt Immobilier ".$pretimmo['objet']." a ete ".$pretimmo['valider']." par ".$pretimmo['empNumber']
                    ." \n Merci de vous connecter \n Ceci est une notification automatique";
            }
        }else{
            $message = "Notification Prêt Immobilier \n Vous avez effectue une demande de Prêt Immobilier \n Merci de vous connecter \n Ceci est une notification automatique";
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