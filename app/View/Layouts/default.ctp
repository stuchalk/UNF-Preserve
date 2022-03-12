<!DOCTYPE html>
<html lang="en-US">
	<head>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-PPXH5X7CZ6"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());

			gtag('config', 'G-PPXH5X7CZ6');
		</script>
		<?php echo $this->Html->charset(); ?>
		<title><?php echo $title_for_layout; ?></title>
		<?php
        echo $this->Html->meta('icon');
        //echo $this->Html->css('unf');
        echo $this->Html->css('jquery-ui');
        echo $this->Html->css('bootstrap.min');
        echo $this->Html->css('signin');
        echo $this->Html->css('preserve');
        echo $this->Html->script('jquery.min');
        echo $this->Html->script('tinymce/js/tinymce/jquery.tinymce.min');
        echo $this->Html->script('tinymce/js/tinymce/tinymce.min');
        echo $this->Html->script('jquery-ui.min');
        echo $this->Html->script('bootstrap.bundle.min');
        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
		?>
		<!--Google Maps-->
		<script type="text/javascript">
			function gmap(lat,lon,zlevel,type,info,points)
			{
				let myLatlng = new google.maps.LatLng(lat, lon);
				let myOptions = {
					zoom: zlevel,
					center: myLatlng,
					mapTypeId: google.maps.MapTypeId.SATELLITE
					};
				let map = new google.maps.Map(document.getElementById("mapdiv"), myOptions);
				if(type==='point') {
					let marker = new google.maps.Marker({
						position: myLatlng,
						title: info
						});
					google.maps.event.addListener(marker, 'mouseover', function() { infowindow.open(map, marker); });
					marker.setMap(map);
				} else if(type==='kml') {
					let colpts = new google.maps.KmlLayer({url: points});
					colpts.setMap(map);
					google.maps.event.addListener(colpts, 'click', function(kmlEvent) {
						let url = '/datastreams/view/' + kmlEvent.featureData.snippet + '/Source/modal';
						let title = kmlEvent.featureData.description + ' (' + kmlEvent.featureData.name +')  ** Click image to see details **';
						showModalImage(url,title);
					});
				}
			}
		</script>
		<!--Modal-->
		<script type="text/javascript">
			function showModalProfile(pid) {
				Modalbox.show('/objects/profile/'+pid+'?modal=yes', {title: 'Collection Item', width: 700, height: 600});
			}
			function showModalImage(url, title) {
				Modalbox.show(url, {title: title, width: 825, height: 650});
			}
			function showModalMap(url, title) {
				Modalbox.show(url, {title: title, width: 725, height: 525});
			}
		</script>
	</head>
	<body>
<!--		<div id='livesearch' style='visibility: hidden;'></div>-->
		<?php include('header.php'); ?>
		<div class="container-fluid" role="main">
            <?php echo $this->fetch('content'); ?>
		</div>
		<?php include('footer.php'); ?>
	</body>
</html>
