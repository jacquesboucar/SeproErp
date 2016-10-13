<?php

/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */
class TrainingDao extends BaseDao {

     /**
     *
     * @param sfDoctrineRecord $kpi
     * @return \sfDoctrineRecord
     * @throws DaoException 
     */
    public function saveTraining(sfDoctrineRecord $training) {
        $conn = Doctrine_Manager::connection();
        $conn->beginTransaction();   
        try {
            //var_dump($training->getId());die;
            $training->save();
            $conn->commit();
            return $training;
        } catch (Exception $e) {
            $conn->rollback();
            throw new DaoException($e->getMessage());
        }
    }

    /**
     *
     * @param array $ids
     * @throws DaoException 
     */
    public function deleteTraining($ids) {
        try {
            if (sizeof($ids)) {
                $q = Doctrine_Query::create()
                        ->delete('Training')
                        ->whereIn('id', $ids);
                $q->execute();
            }
            return true;
            //@codeCoverageIgnoreStart
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }//@codeCoverageIgnoreEnd
    }

    
    public function getTrainingById($id){
        try{
            $result = Doctrine :: getTable('Training')->find($id);
            return $result;
            //@codeCoverageIgnoreStart
        } catch (Exception $ex) {
            throw new DaoException($ex->getMessage(), $ex->getCode(), $ex);
        }//@codeCoverageIgnoreEnd
    }
    
    /**
     *
     * @param array $parameters
     * @return Doctrine_Collection
     * @throws DaoException 
     */
    public function searchTraining($parameters = null) {
        try {
            $query = Doctrine_Query:: create()->from('Training');
            
            $offset = ($parameters['page'] > 0) ? (($parameters['page'] - 1) * $parameters['limit']) : 0;

            if (!empty($parameters)) {
                if (isset($parameters['id']) && $parameters['id'] > 0) {
                    $query->andWhere('id = ?', $parameters['id']);
                    return $query->fetchOne();
                } else {
                    foreach ($parameters as $key => $parameter) {
                        if (strlen(trim($parameter)) > 0) {
                            switch ($key) {
                                case 'title':
                                    $query->andWhere('title = ?', $parameter);
                                    break;
                                case 'coutformation':
                                    $query->andWhere('cout_formation = ?', $parameter);
                                    break;
                                case 'employeeNumber':
                                    $query->andWhere('emp_number = ?', $parameter);
                                    break;
                                default:
                                    break;
                            }
                        }
                    }
                }
            }
            //$query->andWhere('deleted_at IS NULL');
            $query->offset($offset);
            
            if ($parameters['limit'] != null) {
                $query->limit($parameters['limit']);
            }
            $query->orderBy('title');
            return $query->execute();
            //@codeCoverageIgnoreStart
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }//@codeCoverageIgnoreEnd
    }
}