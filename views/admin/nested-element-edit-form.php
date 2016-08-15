<?php
    $nameBase = "vra-element[{$omekaElement->id}]";

    if (!isset($newAgentCount)) {
        $newAgentCount = 0;
    }
?>

<div class='vra-data'>
        <?php echo $this->partial('element-attribute-form.php',
                array(
                     'attributeNames' => $globalAttributes,
                     'attributeObjects' => $attributeObjects,
                     'nameBase' => $nameBase.'[display]',
                     'label' => __('Display Attributes'),
                     'topLevel' => 'display',
                     )
                );
        ?>

    <?php $notes = $notesObject ? metadata($notesObject, 'content') : ''; ?>
    <div class='vra-element'>
        <div class='vra-element-header' tabindex="0">
            <div class='drawer closed'></div><label class='vra-notes-element-label'><?php echo __('Notes'); ?></label>
        </div>
        <fieldset class="drawer-contents" style='display: none'>
            <div class='vra-element-inputs'>
                <textarea name='<?php echo $nameBase; ?>[notes][content]'><?php echo $notes; ?></textarea>
                        <?php echo $this->partial('element-attribute-form.php',
                                array(
                                     'attributeNames' => $globalAttributes,
                                     'attributeObjects' => $attributeObjects,
                                     'nameBase' => $nameBase.'[notes]',
                                     'label' => __('Notes Attributes'),
                                     'topLevel' => 'notes',
                                     )
                                );
                        ?>
            </div>
        </fieldset>
    </div>

    <input class='parent-element-add' type='submit' value='Add VRA <?php echo $omekaElement->name; ?> element' data-namebase='<?php echo $nameBase; ?>' data-element-name='<?php echo $omekaElement->name; ?>' data-omeka-element-name='<?php echo $omekaElement->name; ?>'></input>

<?php if (count($vraElementObjects) == 0): ?>
    <!-- copy of parent-element.php -->

