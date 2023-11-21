<?php 
$offer = $results->offer;
$offerdetails = $results->offerdetails;
$agent = $results->agent;
$inquiry_success = $results->inquiry_success;

$pagePath = get_bloginfo('wpurl') . '/' . EW_PLUGIN_ROUTE . '/' . EW_ESTATEVIEW_ROUTE . '/estates/' . $offer->id . '/';

function removeLastTwoZero($number){
	$number_int = intval($number);
	if($number_int == $number)return $number_int;
	return number_format($number ,1,",","");
}

$gmaplink='';
if($offerdetails->lat != '' && $offerdetails->lon != ''){
	$gmaplink = 'https://maps.google.com/maps?ll='
		.$offerdetails->lat
		.','.$offerdetails->lon
		.'&q='.$offerdetails->lat
		.','.$offerdetails->lon
		.'&spn=0.1,0.1&t=h&hl=de';
} else {
	$gmaplink = 'https://www.google.com/maps/place/'
		. urlencode(
			$offerdetails->street
			. ' ' . $offerdetails->houseNumber
			. ', ' . $offerdetails->postcode
			. ' ' . $offerdetails->city
		);
}

$gmaplink_agent = 'https://www.google.com/maps/place/'
	. urlencode(
		$agent->street
		. ', ' . $agent->zip
		. ' ' . $agent->city
	);

$upload_dir = wp_upload_dir();
$pics_url = $upload_dir['baseurl'] . '/estates/';
$agents_img_url = $upload_dir['baseurl'] . '/agents/';
?>

<?php 
if(empty($offerdetails->mainPic)){
	$mainImgUrl = '';
}else{
	$mainImgUrl = $pics_url.$offer->id.'/'.$offerdetails->mainPic;
}
?>

<?php if(!empty($mainImgUrl)){ ?>
	<div class="text-center">
		<img src="<?=$mainImgUrl; ?>" class="img-fluid" alt="<?=$offer->name; ?>">
	</div>
<?php } ?>

<?php // OLD Gallery
/* if(count((array) $offerdetails->pictures) > 0){ ?>
	<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
		<div class="carousel-indicators">
			<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
			<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
			<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
		</div>
		<div class="carousel-inner">
			<?php $i = 0; 
			foreach($offerdetails->pictures as $pic){ 
				$filename = $pic->filename;
				// $filename = str_replace('.jpg', '_gal.jpg', $filename);
				$pic_url = $pics_url.$offer->id.'/'.$filename;
				?>
				<div class="carousel-item <?=($i==0?'active':''); ?>">
					<img src="<?=$pic_url; ?>" class="d-block w-100" alt="<?=$pic->caption; ?>">
					<div class="carousel-caption d-none d-md-block">
						<!-- <h5><?=$offer->name; ?></h5> -->
						<p><?=$pic->caption; ?></p>
					</div>
				</div>
			<?php $i ++; 
			} ?>
		</div>
		<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="visually-hidden">Vorherige</span>
		</button>
		<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="visually-hidden">Nächste</span>
		</button>
	</div>
<?php } */ ?>

<h3 class="mt-5">Eckdaten</h3>
<table class="table mt-1 w-100" style="width: 100%!important;">
    <tbody>

<?php
$typestr = '';
if($offer->category=='APARTMENT'){
	if($offerdetails->apartmentType == 'NO_INFORMATION'){
		$typestr = ExpowandDictionary::$category_options_residential[$offer->category];
	}else{
		$typestr = ExpowandDictionary::$apartmentType_options[$offerdetails->apartmentType];
	}
}else if($offer->category=='HOUSE'){
	if($offerdetails->buildingType == 'NO_INFORMATION'){
		$typestr = ExpowandDictionary::$category_options_residential[$offer->category];
	}else{
		$typestr = ExpowandDictionary::$buildingType_options[$offerdetails->buildingType];
	}
}else if($offer->category=='LIVING_SITE'){
	$typestr = ExpowandDictionary::$category_options_residential[$offer->category];
}else if($offer->category=='OFFICE'){
	if($offerdetails->officeType == 'NO_INFORMATION'){
		$typestr = ExpowandDictionary::$category_options_commertial[$offer->category];
	}else{
		$typestr = ExpowandDictionary::$officeType_options[$offerdetails->officeType];
	}
}else if($offer->category=='STORE'){
	if($offerdetails->storeType == 'NO_INFORMATION'){
		$typestr = ExpowandDictionary::$category_options_commertial[$offer->category];
	}else{
		$typestr = ExpowandDictionary::$storeType_options[$offerdetails->storeType];
	}
}else if($offer->category=='INDUSTRY'){
	if($offerdetails->industryType == 'NO_INFORMATION'){
		$typestr = ExpowandDictionary::$category_options_commertial[$offer->category];
	}else{
		$typestr = ExpowandDictionary::$industryType_options[$offerdetails->industryType];
	}
}
if($typestr != ''){
?>
	<tr>
		<th scope="row" style="text-align: left; width: 50%;">Immobilientyp</th>
		<td style="width: 50%;"><?=$typestr; ?></td>
	</tr>
<?php } ?>

