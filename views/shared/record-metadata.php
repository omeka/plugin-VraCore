<?php foreach ($elementsForDisplay as $setName => $setElements): ?>
<?php
if ($setName == 'VRA Core') {
    $class = 'element';
} else {
    $class = 'element';
}
?>
<div class="element-set">
    <?php if ($showElementSetHeadings): ?>
    <h2><?php echo html_escape(__($setName)); ?></h2>
    <?php endif; ?>
    <?php if ($setName == 'VRA Core') {
    $db = get_db();
    $recordType = get_class($record);
    $params = array(
                    'record_id' => $record->id,
                    'record_type' => $recordType,
                    'element_id' => false,
                    'vra_element_id' => false,
                    );
    $recordLevelAttrs = $db->getTable('VraCoreAttribute')->findBy($params);

    switch ($recordType) {
            case 'Item':
                $label = __('Work Attributes');
            break;
            case 'File':
                $label = __('Image Attributes');
            break;
            case 'Collection':
                $label = __('Collection Attributes');
            break;
            }
}
    ?>

    <?php if (!get_option('vra-core-hide-public-attributes') && isset($recordLevelAttrs)): ?>
        <div>
            <h3><?php echo $label; ?></h3>
            <ul class='vra-core-attributes'>
            <?php foreach ($recordLevelAttrs as $attribute): ?>
                <li><span class='vra-core-attribute-name'>@<?php echo metadata($attribute, 'name'); ?></span>
                    <?php echo metadata($attribute, 'content'); ?>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif;?>
    <?php foreach ($setElements as $elementName => $elementInfo): ?>
    <div id="<?php echo text_to_id(html_escape("$setName $elementName")); ?>" class="<?php echo $class; ?>">
        <h3><?php echo html_escape(__($elementName)); ?></h3>
        <?php foreach ($elementInfo['texts'] as $text): ?>
            <div class="element-text">
                <?php echo $text; ?>
                <?php
                fire_plugin_hook('elements_show', array(
                    'view' => $this,
                    'elementInfo' => $elementInfo,
                    'record' => $record,
                    )
                );
                ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
</div><!-- end element-set -->
<?php endforeach;
