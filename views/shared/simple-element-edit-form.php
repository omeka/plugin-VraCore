<?php
    $nameBase = "vra-element[{$omekaElement->id}]";
    $ignoreAttributes =  get_option('vra-core-ignore-attributes');
?>
<div class='vra-data'>
<!-- Display Element Attributes -->
        <?php echo $this->partial('element-attribute-form.php', 
                array(
                     'attributeNames'   => $globalAttributes,
                     'attributeObjects' => $attributeObjects,
                     'nameBase'         => $nameBase . "[display]",
                     'label'            => _('Display Element Attributes'),
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
            <div class='drawer closed'></div><label class='vra-elements-label'><?php echo $omekaElement->name; ?> Elements</label>
        </div>
        <fieldset style='display: none'>
            <input type='submit' value='Add Input'></input>
            <div class='vra-element-inputs'>
                <textarea name='<?php echo $nameBase; ?>[newElements][0][content]' value=''></textarea>
                <input type='hidden' name='<?php echo $nameBase; ?>[newElements][0][name]' value='<?php echo $omekaElement->name; ?>'></input>
                    <?php echo $this->partial('element-attribute-form.php', 
                            array(
                                 'attributeNames'   => $attributeNames,
                                 'attributeObjects' => $attributeObjects,
                                 //kind of a cheat. put true at the front to produce a new set of attributes for new element
                                 'vraElementObjects' => array(true),
                                 'nameBase'         => $nameBase . "[newElements][0]",
                                 'label'            => __('Attributes')
                                 )
                            );
                    ?>
            </div>

            <?php foreach($vraElementObjects as $vraElementObject): ?>
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
            <?php endforeach;?>
        </fieldset>
    </div>
</div>
