<?php
    $nameBase = "vra-element[{$omekaElement->id}]";
    
    if(! isset($newAgentCount)) {
        $newAgentCount = 0;
    }
?>

<div class='vra-data'>
        <?php echo $this->partial('element-attribute-form.php', 
                array(
                     'attributeNames'   => $globalAttributes,
                     'attributeObjects' => $attributeObjects,
                     'nameBase'         => $nameBase . "[display]",
                     'label'            => __('Display Element Attributes'),
                     'topLevel'         => 'display',
                     )
                );
        ?>

    <?php $notes = $notesObject ? $notesObject->content : ''; ?>
    <div class='vra-element'>
        <div class='vra-element-header'>
            <div class='drawer closed'></div><label class='vra-notes-element-label'><?php echo __('Notes'); ?></label>
        </div>
        <fieldset style='display: none'>
            <div class='vra-element-inputs'>
                <textarea name='<?php echo $nameBase; ?>[notes][content]' value='<?php echo $notes; ?>'><?php echo $notes; ?></textarea>
                        <?php echo $this->partial('element-attribute-form.php', 
                                array(
                                     'attributeNames'   => $globalAttributes,
                                     'attributeObjects' => $attributeObjects,
                                     'nameBase'         => $nameBase . "[notes]",
                                     'label'            => __('Notes Attributes'),
                                     'topLevel'         => 'notes',
                                     )
                                );
                        ?>
            </div>
        </fieldset>
    </div>

    <input class='parent-element-add' type='submit' value='Add <?php echo $omekaElement->name; ?> element' data-namebase='<?php echo $nameBase; ?>' data-element-name='<?php echo $omekaElement->name; ?>' data-omeka-element-name='<?php echo $omekaElement->name; ?>'></input>

<?php if(count($vraElementObjects) == 0 ): ?>
    <!-- copy of parent-element.php -->

