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
 * 
 * @method integer  getId()                 Returns the current record's "id" value
 * @method integer  getEmpNumber()          Returns the current record's "emp_number" value
 * @method string   getMarque()             Returns the current record's "marque" value
 * @method string   getEnergie()            Returns the current record's "energie" value
 * @method string   getMatriculeVehicule()  Returns the current record's "matricule_vehicule" value
 * @method string   getDotationCarburant()  Returns the current record's "dotation_carburant" value
 * @method date     getDateApplied()        Returns the current record's "date_applied" value
 * @method Vehicule setId()                 Sets the current record's "id" value
 * @method Vehicule setEmpNumber()          Sets the current record's "emp_number" value
 * @method Vehicule setMarque()             Sets the current record's "marque" value
 * @method Vehicule setEnergie()            Sets the current record's "energie" value
 * @method Vehicule setMatriculeVehicule()  Sets the current record's "matricule_vehicule" value
 * @method Vehicule setDotationCarburant()  Sets the current record's "dotation_carburant" value
 * @method Vehicule setDateApplied()        Sets the current record's "date_applied" value
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
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}