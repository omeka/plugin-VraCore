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
        $nameBase = $this->getParam('nameBase');
        $omekaElementName = $this->getParam('omekaElementName');
        $newElementCount = $this->getParam('newElementCount');

        $attributeNames = array_merge($elementsData[$omekaElementName]['attrs'], $globalAttrs);
        $this->view->attributeNames = $attributeNames;
        $this->view->nameBase = $nameBase;
        $this->view->omekaElementName = $omekaElementName;
        $this->view->newElementCount = $newElementCount;
    }

    public function subelementAction()
    {
        //@todo there has to be a cleaner way to pass the data around with statics
        // or at least know that it already exists someplace
        $plugin = new VraCorePlugin();
        $globalAttrs = $plugin->getGlobalAttrs();
        $elementsData = $plugin->getElementsData();
        $subelementsData = $plugin->getSubelementsData();
        $omekaElementName = $this->getParam('omekaElementName');
        $nameBase = $this->getParam('nameBase');
        $subelementName = $this->getParam('subelementName');
        $newSubelementCount = $this->getParam('newSubelementCount');
        //to handle the extra nesting of agent/dates
        $newAgentIndex = $this->getParam('newAgentIndex');
        $vraParentId = $this->getParam('vraParentId');

        if (isset($subelementsData[$subelementName])) {
            $attributeNames = array_merge($subelementsData[$subelementName]['attrs'], $globalAttrs);
        } else {
            $attributeNames = $globalAttrs;
        }

        $this->view->attributeNames = $attributeNames;
        $this->view->nameBase = $nameBase;
        $this->view->subelementName = $subelementName;
        $this->view->newSubelementCount = $newSubelementCount;
        $this->view->newAgentIndex = $newAgentIndex;
        $this->view->vraParentId = $vraParentId;
    }

    public function parentElementAction()
    {
        $plugin = new VraCorePlugin();
        $globalAttrs = $plugin->getGlobalAttrs();
        $elementsData = $plugin->getElementsData();
        $subelementsData = $plugin->getSubelementsData();
        $omekaElementName = $this->getParam('omekaElementName');
        $nameBase = $this->getParam('nameBase');
        $newElementCount = $this->getParam('newElementCount');
        $attributeNames = array_merge($elementsData[$omekaElementName]['attrs'], $globalAttrs);

        $this->view->attributeNames = $attributeNames;
        $this->view->nameBase = $nameBase;
        $this->view->omekaElementName = $omekaElementName;
        $this->view->newElementCount = $newElementCount;
        $this->view->elementsData = $elementsData;
        $this->view->subelementsData = $subelementsData;
        $this->view->globalAttrs = $globalAttrs;
        if ($omekaElementName == 'Agent') {
            $this->view->newAgentCount = $newElementCount;
        }
    }
}
