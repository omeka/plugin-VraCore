<p>Attributes</p>
<fieldset class='vra-attributes'>
    <?php foreach($attributeNames as $attributeName): ?>
        <label><?php echo $attributeName; ?></label>
        <?php if (array_key_exists('display', $attributeObjects)): ?>
            <?php if (isset($attributeObjects['display'][$attributeName])): ?>
                <?php $attributeObject = $attributeObjects['display'][$attributeName]; ?>
                <input name='<?php echo $nameBase; ?>[new][][data][attrs][<?php echo $attributeObject->id; ?>][<?php echo $attributeName; ?>]' type='text' value='<?php echo $attributeObject->content; ?>' ></input>
            <?php else: ?>
                <input name='<?php echo $nameBase; ?>[new][][data][attrs][new][][<?php echo $attributeName; ?>]' type='text' ></input>
            <?php endif; ?>
        <?php else: ?>
        
            <?php if (isset($vraElementObject) && isset($attributeObjects[$vraElementObject->id][$attributeName])): ?>
                <?php $attributeObject = $attributeObjects[$attributeName]; ?>
                <input name='<?php echo $nameBase; ?>[new][][data][attrs][<?php echo $attributeObject->id; ?>][<?php echo $attributeName; ?>]' type='text' value='<?php echo $attributeObject->content; ?>' ></input>
            <?php else: ?>
                <input name='<?php echo $nameBase; ?>[new][][data][attrs][new][][<?php echo $attributeName; ?>]' type='text' ></input>
            <?php endif; ?>
        <?php endif; ?>
    <?php endforeach; ?>    
</fieldset>