<?php

class Table_VraCoreElement extends Omeka_Db_Table
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

    public function findNotesForRecordElement($omekaRecord, $omekaElementId)
    {
        $params = array(
                'record_id' => $omekaRecord->id,
                'record_type' => get_class($omekaRecord),
                'name' => 'notes',
                'element_id' => $omekaElementId,
                );
        $select = $this->getSelectForFindBy($params);

        return $this->fetchObject($select);
    }

    public function omekaElementHasVraElements($omekaElement, $omekaRecord)
    {
        $count = $this->count(array('element_id' => $omekaElement->id,
                'record_id' => $omekaRecord->id,
                'record_type' => get_class($omekaRecord),
                ));
        if ($count > 0) {
            return true;
        }

        return false;
    }
}
