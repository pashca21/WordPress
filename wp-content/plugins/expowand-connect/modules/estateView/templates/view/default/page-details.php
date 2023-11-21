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
	<div class="text-center EWestateView-default-main-pic-container">
		<img src="<?=$mainImgUrl; ?>" class="img-fluid" alt="<?=$offer->name; ?>">
	</div>
<?php } ?>

<h3 class="EWestateView-default-section-label">Eckdaten</h3>
<table class="table EWestateView-default-section-table" >
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
		<th scope="row" class="EWestateView-default-section-table-label">Immobilientyp</th>
		<td class="EWestateView-default-section-table-value"><?=$typestr; ?></td>
	</tr>
<?php } ?>

<?php if($offerdetails->shortTermConstructible == 'YES'){ ?>
	<tr>
		<th scope="row" class="EWestateView-default-section-table-label">Kurzfristig bebaubar</th>
		<td class="EWestateView-default-section-table-value">Erlaubt</td>
	</tr>
<?php } ?>

<?php if($offerdetails->buildingPermission == 'YES'){ ?>
	<tr>
		<th scope="row" class="EWestateView-default-section-table-label">Baugenehmigung</th>
		<td class="EWestateView-default-section-table-value">Verfügbar</td>
	</tr>
<?php } ?>

<?php if($offerdetails->demolition == 'YES'){ ?>
	<tr>
		<th scope="row" class="EWestateView-default-section-table-label">Abriss</th>
		<td class="EWestateView-default-section-table-value">Erlaubt</td>
	</tr>
<?php } ?>

<?php if(!empty($offerdetails->constructionYear)){ ?>
	<tr>
		<th scope="row" class="EWestateView-default-section-table-label">Baujahr</th>
		<td class="EWestateView-default-section-table-value"><?=$offerdetails->constructionYear; ?></td>
	</tr>
<?php } ?>

<?php if($offerdetails->additionalCosts>0){ ?>
	<tr>
		<th scope="row" class="EWestateView-default-section-table-label">Nebenkosten</th>
		<td class="EWestateView-default-section-table-value"><?=number_format($offerdetails->additionalCosts,0,",","."); ?> &euro;</td>
	</tr>
<?php } ?>

<?php if($offerdetails->deposit!=''){ ?>
	<tr>
		<th scope="row" class="EWestateView-default-section-table-label">Kaution</th>
		<td class="EWestateView-default-section-table-value"><?=$offerdetails->deposit; ?></td>
	</tr>
<?php } ?>

<?php if(($offer->type == 1)&&($offerdetails->baseRent!=0)){ ?>
	<tr>
		<th scope="row" class="EWestateView-default-section-table-label">Kaltmiete</th>
		<td class="EWestateView-default-section-table-value"><?=number_format($offerdetails->baseRent,0,",","."); ?> &euro;</td>
	</tr>
<?php } ?>
<?php if(($offer->type == 1)&&($offerdetails->serviceCharge!=0)){ ?>
	<tr>
		<th scope="row" class="EWestateView-default-section-table-label">Nebenkosten</th>
		<td class="EWestateView-default-section-table-value"><?=number_format($offerdetails->serviceCharge ,0,",","."); ?> &euro;</td>
	</tr>
<?php } ?>
<?php if(($offer->type == 1)&&($offerdetails->heatingCosts!=0)){ ?>
	<tr>
		<th scope="row" class="EWestateView-default-section-table-label">Heizkosten</th>
		<td class="EWestateView-default-section-table-value"><?=number_format($offerdetails->heatingCosts,0,",","."); ?> &euro;</td>
	</tr>
<?php } ?>
<?php if(($offer->type == 1)&&($offerdetails->totalRent!=0)){ ?>
	<tr>
		<th scope="row" class="EWestateView-default-section-table-label">Gesamtmiete</th>
		<td class="EWestateView-default-section-table-value"><?=number_format($offerdetails->totalRent,0,",","."); ?> &euro;</td>
	</tr>
<?php } ?>

<?php if($offerdetails->freeFrom != ''){ ?>
	<tr>
		<th scope="row" class="EWestateView-default-section-table-label">Verfügbar ab</th>
		<td class="EWestateView-default-section-table-value"><?=$offerdetails->freeFrom; ?></td>
	</tr>
<?php } ?>