<?php if($offerdetails->shortTermConstructible == 'YES'){ ?>
	<tr>
		<th scope="row" style="text-align: left; width: 50%;">Kurzfristig bebaubar</th>
		<td style="width: 50%;">Erlaubt</td>
	</tr>
<?php } ?>

<?php if($offerdetails->buildingPermission == 'YES'){ ?>
	<tr>
		<th scope="row" style="text-align: left; width: 50%;">Baugenehmigung</th>
		<td style="width: 50%;">Verfügbar</td>
	</tr>
<?php } ?>

<?php if($offerdetails->demolition == 'YES'){ ?>
	<tr>
		<th scope="row" style="text-align: left; width: 50%;">Abriss</th>
		<td style="width: 50%;">Erlaubt</td>
	</tr>
<?php } ?>

<?php if(!empty($offerdetails->constructionYear)){ ?>
	<tr>
		<th scope="row" style="text-align: left; width: 50%;">Baujahr</th>
		<td style="width: 50%;"><?=$offerdetails->constructionYear; ?></td>
	</tr>
<?php } ?>

<?php if($offerdetails->additionalCosts>0){ ?>
	<tr>
		<th scope="row" style="text-align: left; width: 50%;">Nebenkosten</th>
		<td style="width: 50%;"><?=number_format($offerdetails->additionalCosts,0,",","."); ?> &euro;</td>
	</tr>
<?php } ?>

<?php if($offerdetails->deposit!=''){ ?>
	<tr>
		<th scope="row" style="text-align: left; width: 50%;">Kaution</th>
		<td style="width: 50%;"><?=$offerdetails->deposit; ?></td>
	</tr>
<?php } ?>

<?php if(($offer->type == 1)&&($offerdetails->baseRent!=0)){ ?>
	<tr>
		<th scope="row" style="text-align: left; width: 50%;">Kaltmiete</th>
		<td style="width: 50%;"><?=number_format($offerdetails->baseRent,0,",","."); ?> &euro;</td>
	</tr>
<?php } ?>
<?php if(($offer->type == 1)&&($offerdetails->serviceCharge!=0)){ ?>
	<tr>
		<th scope="row" style="text-align: left; width: 50%;">Nebenkosten</th>
		<td style="width: 50%;"><?=number_format($offerdetails->serviceCharge ,0,",","."); ?> &euro;</td>
	</tr>
<?php } ?>
<?php if(($offer->type == 1)&&($offerdetails->heatingCosts!=0)){ ?>
	<tr>
		<th scope="row" style="text-align: left; width: 50%;">Heizkosten</th>
		<td style="width: 50%;"><?=number_format($offerdetails->heatingCosts,0,",","."); ?> &euro;</td>
	</tr>
<?php } ?>
<?php if(($offer->type == 1)&&($offerdetails->totalRent!=0)){ ?>
	<tr>
		<th scope="row" style="text-align: left; width: 50%;">Gesamtmiete</th>
		<td style="width: 50%;"><?=number_format($offerdetails->totalRent,0,",","."); ?> &euro;</td>
	</tr>
<?php } ?>

<?php if($offerdetails->freeFrom != ''){ ?>
	<tr>
		<th scope="row" style="text-align: left; width: 50%;">Verfügbar ab</th>
		<td style="width: 50%;"><?=$offerdetails->freeFrom; ?></td>
	</tr>
<?php } ?>

