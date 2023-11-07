<?php 
$offer = $results->offer;
$offerdetails = $results->offerdetails;
function removeLastTwoZero($number){
	$number_int = intval($number);
	if($number_int == $number)return $number_int;
	return number_format($number ,1,",","");
}
?>

<div id="carouselExampleCaptions" class="carousel slide vw-100" data-bs-ride="carousel">
	<div class="carousel-indicators">
		<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
		<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
		<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
	</div>
	<div class="carousel-inner">
		<?php foreach($offerdetails->pictures as $pic){ 
			$filename = $pic->filename;
			// $filename = str_replace('.jpg', '_gal.jpg', $filename);
			$pic_url = 'http://work-expowand-dev.local/www/pictures/'.$offer->id.'/'.$filename;
			?>
			<div class="carousel-item <?=($pic->main==1?'active':''); ?>">
				<img src="<?=$pic_url; ?>" class="d-block w-100" alt="<?=$pic->caption; ?>">
				<div class="carousel-caption d-none d-md-block">
					<!-- <h5><?=$offer->name; ?></h5> -->
					<p><?=$pic->caption; ?></p>
				</div>
			</div>
		<?php } ?>
	</div>
	<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
		<span class="carousel-control-prev-icon" aria-hidden="true"></span>
		<span class="visually-hidden">Previous</span>
	</button>
	<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
		<span class="carousel-control-next-icon" aria-hidden="true"></span>
		<span class="visually-hidden">Next</span>
	</button>
</div>

<h3>Eckdaten</h3>
<table class="table mt-1">
    <thead>
        <tr>
            <th class="w-50" style="width: 50%;"></th>
            <th class="w-50" style="width: 50%"></th>
        </tr>
    </thead>

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
		<th scope="row" style="text-align: left;">Immobilientyp</th>
		<td><?=$typestr; ?></td>
	</tr>
<?php } ?>

<?php if($offerdetails->shortTermConstructible == 'YES'){ ?>
	<tr>
		<th scope="row" style="text-align: left;">Kurzfristig bebaubar</th>
		<td>Erlaubt</td>
	</tr>
<?php } ?>

<?php if($offerdetails->buildingPermission == 'YES'){ ?>
	<tr>
		<th scope="row" style="text-align: left;">Baugenehmigung</th>
		<td>Verfügbar</td>
	</tr>
<?php } ?>

<?php if($offerdetails->demolition == 'YES'){ ?>
	<tr>
		<th scope="row" style="text-align: left;">Abriss</th>
		<td>Erlaubt</td>
	</tr>
<?php } ?>

<?php if(!empty($offerdetails->constructionYear)){ ?>
	<tr>
		<th scope="row" style="text-align: left;">Baujahr</th>
		<td><?=$offerdetails->constructionYear; ?></td>
	</tr>
<?php } ?>

<?php if($offerdetails->additionalCosts>0){ ?>
	<tr>
		<th scope="row" style="text-align: left;">Nebenkosten</th>
		<td><?=$offerdetails->additionalCosts; ?> &euro;</td>
	</tr>
<?php } ?>

<?php if($offerdetails->deposit!=''){ ?>
	<tr>
		<th scope="row" style="text-align: left;">Kaution</th>
		<td><?=$offerdetails->deposit; ?></td>
	</tr>
<?php } ?>

<?php if(($offer->type == 1)&&($offerdetails->baseRent!=0)){ ?>
	<tr>
		<th scope="row" style="text-align: left;">Kaltmiete</th>
		<td><?=number_format($offerdetails->baseRent,2,",","."); ?> &euro;</td>
	</tr>
<?php } ?>
<?php if(($offer->type == 1)&&($offerdetails->serviceCharge!=0)){ ?>
	<tr>
		<th scope="row" style="text-align: left;">Nebenkosten</th>
		<td><?=number_format($offerdetails->serviceCharge ,2,",","."); ?> &euro;</td>
	</tr>
<?php } ?>
<?php if(($offer->type == 1)&&($offerdetails->heatingCosts!=0)){ ?>
	<tr>
		<th scope="row" style="text-align: left;">Heizkosten</th>
		<td><?=number_format($offerdetails->heatingCosts,2,",","."); ?> &euro;</td>
	</tr>
<?php } ?>
<?php if(($offer->type == 1)&&($offerdetails->totalRent!=0)){ ?>
	<tr>
		<th scope="row" style="text-align: left;">Gesamtmiete</th>
		<td><?=number_format($offerdetails->totalRent,2,",","."); ?> &euro;</td>
	</tr>
<?php } ?>

