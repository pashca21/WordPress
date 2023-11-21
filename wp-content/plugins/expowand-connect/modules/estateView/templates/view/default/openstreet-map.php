<?php 
?>

<h3 class="mt-5">Karte</h3>
<h4 class="mb-3">
	<i class="fa fa-map text-primary fs-0" aria-hidden="true"></i> 
	<a href="<?=$gmaplink; ?>" target="_blank" >
		<?=$offerdetails->street; ?> <?=$offerdetails->houseNumber; ?>, <?=$offerdetails->postcode; ?> <?=$offerdetails->city; ?> 
		<i class="fa fa-external-link  fs-0" aria-hidden="true"></i>
	</a>
</h4>
<div id="osfmapdiv" style ="width:100%!important; height: 50vh!important; border-radius:1.0rem;"></div>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
	
var lat = '<?=$offerdetails->lat; ?>';
var lon = '<?=$offerdetails->lon; ?>';

if(lat != '' && lon != ''){
	var mapOptions = {
		center: [lat, lon],
		zoom: 17
	}
	var map = new L.map('osfmapdiv', mapOptions);
	var marker = L.marker([lat, lon]).addTo(map);
	var layer = new L.TileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
	map.addLayer(layer);
}

</script>