<?php if($offerdetails->condition != 'NO_INFORMATION'){ ?>
	<tr>
		<th scope="row" style="text-align: left; width: 50%;">Objektzustand</th>
		<td style="width: 50%;"><?=ExpowandDictionary::$condition_options[$offerdetails->condition]; ?></td>
	</tr>
<?php } ?>

<?php if($offerdetails->interiorQuality != 'NO_INFORMATION'){ ?>
	<tr>
		<th scope="row" style="text-align: left; width: 50%;">Qualität der Ausstattung</th>
		<td style="width: 50%;"><?=ExpowandDictionary::$interiorQuality_options[$offerdetails->interiorQuality]; ?></td>
	</tr>
<?php } ?>

<?php if($offerdetails->numberOfParkingSpaces > 0){ ?>
	<tr>
		<th scope="row" style="text-align: left; width: 50%;">Anzahl Stellplätze</th>
		<td style="width: 50%;"><?=$offerdetails->numberOfParkingSpaces; ?></td>
	</tr>
<?php } ?>

	</tbody>
</table>

<?php if(!empty($offerdetails->descriptionNote)){ ?>
	<h3 class="mt-5">Beschreibung</h3>
	<p class="text-justify" style="text-align: justify!important;"><?=str_ireplace(PHP_EOL, '<br />', $offerdetails->descriptionNote); ?></p>
<?php } ?>

