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
class KpiGroupDao extends BaseDao {

     /**
     *
     * @param sfDoctrineRecord $kpi
     * @return \sfDoctrineRecord
     * @throws DaoException 
     */
    public function saveKpiGroup(sfDoctrineRecord $kpiGroup) {
        $conn = Doctrine_Manager::connection();
        $conn->beginTransaction();   
        try {
            $kpiGroup->save();
            $conn->commit();
            return $kpiGroup;
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
    public function deleteKpiGroup($ids) {
        try {
            if (sizeof($ids)) {
                $q = Doctrine_Query::create()
                        ->delete('KpiGroup')
                        ->whereIn('id', $ids);
                $q->execute();
            }
            return true;
            //@codeCoverageIgnoreStart
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }//@codeCoverageIgnoreEnd
    }

    
    public function getKpiGroupById($id){
        try{
            $result = Doctrine :: getTable('KpiGroup')->find($id);
            return $result;
            //@codeCoverageIgnoreStart
        } catch (Exception $ex) {
            throw new DaoException($ex->getMessage(), $ex->getCode(), $ex);
        }//@codeCoverageIgnoreEnd
    }
    public function getKpiGroupList() {

        

        try {
            $q = Doctrine_Query :: create()
                            ->from('KpiGroup');
            return $q->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }
}