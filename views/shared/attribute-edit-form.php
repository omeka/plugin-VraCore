

<h4>Global Attributes</h4>
<div class='global-attributes'>

    <?php foreach($globalAttributes as $attr) :?>
        <?php
        //need to figure out what happens when this is an attribute on a subelement
            if(array_key_exists($attr, $attributeValues)) {
                $value = $attributeValues[$attr];
            } else {
                $value = '';
            }
        ?>
        <label><?php echo ucfirst($attr); ?></label>
        <input name='vra-attr[<?php echo $element->id; ?>][<?php echo $attr; ?>][value]' value='<?php echo $value ?>' type='text' />
        <?php if(isset($subelement)): ?>
            <?php if(is_numeric($subelement)): ?>
            <input name='vra-attr[<?php echo $element->id; ?>][<?php echo $attr; ?>][subelementId][<?php echo $subelement->id; ?>]' value='<?php echo $subelement->id; ?>' type='hidden' />
            
            <?php else: ?>
            <?php //figure this shit out?>
            <input name='vra-attr[<?php echo $element->id; ?>][<?php echo $attr; ?>][subelement][<?php echo $subelement; ?>]' value='<?php echo $subelement; ?>' type='hidden' />
            <?php endif; ?>
        <?php endif; ?>
    <?php endforeach;?>

</div>

<?php
    if(isset($subelement)) {
        if(is_string($subelement)) {
            $subelementName = $subelement;
        } else {
            $subelementName = $subelement->name;
        }
        if(isset($subelementsData[$subelementName])) {
            $additionalAttributes = array_merge($elementsData[$element->name]['attrs'], 
                                        $subelementsData[$subelementName]['attrs']
                                        );
        }
    } else {
        $additionalAttributes = $elementsData[$element->name]['attrs'];
    }
    

?>

<?php if (!empty($additionalAttributes)): ?>
    <h4>Additional Attributes</h4>
    <div>

    <?php foreach($additionalAttributes as $attr) :?>
        <?php
            if(array_key_exists($attr, $attributeValues)) {
                $value = $attributeValues[$attr];
            } else {
                $value = '';
            }
        ?>
        <label><?php echo ucfirst($attr); ?></label>
        <input name='vra-attr[<?php echo $element->id; ?>][<?php echo $attr; ?>][value]' value='<?php echo $value ?>' type='text' />
    <?php endforeach;?>
    </div>
<?php endif; ?>
