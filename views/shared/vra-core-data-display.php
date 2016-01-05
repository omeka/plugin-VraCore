<ul class='vra-core-attributes'>
<?php foreach($attributes as $attribute): ?>
    <li><span class='vra-core-attribute-name'>@<?php echo metadata($attribute, 'name'); ?></span>;
        <?php echo metadata($attribute, 'content'); ?>
    </li>
<?php endforeach; ?>
</ul>



<?php if (count($groupedElements) != 0): ?>
    <h4>Elements</h4>
    <?php foreach($groupedElements as $name => $elements): ?>

        <?php foreach($elements as $element): ?>
            <?php $subelements = $element->getSubelements(); ?>
            <?php if (empty($subelements)): ?>
                <h5 class='vra-core-element-name'><?php echo $name; ?></h5>
                
                <?php echo metadata($element, 'content'); ?>
                <ul class='vra-core-attributes'>
                <?php foreach($element->getAttributes() as $attribute): ?>
                    <li><span class='vra-core-attribute-name'>@<?php echo metadata($attribute, 'name'); ?></span>
                    <?php echo metadata($attribute, 'content'); ?>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <?php foreach($subelements as $subelement): ?>
                    <h5 class='vra-core-element-name'> <?php echo metadata($subelement, 'name'); ?> </h5>
                    <?php echo metadata($subelement, 'content'); ?>
                    <ul class='vra-core-attributes'>
                    <?php foreach($subelement->getAttributes() as $attribute): ?>
                        <li><span class='vra-core-attribute-name'>@<?php echo metadata($attribute, 'name'); ?></span>
                        <?php echo metadata($attribute, 'content'); ?>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                
                <?php endforeach; ?>
            <?php endif;?>
        <?php endforeach; ?>
    <?php endforeach;?>
<?php endif; ?>