<h3 class="mt-5">Fläche</h3>
<table class="table mt-1 w-100" style="width: 100%!important;">
    <tbody>
			
	<?php if($offerdetails->livingSpace > 0){ ?>
		<tr>
			<?php if($offer->category == 'OFFICE'){ // TODO: check if correct ?>
				<th scope="row" style="text-align: left; width: 50%;">Bürofläche</th>
			<?php }else{ ?>
				<th scope="row" style="text-align: left; width: 50%;">Wohnfläche</th>
			<?php } ?>
			<td style="width: 50%;">ca. <?=intval($offerdetails->livingSpace); ?> m²</td>
		</tr>
	<?php } ?>

	<?php if($offerdetails->totalFloorSpace > 0){ ?>
		<tr>
			<th scope="row" style="text-align: left; width: 50%;">Gesamtfläche</th>
			<td style="width: 50%;">ca. <?=intval($offerdetails->totalFloorSpace); ?> m²</td>
		</tr>
	<?php } ?>

	<?php if(($offer->category == 'OFFICE' || $offer->category == 'INDUSTRY' || $offer->category == 'STORE') && $offerdetails->netFloorSpace > 0){ ?>
		<tr>
			<?php if($offer->category == 'OFFICE'){ ?>
				<th scope="row" style="text-align: left; width: 50%;">Büro-/ Praxisfläche</th>
			<?php }else if($offer->category == 'INDUSTRY'){ ?>
				<th scope="row" style="text-align: left; width: 50%;">Lager-/ Produktionsfläche</th>
			<?php }else if($offer->category == 'STORE'){ ?>
				<th scope="row" style="text-align: left; width: 50%;">Verkaufsfläche</th>
			<?php } ?>
			<td style="width: 50%;">ca. <?=intval($offerdetails->netFloorSpace); ?> m²</td>
		</tr>
	<?php } ?>

	<?php if(($offer->category=='HOUSE' || $offer->category=='LIVING_SITE') && $offerdetails->plotArea > 0){ ?>
		<tr>
			<th scope="row" style="text-align: left; width: 50%;">Grundstücksfläche</th>
			<td style="width: 50%;">ca. <?=intval($offerdetails->plotArea); ?> m²</td>
		</tr>
	<?php } ?>

	<?php if($offerdetails->additionalArea > 0){ ?>
		<tr>
			<th scope="row" style="text-align: left; width: 50%;">Nebenfläche</th>
			<td style="width: 50%;">ca. <?=intval($offerdetails->additionalArea); ?> m²</td>
		</tr>
	<?php } ?>

	<?php if($offer->category=='LIVING_SITE' && $offerdetails->minDivisible > 0){ ?>
		<tr>
			<th scope="row" style="text-align: left; width: 50%;">Fläche teilbar ab</th>
			<td style="width: 50%;">ca. <?=intval($offerdetails->minDivisible); ?> m²</td>
		</tr>
	<?php } ?>

	<?php if($offerdetails->shopWindowLength > 0){ ?>
		<tr>
			<th scope="row" style="text-align: left; width: 50%;">Schaufensterfront</th>
			<td style="width: 50%;"><?=removeLastTwoZero($offerdetails->shopWindowLength); ?> m</td>
		</tr>
	<?php } ?>

	<?php if($offerdetails->hallHeight > 0){ ?>
		<tr>
			<th scope="row" style="text-align: left; width: 50%;">Hallen-/ Geschosshöhe</th>
			<td style="width: 50%;"><?=removeLastTwoZero($offerdetails->hallHeight); ?> m</td>
		</tr>
	<?php } ?>

	<?php if($offerdetails->numberOfRooms > 0){ ?>
		<tr>
			<th scope="row" style="text-align: left; width: 50%;">Zimmer</th>
			<td style="width: 50%;"><?=removeLastTwoZero($offerdetails->numberOfRooms); ?></td>
		</tr>
	<?php } ?>

	<?php if($offer->category != 'OFFICE'){ ?>

		<?php if($offerdetails->numberOfBedRooms > 0){ ?>
			<tr>
				<th scope="row" style="text-align: left; width: 50%;">Anzahl Schlafzimmer</th>
				<td style="width: 50%;"><?=removeLastTwoZero($offerdetails->numberOfBedRooms); ?></td>
			</tr>
		<?php } ?>

		<?php if($offerdetails->numberOfBathRooms > 0){ ?>
			<tr>
				<th scope="row" style="text-align: left; width: 50%;">Anzahl Badezimmer</th>
				<td style="width: 50%;"><?=removeLastTwoZero($offerdetails->numberOfBathRooms); ?></td>
			</tr>
		<?php } ?>

	<?php } ?>

	<?php if(($offerdetails->numberOfFloors > 1)&&($offer->category=='APARTMENT')&&($offerdetails->floor!='')){ ?>
		<tr>
			<th scope="row" style="text-align: left; width: 50%;">Etage</th>
			<td style="width: 50%;">
				<?php if($offerdetails->floor == 0){ ?>
					EG
				<?php }else{ ?>
					<?=$offerdetails->floor; ?>. OG
				<?php } ?>
			</td>
		</tr>
	<?php } ?>

	<?php if($offerdetails->numberOfFloors > 0){ ?>
		<tr>
			<th scope="row" style="text-align: left; width: 50%;">Etagenzahl insgesamt</th>
			<td style="width: 50%;"><?=removeLastTwoZero($offerdetails->numberOfFloors); ?></td>
		</tr>
	<?php } ?>	

	</tbody>
</table>

