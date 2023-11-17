<?php
$searchPath = get_bloginfo('wpurl') . '/' . EW_PLUGIN_ROUTE . '/' . EW_ESTATEREFERENCE_ROUTE;
$upload_dir = wp_upload_dir();
$pics_url = $upload_dir['baseurl'] . '/estates/';
// echo $pics_url;
?>

<h1 class="mb-3">Immobilien</h1>

<form id="form_search_offers" autocomplete="off" action="<?=$searchPath; ?>" method="get" >
	<input type="hidden" name="do_filter" id="do_filter" value="" />

	<div class="row w-100 g-3 mb-5">
		<div class="col-6">
			<label class="fw-bold mb-2 text-1000" for="type">Angebotsart</label>
			<select class="form-select" id="type" name="type">
				<option value="-1" <?=( $list->type==-1)?"selected='selected'":""; ?>>
					alle
				</option>
				<option value="0" <?=( $list->type==0)?"selected='selected'":""; ?>>
					Kauf
				</option>
				<option value="1" <?=( $list->type==1)?"selected='selected'":""; ?>>
					Miete
				</option>
			</select>
		</div>
		
		<div class="col-6">
			<label class="fw-bold mb-2 text-1000" for="category">Kategorie</label>
			<select class="form-select" id="category" name="category">
				<option value="" <?=( $list->category=='')?"selected='selected'":""; ?>>
					alle
				</option>
				<?php foreach(ExpowandDictionary::$category_options_residential as $key => $val){ ?>
					<option value="<?=$key; ?>" <?=( $list->category==$key)?"selected='selected'":""; ?>>
						<?=$val; ?>
					</option>
				<?php } ?>
				<?php foreach(ExpowandDictionary::$category_options_commertial as $key => $val){ ?>
					<option value="<?=$key; ?>" <?=( $list->category==$key)?"selected='selected'":""; ?>>
						<?=$val; ?>
					</option>
				<?php } ?>
			</select>
		</div>

		<div class="col-12 text-end">
			<a class="btn btn-phoenix-primary px-4 my-0" type="button" href="offers">Alle</a>
			<button class="btn btn-primary px-9 my-0" type="submit">Suchen</button>
		</div>
	
	</div>

</form>