<?php if($offerdetails->condition != 'NO_INFORMATION'){ ?>
	<tr>
		<th scope="row" class="EWestateView-default-section-table-label">Objektzustand</th>
		<td class="EWestateView-default-section-table-value"><?=ExpowandDictionary::$condition_options[$offerdetails->condition]; ?></td>
	</tr>
<?php } ?>

<?php if($offerdetails->interiorQuality != 'NO_INFORMATION'){ ?>
	<tr>
		<th scope="row" class="EWestateView-default-section-table-label">Qualität der Ausstattung</th>
		<td class="EWestateView-default-section-table-value"><?=ExpowandDictionary::$interiorQuality_options[$offerdetails->interiorQuality]; ?></td>
	</tr>
<?php } ?>

<?php if($offerdetails->numberOfParkingSpaces > 0){ ?>
	<tr>
		<th scope="row" class="EWestateView-default-section-table-label">Anzahl Stellplätze</th>
		<td class="EWestateView-default-section-table-value"><?=$offerdetails->numberOfParkingSpaces; ?></td>
	</tr>
<?php } ?>

	</tbody>
</table>

<?php if(!empty($offerdetails->descriptionNote)){ ?>
	<h3 class="EWestateView-default-section-label">Beschreibung</h3>
	<p class="EWestateView-default-section-text"><?=str_ireplace(PHP_EOL, '<br />', $offerdetails->descriptionNote); ?></p>
<?php } ?>

