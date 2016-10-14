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
            'montant_pret' => __('Motant du pret').$requiredMarker,
            'objet' => __('Objet').$requiredMarker,
            'nombre_mois' => __('Nombre de mois').$requiredMarker,
            'date_accord' => __('Date accord'),
            'date_prelevement' => __('Date prelevement'),
            'quotite_saisissable' => __('Quotite saisissable'),
            'description' => __('Description'),
            'valider' => __('Valider'),
            'file' => __('Televersement')
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
        if(!empty($file)){
            $filetype=$file->getType();
            $filename=$file->getOriginalName();
            $filesize=$file->getSize();
            $fileTmpName=file_get_contents($file->getTempName());
        }else{
            $filetype=null;
            $filename=null;
            $filesize=null;
            $fileTmpName=null;
        }

        $pretimmobilier->setMontantPret($values['montant_pret']);
        $pretimmobilier->setObjet($values['objet']);
        $pretimmobilier->setNombreMois($values['nombre_mois']);
        $pretimmobilier->setDateAccord($values['date_accord']);
        $pretimmobilier->setDatePrelevement($values['date_prelevement']);
        $pretimmobilier->setQuotiteSaisissable($values['quotite_saisissable']);
        $pretimmobilier->setValider($values['valider']);
        $pretimmobilier->setFilecontent($fileTmpName);
        $pretimmobilier->setFiletype($filetype);
        $pretimmobilier->setFilesize($filesize);
        $pretimmobilier->setFilename($filename);
        $pretimmobilier->setDescription($values['description']);
        $pretimmobilier->setEmpNumber($loggedInEmpNumber);
        $this->getPretImmobilierService()->savePretImmobilier($pretimmobilier);

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
}