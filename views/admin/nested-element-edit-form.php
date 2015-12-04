<?php
    $nameBase = "vra-element[{$omekaElement->id}]";
?>
<div class='vra-data'>
        <?php echo $this->partial('element-attribute-form.php', 
                array(
                     'attributeNames'   => $globalAttributes,
                     'attributeObjects' => $attributeObjects,
                     'nameBase'         => $nameBase . "[display]",
                     'label'            => __('Display Element Attributes'),
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
                                     )
                                );
                        ?>
            </div>
        </fieldset>
    </div>

    <div class='vra-element'>
        <div class='vra-element-header'>
            <div class='drawer closed'></div><label class='vra-elements-label'><?php echo $omekaElement->name . ' ' . __('Subelements'); ?></label>
        </div>
        <fieldset style='display: none;'>
            <input type='hidden' name='<?php echo $nameBase; ?>[newElements][0][name]' value='<?php echo $omekaElement->name; ?>'></input>
            <input type='hidden' name='<?php echo $nameBase; ?>[newElements][0][hasSubelements]' value='1'></input>
            <div class='vra-subelements'>
            <?php foreach($elementsData[$omekaElement->name]['subelements'] as $subelementName): ?>
                <div class='vra-subelement-container'>
                    <div class='vra-element-header'>
                        <div class='drawer closed'></div><label class='vra-subelement-label'><?php echo $subelementName; ?></label>
                    </div>
                    <fieldset style='display: none'>
                        <input type='submit' value='Add Input'></input>
                        <div class='vra-subelement vra-element-inputs new'>
                            <textarea name='<?php echo $nameBase; ?>[newElements][0][newSubelements][<?php echo $subelementName; ?>][0][content]' value=''></textarea>
                                <?php echo $this->partial('element-attribute-form.php', 
                                        array(
                                             'attributeNames'   => $attributeNames,
                                             'attributeObjects' => $attributeObjects,
                                             //kind of a cheat. put true at the front to produce a new set of attributes for new element
                                             'vraElementObjects' => array(true),
                                             'nameBase'         => $nameBase . "[newElements][0][newSubelements][$subelementName][0]",
                                             'label'            => __('Attributes')
                                             )
                                        );
                                ?>
                        </div>

                        <!-- insert the existing data: content and attributes -->
                        <div class='vra-subelement vra-element-inputs existing'>
                        <?php foreach($vraElementObjects as $vraElementObject): ?>
                            <?php if($vraElementObject->name == $subelementName): ?>
                                <div class='vra-element-inputs'>
                                    <textarea name='<?php echo $nameBase; ?>[<?php echo $vraElementObject->id; ?>][content]' value='<?php echo $vraElementObject->content; ?>'><?php echo $vraElementObject->content; ?></textarea>
                                    <?php echo $this->partial('element-attribute-form.php', 
                                            array(
                                                 'attributeNames'   => $attributeNames,
                                                 'attributeObjects' => $attributeObjects,
                                                 'vraElementObject' => $vraElementObject,
                                                 'vraElementObjects' => $vraElementObjects,
                                                 'nameBase'         => $nameBase . "[{$vraElementObject->id}]",
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
            <?php endforeach; ?>
            </div>
        </fieldset>
    </div>
</div>