<h3 class="EWestateView-default-section-label">Fläche</h3>
<table class="table EWestateView-default-section-table">
    <tbody>
			
	<?php if($offerdetails->livingSpace > 0){ ?>
		<tr>
			<?php if($offer->category == 'OFFICE'){ // TODO: check if correct ?>
				<th scope="row" class="EWestateView-default-section-table-label">Bürofläche</th>
			<?php }else{ ?>
				<th scope="row" class="EWestateView-default-section-table-label">Wohnfläche</th>
			<?php } ?>
			<td class="EWestateView-default-section-table-value">ca. <?=intval($offerdetails->livingSpace); ?> m²</td>
		</tr>
	<?php } ?>

	<?php if($offerdetails->totalFloorSpace > 0){ ?>
		<tr>
			<th scope="row" class="EWestateView-default-section-table-label">Gesamtfläche</th>
			<td class="EWestateView-default-section-table-value">ca. <?=intval($offerdetails->totalFloorSpace); ?> m²</td>
		</tr>
	<?php } ?>

	<?php if(($offer->category == 'OFFICE' || $offer->category == 'INDUSTRY' || $offer->category == 'STORE') && $offerdetails->netFloorSpace > 0){ ?>
		<tr>
			<?php if($offer->category == 'OFFICE'){ ?>
				<th scope="row" class="EWestateView-default-section-table-label">Büro-/ Praxisfläche</th>
			<?php }else if($offer->category == 'INDUSTRY'){ ?>
				<th scope="row" class="EWestateView-default-section-table-label">Lager-/ Produktionsfläche</th>
			<?php }else if($offer->category == 'STORE'){ ?>
				<th scope="row" class="EWestateView-default-section-table-label">Verkaufsfläche</th>
			<?php } ?>
			<td class="EWestateView-default-section-table-value">ca. <?=intval($offerdetails->netFloorSpace); ?> m²</td>
		</tr>
	<?php } ?>

	<?php if(($offer->category=='HOUSE' || $offer->category=='LIVING_SITE') && $offerdetails->plotArea > 0){ ?>
		<tr>
			<th scope="row" class="EWestateView-default-section-table-label">Grundstücksfläche</th>
			<td class="EWestateView-default-section-table-value">ca. <?=intval($offerdetails->plotArea); ?> m²</td>
		</tr>
	<?php } ?>

	<?php if($offerdetails->additionalArea > 0){ ?>
		<tr>
			<th scope="row" class="EWestateView-default-section-table-label">Nebenfläche</th>
			<td class="EWestateView-default-section-table-value">ca. <?=intval($offerdetails->additionalArea); ?> m²</td>
		</tr>
	<?php } ?>

	<?php if($offer->category=='LIVING_SITE' && $offerdetails->minDivisible > 0){ ?>
		<tr>
			<th scope="row" class="EWestateView-default-section-table-label">Fläche teilbar ab</th>
			<td class="EWestateView-default-section-table-value">ca. <?=intval($offerdetails->minDivisible); ?> m²</td>
		</tr>
	<?php } ?>

	<?php if($offerdetails->shopWindowLength > 0){ ?>
		<tr>
			<th scope="row" class="EWestateView-default-section-table-label">Schaufensterfront</th>
			<td class="EWestateView-default-section-table-value"><?=removeLastTwoZero($offerdetails->shopWindowLength); ?> m</td>
		</tr>
	<?php } ?>

	<?php if($offerdetails->hallHeight > 0){ ?>
		<tr>
			<th scope="row" class="EWestateView-default-section-table-label">Hallen-/ Geschosshöhe</th>
			<td class="EWestateView-default-section-table-value"><?=removeLastTwoZero($offerdetails->hallHeight); ?> m</td>
		</tr>
	<?php } ?>

	<?php if($offerdetails->numberOfRooms > 0){ ?>
		<tr>
			<th scope="row" class="EWestateView-default-section-table-label">Zimmer</th>
			<td class="EWestateView-default-section-table-value"><?=removeLastTwoZero($offerdetails->numberOfRooms); ?></td>
		</tr>
	<?php } ?>

	<?php if($offer->category != 'OFFICE'){ ?>

		<?php if($offerdetails->numberOfBedRooms > 0){ ?>
			<tr>
				<th scope="row" class="EWestateView-default-section-table-label">Anzahl Schlafzimmer</th>
				<td class="EWestateView-default-section-table-value"><?=removeLastTwoZero($offerdetails->numberOfBedRooms); ?></td>
			</tr>
		<?php } ?>

		<?php if($offerdetails->numberOfBathRooms > 0){ ?>
			<tr>
				<th scope="row" class="EWestateView-default-section-table-label">Anzahl Badezimmer</th>
				<td class="EWestateView-default-section-table-value"><?=removeLastTwoZero($offerdetails->numberOfBathRooms); ?></td>
			</tr>
		<?php } ?>

	<?php } ?>

	<?php if(($offerdetails->numberOfFloors > 1)&&($offer->category=='APARTMENT')&&($offerdetails->floor!='')){ ?>
		<tr>
			<th scope="row" class="EWestateView-default-section-table-label">Etage</th>
			<td class="EWestateView-default-section-table-value">
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
			<th scope="row" class="EWestateView-default-section-table-label">Etagenzahl insgesamt</th>
			<td class="EWestateView-default-section-table-value"><?=removeLastTwoZero($offerdetails->numberOfFloors); ?></td>
		</tr>
	<?php } ?>	

	</tbody>
</table>

<?php if(!empty($offer->type == 0)){ ?> 
	<h3 class="EWestateView-default-section-label">Preis</h3>
	<table class="table EWestateView-default-section-table">
		<tbody>
			<tr>
				<th scope="row" class="EWestateView-default-section-table-label">Kaufpreis</th>
				<td class="EWestateView-default-section-table-value"><?=number_format($offer->immoprice,0,",","."); ?> €</td>
			</tr>
			<tr>
				<th scope="row" class="EWestateView-default-section-table-label">Grunderwerbsteuer</th>
				<td class="EWestateView-default-section-table-value"><?=number_format($offer->grunderwerbsteuer,1,",","."); ?> %</td>
			</tr>
			<tr>
				<th scope="row" class="EWestateView-default-section-table-label">Maklerprovision</th>
				<td class="EWestateView-default-section-table-value"><?=number_format($offer->maklerprovision,2,",","."); ?> %</td>
			</tr>
			<tr>
				<th scope="row" class="EWestateView-default-section-table-label">Notarkosten/ Grundbucheintrag</th>
				<td class="EWestateView-default-section-table-value"><?=number_format($offer->notargebuhren,1,",","."); ?> %</td>
			</tr>	
			<tr>
				<th scope="row" class="EWestateView-default-section-table-label">Käuferprovision</th>
				<td class="EWestateView-default-section-table-value"><?=str_replace('.',',',$offer->maklerprovision+0) ?> % (inkl. MwSt.) vom Kaufpreis</td>
			</tr>
		</tbody>
	</table>

	<p class="EWestateView-default-price-descr">
		Vertragsgrundlage unserer Leistung sind unsere Allgemeinen Geschäftsbedingungen.
	</p>
	<p class="EWestateView-default-price-descr">
		Dies ist der aktuelle Angebotskaufpreis. Wir weisen darauf hin, dass dieser Angebotskaufpreis 
		fallen oder bei erheblicher Nachfrage nach Objekten dieser Art auch steigen kann.
	</p>
	<p class="EWestateView-default-price-descr">
		Die Maklercourtage beträgt <?=str_replace('.',',',$offer->maklerprovision+0) ?> % inkl. der gesetzlichen Mehrwertsteuer.
		Sie ist verdient und fällig bei Abschluss eines notariellen Kaufvertrages und vom Käufer zu zahlen.
		Grunderwerbssteuer, Notar- und Gerichtskosten sind vom Käufer zu tragen.
		Im Übrigen gelten unsere Allgemeinen Geschäftsbedingungen.
		Irrtum und Zwischenverkauf vorbehalten.
	</p>
<?php } ?>

