<?php if(! get_option('vra-core-ignore-attributes')): ?>
<?php if (! isset($topLevel)) {
    $topLevel = false;
}
?>
<div class='vra-attributes'>
    <div class='vra-attributes-header'>
        <div class='drawer closed'></div><label class='vra-attributes-label'><?php echo $label; ?></label>
    </div>
    
    <fieldset class='vra-attributes' style='display: none;'>
        <?php foreach($attributeNames as $attributeName): ?>
            <div class='vra-attribute'>
                <label><?php echo $attributeName; ?></label>
                <?php if ($attributeName == 'circa') {
                        $inputType = 'checkbox';
                      } else {
                          $inputType = 'text';
                      }
                ?>
                <?php if (isset($vraElementObject)): ?>
                        <?php if (is_object($vraElementObject) && isset($attributeObjects[$vraElementObject->id][$attributeName])):?>
                            <?php $attributeObject = $attributeObjects[$vraElementObject->id][$attributeName]; ?>
                            <input name='<?php echo $nameBase; ?>[attrs][<?php echo $attributeObject->id; ?>][<?php echo $attributeName; ?>]' type='<?php echo $inputType?>' value='<?php echo $attributeObject->content; ?>' ></input>
                        <?php else: ?>
                            <input name='<?php echo $nameBase; ?>[attrs][new][<?php echo $attributeName; ?>]' type='<?php echo $inputType?>' ></input>
                        <?php endif; ?>
                <?php else: ?>
        
                    <?php if ($topLevel && isset($attributeObjects[$topLevel][$attributeName])) : ?>
                        <?php $attributeObject = $attributeObjects[$topLevel][$attributeName]; ?>
                        <input name='<?php echo $nameBase; ?>[attrs][<?php echo $attributeObject->id; ?>][<?php echo $attributeName; ?>]' type='<?php echo $inputType?>' value='<?php echo $attributeObject->content; ?>' ></input>
                    <?php else: ?>
                        <input name='<?php echo $nameBase; ?>[attrs][new][<?php echo $attributeName; ?>]' type='<?php echo $inputType?>' ></input>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </fieldset>
</div>
<?php endif; ?>
