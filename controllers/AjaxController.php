<?php
class VraCore_AjaxController extends Omeka_Controller_AbstractActionController
{
    public function elementAction()
    {
        $attributeNames = array();
        $nameBase = '';
        $omekaElementName = '';
        $this->view->attributeNames = $attributeNames;
        $this->view->nameBase = $nameBase;
        $this->view->omekaElementName = $omekaElementName;
    }

    public function subelementAction()
    {
        $attributeNames = array();
        $nameBase = '';
        $subelementName = '';
        $this->view->attributeNames = $attributeNames;
        $this->view->nameBase = $nameBase;
        $this->view->subelementName = $subelementName;
    }
}