<?php if(!empty($offer->type == 0)){ ?> 
	<h3 class="mt-5">Preis</h3>
	<table class="table mt-1 w-100" style="width: 100%!important;">
		<tbody>
			<tr>
				<th scope="row" style="text-align: left; width: 50%;">Kaufpreis</th>
				<td style="width: 50%;"><?=number_format($offer->immoprice,0,",","."); ?> €</td>
			</tr>
			<tr>
				<th scope="row" style="text-align: left; width: 50%;">Grunderwerbsteuer</th>
				<td style="width: 50%;"><?=number_format($offer->grunderwerbsteuer,1,",","."); ?> %</td>
			</tr>
			<tr>
				<th scope="row" style="text-align: left; width: 50%;">Maklerprovision</th>
				<td style="width: 50%;"><?=number_format($offer->maklerprovision,2,",","."); ?> %</td>
			</tr>
			<tr>
				<th scope="row" style="text-align: left; width: 50%;">Notarkosten/ Grundbucheintrag</th>
				<td style="width: 50%;"><?=number_format($offer->notargebuhren,1,",","."); ?> %</td>
			</tr>	
			<tr>
				<th scope="row" style="text-align: left; width: 50%;">Käuferprovision</th>
				<td style="width: 50%;"><?=str_replace('.',',',$offer->maklerprovision+0) ?> % (inkl. MwSt.) vom Kaufpreis</td>
			</tr>
		</tbody>
	</table>

	<p class="text-700 fs-6" style="text-align: justify!important;">
		Vertragsgrundlage unserer Leistung sind unsere Allgemeinen Geschäftsbedingungen.
	</p>
	<p class="text-700 fs-6" style="text-align: justify!important;">
		Dies ist der aktuelle Angebotskaufpreis. Wir weisen darauf hin, dass dieser Angebotskaufpreis 
		fallen oder bei erheblicher Nachfrage nach Objekten dieser Art auch steigen kann.
	</p>
	<p class="text-700 fs-6" style="text-align: justify!important;">
		Die Maklercourtage beträgt <?=str_replace('.',',',$offer->maklerprovision+0) ?> % inkl. der gesetzlichen Mehrwertsteuer.
		Sie ist verdient und fällig bei Abschluss eines notariellen Kaufvertrages und vom Käufer zu zahlen.
		Grunderwerbssteuer, Notar- und Gerichtskosten sind vom Käufer zu tragen.
		Im Übrigen gelten unsere Allgemeinen Geschäftsbedingungen.
		Irrtum und Zwischenverkauf vorbehalten.
		<?php /* <?=$agent->firma; ?>, <?=$agent->street; ?>, <?=$agent->zip; ?> <?=$agent->city; ?> <?php if ($agent->ustid != ''){ ?>,  Ust. IdNr.: <?=$agent->ustid; ?><?php } ?>. */ ?>
	</p>
<?php } ?>

<?php //if(!empty($offerdetails->furnishingNote)){ ?>
	<h3 class="mt-5">Ausstattung</h3>
<?php //} ?>
<div class="row mt-1 mb-3 g-3">

	<?php if($offerdetails->balcony != 0){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light px-3 py-1 shadow">Balkon</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->terrace != 0){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light px-3 py-1 shadow">Terrasse</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->lift != 0){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light px-3 py-1 shadow">Personenaufzug</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->ramp == 'YES'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light px-3 py-1 shadow">Rampe</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->autoLift == 'YES'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light px-3 py-1 shadow">Hebebühne</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->goodsLift == 'YES'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light px-3 py-1 shadow">
				Lastenaufzug <?=($offerdetails->goodsLiftLoad>0)?removeLastTwoZero($offerdetails->goodsLiftLoad).' kg':''; ?>
			</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->craneRunway == 'YES'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light px-3 py-1 shadow">
				Kranbahn <?=($offerdetails->craneRunwayLoad>0)?removeLastTwoZero($offerdetails->craneRunwayLoad).' tonnen':''; ?>
			</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->floorLoad > 0){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light px-3 py-1 shadow">
				Bodenbelastung <?=removeLastTwoZero($offerdetails->floorLoad); ?> kg/m²
			</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->connectedLoad > 0){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light px-3 py-1 shadow">
				Stromanschlusswert <?=removeLastTwoZero($offerdetails->connectedLoad); ?> kVA
			</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->garden != 0){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light px-3 py-1 shadow">Garten / -mitbenutzung</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->builtInKitchen != 0){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light px-3 py-1 shadow">Einbauküche</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->cellar != 'NOT_APPLICABLE'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light px-3 py-1 shadow">Keller</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->guestToilet != 'NOT_APPLICABLE'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light px-3 py-1 shadow">Gäste-WC</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->hasCanteen == 'YES'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light px-3 py-1 shadow">Kantine/ Cafeteria</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->kitchenComplete == 'YES'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light px-3 py-1 shadow">Küche vorhanden</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->highVoltage == 'YES'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light px-3 py-1 shadow">Starkstrom</span>
		</div>
	<?php } ?>
	
	<?php if($offerdetails->handicappedAccessible == 'YES'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light px-3 py-1 shadow">Barrierefrei</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->lanCables == 'YES' || $offerdetails->lanCables == 'BY_APPOINTMENT'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light px-3 py-1 shadow">DV-Verkabelung vorhanden</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->airConditioning == 'YES' || $offerdetails->airConditioning == 'BY_APPOINTMENT'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light px-3 py-1 shadow">Klimaanlage</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->flooringType != 'NO_INFORMATION'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light px-3 py-1 shadow">Bodenbelag: <?=ExpowandDictionary::$flooringType_options[$offerdetails->flooringType]; ?>
		</div>
	<?php } ?>

	<?php if($offerdetails->supplyType != 'NO_INFORMATION'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light px-3 py-1 shadow">Zulieferung: <?=ExpowandDictionary::$supplyType_options[$offerdetails->supplyType]; ?>
		</div>
	<?php } ?>

