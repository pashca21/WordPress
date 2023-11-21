<?php
$searchPath = get_bloginfo('wpurl') . '/' . EW_PLUGIN_ROUTE . '/' . EW_ESTATEREFERENCE_ROUTE;
$upload_dir = wp_upload_dir();
$pics_url = $upload_dir['baseurl'] . '/estates/';

$list->action = $searchPath;
?>

<h1 class="mb-3 EWestateReference-default-header">Immobilien</h1>

<?php include_once 'search-form.php'; ?>

<div class="row EWestateReference-default-list-row">
	<?php if(empty($estates)){ ?>
		<div class="col-12">
			<div class="alert alert-info EWestateReference-default-list-noresults" role="alert">
				Es wurden keine Immobilien gefunden.
			</div>
		</div>
	<?php } ?>
	<?php foreach($estates as $estate){ 
		$offer = $estate->offer;
		$offerdetails = $estate->offerdetails;
		if(empty($offerdetails->mainPic)){
			$plugin_dir_url = plugin_dir_url( __FILE__ );
			$mainImgUrl = $plugin_dir_url.'noimage.jpg';
		}else{
			$mainImgUrl = $pics_url.$offer->id.'/'.$offerdetails->mainPic;
		}

		$url = get_bloginfo('wpurl') . '/' . EW_PLUGIN_ROUTE . '/' . EW_ESTATEVIEW_ROUTE . '/' . '/estates/' . $offer->id;
		?>

		<div class="col-auto mx-auto mb-3 EWestateReference-default-list-col">
			<div class="card h-100 EWestateReference-default-list-card">
				<a style="color:inherit; text-decoration:none;" href="<?=$url; ?>">
					<img src="<?=$mainImgUrl; ?>" class="card-img-top EWestateReference-default-list-img" alt="" >
				</a>
				<div class="card-body">
					<h5 class="card-title EWestateReference-default-list-title">
						<a style="color:inherit; text-decoration:none;" href="<?=$url; ?>">
							<?=$offer->name; ?>
						</a>
					</h5>

					<?php if($offer->type==0){ ?>
						<span class="badge bg-primary EWestateReference-default-list-badge-buy">Kauf</span>
					<?php }else if($offer->type==1){ ?>
						<span class="badge bg-info EWestateReference-default-list-badge-rent">Miete</span>
					<?php } ?>

					<?php if($offer->category=='APARTMENT'){ ?>
						<?php if($offerdetails->apartmentType == 'NO_INFORMATION'){ ?>
							<span class="badge bg-secondary EWestateReference-default-list-badge-category"><?=ExpowandDictionary::$category_options_residential[$offer->category]; ?></span>
						<?php }else{ ?>
							<span class="badge bg-secondary EWestateReference-default-list-badge-category"><?=ExpowandDictionary::$apartmentType_options[$offerdetails->apartmentType]; ?></span>
						<?php } ?>
					<?php }else if($offer->category=='HOUSE'){ ?>
						<?php if($offerdetails->buildingType == 'NO_INFORMATION'){ ?>
							<span class="badge bg-secondary EWestateReference-default-list-badge-category"><?=ExpowandDictionary::$category_options_residential[$offer->category]; ?></span>
						<?php }else{ ?>
							<span class="badge bg-secondary EWestateReference-default-list-badge-category"><?=ExpowandDictionary::$buildingType_options[$offerdetails->buildingType]; ?></span>
						<?php } ?>
					<?php }else if($offer->category=='LIVING_SITE'){ ?>
						<span class="badge bg-secondary EWestateReference-default-list-badge-category"><?=ExpowandDictionary::$category_options_residential[$offer->category]; ?></span>
					<?php }else if($offer->category=='OFFICE'){ ?>
						<?php if($offerdetails->officeType == 'NO_INFORMATION'){ ?>
							<span class="badge bg-secondary EWestateReference-default-list-badge-category"><?=ExpowandDictionary::$category_options_commertial[$offer->category]; ?></span>
						<?php }else{ ?>
							<span class="badge bg-secondary EWestateReference-default-list-badge-category"><?=ExpowandDictionary::$officeType_options[$offerdetails->officeType]; ?></span>
						<?php } ?>
					<?php }else if($offer->category=='STORE'){ ?>
						<?php if($offerdetails->storeType == 'NO_INFORMATION'){ ?>
							<span class="badge bg-secondary EWestateReference-default-list-badge-category"><?=ExpowandDictionary::$category_options_commertial[$offer->category]; ?></span>
						<?php }else{ ?>
							<span class="badge bg-secondary EWestateReference-default-list-badge-category"><?=ExpowandDictionary::$storeType_options[$offerdetails->storeType]; ?></span>
						<?php } ?>
					<?php }else if($offer->category=='INDUSTRY'){ ?>
						<?php if($offerdetails->industryType == 'NO_INFORMATION'){ ?>
							<span class="badge bg-secondary EWestateReference-default-list-badge-category"><?=ExpowandDictionary::$category_options_commertial[$offer->category]; ?></span>
						<?php }else{ ?>
							<span class="badge bg-secondary EWestateReference-default-list-badge-category"><?=ExpowandDictionary::$industryType_options[$offerdetails->industryType]; ?></span>
						<?php } ?>
					<?php } ?>

					<div class="row mt-2 ">
						<?php if($offer->type == 0 && $offer->immoprice != 0){ ?>
							<div class="col-6">
								<h5 class="EWestateReference-default-list-value"><?=number_format($offer->immoprice, 0, ',', '.'); ?> €</h5>
								<h6 class="EWestateReference-default-list-label">Kaufpreis</h6>
							</div>
						<?php } ?>
						<?php if($offer->type == 1 && $offerdetails->baseRent != 0){ ?>
							<div class="col-6">
								<h5 class="EWestateReference-default-list-value"><?=number_format($offerdetails->baseRent, 0, ',', '.'); ?> €</h5>
								<h6 class="EWestateReference-default-list-label">Kaltmiete</h6>
							</div>
						<?php } ?>
						<?php if(($offer->category == 'APARTMENT' || $offer->category == 'HOUSE') && $offerdetails->livingSpace != 0){ ?>
							<div class="col-6">
								<h5 class="EWestateReference-default-list-value">ca. <?=number_format($offerdetails->livingSpace, 0, ',', '.'); ?> m²</h5>
								<h6 class="EWestateReference-default-list-label">Wohnfläche</h6>
							</div>
						<?php } ?>
						<?php if(($offer->category == 'OFFICE' || $offer->category == 'STORE' || $offer->category == 'INDUSTRY') && $offerdetails->totalFloorSpace != 0){ ?>
							<div class="col-6">
								<h5 class="EWestateReference-default-list-value">ca. <?=number_format($offerdetails->totalFloorSpace, 0, ',', '.'); ?> m²</h5>
								<h6 class="EWestateReference-default-list-label">Gesamtfläche</h6>
							</div>
						<?php } ?>
						<?php if(($offer->category == 'LIVING_SITE' || $offer->category == 'HOUSE' || $offer->category == 'INDUSTRY') && $offerdetails->plotArea != 0){ ?>
							<div class="col-6">
								<h5 class="EWestateReference-default-list-value">ca. <?=number_format($offerdetails->plotArea, 0, ',', '.'); ?> m²</h5>
								<h6 class="EWestateReference-default-list-label">Grundstücksfläche</h6>
							</div>
						<?php } ?>
						<?php if(($offer->category == 'OFFICE' || $offer->category == 'STORE' || $offer->category == 'INDUSTRY') && $offerdetails->netFloorSpace != 0){ ?>
							<div class="col-6">
								<h5 class="EWestateReference-default-list-value">ca. <?=number_format($offerdetails->netFloorSpace, 0, ',', '.'); ?> m²</h5>
								<h6 class="EWestateReference-default-list-label">Netto-Grundfläche</h6>
							</div>
						<?php } ?>
						<?php if(($offer->category == 'APARTMENT' || $offer->category == 'HOUSE') && $offerdetails->numberOfRooms != 0){ ?>
							<div class="col-6">
								<h5 class="EWestateReference-default-list-value"><?=number_format($offerdetails->numberOfRooms, 0, ',', '.'); ?></h5>
								<h6 class="EWestateReference-default-list-label">Zimmer</h6>
							</div>
						<?php } ?>
					</div>
					
				</div>
			</div>
		</div>
		
	<?php } ?>

</div>

<?php include_once 'paginator.php'; ?>
