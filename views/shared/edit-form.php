
<div class='vra-data'>
    <h4>Global Attributes</h4>
    <div class='global-attributes'>
        <?php foreach($globalAttributes as $attr) :?>
            <?php
                if(array_key_exists($attr, $attributeValues)) {
                    $value = $attributeValues[$attr];
                } else {
                    $value = '';
                }
            ?>
            <label><?php echo ucfirst($attr); ?></label>
            <input name='vra-attr[<?php echo $element->id; ?>][<?php echo $attr; ?>]' value='<?php echo $value ?>' type='text' />
        <?php endforeach;?>

    </div>
    <?php if (!empty($elementsData[$element->name]['attrs'])): ?>
        <h4>Additional Attributes</h4>
        <div>
        <?php foreach($elementsData[$element->name]['attrs'] as $attr) :?>
            <?php
                if(array_key_exists($attr, $attributeValues)) {
                    $value = $attributeValues[$attr];
                } else {
                    $value = '';
                }
            ?>
            <label><?php echo ucfirst($attr); ?></label>
            <input name='vra-attr[<?php echo $element->id; ?>]["<?php echo $attr; ?>"]' type='text' />
        <?php endforeach;?>
        </div>
    <?php endif; ?>
</div>