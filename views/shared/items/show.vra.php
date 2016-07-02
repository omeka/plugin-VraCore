<vra xmlns="http://www.vraweb.org/vracore4.htm"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.vraweb.org/vracore4.htm http://www.loc.gov/standards/vracore/vra.xsd">

    
<?php
$db = get_db();

$refidAttributes = $db->getTable('VraCoreAttribute')->findBy(array('record_type' => 'Item',
                                                'record_id'   => $item->id,
                                                'name' => 'refid',
                                                'vra_element_id' => false,
                                                'element_id' => false,
                                    ));

if(!empty($refidAttributes)) {
    $refidAttribute = $refidAttributes[0];
    $refid = $refidAttribute->content;
} else {
    $refid = $item->id;
}
?>

<?php  echo $this->partial('record-xml-partial.php', 
        array(
                'record' => $item,
             )
        );
?>



</vra>