<?php if($offerdetails->freeFrom != ''){ ?>
	<tr>
		<th scope="row" style="text-align: left;">Verfügbar ab</th>
		<td><?=$offerdetails->freeFrom; ?></td>
	</tr>
<?php } ?>

<?php if($offerdetails->condition != 'NO_INFORMATION'){ ?>
	<tr>
		<th scope="row" style="text-align: left;">Objektzustand</th>
		<td><?=ExpowandDictionary::$condition_options[$offerdetails->condition]; ?></td>
	</tr>
<?php } ?>

<?php if($offerdetails->interiorQuality != 'NO_INFORMATION'){ ?>
	<tr>
		<th scope="row" style="text-align: left;">Qualität der Ausstattung</th>
		<td><?=ExpowandDictionary::$interiorQuality_options[$offerdetails->interiorQuality]; ?></td>
	</tr>
<?php } ?>

<?php if($offerdetails->numberOfParkingSpaces > 0){ ?>
	<tr>
		<th scope="row" style="text-align: left;">Anzahl Stellplätze</th>
		<td><?=$offerdetails->numberOfParkingSpaces; ?></td>
	</tr>
<?php } ?>

	</tbody>
</table>

<?php if(!empty($offerdetails->descriptionNote)){ ?>
	<h2 class="">Beschreibung</h2>
	<p class="text-justify"><?=$offerdetails->descriptionNote;; ?></p>
<?php } ?>

