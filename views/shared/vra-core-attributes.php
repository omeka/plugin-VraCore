<?php if($showAttributes && (count($attributes) > 0)): ?>
<ul class='vra-core-attributes'>
<?php foreach($attributes as $attribute): ?>
    <li><span class='vra-core-attribute-name'>@<?php echo metadata($attribute, 'name'); ?></span>
        <?php echo metadata($attribute, 'content'); ?>
    </li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