<?php if(!empty($offerdetails->furnishingNote)){ ?>
	<h3 class="EWestateView-default-section-label">Ausstattung</h3>
<?php } ?>
<div class="row mt-1 mb-3 g-3">

	<?php if($offerdetails->balcony != 0){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light shadow EWestateView-default-tag">Balkon</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->terrace != 0){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light shadow EWestateView-default-tag">Terrasse</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->lift != 0){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light shadow EWestateView-default-tag">Personenaufzug</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->ramp == 'YES'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light shadow EWestateView-default-tag">Rampe</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->autoLift == 'YES'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light shadow EWestateView-default-tag">Hebebühne</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->goodsLift == 'YES'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light shadow EWestateView-default-tag">
				Lastenaufzug <?=($offerdetails->goodsLiftLoad>0)?removeLastTwoZero($offerdetails->goodsLiftLoad).' kg':''; ?>
			</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->craneRunway == 'YES'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light shadow EWestateView-default-tag">
				Kranbahn <?=($offerdetails->craneRunwayLoad>0)?removeLastTwoZero($offerdetails->craneRunwayLoad).' tonnen':''; ?>
			</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->floorLoad > 0){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light shadow EWestateView-default-tag">
				Bodenbelastung <?=removeLastTwoZero($offerdetails->floorLoad); ?> kg/m²
			</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->connectedLoad > 0){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light shadow EWestateView-default-tag">
				Stromanschlusswert <?=removeLastTwoZero($offerdetails->connectedLoad); ?> kVA
			</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->garden != 0){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light shadow EWestateView-default-tag">Garten / -mitbenutzung</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->builtInKitchen != 0){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light shadow EWestateView-default-tag">Einbauküche</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->cellar != 'NOT_APPLICABLE'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light shadow EWestateView-default-tag">Keller</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->guestToilet != 'NOT_APPLICABLE'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light shadow EWestateView-default-tag">Gäste-WC</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->hasCanteen == 'YES'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light shadow EWestateView-default-tag">Kantine/ Cafeteria</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->kitchenComplete == 'YES'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light shadow EWestateView-default-tag">Küche vorhanden</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->highVoltage == 'YES'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light shadow EWestateView-default-tag">Starkstrom</span>
		</div>
	<?php } ?>
	
	<?php if($offerdetails->handicappedAccessible == 'YES'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light shadow EWestateView-default-tag">Barrierefrei</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->lanCables == 'YES' || $offerdetails->lanCables == 'BY_APPOINTMENT'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light shadow EWestateView-default-tag">DV-Verkabelung vorhanden</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->airConditioning == 'YES' || $offerdetails->airConditioning == 'BY_APPOINTMENT'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light shadow EWestateView-default-tag">Klimaanlage</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->flooringType != 'NO_INFORMATION'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light shadow EWestateView-default-tag">Bodenbelag: <?=ExpowandDictionary::$flooringType_options[$offerdetails->flooringType]; ?>
		</div>
	<?php } ?>

	<?php if($offerdetails->supplyType != 'NO_INFORMATION'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-light shadow EWestateView-default-tag">Zulieferung: <?=ExpowandDictionary::$supplyType_options[$offerdetails->supplyType]; ?>
		</div>
	<?php } ?>

