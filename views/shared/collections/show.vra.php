<vra xmlns="http://www.vraweb.org/vracore4.htm"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.vraweb.org/vracore4.htm http://www.loc.gov/standards/vracore/vra.xsd">

<?php  echo $this->partial('record-xml-partial.php',
        array(
                'record' => $collection,
             )
        );
?>
</vra>
