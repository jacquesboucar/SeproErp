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
class PretImmobilierDao extends BaseDao {


    /**
     * @param sfDoctrineRecord $pretimmobilier
     * @return sfDoctrineRecord
     * @throws DaoException
     */
    public function savePretImmobilier(sfDoctrineRecord $pretimmobilier) {
        $conn = Doctrine_Manager::connection();
        $conn->beginTransaction();   
        try {
            $pretimmobilier->save();
            $conn->commit();
            return $pretimmobilier;
        } catch (Exception $e) {
            $conn->rollback();
            throw new DaoException($e->getMessage());
        }
    }


    /**
     * @param $ids
     * @return bool
     * @throws DaoException
     */
    public function deletePretImmobilier($ids) {
        try {
            if (sizeof($ids)) {
                $q = Doctrine_Query::create()
                        ->delete('PretImmobilier')
                        ->whereIn('id', $ids);
                $q->execute();
            }
            return true;
            //@codeCoverageIgnoreStart
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }//@codeCoverageIgnoreEnd
    }


    /**
     * @param $id
     * @return mixed
     * @throws DaoException
     */
    public function getPretImmobilierById($id){
        try{
            $result = Doctrine :: getTable('PretImmobilier')->find($id);
            return $result;
            //@codeCoverageIgnoreStart
        } catch (Exception $ex) {
            throw new DaoException($ex->getMessage(), $ex->getCode(), $ex);
        }//@codeCoverageIgnoreEnd
    }


    /**
     * @param null $parameters
     * @return Doctrine_Collection|mixed
     * @throws DaoException
     */
    public function searchPretImmobilier($parameters = null) {
        try {
            $query = Doctrine_Query:: create()->from('PretImmobilier');
            
            $offset = ($parameters['page'] > 0) ? (($parameters['page'] - 1) * $parameters['limit']) : 0;

            if (!empty($parameters)) {
                if (isset($parameters['id']) && $parameters['id'] > 0) {
                    $query->andWhere('id = ?', $parameters['id']);
                    return $query->fetchOne();
                } else {
                    foreach ($parameters as $key => $parameter) {
                        if (strlen(trim($parameter)) > 0) {
                            switch ($key) {
                                case 'objet':
                                    $query->andWhere('objet = ?', $parameter);
                                    break;
                                case 'isDefault':
                                    $query->andWhere('default_training = ?', $parameter);
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
            $query->orderBy('objet');
            return $query->execute();
            //@codeCoverageIgnoreStart
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }//@codeCoverageIgnoreEnd
    }
}