<?php

/**
 * BaseKpi
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $jobTitleCode
 * @property varchar $kpi_indicators
 * @property integer $min_rating
 * @property integer $max_rating
 * @property integer $default_kpi
 * @property varchar $kpi_group
 * @property string $delai
 * @property string $objectif
 * @property string $mode_calcul
 * @property varchar $kpi_type
 * @property JobTitle $JobTitle
 * @property Doctrine_Collection $ReviewerRating
 * @property Doctrine_Collection $Commentaire
 * 
 * @method integer             getId()             Returns the current record's "id" value
 * @method string              getJobTitleCode()   Returns the current record's "jobTitleCode" value
 * @method varchar             getKpiIndicators()  Returns the current record's "kpi_indicators" value
 * @method integer             getMinRating()      Returns the current record's "min_rating" value
 * @method integer             getMaxRating()      Returns the current record's "max_rating" value
 * @method integer             getDefaultKpi()     Returns the current record's "default_kpi" value
 * @method varchar             getKpiGroup()       Returns the current record's "kpi_group" value
 * @method string              getDelai()          Returns the current record's "delai" value
 * @method string              getObjectif()       Returns the current record's "objectif" value
 * @method string              getModeCalcul()     Returns the current record's "mode_calcul" value
 * @method varchar             getKpiType()        Returns the current record's "kpi_type" value
 * @method JobTitle            getJobTitle()       Returns the current record's "JobTitle" value
 * @method Doctrine_Collection getReviewerRating() Returns the current record's "ReviewerRating" collection
 * @method Doctrine_Collection getCommentaire()    Returns the current record's "Commentaire" collection
 * @method Kpi                 setId()             Sets the current record's "id" value
 * @method Kpi                 setJobTitleCode()   Sets the current record's "jobTitleCode" value
 * @method Kpi                 setKpiIndicators()  Sets the current record's "kpi_indicators" value
 * @method Kpi                 setMinRating()      Sets the current record's "min_rating" value
 * @method Kpi                 setMaxRating()      Sets the current record's "max_rating" value
 * @method Kpi                 setDefaultKpi()     Sets the current record's "default_kpi" value
 * @method Kpi                 setKpiGroup()       Sets the current record's "kpi_group" value
 * @method Kpi                 setDelai()          Sets the current record's "delai" value
 * @method Kpi                 setObjectif()       Sets the current record's "objectif" value
 * @method Kpi                 setModeCalcul()     Sets the current record's "mode_calcul" value
 * @method Kpi                 setKpiType()        Sets the current record's "kpi_type" value
 * @method Kpi                 setJobTitle()       Sets the current record's "JobTitle" value
 * @method Kpi                 setReviewerRating() Sets the current record's "ReviewerRating" collection
 * @method Kpi                 setCommentaire()    Sets the current record's "Commentaire" collection
 * 
 * @package    orangehrm
 * @subpackage model\performance\base
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseKpi extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ohrm_kpi');
        $this->hasColumn('id', 'integer', 6, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => 6,
             ));
        $this->hasColumn('job_title_code as jobTitleCode', 'string', 10, array(
             'type' => 'string',
             'length' => 10,
             ));
        $this->hasColumn('kpi_indicators', 'varchar', 255, array(
             'type' => 'varchar',
             'length' => 255,
             ));
        $this->hasColumn('min_rating', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));
        $this->hasColumn('max_rating', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));
        $this->hasColumn('default_kpi', 'integer', 2, array(
             'type' => 'integer',
             'length' => 2,
             ));
        $this->hasColumn('kpi_group', 'varchar', 255, array(
             'type' => 'varchar',
             'length' => 255,
             ));
        $this->hasColumn('delai', 'string', 250, array(
             'type' => 'string',
             'length' => 250,
             ));
        $this->hasColumn('objectif', 'string', 250, array(
             'type' => 'string',
             'length' => 250,
             ));
        $this->hasColumn('mode_calcul', 'string', 250, array(
             'type' => 'string',
             'length' => 250,
             ));
        $this->hasColumn('kpi_type', 'varchar', 400, array(
             'type' => 'varchar',
             'length' => 400,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('JobTitle', array(
             'local' => 'jobTitleCode',
             'foreign' => 'id'));

        $this->hasMany('ReviewerRating', array(
             'local' => 'id',
             'foreign' => 'kpi_id'));

        $this->hasMany('Commentaire', array(
             'local' => 'id',
             'foreign' => 'kpi_id'));

        $softdelete0 = new Doctrine_Template_SoftDelete(array(
             ));
        $this->actAs($softdelete0);
    }
}