<h2 class="">Fläche</h2>
<div class="row gy-3">
	
	<?php if($offerdetails->livingSpace > 0){ ?>
		<div class="col-6 col-md-4 col-lg-3 text-center">
			<span class="fas fa-expand-arrows-alt text-info light fs-3 "></span>
			<?php if($offer->category == 'OFFICE'){ // TODO: check if correct?>
				<h4 class="mt-1">ca. <?=intval($offerdetails->livingSpace); ?> m²</h4>
				<p>Bürofläche</p>
			<?php }else{ ?>
				<h4 class="mt-1">ca. <?=intval($offerdetails->livingSpace); ?> m²</h4>
				<p>Wohnfläche</p>
			<?php } ?>
		</div>
	<?php } ?>

	<?php if($offerdetails->totalFloorSpace > 0){ ?>
		<div class="col-6 col-md-4 col-lg-3 text-center">
			<span class="fas fa-expand-arrows-alt text-info light fs-3 "></span>
			<h4 class="mt-1">ca. <?=intval($offerdetails->totalFloorSpace); ?> m²</h4>
			<p>Gesamtfläche</p>
		</div>
	<?php } ?>

	<?php if($offerdetails->netFloorSpace > 0){ ?>
		<div class="col-6 col-md-4 col-lg-3 text-center">
			<span class="fas fa-expand-arrows-alt text-info light fs-3 "></span>
			<h4 class="mt-1">ca. <?=intval($offerdetails->netFloorSpace); ?> m²</h4>
			<?php if($offer->category == 'OFFICE'){ ?>
				<p>Büro-/Praxisfläche</p>
			<?php }else if($offer->category == 'INDUSTRY'){ ?>
				<p>Lager-/ Produktionsfläche</p>
			<?php }else if($offer->category == 'STORE'){ ?>
				<p>Verkaufsfläche</p>
			<?php } ?>
		</div>
	<?php } ?>

	<?php if(($offer->category=='HOUSE' || $offer->category=='LIVING_SITE') && $offerdetails->plotArea > 0){ ?>
		<div class="col-6 col-md-4 col-lg-3 text-center">
			<span class="fas fa-drafting-compass text-info light fs-3 "></span>
			<h4 class="mt-1">ca. <?=intval($offerdetails->plotArea); ?> m²</h4>
			<p>Grundstücksfläche</p>
		</div>
	<?php } ?>

	<?php if($offerdetails->additionalArea > 0){ ?>
		<div class="col-6 col-md-4 col-lg-3 text-center">
			<span class="fas fa-expand-arrows-alt text-info light fs-3 "></span>
			<h4 class="mt-1">ca. <?=intval($offerdetails->additionalArea); ?> m²</h4>
			<p>Nebenfläche</p>
		</div>
	<?php } ?>

	<?php if($offer->category=='LIVING_SITE' && $offerdetails->minDivisible > 0){ ?>
		<div class="col-6 col-md-4 col-lg-3 text-center">
			<span class="fas fa-expand-arrows-alt text-info light fs-3 "></span>
			<h4 class="mt-1">ca. <?=intval($offerdetails->minDivisible); ?> m²</h4>
			<p>Fläche teilbar ab</p>
		</div>
	<?php } ?>

	<?php if($offerdetails->shopWindowLength > 0){ ?>
		<div class="col-6 col-md-4 col-lg-3 text-center">
			<span class="fas fa-ruler-horizontal text-info light fs-3 "></span>
			<h4 class="mt-1"><?=removeLastTwoZero($offerdetails->shopWindowLength); ?> m</h4>
			<p>Schaufensterfront</p>
		</div>
	<?php } ?>

	<?php if($offerdetails->hallHeight > 0){ ?>
		<div class="col-6 col-md-4 col-lg-3 text-center">
			<span class="fas fa-ruler-vertical text-info light fs-3 "></span>
			<h4 class="mt-1"><?=removeLastTwoZero($offerdetails->hallHeight); ?> m</h4>
			<p>Hallen-/Geschosshöhe</p>
		</div>
	<?php } ?>

	<?php if($offerdetails->numberOfRooms > 0){ ?>
		<div class="col-6 col-md-4 col-lg-3 text-center">
			<span class="fas fa-door-open text-info light fs-3 "></span>
			<h4 class="mt-1"><?=$offerdetails->numberOfRooms; ?></h4>
			<p>Zimmer</p>
		</div>
	<?php } ?>

	<?php if($offer->category != 'OFFICE'){ ?>

		<?php if($offerdetails->numberOfBedRooms > 0){ ?>
			<div class="col-6 col-md-4 col-lg-3 text-center">
				<span class="fas fa-bed text-info light fs-3 "></span>
				<h4 class="mb-2 mt-2"><?=removeLastTwoZero($offerdetails->numberOfBedRooms); ?></h4>
				<p>Anzahl Schlafzimmer</p>
			</div>
		<?php } ?>

		<?php if($offerdetails->numberOfBathRooms > 0){ ?>
			<div class="col-6 col-md-4 col-lg-3 text-center">
				<span class="fas fa-bath text-info light fs-3 "></span>
				<h4 class="mb-2 mt-2"><?=removeLastTwoZero($offerdetails->numberOfBathRooms); ?></h4>
				<p>Anzahl Badezimmer</p>
			</div>
		<?php } ?>

	<?php } ?>

	<?php if(($offerdetails->numberOfFloors > 1)&&($offer->category=='APARTMENT')&&($offerdetails->floor!='')){ ?>
		<div class="col-6 col-md-4 col-lg-3 text-center">
			<span class="fas fa-stairs text-info light fs-3 "></span>
			<h4 class="mb-2 mt-2">
				<?php if($offerdetails->floor == 0){ ?>
					EG
				<?php }else{ ?>
					<?=$offerdetails->floor; ?>. OG
				<?php } ?>
			</h4>
			<p>Etage</p>
		</div>
	<?php } ?>

	<?php if($offerdetails->numberOfFloors > 0){ ?>
		<div class="col-6 col-md-4 col-lg-3 text-center">
			<span class="fas fa-building text-info light fs-3 "></span>
			<h4 class="mb-2 mt-2"><?=$offerdetails->numberOfFloors; ?></h4>
			<p>Etagenzahl insgesamt</p>
		</div>	
	<?php } ?>	

</div>

