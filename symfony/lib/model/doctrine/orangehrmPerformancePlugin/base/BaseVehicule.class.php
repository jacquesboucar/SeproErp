<?php

/**
 * BaseVehicule
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $emp_number
 * @property string $marque
 * @property string $energie
 * @property string $matricule_vehicule
 * @property string $dotation_carburant
 * @property date $date_applied
 * @property string $valider
 * @property blob $filecontent
 * @property string $filename
 * @property string $filetype
 * @property integer $filesize
 * @property string $description
 * @property Employee $Employee
 * 
 * @method integer  getId()                 Returns the current record's "id" value
 * @method integer  getEmpNumber()          Returns the current record's "emp_number" value
 * @method string   getMarque()             Returns the current record's "marque" value
 * @method string   getEnergie()            Returns the current record's "energie" value
 * @method string   getMatriculeVehicule()  Returns the current record's "matricule_vehicule" value
 * @method string   getDotationCarburant()  Returns the current record's "dotation_carburant" value
 * @method date     getDateApplied()        Returns the current record's "date_applied" value
 * @method string   getValider()            Returns the current record's "valider" value
 * @method blob     getFilecontent()        Returns the current record's "filecontent" value
 * @method string   getFilename()           Returns the current record's "filename" value
 * @method string   getFiletype()           Returns the current record's "filetype" value
 * @method integer  getFilesize()           Returns the current record's "filesize" value
 * @method string   getDescription()        Returns the current record's "description" value
 * @method Employee getEmployee()           Returns the current record's "Employee" value
 * @method Vehicule setId()                 Sets the current record's "id" value
 * @method Vehicule setEmpNumber()          Sets the current record's "emp_number" value
 * @method Vehicule setMarque()             Sets the current record's "marque" value
 * @method Vehicule setEnergie()            Sets the current record's "energie" value
 * @method Vehicule setMatriculeVehicule()  Sets the current record's "matricule_vehicule" value
 * @method Vehicule setDotationCarburant()  Sets the current record's "dotation_carburant" value
 * @method Vehicule setDateApplied()        Sets the current record's "date_applied" value
 * @method Vehicule setValider()            Sets the current record's "valider" value
 * @method Vehicule setFilecontent()        Sets the current record's "filecontent" value
 * @method Vehicule setFilename()           Sets the current record's "filename" value
 * @method Vehicule setFiletype()           Sets the current record's "filetype" value
 * @method Vehicule setFilesize()           Sets the current record's "filesize" value
 * @method Vehicule setDescription()        Sets the current record's "description" value
 * @method Vehicule setEmployee()           Sets the current record's "Employee" value
 * 
 * @package    orangehrm
 * @subpackage model\performance\base
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseVehicule extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ohrm_vehicule');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => 4,
             ));
        $this->hasColumn('emp_number', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('marque', 'string', 100, array(
             'type' => 'string',
             'length' => 100,
             ));
        $this->hasColumn('energie', 'string', 100, array(
             'type' => 'string',
             'length' => 100,
             ));
        $this->hasColumn('matricule_vehicule', 'string', 50, array(
             'type' => 'string',
             'length' => 50,
             ));
        $this->hasColumn('dotation_carburant', 'string', 50, array(
             'type' => 'string',
             'length' => 50,
             ));
        $this->hasColumn('date_applied', 'date', 25, array(
             'type' => 'date',
             'length' => 25,
             ));
        $this->hasColumn('valider', 'string', 20, array(
             'type' => 'string',
             'notnull' => false,
             'default' => 'En cours',
             'length' => 20,
             ));
        $this->hasColumn('filecontent', 'blob', 2147483647, array(
             'type' => 'blob',
             'notnull' => false,
             'length' => 2147483647,
             ));
        $this->hasColumn('filename', 'string', 255, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 255,
             ));
        $this->hasColumn('filetype', 'string', 255, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 255,
             ));
        $this->hasColumn('filesize', 'integer', null, array(
             'type' => 'integer',
             'notnull' => false,
             ));
        $this->hasColumn('description', 'string', 500, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'length' => 500,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Employee', array(
             'local' => 'emp_number',
             'foreign' => 'emp_number'));
    }
}