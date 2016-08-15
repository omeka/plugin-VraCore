<div class='element vra-element'>
    <h5 class='vra-core-element-name'><?php echo $elementName; ?></h5>
    <?php if ($elementContent = metadata($element, 'content')): ?>
    <div class='element-text vra-element-text'><?php echo metadata($element, 'content'); ?></div>
    <?php endif; ?>
    <?php $elementAttributes = $element->getAttributes(); ?>
    <?php
    echo $this->partial('vra-core-attributes.php', array(
            'showAttributes' => $showAttributes,
            'attributes' => $elementAttributes,
         )
    );
    ?>
    <?php if ($subelements): ?>
        <?php foreach ($subelements as $subelement): ?>
            <?php if (metadata($subelement, 'name') == 'dates'): ?>
                <?php $datesSubelements = $subelement->getSubelements(); ?>
            <?php else: ?>
                <?php $datesSubelements = false; ?>
            <?php endif; ?>
            <?php
            echo $this->partial('vra-core-element.php', array(
                    'element' => $subelement,
                    'elementName' => metadata($subelement, 'name'),
                    'subelements' => $datesSubelements,
                    'showAttributes' => $showAttributes,
                 )
            );
            ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
