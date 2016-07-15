<?php
//check if public side, and if attributes should be shown
if (is_admin_theme()) {
    $showAttributes = true;
} elseif (get_option('vra-core-hide-public-attributes' )) {
    $showAttributes = false;
} else {
    $showAttributes = true;
}

?>

<?php if($showAttributes): ?>
<ul class='vra-core-attributes'>
<?php foreach($attributes as $attribute): ?>
    <li><span class='vra-core-attribute-name'>@<?php echo metadata($attribute, 'name'); ?></span>
        <?php echo metadata($attribute, 'content'); ?>
    </li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if(isset($groupedElements['notes'])): ?>
    <?php $notesElement = $groupedElements['notes'][0]; ?>
    <div class='element vra-element'>
        <h5 class='vra-core-element-name'><?php echo __('Notes'); ?></h5>
        <div class='element-text vra-element-text'><?php echo metadata($notesElement, 'content'); ?></div>
        <?php if ($showAttributes): ?>
            <ul class='vra-core-attributes'>
            <?php foreach($notesElement->getAttributes() as $attribute): ?>
                <li><span class='vra-core-attribute-name'>@<?php echo metadata($attribute, 'name'); ?></span>
                <?php echo metadata($attribute, 'content'); ?>
                </li>
            <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <?php unset($groupedElements['notes']); ?>
    </div>
<?php endif;?>

<?php if (count($groupedElements) != 0): ?>
    <h4>Elements</h4>
    <?php foreach($groupedElements as $name => $elements): ?>
        <?php foreach($elements as $element): ?>
            <?php $subelements = $element->getSubelements(); ?>
            <?php if (empty($subelements)): ?>
                <div class='element vra-element'>
                    <h5 class='vra-core-element-name'><?php echo $name; ?></h5>
                    <div class='element-text vra-element-text'>
                    <?php echo metadata($element, 'content'); ?>
                    </div>
                    <?php if ($showAttributes): ?>
                    <ul class='vra-core-attributes'>
                    <?php foreach($element->getAttributes() as $attribute): ?>
                        <li><span class='vra-core-attribute-name'>@<?php echo metadata($attribute, 'name'); ?></span>
                        <?php echo metadata($attribute, 'content'); ?>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <?php if(array_key_exists('name', $subelements)): ?>
                    <?php $nameElement = $subelements['name']; ?>
                    <?php $nameData = $nameElement->content; ?>
                <?php else: ?>
                    <?php $nameData = ''; ?>
                <?php endif; ?>

                <div class='element vra-element'>
                    <h4><?php echo $name; ?></h4>
                    <div class='element-text vra-element-text'><?php echo $nameData; ?></div>
                    <?php if ($showAttributes): ?>
                        <ul class='vra-core-attributes'>
                        <?php foreach($element->getAttributes() as $attribute): ?>
                            <li>
                                <span class='vra-core-attribute-name'>@<?php echo metadata($attribute, 'name'); ?></span>
                                <?php echo metadata($attribute, 'content'); ?>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <?php foreach($subelements as $subelement): ?>
                        <div class='element vra-element'>
                            <h5 class='vra-core-element-name'> <?php echo metadata($subelement, 'name'); ?> </h5>
                            <div class='element-text vra-element-text'><?php echo metadata($subelement, 'content'); ?></div>

                            <?php if ($showAttributes): ?>
                            <ul class='vra-core-attributes'>
                            <?php foreach($subelement->getAttributes() as $attribute): ?>
                                <li><span class='vra-core-attribute-name'>@<?php echo metadata($attribute, 'name'); ?></span>
                                <?php echo metadata($attribute, 'content'); ?>
                                </li>
                            <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>

                            <?php if(metadata($subelement, 'name') == 'dates'): ?>
                                <?php $datesSubelements = $subelement->getSubelements(); ?>
                                <?php foreach($datesSubelements as $datesSubelement): ?>
                                    <div class='element vra-element dates-subelements'>
                                    <h5 class='vra-core-element-name'> <?php echo metadata($datesSubelement, 'name'); ?> </h5>
                                    <div class='element-text vra-element-text'><?php echo metadata($datesSubelement, 'content'); ?></div>
                                    <?php if ($showAttributes): ?>
                                        <ul class='vra-core-attributes'>
                                            <?php foreach($datesSubelement->getAttributes() as $datesSubAttribute): ?>
                                                <li><span class='vra-core-attribute-name'>@<?php echo metadata($datesSubAttribute, 'name'); ?></span>
                                                <?php echo metadata($datesSubAttribute, 'content'); ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif;?>
        <?php endforeach; ?>
    <?php endforeach;?>
<?php endif; ?>
