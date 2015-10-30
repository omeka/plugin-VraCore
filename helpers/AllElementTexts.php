<?php
/**
 * Omeka
 * 
 * @copyright Copyright 2007-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * View helper for retrieving lists of metadata for any record that uses 
 * Mixin_ElementText.
 * 
 * @package Omeka\View\Helper
 */
class VraCore_View_Helper_AllElementTexts extends Omeka_View_Helper_AllElementTexts
{
    /**
     * Path for the view partial.
     *
     * @var string
     */
    protected $_partial = 'record-metadata.php';

}
