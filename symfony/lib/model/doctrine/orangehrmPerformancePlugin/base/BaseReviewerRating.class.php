<?php

/**
 * BaseReviewerRating
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property decimal $rating
 * @property integer $kpiId
 * @property integer $reviewId
 * @property integer $reviewerId
 * @property clob $comment
 * @property decimal $mois2
 * @property decimal $mois3
 * @property decimal $mois4
 * @property decimal $mois5
 * @property decimal $mois6
 * @property decimal $mois7
 * @property decimal $mois8
 * @property decimal $mois9
 * @property decimal $mois10
 * @property decimal $mois11
 * @property decimal $mois12
 * @property decimal $note
 * @property decimal $taux_atteint
 * @property decimal $cumule
 * @property string $valeur_cible
 * @property decimal $poids
 * @property PerformanceReview $performanceReview
 * @property Reviewer $reviewer
 * @property Kpi $kpi
 * @property Doctrine_Collection $Commentaire
 * 
 * @method integer             getId()                Returns the current record's "id" value
 * @method decimal             getRating()            Returns the current record's "rating" value
 * @method integer             getKpiId()             Returns the current record's "kpiId" value
 * @method integer             getReviewId()          Returns the current record's "reviewId" value
 * @method integer             getReviewerId()        Returns the current record's "reviewerId" value
 * @method clob                getComment()           Returns the current record's "comment" value
 * @method decimal             getMois2()             Returns the current record's "mois2" value
 * @method decimal             getMois3()             Returns the current record's "mois3" value
 * @method decimal             getMois4()             Returns the current record's "mois4" value
 * @method decimal             getMois5()             Returns the current record's "mois5" value
 * @method decimal             getMois6()             Returns the current record's "mois6" value
 * @method decimal             getMois7()             Returns the current record's "mois7" value
 * @method decimal             getMois8()             Returns the current record's "mois8" value
 * @method decimal             getMois9()             Returns the current record's "mois9" value
 * @method decimal             getMois10()            Returns the current record's "mois10" value
 * @method decimal             getMois11()            Returns the current record's "mois11" value
 * @method decimal             getMois12()            Returns the current record's "mois12" value
 * @method decimal             getNote()              Returns the current record's "note" value
 * @method decimal             getTauxAtteint()       Returns the current record's "taux_atteint" value
 * @method decimal             getCumule()            Returns the current record's "cumule" value
 * @method string              getValeurCible()       Returns the current record's "valeur_cible" value
 * @method decimal             getPoids()             Returns the current record's "poids" value
 * @method PerformanceReview   getPerformanceReview() Returns the current record's "performanceReview" value
 * @method Reviewer            getReviewer()          Returns the current record's "reviewer" value
 * @method Kpi                 getKpi()               Returns the current record's "kpi" value
 * @method Doctrine_Collection getCommentaire()       Returns the current record's "Commentaire" collection
 * @method ReviewerRating      setId()                Sets the current record's "id" value
 * @method ReviewerRating      setRating()            Sets the current record's "rating" value
 * @method ReviewerRating      setKpiId()             Sets the current record's "kpiId" value
 * @method ReviewerRating      setReviewId()          Sets the current record's "reviewId" value
 * @method ReviewerRating      setReviewerId()        Sets the current record's "reviewerId" value
 * @method ReviewerRating      setComment()           Sets the current record's "comment" value
 * @method ReviewerRating      setMois2()             Sets the current record's "mois2" value
 * @method ReviewerRating      setMois3()             Sets the current record's "mois3" value
 * @method ReviewerRating      setMois4()             Sets the current record's "mois4" value
 * @method ReviewerRating      setMois5()             Sets the current record's "mois5" value
 * @method ReviewerRating      setMois6()             Sets the current record's "mois6" value
 * @method ReviewerRating      setMois7()             Sets the current record's "mois7" value
 * @method ReviewerRating      setMois8()             Sets the current record's "mois8" value
 * @method ReviewerRating      setMois9()             Sets the current record's "mois9" value
 * @method ReviewerRating      setMois10()            Sets the current record's "mois10" value
 * @method ReviewerRating      setMois11()            Sets the current record's "mois11" value
 * @method ReviewerRating      setMois12()            Sets the current record's "mois12" value
 * @method ReviewerRating      setNote()              Sets the current record's "note" value
 * @method ReviewerRating      setTauxAtteint()       Sets the current record's "taux_atteint" value
 * @method ReviewerRating      setCumule()            Sets the current record's "cumule" value
 * @method ReviewerRating      setValeurCible()       Sets the current record's "valeur_cible" value
 * @method ReviewerRating      setPoids()             Sets the current record's "poids" value
 * @method ReviewerRating      setPerformanceReview() Sets the current record's "performanceReview" value
 * @method ReviewerRating      setReviewer()          Sets the current record's "reviewer" value
 * @method ReviewerRating      setKpi()               Sets the current record's "kpi" value
 * @method ReviewerRating      setCommentaire()       Sets the current record's "Commentaire" collection
 * 
 * @package    orangehrm
 * @subpackage model\performance\base
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseReviewerRating extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ohrm_reviewer_rating');
        $this->hasColumn('id', 'integer', 6, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => 6,
             ));
        $this->hasColumn('rating as rating', 'decimal', null, array(
             'type' => 'decimal',
             'notnull' => false,
             ));
        $this->hasColumn('kpi_id as kpiId', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));
        $this->hasColumn('review_id as reviewId', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));
        $this->hasColumn('reviewer_id as reviewerId', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));
        $this->hasColumn('comment as comment', 'clob', 65532, array(
             'type' => 'clob',
             'length' => 65532,
             ));
        $this->hasColumn('mois2', 'decimal', null, array(
             'type' => 'decimal',
             ));
        $this->hasColumn('mois3', 'decimal', null, array(
             'type' => 'decimal',
             ));
        $this->hasColumn('mois4', 'decimal', null, array(
             'type' => 'decimal',
             ));
        $this->hasColumn('mois5', 'decimal', null, array(
             'type' => 'decimal',
             ));
        $this->hasColumn('mois6', 'decimal', null, array(
             'type' => 'decimal',
             ));
        $this->hasColumn('mois7', 'decimal', null, array(
             'type' => 'decimal',
             ));
        $this->hasColumn('mois8', 'decimal', null, array(
             'type' => 'decimal',
             ));
        $this->hasColumn('mois9', 'decimal', null, array(
             'type' => 'decimal',
             ));
        $this->hasColumn('mois10', 'decimal', null, array(
             'type' => 'decimal',
             ));
        $this->hasColumn('mois11', 'decimal', null, array(
             'type' => 'decimal',
             ));
        $this->hasColumn('mois12', 'decimal', null, array(
             'type' => 'decimal',
             ));
        $this->hasColumn('note', 'decimal', null, array(
             'type' => 'decimal',
             ));
        $this->hasColumn('taux_atteint', 'decimal', null, array(
             'type' => 'decimal',
             ));
        $this->hasColumn('cumule', 'decimal', null, array(
             'type' => 'decimal',
             ));
        $this->hasColumn('valeur_cible', 'string', 250, array(
             'type' => 'string',
             'length' => 250,
             ));
        $this->hasColumn('poids', 'decimal', 11, array(
             'type' => 'decimal',
             'length' => 11,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('PerformanceReview as performanceReview', array(
             'local' => 'review_id',
             'foreign' => 'id'));

        $this->hasOne('Reviewer as reviewer', array(
             'local' => 'reviewer_id',
             'foreign' => 'id'));

        $this->hasOne('Kpi as kpi', array(
             'local' => 'kpi_id',
             'foreign' => 'id'));

        $this->hasMany('Commentaire', array(
             'local' => 'id',
             'foreign' => 'rating_id'));
    }
}