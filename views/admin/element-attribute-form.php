<?php if (!get_option('vra-core-ignore-attributes')): ?>
<?php if (!isset($topLevel)) {
    $topLevel = false;
}
?>
<div class='vra-attributes vra-drawer' aria-labelledby='<?php echo $nameBase; ?>-group-label' role="group">
    <div class='vra-attributes-header drawer'>
        <label id="<?php echo $nameBase; ?>-group-label" class='vra-attributes-label drawer-name'><?php echo $label; ?></label>
        <button type="button" aria-expanded="false" aria-label="<?php echo __('Show'); ?>" aria-controls="<?php echo $nameBase; ?>-drawer" class="drawer-toggle" data-action-selector="opened" title="<?php echo __('Show'); ?>"><span class="icon"></span></button>
    </div>
    <div class='vra-attributes drawer-contents' id="<?php echo $nameBase; ?>-drawer">
        <?php foreach ($attributeNames as $attributeName): ?>
            <div class='vra-attribute'>
                <label>
                <?php echo $attributeName; ?>
                <?php $inputType = ($attributeName == 'circa') ? 'checkbox' : 'text'; ?>
                <?php if (isset($vraElementObject)): ?>
                    <?php if (is_object($vraElementObject) && isset($attributeObjects[$vraElementObject->id][$attributeName])):?>
                        <?php 
                            $attributeObject = $attributeObjects[$vraElementObject->id][$attributeName]; 
                            $attributeObjectId = $attributeObject->id;
                        ?>
                        <?php if ($inputType == 'checkbox'): ?>
                        <input name='<?php echo $nameBase; ?>[attrs][<?php echo $attributeObjectId; ?>][<?php echo $attributeName; ?>]' type='hidden' value='delete'></input>
                        <input name='<?php echo $nameBase; ?>[attrs][<?php echo $attributeObjectId; ?>][<?php echo $attributeName; ?>]' type='<?php echo $inputType?>' checked='checked'></input>
                        <?php else: ?>
                        <input name='<?php echo $nameBase; ?>[attrs][<?php echo $attributeObjectId; ?>][<?php echo $attributeName; ?>]' type='<?php echo $inputType?>' value='<?php echo metadata($attributeObject, 'content'); ?>'></input>
                        <?php endif; ?>
                    <?php else: ?>
                        <input class='vra-new' name='<?php echo $nameBase; ?>[attrs][new][<?php echo $attributeName; ?>]' type='<?php echo $inputType?>' ></input>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if ($topLevel && isset($attributeObjects[$topLevel][$attributeName])) : ?>
                        <?php $attributeObject = $attributeObjects[$topLevel][$attributeName]; ?>
                        <?php if ($inputType == 'checkbox'): ?>
                        <input name='<?php echo $nameBase; ?>[attrs][<?php echo $attributeObject->id; ?>][<?php echo $attributeName; ?>]' type='hidden' value='delete' ></input>
                        <input name='<?php echo $nameBase; ?>[attrs][<?php echo $attributeObject->id; ?>][<?php echo $attributeName; ?>]' type='<?php echo $inputType?>' checked='checked' ></input>
                        <?php else: ?>
                        <input name='<?php echo $nameBase; ?>[attrs][<?php echo $attributeObject->id; ?>][<?php echo $attributeName; ?>]' type='<?php echo $inputType?>' value='<?php echo metadata($attributeObject, 'content'); ?>'></input>
                        <?php endif; ?>
                    <?php else: ?>
                        <input class='vra-new' name='<?php echo $nameBase; ?>[attrs][new][<?php echo $attributeName; ?>]' type='<?php echo $inputType?>' ></input>
                    <?php endif; ?>
                <?php endif; ?>
                </label>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
