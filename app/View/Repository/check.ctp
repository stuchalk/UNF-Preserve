<div class="left">
    <h2>Validity of Fedora Objects in Namespace <?php echo $args['ns']; ?></h2>
</div>
<div class="right">
    <?php
    ksort($data);
    foreach($data as $pid=>$obj) {

        if($obj['valid']=="Valid"): echo $pid.": ".$obj['title']." (".$obj['valid'].")<br />";
            else:                   echo "<b>".$pid.": ".$obj['title']." (".$obj['valid'].")</b><br />";
                endif;
    }
    ?>
</div>