<?php

class VraCoreElement extends Omeka_Record_AbstractRecord
{
    public $record_id;

    public $record_type;

    public $element_id;

    public $vra_element_id;

    public $name;

    public $content;

    protected $attributes;

    protected $subelements = null;

    public function getAttributes()
    {
        if (!$this->attributes) {
            $this->attributes = $this->getDb()->getTable('VraCoreAttribute')
                ->findBy(array('vra_element_id' => $this->id));
        }

        return $this->attributes;
    }

    public function getAttributesAsHtml()
    {
        $attrs = $this->getAttributes();
        $attrsHtml = ' ';
        foreach ($attrs as $attr) {
            $attrsHtml .= $attr->name."='{$attr->content}' ";
        }

        return $attrsHtml;
    }

    public function getSubelements($name = null)
    {
        $params = array('vra_element_id' => $this->id,
                        'sort_field' => 'name',
                  );
        if ($name) {
            $params['name'] = $name;
        }
        $subelements = $this->getDb()->getTable('VraCoreElement')
            ->findBy($params);

        return $subelements;
    }

    public function hasSubelements($name = null)
    {
        $params = array('vra_element_id' => $this->id);
        if ($name) {
            $params['name'] = $name;
        }
        $count = $this->subelements = $this->getDb()->getTable('VraCoreElement')
            ->count($params);

        return $count !== 0;
    }

    public function hasAttributes()
    {
        $attributes = $this->getAttributes();
        $hasAttributes = true;
        if (count($attributes == 1)) {
            $attribute = $attributes[0];
            if ($attribute->name == 'dataDate') {
                $hasAttributes = false;
            }
        }

        return $hasAttributes;
    }

    public function getParentElements()
    {
        $allParentElements = array();

        if ($this->vra_element_id) {
            $params = array('id' => $this->vra_element_id);
            $parentElements = $this->getDb()->getTable('VraCoreElement')
                ->findBy($params);
            $allParentElements = array_merge($allParentElements, $parentElements);
        }
        //agent can go more than one level deep, so add those in, too
        foreach ($allParentElements as $parentElement) {
            if ($parentElement->vra_element_id) {
                $superParentElements = $this->getDb()->getTable('VraCoreElement')
                    ->findBy(array('id' => $parentElement->vra_element_id));
                $allParentElements = array_merge($allParentElements, $superParentElements);
            }
        }

        return $allParentElements;
    }

    public function updateDataDate()
    {
        $dataDateAttr = $this->getDb()->getTable('VraCoreAttribute')->fetchElementDataDate($this);
        if ($dataDateAttr) {
            $dataDateAttr->content = date('Y-m-d H:i:s');
            $dataDateAttr->save();
        } else {
            $dataDateAttr = new VraCoreAttribute();
            $dataDateAttr->name = 'dataDate';
            $dataDateAttr->vra_element_id = $this->id;
            $dataDateAttr->record_id = $this->record_id;
            $dataDateAttr->record_type = $this->record_type;
            $dataDateAttr->element_id = $this->element_id;
            $dataDateAttr->content = date('Y-m-d H:i:s');
            $dataDateAttr->save();
        }
    }

    protected function afterSave($args)
    {
        // update the dataDate on all parent elements
        $parentElements = $this->getParentElements();
        foreach ($parentElements as $parentElement) {
            $parentElement->updateDataDate();
        }
    }

    protected function beforeSave($args)
    {
        $this->content = trim($this->content);
    }

    protected function afterDelete()
    {
        $attributes = $this->getAttributes();
        $subElements = $this->getSubelements();
        foreach ($subElements as $subelement) {
            $subelement->delete();
        }
        foreach ($attributes as $attribute) {
            $attribute->delete();
        }

        $parentElements = $this->getParentElements();
        foreach ($parentElements as $parentElement) {
            if (!$parentElement->hasSubElements() && !$parentElement->hasAttributes()) {
                $parentElement->delete();
            }
        }
    }
}
