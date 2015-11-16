<p>Attributes</p>
<fieldset class='vra-attributes'>
    <?php foreach($attributeNames as $attributeName): ?>
        <label><?php echo $attributeName; ?></label>
        
        
        <?php if (isset($vraElementObjects)): ?>
            <?php foreach($vraElementObjects as $vraElement): ?>
                <?php if (is_object($vraElement) && isset($attributeObjects[$vraElement->id][$attributeName])):?>
                    <?php $attributeObject = $attributeObjects[$attributeName]; ?>
                    <input name='<?php echo $nameBase; ?>[attrs][<?php echo $attributeObject->id; ?>][<?php echo $attributeName; ?>]' type='text' value='<?php echo $attributeObject->content; ?>' ></input>
                <?php else: ?>
                    <input name='<?php echo $nameBase; ?>[attrs][new][<?php echo $attributeName; ?>]' type='text' ></input>
                <?php endif; ?>
            <?php endforeach;?>
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