<h2 class="lh-base mb-3">Preis</h2>
<div class="row">
	<div class="col-12 col-md-6">
		<div class="d-flex align-items-center mb-1">
			<p class="mb-0 fw-semi-bold text-900 lh-sm flex-1"><span class="d-inline-block bg-info-300 bullet-item me-2"></span> Kaufpreis</p>
			<h5 class="mb-0 text-900 text-end w-50"><?=number_format($offer->immoprice,0,",","."); ?> €</h5>
		</div>
		<div class="d-flex align-items-center mb-1 justify-content-between">
			<p class="mb-0 fw-semi-bold text-900 lh-sm w-50 text-start"><span class="d-inline-block bg-warning-300 bullet-item me-2"></span> Grunderwerbsteuer</p>
			<h6 class="mb-0 text-700 text-end w-25"><?=number_format($offer->grunderwerbsteuer,1,",","."); ?> %</h6>
		</div>
		<div class="d-flex align-items-center mb-1 justify-content-between">
			<p class="mb-0 fw-semi-bold text-900 lh-sm w-50 text-start"><span class="d-inline-block bg-danger-300 bullet-item me-2"></span> Maklerprovision</p>
			<h6 class="mb-0 text-700 text-end w-25"><?=number_format($offer->maklerprovision,2,",","."); ?> %</h6>
		</div>
		<div class="d-flex align-items-center mb-1 justify-content-between text-end">
			<p class="mb-0 fw-semi-bold text-900 lh-sm w-50 text-start"><span class="d-inline-block bg-success-300 bullet-item me-2"></span> Notarkosten/Grundbucheintrag</p>
			<h6 class="mb-0 text-700 text-end w-25"><?=number_format($offer->notargebuhren,1,",","."); ?> %</h6>
		</div>
	
		<p class="mt-2 mt-md-7"><small><i class="fa fa-exclamation-circle fs-0" aria-hidden="true"></i> Vertragsgrundlage unserer Leistung sind unsere Allgemeinen Geschäftsbedingungen.</small></p>

		<p class="text-700 mt-md-7 mb-md-7">Dies ist der aktuelle Angebotskaufpreis. Wir weisen darauf hin, dass dieser Angebotskaufpreis fallen oder bei erheblicher Nachfrage nach Objekten dieser Art auch steigen kann.</p>
		
		<p class="text-700 lh-sm flex-1">
			<span class="fa fa-exclamation-circle" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#exampleModal"></span>
			Käuferprovision: 
			<?=str_replace('.',',',$offer->maklerprovision+0) ?> % (inkl. MwSt.) vom Kaufpreis
		</p>
		<h5 class="modal-title" id="exampleModalLabel">Käuferprovision</h5>
		<p class="text-700 lh-lg mb-0">
			Die Maklercourtage beträgt <?=str_replace('.',',',$offer->maklerprovision+0) ?> % inkl. der gesetzlichen Mehrwertsteuer.
			Sie ist verdient und fällig bei Abschluss eines notariellen Kaufvertrages und vom Käufer zu zahlen.
			Grunderwerbssteuer, Notar- und Gerichtskosten sind vom Käufer zu tragen.
			Im Übrigen gelten unsere Allgemeinen Geschäftsbedingungen.
			Irrtum und Zwischenverkauf vorbehalten.
			<?php /* <?=$agent->firma; ?>, <?=$agent->street; ?>, <?=$agent->zip; ?> <?=$agent->city; ?> <?php if ($agent->ustid != ''){ ?>,  Ust. IdNr.: <?=$agent->ustid; ?><?php } ?>. */ ?>
		</p>
	</div>
</div>