<?php
//setup adjusted from parent-element.php
if (!isset($parentVraElementId)) {
    $parentVraElementId = '';
}
$newElementCount = 0;
$omekaElementName = $omekaElement->name;
?>
<div class='vra-element new'>
    <div class='vra-element-header' tabindex="0">
        <div class='drawer closed'></div><label class='vra-elements-label'><?php echo __('%s Attributes and Subelements', $omekaElementName); ?></label>
    </div>
    <fieldset class="drawer-contents" style='display: none;'>
        <input type='hidden' class='vra-new' name='<?php echo $nameBase; ?>[newElements][<?php echo $newElementCount; ?>][name]' value='<?php echo $omekaElementName; ?>'></input>
        <input type='hidden' class='vra-new' name='<?php echo $nameBase; ?>[newElements][<?php echo $newElementCount; ?>][hasSubelements]' value='1'></input>


        <div class='vra-subelements'>
        <?php echo $this->partial('element-attribute-form.php',
                array(
                     'attributeNames' => $attributeNames,
                     'attributeObjects' => $attributeObjects,
                     'nameBase' => $nameBase."[newElements][$newElementCount]",
                     'label' => __('%s Attributes', $omekaElementName),
                     )
                );
        ?>

        <?php foreach ($elementsData[$omekaElementName]['subelements'] as $subelementName): ?>
            <?php if ($subelementName == 'dates'): ?>
            <div class='vra-subelement-container'>
                <div class='vra-element-header' tabindex="0">
                    <div class='drawer closed'></div><label class='vra-subelement-label'><?php echo ucfirst($subelementName); ?></label>
                </div>
                <fieldset class="drawer-contents" style='display:none'>
                    <input class='subelement-add' type='submit' value='Add VRA Dates Element' data-newAgentIndex='<?php echo $newAgentCount; ?>' data-namebase='<?php echo $nameBase; ?>' data-subelement-name='<?php echo $subelementName; ?>' data-omeka-element-name='<?php echo $omekaElement->name; ?>'></input>
                    <?php echo $this->partial('element-attribute-form.php',
                            array(
                                 'attributeNames' => array_merge(array('type'), $attributeNames),
                                 //kind of a cheat. put true at the front to produce a new set of attributes for new element
                                 'vraElementObjects' => array(true),
                                 'nameBase' => $nameBase."[newElements][$newAgentCount][newSubelements][$subelementName][0]",
                                 'label' => __('%s Attributes', ucfirst($subelementName)),
                                 )
                            );
                    ?>
                <?php
                        echo $this->partial('dates-form.php',
                            array(
                                  'elementsData' => $this->elementsData,
                                  'subelementsData' => $this->subelementsData,
                                  'subelementName' => $subelementName,
                                  'nameBase' => $nameBase,
                                  'newAgentCount' => $newAgentCount,
                                  'newElementCount' => 0,
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
                    <div class='drawer closed'></div><label class='vra-subelement-label'><?php echo $subelementLabel; ?></label>
                </div>
                <fieldset class="drawer-contents" style='display: none'>
                    <?php if ($subelementName != 'earliestDate' && $subelementName != 'latestDate'): ?>
                        <input class='subelement-add' type='submit' value='Add Input' data-namebase='<?php echo $nameBase; ?>' data-subelement-name='<?php echo $subelementName; ?>' data-omeka-element-name='<?php echo $omekaElementName; ?>'></input>
                    <?php endif; ?>

                    <div class='vra-subelement vra-element-inputs new'>
                        <?php
                            if (isset($subelementsData[$subelementName])) {
                                $attributeNames = array_merge($subelementsData[$subelementName]['attrs'], $globalAttributes);
                            } else {
                                $attributeNames = $globalAttributes;
                            }
                        ?>
                        <textarea class='vra-new' name='<?php echo $nameBase; ?>[newElements][<?php echo $newElementCount; ?>][newSubelements][<?php echo $subelementName; ?>][0][content]'></textarea>
                        <input type='hidden' name='<?php echo $nameBase; ?>[newElements][<?php echo $newElementCount; ?>][newSubelements][<?php echo $subelementName; ?>][0][parent_id]' value='<?php echo $parentVraElementId; ?>'></input>
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



    <!-- end copy of parent-element.php -->
<?php endif; ?>



    <?php foreach ($vraElementObjects as $parentObject): ?>
    <?php
        $vraSubElementObjects = $parentObject->getSubelements();
    ?>


    <div class='vra-element'>
        <div class='vra-element-header' tabindex="0">
            <div class='drawer closed'></div><label class='vra-elements-label'><?php echo $omekaElement->name.' '.__('attributes and subelements'); ?></label>
        </div>
        <fieldset class="drawer-contents" style='display: none;'>
            <?php echo $this->partial('element-attribute-form.php',
                    array(
                         'attributeNames' => $attributeNames,
                         'attributeObjects' => $attributeObjects,
                         //kind of a cheat. put true at the front to produce a new set of attributes for new element
                         'vraElementObjects' => array(true),
                         'vraElementObject' => $parentObject,
                         'nameBase' => $nameBase."[{$parentObject->id}]",
                         'label' => __('%s Attributes', $omekaElement->name),
                         )
                    );
            ?>
            <input type='hidden' name='<?php echo $nameBase; ?>[newElements][0][name]' value='<?php echo $omekaElement->name; ?>'></input>
            <input type='hidden' name='<?php echo $nameBase; ?>[newElements][0][hasSubelements]' value='1'></input>
            <div class='vra-subelements'>
            <?php foreach ($elementsData[$omekaElement->name]['subelements'] as $subelementName): ?>
                <?php
                    if (isset($subelementsData[$subelementName])) {
                        $attributeNames = array_merge($subelementsData[$subelementName]['attrs'], $globalAttributes);
                    } else {
                        $attributeNames = $globalAttributes;
                    }
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

                <!--  begin dates -->

                <?php if ($subelementName == 'dates') :?>
                
                <?php
                    //loop through to see if an actual object is set
                    $hasDatesObject = false;
                    foreach ($vraSubElementObjects as $obj) {
                        if ($obj->name == 'dates') {
                            $hasDatesObject = true;
                            break;
                        }
                    }

                ?>
                
                    <div class='vra-subelement-container'>
                        <div class='vra-element-header' tabindex="0">
                            <div class='drawer closed'></div><label class='vra-subelement-label'><?php echo $subelementLabel; ?></label>
                        </div>
                        <fieldset class="drawer-contents" style='display:none'>
                            <input class='subelement-add'
                                   type='submit'
                                   value='Add VRA  Dates Element'
                                   data-namebase='<?php echo $nameBase; ?>'
                                   data-subelement-name='<?php echo $subelementName; ?>'
                                   data-omeka-element-name='<?php echo $omekaElement->name; ?>'
                                   data-vra-parent-id='<?php echo $parentObject->id ?>' >
                           </input>

                        <?php
                            if (empty($vraSubElementObjects) || !$hasDatesObject) {
                                echo $this->partial('dates-form.php',
                                        array('omekaElement' => $omekaElement,
                                              'record' => $record,
                                              'elementsData' => $this->elementsData,
                                              'subelementsData' => $this->subelementsData,
                                              'subelementName' => $subelementName,
                                              'agentId' => $parentObject->id,
                                              'nameBase' => $nameBase,
                                              'vraElementObjects' => $vraElementObjects,
                                                //@todo : how many of these are still actually used?
                                              'globalAttributes' => $this->globalAttrs,
                                              'attributeNames' => $attributeNames,
                                              'attributeObjects' => $attributeObjects,
                                        )
                                    );
                            }
                        ?>
                        <?php
                            foreach ($vraSubElementObjects as $subElementObject) {
                                if ($subElementObject->name != 'dates') {
                                    continue;
                                }
                                echo $this->partial('element-attribute-form.php',
                                    array(
                                         'attributeNames' => $attributeNames,
                                         'attributeObjects' => $attributeObjects,
                                         'vraElementObject' => $subElementObject,
                                         'vraElementObjects' => $vraSubElementObjects,
                                         'nameBase' => $nameBase."[{$subElementObject->id}]",
                                         'label' => __('Dates Attributes'),
                                         )
                                    );

                                echo $this->partial('dates-form.php',
                                    array('omekaElement' => $omekaElement,
                                          'record' => $record,
                                          'elementsData' => $this->elementsData,
                                          'subelementsData' => $this->subelementsData,
                                          'subelementName' => $subelementName,
                                          'nameBase' => $nameBase,
                                          'vraElementObjects' => $vraElementObjects,
                                          //'agentElementObject' => $agentElementObject,
                                          'datesElementObjects' => array($subElementObject),
                                            //@todo : how many of these are still actually used?
                                          'globalAttributes' => $this->globalAttrs,
                                          'attributeNames' => $attributeNames,
                                          'attributeObjects' => $attributeObjects,
                                    )
                                );
                            }
                        ?>
                        </fieldset>
                    </div>

                <!-- end dates -->

                <?php else: ?>
                        <div class='vra-subelement-container'>
                            <div class='vra-element-header' tabindex="0">
                                <div class='drawer closed'></div><label class='vra-subelement-label'><?php echo $subelementLabel; ?></label>
                            </div>
                            <fieldset class="drawer-contents" style='display: none'>
                                <?php
                                //ugh, this is ughly
                                //roll through all the objects to just check if there is one extant,
                                //even though I'll roll through again below to check and display
                                $hasVraElementObject = false;
                                $parentVraElementId = '';
                                foreach ($vraSubElementObjects as $vraSubElementObject) {
                                    if ($vraSubElementObject->name == $subelementName) {
                                        $hasVraElementObject = true;
                                        $vraParentVraId = $vraSubElementObject->id;
                                        break;
                                    }
                                }
                                ?>
                                <?php if ($subelementName != 'earliestDate' && $subelementName != 'latestDate'): ?>
                                    <input class='subelement-add' type='submit' value='Add Input' data-namebase='<?php echo $nameBase; ?>' data-subelement-name='<?php echo $subelementName; ?>' data-vra-parent-id='<?php echo $parentObject->id; ?>' data-omeka-element-name='<?php echo $omekaElement->name; ?>'></input>
                                <?php endif; ?>
                                <div class='vra-subelement vra-element-inputs existing'>
                                <?php foreach ($vraSubElementObjects as $vraSubElementObject): ?>
                                    <?php
                                        $content = $vraSubElementObject->content ? metadata($vraSubElementObject, 'content') : '';
                                    ?>
                                    <?php if ($vraSubElementObject->name == $subelementName): ?>

                                        <div class='vra-element-inputs'>
                                            <textarea name='<?php echo $nameBase; ?>[<?php echo $vraSubElementObject->id; ?>][content]'><?php echo $content; ?></textarea>

                                            <?php echo $this->partial('element-attribute-form.php',
                                                    array(
                                                         'attributeNames' => $attributeNames,
                                                         'attributeObjects' => $attributeObjects,
                                                         'vraElementObject' => $vraSubElementObject,
                                                         'vraElementObjects' => $vraSubElementObjects,
                                                         'nameBase' => $nameBase."[{$vraSubElementObject->id}]",
                                                         'label' => __('%s Attributes', $subelementLabel),
                                                         )
                                                    );
                                            ?>
                                        </div>
                                        <?php elseif (!$hasVraElementObject): ?>
                                        <div class='vra-element-inputs'>
                                            <textarea name='<?php echo $nameBase; ?>[<?php echo $parentObject->id; ?>][newSubelements][<?php echo $subelementName; ?>][0][content]'></textarea>

                                            <?php echo $this->partial('element-attribute-form.php',
                                                    array(
                                                         'attributeNames' => $attributeNames,
                                                         'attributeObjects' => $attributeObjects,
                                                         'vraElementObject' => $parentObject,
                                                         'vraElementObjects' => $vraSubElementObjects,
                                                         'nameBase' => $nameBase."[{$parentObject->id}][newSubelements][$subelementName][0]",
                                                         'label' => __('%s Attributes', $subelementLabel),
                                                         )
                                                    );
                                            ?>
                                        </div>
                                        <?php break; ?>
                                    <?php endif; ?>
                                <?php endforeach;?>
                                </div>
                            </fieldset>
                        </div>
                    <?php endif; ?>
            <?php endforeach; ?>
            </div>
        </fieldset>
    </div>
    <?php endforeach; ?>
</div>