<?php
//setup adjusted from parent-element.php 
if (! isset($parentVraElementId)) {
    $parentVraElementId = '';
}
$newElementCount = 0;
$omekaElementName = $omekaElement->name;
?>
<div class='vra-element new'>
    <div class='vra-element-header'>
        <div class='drawer closed'></div><label class='vra-elements-label'><?php echo $omekaElementName . ' ' . __('Subelements'); ?></label>
    </div>
    <fieldset style='display: none;'>
    
        <?php echo $this->partial('element-attribute-form.php', 
                array(
                     'attributeNames'   => $attributeNames,
                     'attributeObjects' => $attributeObjects,
                     //kind of a cheat. put true at the front to produce a new set of attributes for new element
                     'vraElementObjects' => array(true),
                     'nameBase'         => $nameBase . "[newElements][0]",
                     'label'            => __('Attributes'),
                     )
                );
        ?>
        <input type='hidden' class='vra-new' name='<?php echo $nameBase; ?>[newElements][<?php echo $newElementCount; ?>][name]' value='<?php echo $omekaElementName; ?>'></input>
        <input type='hidden' class='vra-new' name='<?php echo $nameBase; ?>[newElements][<?php echo $newElementCount; ?>][hasSubelements]' value='1'></input>
        

        <div class='vra-subelements'>


        <?php foreach($elementsData[$omekaElementName]['subelements'] as $subelementName): ?>
            <?php if($subelementName == 'dates'): ?>
            <div class='vra-subelement-container'>
                <div class='vra-element-header'>
                    <div class='drawer closed'></div><label class='vra-subelement-label'><?php echo $subelementName; ?></label>
                </div>
                <fieldset style='display:none'>
                    <input class='subelement-add' type='submit' value='Add Dates Element' data-newAgentIndex='<?php echo $newAgentCount; ?>' data-namebase='<?php echo $nameBase; ?>' data-subelement-name='<?php echo $subelementName; ?>' data-omeka-element-name='<?php echo $omekaElement->name; ?>'></input>
                    <?php echo $this->partial('element-attribute-form.php', 
                            array(
                                 'attributeNames'   => $attributeNames,
                                 //kind of a cheat. put true at the front to produce a new set of attributes for new element
                                 'vraElementObjects' => array(true),
                                 'nameBase'         => $nameBase . "[newElements][$newAgentCount][newSubelements][$subelementName][0]",
                                 'label'            => __('Attributes')
                                 )
                            );
                    ?>
                <?php
                        echo $this->partial('dates-form.php',
                            array(
                                  'elementsData'     => $this->elementsData,
                                  'subelementsData'  => $this->subelementsData,
                                  'subelementName'   => $subelementName,
                                  'nameBase'         => $nameBase,
                                  'newAgentCount'    => $newAgentCount,
                                  'newElementCount'  => 0,
                                    //@todo : how many of these are still actually used?
                                  'globalAttributes' => $this->globalAttrs,
                                  'attributeNames'    => $attributeNames,
                                  'attributeObjects' => array()
                            )
                        );
                ?>
                </fieldset>
            </div>
            <?php else: ?>
            <div class='vra-subelement-container'>
                <div class='vra-element-header'>
                    <div class='drawer closed'></div><label class='vra-subelement-label'><?php echo $subelementName; ?></label>
                </div>
                <fieldset style='display: none'>
                    <?php if($subelementName != 'earliestDate' && $subelementName != 'latestDate'): ?>
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
                        <textarea class='vra-new' name='<?php echo $nameBase; ?>[newElements][<?php echo $newElementCount; ?>][newSubelements][<?php echo $subelementName; ?>][0][content]' value=''></textarea>
                        <input type='hidden' name='<?php echo $nameBase; ?>[newElements][<?php echo $newElementCount; ?>][newSubelements][<?php echo $subelementName; ?>][0][parent_id]' value='<?php echo $parentVraElementId; ?>'></input>
                            <?php echo $this->partial('element-attribute-form.php', 
                                    array(
                                         'attributeNames'   => $attributeNames,
                                         'attributeObjects' => array(),
                                         //kind of a cheat. put true at the front to produce a new set of attributes for new element
                                         'vraElementObjects' => array(true),
                                         'nameBase'         => $nameBase . "[newElements][$newElementCount][newSubelements][$subelementName][0]",
                                         'label'            => __('Attributes')
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
    
    
    
    <?php foreach($vraElementObjects as $parentObject): ?>
    <?php 
        $vraSubElementObjects = $parentObject->getSubelements();
    ?>
    
    
    <div class='vra-element'>
    
    
        
    
        <div class='vra-element-header'>
            <div class='drawer closed'></div><label class='vra-elements-label'><?php echo $omekaElement->name . ' ' . __('Subelements'); ?></label>
        </div>
        <fieldset style='display: none;'>
        
        
        
        
        
        <?php echo $this->partial('element-attribute-form.php', 
                array(
                     'attributeNames'   => $attributeNames,
                     'attributeObjects' => $attributeObjects,
                     //kind of a cheat. put true at the front to produce a new set of attributes for new element
                     'vraElementObjects' => array(true),
                     'vraElementObject' => $parentObject, 
                     'nameBase'         => $nameBase . "[{$parentObject->id}]",
                     'label'            => __('Attributes'),
                     )
                );
        ?>
        
        
        
        
        
        
            <input type='hidden' name='<?php echo $nameBase; ?>[newElements][0][name]' value='<?php echo $omekaElement->name; ?>'></input>
            <input type='hidden' name='<?php echo $nameBase; ?>[newElements][0][hasSubelements]' value='1'></input>
            <div class='vra-subelements'>
            <?php foreach($elementsData[$omekaElement->name]['subelements'] as $subelementName): ?>
            <?php
                if (isset($subelementsData[$subelementName])) {
                    $attributeNames = array_merge($subelementsData[$subelementName]['attrs'], $globalAttributes);
                } else {
                    $attributeNames = $globalAttributes;
                }
            ?>
            
            
            
            
            
            
            <?php if($subelementName == 'dates') :?>
            <div class='vra-subelement-container'>
                <div class='vra-element-header'>
                    <div class='drawer closed'></div><label class='vra-subelement-label'><?php echo $subelementName; ?></label>
                </div>
                <fieldset style='display:none'>
                    <input class='subelement-add' type='submit' value='Add Dates Element' data-namebase='<?php echo $nameBase; ?>' data-subelement-name='<?php echo $subelementName; ?>' data-omeka-element-name='<?php echo $omekaElement->name; ?>'></input>
                    <?php echo $this->partial('element-attribute-form.php', 
                            array(
                                 'attributeNames'   => $attributeNames,
                                 'attributeObjects' => $attributeObjects,
                                 //kind of a cheat. put true at the front to produce a new set of attributes for new element
                                 'vraElementObjects' => array(true),
                                 'nameBase'         => $nameBase . "[newElements][0][newSubelements][$subelementName][0]",
                                 'label'            => __('Dates Element Attributes')
                                 )
                            );
                    ?>
                <?php 
                    if (empty($vraSubElementObjects)) {
                            echo $this->partial('dates-form.php',
                                array('omekaElement'     => $omekaElement,
                                      'record'           => $record,
                                      'elementsData'     => $this->elementsData,
                                      'subelementsData'  => $this->subelementsData,
                                      'subelementName'   => $subelementName,
                                      'nameBase'         => $nameBase,
                                      'vraElementObjects' => $vraElementObjects,
                                        //@todo : how many of these are still actually used?
                                      'globalAttributes' => $this->globalAttrs,
                                      'attributeNames'    => $attributeNames,
                                      'attributeObjects'  => $attributeObjects
                                )
                            );
                    }
                ?>
                <?php
                    foreach($vraSubElementObjects as $subElementObject ) {
                        if ($subElementObject->name != 'dates') {
                            continue;
                        }
                        echo $this->partial('dates-form.php',
                            array('omekaElement'     => $omekaElement,
                                  'record'           => $record,
                                  'elementsData'     => $this->elementsData,
                                  'subelementsData'  => $this->subelementsData,
                                  'subelementName'   => $subelementName,
                                  'nameBase'         => $nameBase,
                                  'vraElementObjects' => $vraElementObjects,
                                  //'agentElementObject' => $agentElementObject,
                                  'datesElementObjects' => array($subElementObject),
                                    //@todo : how many of these are still actually used?
                                  'globalAttributes' => $this->globalAttrs,
                                  'attributeNames'    => $attributeNames,
                                  'attributeObjects'  => $attributeObjects
                            )
                        );
                    }
                ?>
                </fieldset>
            </div>
            
            
            
            
            
            
            
            
            
            
            
            <?php else: ?>
                <div class='vra-subelement-container'>
                    <div class='vra-element-header'>
                        <div class='drawer closed'></div><label class='vra-subelement-label'><?php echo $subelementName; ?></label>
                    </div>
                    <fieldset style='display: none'>
                        <?php 
                        //ugh, this is ughly
                        //roll through all the objects to just check if there is one extant,
                        //even though I'll roll through again below to check and display
                        $hasVraElementObject = false;
                        $parentVraElementId = '';
                        foreach($vraSubElementObjects as $vraSubElementObject) {
                            if($vraSubElementObject->name == $subelementName) {
                                $hasVraElementObject = true;
                                $vraParentVraId = $vraSubElementObject->id;
                                break;
                            }
                        }
                        ?>
                        <?php if($subelementName != 'earliestDate' && $subelementName != 'latestDate'): ?>

                            <input class='subelement-add' type='submit' value='Add Input' data-namebase='<?php echo $nameBase; ?>' data-subelement-name='<?php echo $subelementName; ?>' data-vra-parent-id='<?php echo $parentObject->id; ?>' data-omeka-element-name='<?php echo $omekaElement->name; ?>'></input>
                        <?php endif; ?>
                        
                        <!-- insert the existing data: content and attributes -->
                        <div class='vra-subelement vra-element-inputs existing'>
                        <?php foreach($vraSubElementObjects as $vraSubElementObject): ?>
                            <?php if($vraSubElementObject->name == $subelementName): ?>
                                <div class='vra-element-inputs'>
                                    <textarea name='<?php echo $nameBase; ?>[<?php echo $vraSubElementObject->id; ?>][content]' value='<?php echo $vraSubElementObject->content; ?>'><?php echo $vraSubElementObject->content; ?></textarea>
                                    
                                    <?php echo $this->partial('element-attribute-form.php', 
                                            array(
                                                 'attributeNames'   => $attributeNames,
                                                 'attributeObjects' => $attributeObjects,
                                                 'vraElementObject' => $vraSubElementObject,
                                                 'vraElementObjects' => $vraSubElementObjects,
                                                 'nameBase'         => $nameBase . "[{$vraSubElementObject->id}]",
                                                 'label'            => __('Attributes')
                                                 )
                                            );
                                    ?>
                                </div>
                                
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
