<?php foreach ($elementsForDisplay as $setName => $setElements): ?>
<?php
if($setName == 'VRA Core') {
    $class = 'element';
} else {
    $class = 'element';
}
?>
<div class="element-set">
    <?php if ($showElementSetHeadings): ?>
    <h2><?php echo html_escape(__($setName)); ?></h2>
    <?php endif; ?>
    <?php foreach ($setElements as $elementName => $elementInfo): ?>
    <div id="<?php echo text_to_id(html_escape("$setName $elementName")); ?>" class="<?php echo $class; ?>">
        <h3><?php echo html_escape(__($elementName)); ?></h3>
        <?php foreach ($elementInfo['texts'] as $text): ?>
            <div class="element-text"><?php echo $text; ?></div>
        <?php endforeach; ?>
        <div class='element-text'>
            <div class='vra-subelements'>
            <?php
                    fire_plugin_hook('elements_show',
                                    array('view' => $this,
                                         'elementInfo' => $elementInfo,
                                         'record' => $record
                                         )
                                   );
            ?>
            <!-- end vra-subelements -->
            </div>
        </div>
    </div><!-- end element -->
    <?php endforeach; ?>
</div><!-- end element-set -->
<?php endforeach;