</div>
<p class="text-justify" style="text-align: justify!important;"><?=str_ireplace(PHP_EOL, '<br />', $offerdetails->furnishingNote); ?></p>

<h3 class="mt-5">Energie</h3>
<table class="table mt-1 w-100" style="width: 100%!important;">
    <tbody>

		<tr>
			<th scope="row" style="text-align: left; width: 50%;">Energieausweis</th>
			<td style="width: 50%;"><?=ExpowandDictionary::$energyCertificateAvailability_options[$offerdetails->energyCertificateAvailability]; ?></td>
		</tr>

		<?php if($offerdetails->energyCertificateCreationDate != 'NO_INFORMATION'){ ?>
			<tr>
				<th scope="row" style="text-align: left; width: 50%;">Erstellungsdatum</th>
				<td style="width: 50%;"><?=ExpowandDictionary::$energyCertificateCreationDate_options[$offerdetails->energyCertificateCreationDate]; ?></td>
			</tr>
		<?php } ?>

		<?php if($offerdetails->energyCertificateValidTill != '' && $offerdetails->energyCertificateValidTill != '0000-00-00'  && $offerdetails->energyCertificateValidTill != '1970-01-01'){ ?>
			<tr>
				<th scope="row" style="text-align: left; width: 50%;">Gültig bis</th>
				<td style="width: 50%;"><?=date('d.m.Y', strtotime($offerdetails->energyCertificateValidTill)); ?></td>
			</tr>
		<?php } ?>

		<?php if($offerdetails->energyCertificateAvailability == 'AVAILABLE' && $offerdetails->buildingEnergyRatingType != 'NO_INFORMATION'){ ?>
			<tr>
				<th scope="row" style="text-align: left; width: 50%;">Energieausweistyp</th>
				<td style="width: 50%;"><?=ExpowandDictionary::$buildingEnergyRatingType_options[$offerdetails->buildingEnergyRatingType]; ?></td>
			</tr>
		<?php } ?>

		<?php if($offerdetails->energyCertificateAvailability == 'AVAILABLE' && !empty($offerdetails->thermalCharacteristic)){ ?>
			<tr>
				<?php if($offerdetails->buildingEnergyRatingType == 'ENERGY_REQUIRED'){ ?>
					<th scope="row" style="text-align: left; width: 50%;">Endenergiebedarf</th>
				<?php }else if($offerdetails->buildingEnergyRatingType == 'ENERGY_CONSUMPTION'){ ?>
					<th scope="row" style="text-align: left; width: 50%;">Endenergieverbrauch</th>
				<?php } ?>
				<td style="width: 50%;"><?=number_format($offerdetails->thermalCharacteristic, 2, ',', '.'); ?> kWh/(m²*a)</td>
			</tr>
		<?php } ?>

		<?php if($offerdetails->energyCertificateCreationDate == 'FROM_01_MAY_2014' && $offerdetails->energyEfficiencyClass != 'NOT_APPLICABLE'){ ?>
			<tr>
				<th scope="row" style="text-align: left; width: 50%;">Energieeffizienzklasse</th>
				<td style="width: 50%;"><?=ExpowandDictionary::$energyEfficiencyClass_options[$offerdetails->energyEfficiencyClass]; ?></td>
			</tr>
		<?php } ?>

		<?php if($offerdetails->buildingEnergyRatingType == 'ENERGY_CONSUMPTION' && $offerdetails->energyCertificateCreationDate == 'BEFORE_01_MAY_2014' && $offerdetails->energyConsumptionContainsWarmWater != 'NOT_APPLICABLE'){ ?>
			<tr>
				<th scope="row" style="text-align: left; width: 50%;">Energieverbrauch für Warmwasser enthalten</th>
				<td style="width: 50%;"><?=ExpowandDictionary::YN_ARR[$offerdetails->energyConsumptionContainsWarmWater]; ?></td>
			</tr>
		<?php } ?>

		<?php if($offerdetails->firingTypes != 'NO_INFORMATION'){ ?>
			<tr>
				<th scope="row" style="text-align: left; width: 50%;">Wesentlicher Energieträger</th>
				<td style="width: 50%;"><?=ExpowandDictionary::$firingTypes_options[$offerdetails->firingTypes]; ?></td>
			</tr>
		<?php } ?>

		<?php if(!empty($offerdetails->legalConstructionYear)){ ?>
			<tr>
				<th scope="row" style="text-align: left; width: 50%;">Baujahr laut Energieausweis</th>
				<td style="width: 50%;"><?=$offerdetails->legalConstructionYear; ?></td>
			</tr>
		<?php } ?>

		<?php if($offerdetails->heatingType != 'NO_INFORMATION'){ ?>
			<tr>
				<th scope="row" style="text-align: left; width: 50%;">Heizungsart</th>
				<td style="width: 50%;"><?=ExpowandDictionary::$heatingType_options[$offerdetails->heatingType]; ?></td>
			</tr>
		<?php } ?>

	</tbody>
