<?php
/**
 * Omeka.
 * 
 * @copyright Copyright 2007-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * View helper for retrieving lists of metadata for any record that uses 
 * Mixin_ElementText.
 */
class VraCore_View_Helper_AllElementTexts extends Omeka_View_Helper_AllElementTexts
{
    protected function _elementIsShowable(Element $element, $texts)
    {
        $elementSet = $element->getElementSet();
        if ($elementSet->name == 'VRA Core') {
            //check if there are VRA elements attached
            $hasVraElements = get_db()->getTable('VraCoreElement')->omekaElementHasVraElements($element, $this->_record);
            if ($hasVraElements) {
                return true;
            }

            return $this->_showEmptyElements || !empty($texts);
        }

        return $this->_showEmptyElements || !empty($texts);
    }
    /**
     * Path for the view partial.
     *
     * @var string
     */
    protected $_partial = 'record-metadata.php';
}
