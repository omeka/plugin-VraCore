<?php

class Table_VraCoreAttribute extends Omeka_Db_Table
{
    public function getSelectForFindBy($params = array())
    {
        if (isset($params['vra_element_id']) && !$params['vra_element_id']) {
            unset($params['vra_element_id']);
            $select = parent::getSelectForFindBy($params);
            $select->where('vra_element_id IS NULL');
        } else {
            $select = parent::getSelectForFindBy($params);
        }
        return $select;
    }

    public function fetchElementDataDate($vraElement)
    {
        $select = $this->getSelectForFindBy(array(
                    'vra_element_id' => $vraElement->id,
                    'name'           => 'dataDate',
                    'element_id'     => $vraElement->element_id,
                    'record_type'    => $vraElement->record_type,
                    'record_id'      => $vraElement->record_id,
                    )
                );
        return $this->fetchObject($select);
    }
}