</table>

<?php if(!empty($offerdetails->otherNote)){ ?>
	<h3 class="mt-5">Sonstiges</h3>
	<p class="text-justify" style="text-align: justify!important;"><?=str_ireplace(PHP_EOL, '<br />', $offerdetails->otherNote); ?></p>
<?php } ?>

<?php if(count((array) $offerdetails->pictures) > 1){ ?>
	<h3 class="mt-5">Bilder</h3>
	<div class="row g-3">
		<?php foreach($offerdetails->pictures as $pic){ 
			$pic_url = $pics_url.$offer->id.'/'.$pic->filename; ?>
			<a href="<?=$pic_url; ?>" data-toggle="lightbox" data-gallery="example-gallery" class="col-sm-4" data-caption="<?=$pic->caption; ?>">
				<img src="<?=$pic_url; ?>" class="img-fluid mx-auto d-block rounded-3" alt="<?=$pic->caption; ?>" title="<?=$pic->caption; ?>" 
					style="width: 15rem; height: 15rem; object-fit: cover;" >
			</a>
		<?php } ?>
	</div>
<?php } ?>

<?php if(!empty($offerdetails->locationNote)){ ?>
	<h3 class="mt-5">Lage</h3>
	<div class="row mt-1 mb-3 g-3">
		<?php if($offerdetails->distanceToPT != 0){ ?>
			<div class="col-auto text-center">
				<span class="rounded-3 bg-light px-3 py-1 shadow">Laufzeit zum Öffentl. Personennahverkehr <?=$offerdetails->distanceToPT; ?> Min.</span>
			</div>
		<?php } ?>

		<?php if($offerdetails->distanceToMRS != 0){ ?>
			<div class="col-auto text-center">
				<span class="rounded-3 bg-light px-3 py-1 shadow">Fahrzeit zum nächsten Bahnhof <?=$offerdetails->distanceToMRS; ?> Min.</span>
			</div>
		<?php } ?>

		<?php if($offerdetails->distanceToFM != 0){ ?>
			<div class="col-auto text-center">
				<span class="rounded-3 bg-light px-3 py-1 shadow">Fahrzeit zur nächsten Autobahn <?=$offerdetails->distanceToFM; ?> Min.</span>
			</div>
		<?php } ?>

		<?php if($offerdetails->distanceToAirport != 0){ ?>
			<div class="col-auto text-center">
				<span class="rounded-3 bg-light px-3 py-1 shadow">Fahrzeit zum nächsten Flughafen <?=$offerdetails->distanceToAirport; ?> Min.</span>
			</div>
		<?php } ?>
	</div>
	<p class="text-justify" style="text-align: justify!important;"><?=str_ireplace(PHP_EOL, '<br />', $offerdetails->locationNote); ?></p>
<?php } ?>

<?php include_once 'openstreet-map.php'; ?>

<?php include_once 'agent-details.php'; ?>

<?php include_once 'inquiry-form.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bs5-lightbox@1.8.3/dist/index.bundle.min.js"></script>
