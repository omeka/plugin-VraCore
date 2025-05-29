<?php
    $nameBase = "vra-element[{$omekaElement->id}]";
?>

<div class='vra-data'>
<!-- Display Element Attributes -->
        <?php  echo $this->partial('element-attribute-form.php',
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
    <div class='vra-element vra-drawer'>
        <div class='vra-element-header drawer'>
            <label class='vra-notes-element-label drawer-name'><?php echo __('Notes'); ?></label>
            <button type="button" aria-expanded="false" aria-label="<?php echo __('Show'); ?>" class="drawer-toggle" data-action-selector="opened" title="<?php echo __('Show'); ?>"><span class="icon"></span></button>
        </div>
        <fieldset class="drawer-contents">
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

    <div class='vra-element vra-drawer'>
        <div class='vra-element-header drawer'>
            <label class='vra-elements-label drawer-name'><?php echo $omekaElement->name; ?> Elements</label>
            <button type="button" aria-expanded="false" aria-label="<?php echo __('Show'); ?>" class="drawer-toggle" data-action-selector="opened" title="<?php echo __('Show'); ?>"><span class="icon"></span></button>
        </div>
        <fieldset class="drawer-contents">
            <input class='element-add' type='submit' value='Add Input' data-namebase='<?php echo $nameBase; ?>' data-omeka-element-name='<?php echo $omekaElement->name; ?>'></input>
            <?php if (empty($vraElementObjects)): ?>
            <div class='vra-element-inputs new'>
                <textarea class='vra-new' name='<?php echo $nameBase; ?>[newElements][0][content]'></textarea>
                <input type='hidden' class='vra-new' name='<?php echo $nameBase; ?>[newElements][0][name]' value='<?php echo $omekaElement->name; ?>'></input>
                    <?php echo $this->partial('element-attribute-form.php',
                            array(
                                 'attributeNames' => $attributeNames,
                                 'attributeObjects' => $attributeObjects,
                                 //kind of a cheat. put true at the front to produce a new set of attributes for new element
                                 'vraElementObjects' => array(true),
                                 'nameBase' => $nameBase.'[newElements][0]',
                                 'label' => __('Attributes'),
                                 )
                            );
                    ?>
            </div>
            <?php endif; ?>
            <?php foreach ($vraElementObjects as $vraElementObject): ?>
                    <div class='vra-element-inputs'>
                        <textarea name='<?php echo $nameBase; ?>[<?php echo $vraElementObject->id; ?>][content]'><?php echo metadata($vraElementObject, 'content'); ?></textarea>
                            <?php echo $this->partial('element-attribute-form.php',
                                    array(
                                         'attributeNames' => $attributeNames,
                                         'attributeObjects' => $attributeObjects,
                                         'vraElementObject' => $vraElementObject,
                                         'vraElementObjects' => $vraElementObjects,
                                         'nameBase' => $nameBase."[{$vraElementObject->id}]",
                                         'label' => __('Attributes'),
                                         )
                                    );
                            ?>
                    </div>
            <?php endforeach;?>
        </fieldset>
    </div>
</div>
