<?php

$author_field = $this->skylight_utilities->getField("Author");
$type_field = $this->skylight_utilities->getField("Type");
$bitstream_field = $this->skylight_utilities->getField("Bitstream");
$thumbnail_field = $this->skylight_utilities->getField("Thumbnail");


$type = 'Unknown';

if(isset($solr[$type_field])) {
    $type = "media-" . strtolower(str_replace(' ','-',$solr[$type_field][0]));
}


?>


<h1 class="itemtitle"><?php echo $record_title ?></h1>
<div class="tags">
    <?php

    if (isset($solr[$author_field])) {
        foreach($solr[$author_field] as $author) {
            $orig_filter = preg_replace('/ /','+',$author, -1);
            $orig_filter = preg_replace('/,/','%2C',$orig_filter, -1);
            echo '<a href=\'./search/*/Author:"'.$orig_filter.'"\'>'.$author.'</a>';
        }
    }

    // Commented out date since we list it in the metadata. Robin.
    //$date_field = $this->skylight_utilities->getField("Date");
    //if (isset($solr[$date_field])) {
    //    foreach($solr[$date_field] as $date) {
    //        echo '<span>('.$date.')</span>';
    //    }
    //}
    //else {
    //    $date_field = $this->skylight_utilities->getField("Year");
    //    if (isset($solr[$date_field])) {
     //       foreach($solr[$date_field] as $date) {
     //           echo '<span>('.$date.')</span>';
     //       }
     //   }
    //}

    ?>
</div>

<div class="content">

    <?php
    $abstract_field = $this->skylight_utilities->getField("Abstract");
    if(isset($solr[$abstract_field])) {
        ?> <h3>Abstract</h3> <?php
        foreach($solr[$abstract_field] as $abstract) {
            echo '<p>'.$abstract.'</p>';
        }
    }
    ?>

    <table>
        <caption>Description</caption>
        <tbody>
        <?php foreach($recorddisplay as $key) {

            $element = $this->skylight_utilities->getField($key);
            if(isset($solr[$element])) {
                echo '<tr><th>'.$key.'</th><td>';
                foreach($solr[$element] as $index => $metadatavalue) {
                    echo $metadatavalue;
                    if($index < sizeof($solr[$element]) - 1) {
                        echo '; ';
                    }
                }
                echo '</td></tr>';
            }

        } ?>
        </tbody>
    </table>

</div>



<?php if(isset($solr[$bitstream_field]) && $link_bitstream) { ?>
    <div class="record_bitstreams">
        <h3>Digital Objects</h3>
        <p>High quality versions of images are available on request by <a href="./feedback">email</a>.</p>
    <?php
    foreach($solr[$bitstream_field] as $bitstream) {

        $bitstreamLink = $this->skylight_utilities->getBitstreamLink($bitstream);
        $bitstreamLinkedImage = $this->skylight_utilities->getBitstreamLinkedImage($bitstream);


        $segments = explode("##", $bitstream);
        $filename = $segments[1];
        $handle = $segments[3];
        $seq = $segments[4];
        $handle_id = preg_replace('/^.*\//', '',$handle);
        $uri = './record/'.$handle_id.'/'.$seq.'/'.$filename;



        if(isset($solr[$thumbnail_field])) {

            foreach ($solr[$thumbnail_field] as $thumbnail) {
                $t_segments = explode("##", $thumbnail);
                $t_filename = $t_segments[1];
                $t_handle = $t_segments[3];
                $t_seq = $t_segments[4];
                $handle_id = preg_replace('/^.*\//', '',$t_handle);
                $t_uri = './record/'.$handle_id.'/'.$t_seq.'/'.$t_filename;

                if ($t_filename == $filename.'.jpg') {
                    $thumbnailLink = '<a title = "' . $solr[$title_field][0] . '" class="fancybox"' . ' href="' . $t_uri . '"> ';
                    $thumbnailLink .= '<img src = "'.$t_uri.'" title="'. $solr[$title_field][0] .'" /></a>';
                    echo $thumbnailLink;
                }
            }
        }
    }


    ?>
    </div>
<?php
} ?>