<div class="row w-100">
	<?php // print("<pre>".print_r($estates)."</pre>"); ?>
	<?php foreach($estates as $estate){ 
		// print("<pre>".print_r($estate,true)."</pre>");continue;
		$offer = $estate->offer;
		$offerdetails = $estate->offerdetails;
		if(empty($offerdetails->mainPic)){
			$mainImgUrl = 'http://work-expowand-dev.local/www/static/noimage.jpg';
		}else{
			$mainImgUrl = $pics_url.$offer->id.'/'.$offerdetails->mainPic;
		}

		$url = get_bloginfo('wpurl') . '/' . EW_PLUGIN_ROUTE . '/' . FF_ESTATEVIEW_ROUTE . '/' . '/estates/' . $offer->id;
		?>

		<div class="col-md-6 mb-3">
			<article class="h-100">
				<div class="card h-100" style="width: 18rem;">
					<a style="color:inherit; text-decoration:none;" href="<?=$url; ?>">
						<img src="<?=$mainImgUrl; ?>" class="card-img-top" alt="" style="height: 15rem; object-fit: cover;">
					</a>
					<div class="card-body">
						<h5 class="card-title">
							<a style="color:inherit; text-decoration:none;" href="<?=$url; ?>">
								<?=$offer->name; ?>
							</a>
						</h5>

						<?php if($offer->type==0){ ?>
							<span class="badge bg-primary">Kauf</span>
						<?php }else if($offer->type==1){ ?>
							<span class="badge bg-info">Miete</span>
						<?php } ?>

						<?php if($offer->category=='APARTMENT'){ ?>
							<?php if($offerdetails->apartmentType == 'NO_INFORMATION'){ ?>
								<span class="badge bg-secondary"><?=ExpowandDictionary::$category_options_residential[$offer->category]; ?></span>
							<?php }else{ ?>
								<span class="badge bg-secondary"><?=ExpowandDictionary::$apartmentType_options[$offerdetails->apartmentType]; ?></span>
							<?php } ?>
						<?php }else if($offer->category=='HOUSE'){ ?>
							<?php if($offerdetails->buildingType == 'NO_INFORMATION'){ ?>
								<span class="badge bg-secondary"><?=ExpowandDictionary::$category_options_residential[$offer->category]; ?></span>
							<?php }else{ ?>
								<span class="badge bg-secondary"><?=ExpowandDictionary::$buildingType_options[$offerdetails->buildingType]; ?></span>
							<?php } ?>
						<?php }else if($offer->category=='LIVING_SITE'){ ?>
							<span class="badge bg-secondary"><?=ExpowandDictionary::$category_options_residential[$offer->category]; ?></span>
						<?php }else if($offer->category=='OFFICE'){ ?>
							<?php if($offerdetails->officeType == 'NO_INFORMATION'){ ?>
								<span class="badge bg-secondary"><?=ExpowandDictionary::$category_options_commertial[$offer->category]; ?></span>
							<?php }else{ ?>
								<span class="badge bg-secondary"><?=ExpowandDictionary::$officeType_options[$offerdetails->officeType]; ?></span>
							<?php } ?>
						<?php }else if($offer->category=='STORE'){ ?>
							<?php if($offerdetails->storeType == 'NO_INFORMATION'){ ?>
								<span class="badge bg-secondary"><?=ExpowandDictionary::$category_options_commertial[$offer->category]; ?></span>
							<?php }else{ ?>
								<span class="badge bg-secondary"><?=ExpowandDictionary::$storeType_options[$offerdetails->storeType]; ?></span>
							<?php } ?>
						<?php }else if($offer->category=='INDUSTRY'){ ?>
							<?php if($offerdetails->industryType == 'NO_INFORMATION'){ ?>
								<span class="badge bg-secondary"><?=ExpowandDictionary::$category_options_commertial[$offer->category]; ?></span>
							<?php }else{ ?>
								<span class="badge bg-secondary"><?=ExpowandDictionary::$industryType_options[$offerdetails->industryType]; ?></span>
							<?php } ?>
						<?php } ?>

						<div class="row mt-2 ">
							<?php if($offer->type == 0 && $offer->immoprice != 0){ ?>
								<div class="col-6">
									<h5 class="mb-1 fw-bold"><?=number_format($offer->immoprice, 0, ',', '.'); ?> €</h5>
									<h6 class="text-700">Kaufpreis</h6>
								</div>
							<?php } ?>
							<?php if($offer->type == 1 && $offerdetails->baseRent != 0){ ?>
								<div class="col-6">
									<h5 class="mb-1 fw-bold"><?=number_format($offerdetails->baseRent, 0, ',', '.'); ?> €</h5>
									<h6 class="text-700">Kaltmiete</h6>
								</div>
							<?php } ?>
							<?php if(($offer->category == 'APARTMENT' || $offer->category == 'HOUSE') && $offerdetails->livingSpace != 0){ ?>
								<div class="col-6">
									<h5 class="mb-1 fw-bold">ca. <?=number_format($offerdetails->livingSpace, 0, ',', '.'); ?> m²</h5>
									<h6 class="text-700">Wohnfläche</h6>
								</div>
							<?php } ?>
							<?php if(($offer->category == 'OFFICE' || $offer->category == 'STORE' || $offer->category == 'INDUSTRY') && $offerdetails->totalFloorSpace != 0){ ?>
								<div class="col-6">
									<h5 class="mb-1 fw-bold">ca. <?=number_format($offerdetails->totalFloorSpace, 0, ',', '.'); ?> m²</h5>
									<h6 class="text-700">Gesamtfläche</h6>
								</div>
							<?php } ?>
							<?php if(($offer->category == 'LIVING_SITE' || $offer->category == 'HOUSE' || $offer->category == 'INDUSTRY') && $offerdetails->plotArea != 0){ ?>
								<div class="col-6">
									<h5 class="mb-1 fw-bold">ca. <?=number_format($offerdetails->plotArea, 0, ',', '.'); ?> m²</h5>
									<h6 class="text-700">Grundstücksfläche</h6>
								</div>
							<?php } ?>
							<?php if(($offer->category == 'OFFICE' || $offer->category == 'STORE' || $offer->category == 'INDUSTRY') && $offerdetails->netFloorSpace != 0){ ?>
								<div class="col-6">
									<h5 class="mb-1 fw-bold">ca. <?=number_format($offerdetails->netFloorSpace, 0, ',', '.'); ?> m²</h5>
									<h6 class="text-700">Netto-Grundfläche</h6>
								</div>
							<?php } ?>
							<?php if(($offer->category == 'APARTMENT' || $offer->category == 'HOUSE') && $offerdetails->numberOfRooms != 0){ ?>
								<div class="col-6">
									<h5 class="mb-1 fw-bold"><?=number_format($offerdetails->numberOfRooms, 0, ',', '.'); ?></h5>
									<h6 class="text-700">Zimmer</h6>
								</div>
							<?php } ?>
						</div>
						
					</div>
				</div>
			</article>
		</div>
		
	<?php } ?>

</div>