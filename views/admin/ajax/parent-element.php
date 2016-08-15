<?php

if (!isset($parentVraElementId)) {
    $parentVraElementId = '';
}

?>

<div class='vra-element new added'>
    <div class='vra-element-header' tabindex="0">
        <div class='drawer closed'></div><label class='vra-elements-label'><?php echo __('%s Attributes and Subelements', $omekaElementName); ?></label>
    </div>
    <fieldset class="drawer-contents" style='display: none;'>
        <input class='vra-new' type='hidden' name='<?php echo $nameBase; ?>[newElements][<?php echo $newElementCount; ?>][name]' value='<?php echo $omekaElementName; ?>'></input>
        <input class='vra-new' type='hidden' name='<?php echo $nameBase; ?>[newElements][<?php echo $newElementCount; ?>][hasSubelements]' value='1'></input>
        <div class='vra-subelements'>
        
        <?php echo $this->partial('element-attribute-form.php',
                array(
                     'attributeNames' => $attributeNames,
                     'attributeObjects' => array(),
                     'nameBase' => $nameBase."[newElements][$newElementCount]",
                     'label' => __('%s Attributes', $omekaElementName),
                     )
                );
        ?>
        
        <?php foreach ($elementsData[$omekaElementName]['subelements'] as $subelementName): ?>
            <?php
                switch ($subelementName) {
                    case 'earliestDate':
                        $subelementLabel = __('Earliest Date');
                    break;
                    case 'latestDate':
                        $subelementLabel = __('Latest Date');
                    break;
                    default:
                        $subelementLabel = ucfirst(__($subelementName));
                    break;
                }
            ?>
            <?php if ($subelementName == 'dates'): ?>
            <div class='vra-subelement-container'>
                <div class='vra-element-header' tabindex="0">
                    <div class='drawer closed'></div><label class='vra-subelement-label'><?php echo $subelementLabel; ?></label>
                </div>
                <fieldset style='display:none'>
                    <input class='subelement-add' type='submit' value='Add VRA Dates Element'  data-newAgentIndex='<?php echo $newAgentCount; ?>' data-namebase='<?php echo $nameBase; ?>' data-subelement-name='<?php echo $subelementName; ?>' data-omeka-element-name='<?php echo $omekaElement->name; ?>'></input>
                    <?php echo $this->partial('element-attribute-form.php',
                            array(
                                 'attributeNames' => array_merge(array('type'), $attributeNames),
                                 //kind of a cheat. put true at the front to produce a new set of attributes for new element
                                 'vraElementObjects' => array(true),
                                 'nameBase' => $nameBase."[newElements][$newElementCount][newSubelements][$subelementName][0]",
                                 'label' => __('%s Attributes', $subelementLabel),
                                 )
                            );
                    ?>
                <?php
                        echo $this->partial('dates-form.php',
                            array(
                                  'elementsData' => $this->elementsData,
                                  'subelementsData' => $this->subelementsData,
                                  'subelementName' => $subelementName,
                                  'newElementCount' => $newElementCount,
                                  'nameBase' => $nameBase,
                                    //@todo : how many of these are still actually used?
                                  'globalAttributes' => $this->globalAttrs,
                                  'attributeNames' => $attributeNames,
                                  'attributeObjects' => array(),
                            )
                        );
                ?>
                </fieldset>
            </div>
            <?php else: ?>
            <div class='vra-subelement-container'>
                <div class='vra-element-header' tabindex="0">
                    <div class='drawer closed'></div><label class='vra-subelement-label'><?php echo $subelementLabel; ?></label>
                </div>
                <fieldset style='display: none'>
                    <?php if ($subelementName != 'earliestDate' && $subelementName != 'latestDate'): ?>
                        <input class='subelement-add' type='submit' value='Add Input' data-namebase='<?php echo $nameBase; ?>' data-subelement-name='<?php echo $subelementName; ?>' data-omeka-element-name='<?php echo $omekaElementName; ?>'></input>
                    <?php endif; ?>

                    <div class='vra-subelement vra-element-inputs new'>
                        <?php
                            if (isset($subelementsData[$subelementName])) {
                                $attributeNames = array_merge($subelementsData[$subelementName]['attrs'], $globalAttrs);
                            } else {
                                $attributeNames = $globalAttrs;
                            }
                        ?>
                        <textarea class='vra-new' name='<?php echo $nameBase; ?>[newElements][<?php echo $newElementCount; ?>][newSubelements][<?php echo $subelementName; ?>][0][content]' value=''></textarea>
                        <input class='vra-new' type='hidden' name='<?php echo $nameBase; ?>[newElements][<?php echo $newElementCount; ?>][newSubelements][<?php echo $subelementName; ?>][0][parent_id]' value='<?php echo $parentVraElementId; ?>'></input>
                            <?php echo $this->partial('element-attribute-form.php',
                                    array(
                                         'attributeNames' => $attributeNames,
                                         'attributeObjects' => array(),
                                         //kind of a cheat. put true at the front to produce a new set of attributes for new element
                                         'vraElementObjects' => array(true),
                                         'nameBase' => $nameBase."[newElements][$newElementCount][newSubelements][$subelementName][0]",
                                         'label' => __('%s Attributes', $subelementLabel),
                                         )
                                    );
                            ?>
                    </div>
                </fieldset>
            </div>
            <?php endif; ?>
        <?php endforeach; ?>
        </div>
    </fieldset>
</div>
