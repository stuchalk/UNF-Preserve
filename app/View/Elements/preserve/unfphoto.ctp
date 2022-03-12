<?php
	// Used on view pages to show one of the photos of a species in the UNF archive
	// Variables: $divid, $type, $name, $photo
	if(!is_array($photo)) { $photo=['id'=>$photo]; }
?>

<div id="<?php echo $divid; ?>" class="photodiv">
	<?php
		$pid=$photo['Asset']['pid'];$thumbstr=$photo['Stream'][0];
        $meta=getimagesize(WWW_ROOT.$thumbstr['path']);
        
		// Admin active/inactive checkboxes
		if($this->Session->check('Auth.User.id')) {
			list($junk,$state)=explode("#",$photo['Asset']['state']);
			$divid=str_replace(":","",$pid);
			$script="setState('".$pid."','KML',this.value,'".$divid."')";
			echo "<div id='".$divid."' style='display: none;width: 200px;float: left;color: green;font-weight: bold;margin-top: 3px;'>Updated</div>";
			echo "<div style='width: 200px;float: left;text-align: center;'>";
                $num=str_replace('unfenvc:','',$pid);$l=substr($state,0,1);$opts=['A'=>'Show','I'=>'Hide','D'=>'Delete'];
                $this->Form->input('activity'.$num,['type'=>'radio','value'=>$l,'div'=>false,'legend'=>false,'options'=>$opts,'onchange'=>$script]);
            echo "</div>";
		}
		
		// Images
        echo $this->element('imagethumb',['type'=>$type,'name'=>$name,'pid'=>$pid,'sid'=>$thumbstr['id'],'size'=>$thumbstr['size']]);
		
		// Creator
		if($this->Session->check('Auth.User.id')) {
			list($ns,$num)=explode(":",$pid);
			$onclick='document.getElementById("cshow'.$num.'").style.display="none";document.getElementById("cedit'.$num.'").style.display="block";return false;';
			$onblur="setDC('".$pid."','creator',this.value,'cshow".$num."','cedit".$num."')";
			echo "<p id='cshow".$num."' style='cursor: pointer;text-align: center;' onclick='".$onclick."'>© ".$photo['Asset']['creator']."</p>";
			echo "<div id='cedit".$num."' style='display: none;'>";
			echo $this->Form->input('creator',['type'=>'text','size'=>'20','label'=>false,'div'=>false,'value'=>$photo['Asset']['creator'],'onblur'=>$onblur]);
			echo "</div>";
		} else {
			echo "<p class='small' style='text-align: center;padding-bottom: 0px;'>© ".$photo['Asset']['creator']."</p>";
		}
	?>
</div>