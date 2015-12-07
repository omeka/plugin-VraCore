<?php
class VraCore_AjaxController extends Omeka_Controller_AbstractActionController
{
    public function elementAction()
    {
        
        //@todo there has to be a cleaner way to pass the data around with statics
        // or at least know that it already exists someplace
        $plugin = new VraCorePlugin();
        $globalAttrs = $plugin->getGlobalAttrs();
        $elementsData = $plugin->getElementsData();
        
        $attributeNames = array();
        $nameBase = $this->getParam('nameBase');
        $omekaElementName = 'Title';
        $newElementCount = $this->getParam('newElementCount');

        $attributeNames = array_merge($elementsData[$omekaElementName]['attrs'], $globalAttrs);
        $this->view->attributeNames = $attributeNames;
        $this->view->nameBase = $nameBase;
        $this->view->omekaElementName = $omekaElementName;
        $this->view->newElementCount  = $newElementCount;
    }

    public function subelementAction()
    {
        $attributeNames = array();
        $nameBase = '';
        $subelementName = '';
        $newElementCount = 1; //ajaxed in
        $newSubelementCount = 1; //ajaxed in
        
        
        $this->view->attributeNames = $attributeNames;
        $this->view->nameBase = $nameBase;
        $this->view->subelementName = $subelementName;
        $this->newElementCount  = $newElementCount;
        
    }
}