<h2 class="">Ausstattung</h2>
<div class="row mt-1 mb-3 g-3">

	<?php if($offerdetails->balcony != 0){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-200 px-3 py-1 fw-bold shadow">Balkon</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->terrace != 0){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-200 px-3 py-1 fw-bold shadow">Terrasse</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->lift != 0){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-200 px-3 py-1 fw-bold shadow">Personenaufzug</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->ramp == 'YES'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-200 px-3 py-1 fw-bold shadow">Rampe</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->autoLift == 'YES'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-200 px-3 py-1 fw-bold shadow">Hebebühne</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->goodsLift == 'YES'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-200 px-3 py-1 fw-bold shadow">
				Lastenaufzug <?=($offerdetails->goodsLiftLoad>0)?removeLastTwoZero($offerdetails->goodsLiftLoad).' kg':''; ?>
			</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->craneRunway == 'YES'){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-200 px-3 py-1 fw-bold shadow">
			Kranbahn <?=($offerdetails->craneRunwayLoad>0)?removeLastTwoZero($offerdetails->craneRunwayLoad).' tonnen':''; ?>
			</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->floorLoad > 0){ ?>
		<div class="col-auto text-center">
			<span class="rounded-3 bg-200 px-3 py-1 fw-bold shadow">
			Bodenbelastung <?=removeLastTwoZero($offerdetails->floorLoad); ?> kg/m²
			</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->connectedLoad > 0){ ?>
		<div class="col-auto text-center">
		<span class="rounded-3 bg-200 px-3 py-1 fw-bold shadow">
			Stromanschlusswert <?=removeLastTwoZero($offerdetails->connectedLoad); ?> kVA
		</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->garden != 0){ ?>
		<div class="col-auto text-center">
		<span class="rounded-3 bg-200 px-3 py-1 fw-bold shadow">Garten / -mitbenutzung</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->builtInKitchen != 0){ ?>
		<div class="col-auto text-center">
		<span class="rounded-3 bg-200 px-3 py-1 fw-bold shadow">Einbauküche</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->cellar != 'NOT_APPLICABLE'){ ?>
		<div class="col-auto text-center">
		<span class="rounded-3 bg-200 px-3 py-1 fw-bold shadow">Keller</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->guestToilet != 'NOT_APPLICABLE'){ ?>
		<div class="col-auto text-center">
		<span class="rounded-3 bg-200 px-3 py-1 fw-bold shadow">Gäste-WC</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->hasCanteen == 'YES'){ ?>
		<div class="col-auto text-center">
		<span class="rounded-3 bg-200 px-3 py-1 fw-bold shadow">Kantine/ Cafeteria</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->kitchenComplete == 'YES'){ ?>
		<div class="col-auto text-center">
		<span class="rounded-3 bg-200 px-3 py-1 fw-bold shadow">Küche vorhanden</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->highVoltage == 'YES'){ ?>
		<div class="col-auto text-center">
		<span class="rounded-3 bg-200 px-3 py-1 fw-bold shadow">Starkstrom</span>
		</div>
	<?php } ?>
	
	<?php if($offerdetails->handicappedAccessible == 'YES'){ ?>
		<div class="col-auto text-center">
		<span class="rounded-3 bg-200 px-3 py-1 fw-bold shadow">Barrierefrei</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->lanCables == 'YES' || $offerdetails->lanCables == 'BY_APPOINTMENT'){ ?>
		<div class="col-auto text-center">
		<span class="rounded-3 bg-200 px-3 py-1 fw-bold shadow">DV-Verkabelung vorhanden</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->airConditioning == 'YES' || $offerdetails->airConditioning == 'BY_APPOINTMENT'){ ?>
		<div class="col-auto text-center">
		<span class="rounded-3 bg-200 px-3 py-1 fw-bold shadow">Klimaanlage</span>
		</div>
	<?php } ?>

	<?php if($offerdetails->flooringType != 'NO_INFORMATION'){ ?>
		<div class="col-auto text-center">
		<span class="rounded-3 bg-200 px-3 py-1 fw-bold shadow">Bodenbelag: <?=$offerdetails->getFlooringType(); ?>
		</div>
	<?php } ?>

	<?php if($offerdetails->supplyType != 'NO_INFORMATION'){ ?>
		<div class="col-auto text-center">
		<span class="rounded-3 bg-200 px-3 py-1 fw-bold shadow">Zulieferung: <?=$offerdetails->getSupplyType(); ?>
		</div>
	<?php } ?>

</div>
<p class="text-justify"><?=str_ireplace(PHP_EOL, '<br />', $offerdetails->furnishingNote); ?></p>

