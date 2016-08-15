<?php

class Table_VraCoreAttribute extends Omeka_Db_Table
{
    public function getSelectForFindBy($params = array())
    {
        //let me search by some columns being null
        $nulledColumns = array();
        if (isset($params['vra_element_id']) && !$params['vra_element_id']) {
            unset($params['vra_element_id']);
            $nulledColumns[] = 'vra_element_id';
        }
        if (isset($params['element_id']) && !$params['element_id']) {
            unset($params['element_id']);
            $nulledColumns[] = 'element_id';
        }
        $select = parent::getSelectForFindBy($params);
        foreach ($nulledColumns as $nulledColumn) {
            $select->where("$nulledColumn IS NULL");
        }

        return $select;
    }

    public function fetchElementDataDate($vraElement)
    {
        $select = $this->getSelectForFindBy(array(
                    'vra_element_id' => $vraElement->id,
                    'name' => 'dataDate',
                    'element_id' => $vraElement->element_id,
                    'record_type' => $vraElement->record_type,
                    'record_id' => $vraElement->record_id,
                    )
                );

        return $this->fetchObject($select);
    }
}
