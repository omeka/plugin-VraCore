<?php
$ignoreAttributes =  get_option('vra-core-ignore-attributes');
?>
<?php if(! $ignoreAttributes): ?>
<div class='vra-attributes'>
    <div class='vra-attributes-header'>
        <div class='drawer closed'></div><label class='vra-attributes-label'><?php echo $label; ?></label>
    </div>
    
    <fieldset class='vra-attributes' style='display: none;'>
        <?php foreach($attributeNames as $attributeName): ?>
            <label><?php echo $attributeName; ?></label>
    
            <?php if (isset($vraElementObject)): ?>
                    <?php if (is_object($vraElementObject) && isset($attributeObjects[$vraElementObject->id][$attributeName])):?>
                        <?php $attributeObject = $attributeObjects[$vraElementObject->id][$attributeName]; ?>
                        <input name='<?php echo $nameBase; ?>[attrs][<?php echo $attributeObject->id; ?>][<?php echo $attributeName; ?>]' type='text' value='<?php echo $attributeObject->content; ?>' ></input>
                    <?php else: ?>
                        <input name='<?php echo $nameBase; ?>[attrs][new][<?php echo $attributeName; ?>]' type='text' ></input>
                    <?php endif; ?>
            <?php else: ?>
    
                <?php if (isset($attributeObjects['display'][$attributeName])): ?>
                    <?php $attributeObject = $attributeObjects['display'][$attributeName]; ?>
                    <input name='<?php echo $nameBase; ?>[attrs][<?php echo $attributeObject->id; ?>][<?php echo $attributeName; ?>]' type='text' value='<?php echo $attributeObject->content; ?>' ></input>
                <?php else: ?>
                    <input name='<?php echo $nameBase; ?>[attrs][new][<?php echo $attributeName; ?>]' type='text' ></input>
                <?php endif; ?>
            <?php endif; ?>
    
        <?php endforeach; ?>
    </fieldset>
</div>
<?php endif; ?>