</div>
<p class="EWestateView-default-section-text"><?=str_ireplace(PHP_EOL, '<br />', $offerdetails->furnishingNote); ?></p>

<h3 class="EWestateView-default-section-label">Energie</h3>
<table class="table EWestateView-default-section-table">
    <tbody>

		<tr>
			<th scope="row" class="EWestateView-default-section-table-label">Energieausweis</th>
			<td class="EWestateView-default-section-table-value"><?=ExpowandDictionary::$energyCertificateAvailability_options[$offerdetails->energyCertificateAvailability]; ?></td>
		</tr>

		<?php if($offerdetails->energyCertificateCreationDate != 'NO_INFORMATION'){ ?>
			<tr>
				<th scope="row" class="EWestateView-default-section-table-label">Erstellungsdatum</th>
				<td class="EWestateView-default-section-table-value"><?=ExpowandDictionary::$energyCertificateCreationDate_options[$offerdetails->energyCertificateCreationDate]; ?></td>
			</tr>
		<?php } ?>

		<?php if($offerdetails->energyCertificateValidTill != '' && $offerdetails->energyCertificateValidTill != '0000-00-00'  && $offerdetails->energyCertificateValidTill != '1970-01-01'){ ?>
			<tr>
				<th scope="row" class="EWestateView-default-section-table-label">Gültig bis</th>
				<td class="EWestateView-default-section-table-value"><?=date('d.m.Y', strtotime($offerdetails->energyCertificateValidTill)); ?></td>
			</tr>
		<?php } ?>

		<?php if($offerdetails->energyCertificateAvailability == 'AVAILABLE' && $offerdetails->buildingEnergyRatingType != 'NO_INFORMATION'){ ?>
			<tr>
				<th scope="row" class="EWestateView-default-section-table-label">Energieausweistyp</th>
				<td class="EWestateView-default-section-table-value"><?=ExpowandDictionary::$buildingEnergyRatingType_options[$offerdetails->buildingEnergyRatingType]; ?></td>
			</tr>
		<?php } ?>

		<?php if($offerdetails->energyCertificateAvailability == 'AVAILABLE' && !empty($offerdetails->thermalCharacteristic)){ ?>
			<tr>
				<?php if($offerdetails->buildingEnergyRatingType == 'ENERGY_REQUIRED'){ ?>
					<th scope="row" class="EWestateView-default-section-table-label">Endenergiebedarf</th>
				<?php }else if($offerdetails->buildingEnergyRatingType == 'ENERGY_CONSUMPTION'){ ?>
					<th scope="row" class="EWestateView-default-section-table-label">Endenergieverbrauch</th>
				<?php } ?>
				<td class="EWestateView-default-section-table-value"><?=number_format($offerdetails->thermalCharacteristic, 2, ',', '.'); ?> kWh/(m²*a)</td>
			</tr>
		<?php } ?>

		<?php if($offerdetails->energyCertificateCreationDate == 'FROM_01_MAY_2014' && $offerdetails->energyEfficiencyClass != 'NOT_APPLICABLE'){ ?>
			<tr>
				<th scope="row" class="EWestateView-default-section-table-label">Energieeffizienzklasse</th>
				<td class="EWestateView-default-section-table-value"><?=ExpowandDictionary::$energyEfficiencyClass_options[$offerdetails->energyEfficiencyClass]; ?></td>
			</tr>
		<?php } ?>

		<?php if($offerdetails->buildingEnergyRatingType == 'ENERGY_CONSUMPTION' && $offerdetails->energyCertificateCreationDate == 'BEFORE_01_MAY_2014' && $offerdetails->energyConsumptionContainsWarmWater != 'NOT_APPLICABLE'){ ?>
			<tr>
				<th scope="row" class="EWestateView-default-section-table-label">Energieverbrauch für Warmwasser enthalten</th>
				<td class="EWestateView-default-section-table-value"><?=ExpowandDictionary::YN_ARR[$offerdetails->energyConsumptionContainsWarmWater]; ?></td>
			</tr>
		<?php } ?>

		<?php if($offerdetails->firingTypes != 'NO_INFORMATION'){ ?>
			<tr>
				<th scope="row" class="EWestateView-default-section-table-label">Wesentlicher Energieträger</th>
				<td class="EWestateView-default-section-table-value"><?=ExpowandDictionary::$firingTypes_options[$offerdetails->firingTypes]; ?></td>
			</tr>
		<?php } ?>

		<?php if(!empty($offerdetails->legalConstructionYear)){ ?>
			<tr>
				<th scope="row" class="EWestateView-default-section-table-label">Baujahr laut Energieausweis</th>
				<td class="EWestateView-default-section-table-value"><?=$offerdetails->legalConstructionYear; ?></td>
			</tr>
		<?php } ?>

		<?php if($offerdetails->heatingType != 'NO_INFORMATION'){ ?>
			<tr>
				<th scope="row" class="EWestateView-default-section-table-label">Heizungsart</th>
				<td class="EWestateView-default-section-table-value"><?=ExpowandDictionary::$heatingType_options[$offerdetails->heatingType]; ?></td>
			</tr>
		<?php } ?>

	</tbody>