<h2 class="">Energie</h2>
<h4 class="mt-1"><?=ExpowandDictionary::$energyCertificateAvailability_options[$offerdetails->energyCertificateAvailability]; ?></h4>
<p>Gesetzliche Pflichtangaben Energieausweis</p>

	<?php if($offerdetails->energyCertificateAvailability == 'AVAILABLE' && $offerdetails->buildingEnergyRatingType != 'NO_INFORMATION'){ ?>
		<h4 class="mt-1"><?=ExpowandDictionary::$buildingEnergyRatingType_options[$offerdetails->buildingEnergyRatingType]; ?></h4>
		<p>Energieausweistyp</p>
	<?php } ?>

	<?php if($offerdetails->energyCertificateAvailability == 'AVAILABLE' && !empty($offerdetails->thermalCharacteristic)){ ?>
		<h4 class="mt-1"><?=$offerdetails->getThermalCharacteristicStr(); ?> kWh/(m²*a)</h4>
		<?php if($offerdetails->buildingEnergyRatingType == 'ENERGY_REQUIRED'){ ?>
			<p>Endenergiebedarf</p>
		<?php }else if($offerdetails->buildingEnergyRatingType == 'ENERGY_CONSUMPTION'){ ?>
			<p>Energieverbrauchskennwert</p>
		<?php } ?>
	<?php } ?>

	<?php if($offerdetails->energyCertificateCreationDate == 'FROM_01_MAY_2014' && $offerdetails->energyEfficiencyClass != 'NOT_APPLICABLE'){ ?>
		<h4 class="mt-1"><?=ExpowandDictionary::$energyEfficiencyClass_options[$offerdetails->energyEfficiencyClass]; ?></h4>
		<p>Energieeffizienzklasse</p>
	<?php } ?>

	<?php if($offerdetails->buildingEnergyRatingType == 'ENERGY_CONSUMPTION' && $offerdetails->energyCertificateCreationDate == 'BEFORE_01_MAY_2014' && $offerdetails->energyConsumptionContainsWarmWater != 'NOT_APPLICABLE'){ ?>
		<h4 class="mt-1"><?=ExpowandDictionary::YN_ARR[$offerdetails->energyConsumptionContainsWarmWater]; ?></h4>
		<p>Energieverbrauch für Warmwasser enthalten</p>
	<?php } ?>

	<?php if($offerdetails->firingTypes != 'NO_INFORMATION'){ ?>
		<h4 class="mt-1"><?=ExpowandDictionary::$firingTypes_options[$offerdetails->firingTypes]; ?></h4>
		<p>Wesentlicher Energieträger</p>
	<?php } ?>

	<?php if(!empty($offerdetails->legalConstructionYear)){ ?>
		<h4 class="mt-1"><?=$offerdetails->legalConstructionYear; ?></h4>
		<p>Baujahr laut Energieausweis</p>
	<?php } ?>

	<?php if($offerdetails->heatingType != 'NO_INFORMATION'){ ?>
		<h4 class="mt-1"><?=ExpowandDictionary::$heatingType_options[$offerdetails->heatingType]; ?></h4>
		<p>Heizungsart</p>
	<?php } ?>

<?php if(!empty($offerdetails->otherNote)){ ?>
	<h2 class="">Sonstiges</h2>
	<p class="text-justify"><?=str_ireplace(PHP_EOL, '<br />', $offerdetails->otherNote); ?></p>
<?php } ?>

<?php if(!empty($offerdetails->locationNote)){ ?>
	<h2 class="">Lage</h2>

	<div class="row mt-1 mb-3 g-3">

		<?php if($offerdetails->distanceToPT != 0){ ?>
			<div class="col-auto text-center">
				<span class="rounded-3 bg-200 px-3 py-1 fw-bold shadow">Laufzeit zum Öffentl. Personennahverkehr <?=$offerdetails->distanceToPT; ?> Min.</span>
			</div>
		<?php } ?>

		<?php if($offerdetails->distanceToMRS != 0){ ?>
			<div class="col-auto text-center">
				<span class="rounded-3 bg-200 px-3 py-1 fw-bold shadow">Fahrzeit zum nächsten Bahnhof <?=$offerdetails->distanceToMRS; ?> Min.</span>
			</div>
		<?php } ?>

		<?php if($offerdetails->distanceToFM != 0){ ?>
			<div class="col-auto text-center">
				<span class="rounded-3 bg-200 px-3 py-1 fw-bold shadow">Fahrzeit zur nächsten Autobahn <?=$offerdetails->distanceToFM; ?> Min.</span>
			</div>
		<?php } ?>

		<?php if($offerdetails->distanceToAirport != 0){ ?>
			<div class="col-auto text-center">
				<span class="rounded-3 bg-200 px-3 py-1 fw-bold shadow">Fahrzeit zum nächsten Flughafen <?=$offerdetails->distanceToAirport; ?> Min.</span>
			</div>
		<?php } ?>

	</div>

	<p class="text-justify"><?=str_ireplace(PHP_EOL, '<br />', $offerdetails->locationNote); ?></p>

<?php } ?>