</table>

<?php if(!empty($offerdetails->otherNote)){ ?>
	<h3 class="EWestateView-default-section-label">Sonstiges</h3>
	<p class="EWestateView-default-section-text"><?=str_ireplace(PHP_EOL, '<br />', $offerdetails->otherNote); ?></p>
<?php } ?>

<?php if(count((array) $offerdetails->pictures) > 1){ ?>
	<h3 class="EWestateView-default-section-label">Bilder</h3>
	<div class="row g-3">
		<?php foreach($offerdetails->pictures as $pic){ 
			$pic_url = $pics_url.$offer->id.'/'.$pic->filename; ?>
			<a href="<?=$pic_url; ?>" data-toggle="lightbox" data-gallery="example-gallery" class="col-md-4" data-caption="<?=$pic->caption; ?>">
				<img src="<?=$pic_url; ?>" class="img-fluid mx-auto d-block rounded-3 EWestateView-default-img" alt="<?=$pic->caption; ?>" title="<?=$pic->caption; ?>" >
			</a>
		<?php } ?>
	</div>
<?php } ?>

<?php if(!empty($offerdetails->locationNote)){ ?>
	<h3 class="EWestateView-default-section-label">Lage</h3>
	<div class="row mt-1 mb-3 g-3">
		<?php if($offerdetails->distanceToPT != 0){ ?>
			<div class="col-auto text-center">
				<span class="rounded-3 bg-light shadow EWestateView-default-tag">Laufzeit zum Öffentl. Personennahverkehr <?=$offerdetails->distanceToPT; ?> Min.</span>
			</div>
		<?php } ?>

		<?php if($offerdetails->distanceToMRS != 0){ ?>
			<div class="col-auto text-center">
				<span class="rounded-3 bg-light shadow EWestateView-default-tag">Fahrzeit zum nächsten Bahnhof <?=$offerdetails->distanceToMRS; ?> Min.</span>
			</div>
		<?php } ?>

		<?php if($offerdetails->distanceToFM != 0){ ?>
			<div class="col-auto text-center">
				<span class="rounded-3 bg-light shadow EWestateView-default-tag">Fahrzeit zur nächsten Autobahn <?=$offerdetails->distanceToFM; ?> Min.</span>
			</div>
		<?php } ?>

		<?php if($offerdetails->distanceToAirport != 0){ ?>
			<div class="col-auto text-center">
				<span class="rounded-3 bg-light shadow EWestateView-default-tag">Fahrzeit zum nächsten Flughafen <?=$offerdetails->distanceToAirport; ?> Min.</span>
			</div>
		<?php } ?>
	</div>
	<p class="EWestateView-default-section-text"><?=str_ireplace(PHP_EOL, '<br />', $offerdetails->locationNote); ?></p>
<?php } ?>

<?php include_once 'openstreet-map.php'; ?>

<?php include_once 'agent-details.php'; ?>

<?php include_once 'inquiry-form.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bs5-lightbox@1.8.3/dist/index.bundle.min.js"></script>
