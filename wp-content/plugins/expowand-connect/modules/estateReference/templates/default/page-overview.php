<?php

?>

<div class="row">
	<?php // print("<pre>".print_r($results)."</pre>"); ?>
	<!-- sorting -->
	<?php foreach($results->estates as $estate){ 
		$offer = $estate->offer;
		$offerdetails = $estate->offerdetails;
		if(empty($offerdetails->mainImgUrl)){
			$mainImgUrl = 'http://work-expowand-dev.local/www/static/noimage.jpg';
		}else{
			$mainImgUrl = $offerdetails->mainImgUrl;
		}
		?>

		<div class="col-md-6 mb-3">
			<div class="card h-100" style="width: 18rem;">
				<img src="<?=$mainImgUrl; ?>" class="card-img-top" alt="" style="height: 15rem; object-fit: cover;">
				<div class="card-body">
					<h5 class="card-title"><?=$offer->name; ?></h5>

					<?php if($offer->type==0){ ?>
						<span class="badge bg-primary">Kauf</span>
					<?php }else if($offer->type==1){ ?>
						<span class="badge bg-info">Miete</span>
					<?php } ?>

					<?php if($offer->category=='APARTMENT'){ ?>
						<span class="badge bg-secondary"><?=ExpowandDictionary::$apartmentType_options[$offerdetails->apartmentType]; ?></span>
					<?php }else if($offer->category=='HOUSE'){ ?>
						<span class="badge bg-secondary"><?=ExpowandDictionary::$buildingType_options[$offerdetails->buildingType]; ?></span>
					<?php }else if($offer->category=='LIVING_SITE'){ ?>
						<span class="badge bg-secondary"><?=ExpowandDictionary::$category_options_residential[$offer->category]; ?></span>
					<?php }else if($offer->category=='OFFICE'){ ?>
						<span class="badge bg-secondary"><?=ExpowandDictionary::$officeType_options[$offerdetails->officeType]; ?></span>
					<?php }else if($offer->category=='STORE'){ ?>
						<span class="badge bg-secondary"><?=ExpowandDictionary::$storeType_options[$offerdetails->storeType]; ?></span>
					<?php }else if($offer->category=='INDUSTRY'){ ?>
						<span class="badge bg-secondary"><?=ExpowandDictionary::$industryType_options[$offerdetails->industryType]; ?></span>
					<?php } ?>

					<div class="row mt-2 ">
						<?php if($offer->immoprice != 0){ ?>
							<div class="col-6">
								<h5 class="mb-1 fw-bold"><?=number_format($offer->immoprice, 0, ',', '.'); ?> €</h5>
								<h6 class="text-700">Kaufpreis</h6>
							</div>
						<?php } ?>
						<?php if($offerdetails->baseRent != 0){ ?>
							<div class="col-6">
								<h5 class="mb-1 fw-bold"><?=number_format($offerdetails->baseRent, 0, ',', '.'); ?> €</h5>
								<h6 class="text-700">Kaltmiete</h6>
							</div>
						<?php } ?>
						<?php if($offerdetails->livingSpace != 0){ ?>
							<div class="col-6">
								<h5 class="mb-1 fw-bold"><?=number_format($offerdetails->livingSpace, 2, ',', '.'); ?> m²</h5>
								<h6 class="text-700">Wohnfläche</h6>
							</div>
						<?php } ?>
						<?php if($offerdetails->totalFloorSpace != 0){ ?>
							<div class="col-6">
								<h5 class="mb-1 fw-bold"><?=number_format($offerdetails->totalFloorSpace, 2, ',', '.'); ?> m²</h5>
								<h6 class="text-700">Gesamtfläche</h6>
							</div>
						<?php } ?>
						<?php if($offerdetails->plotArea != 0){ ?>
							<div class="col-6">
								<h5 class="mb-1 fw-bold"><?=number_format($offerdetails->plotArea, 2, ',', '.'); ?> m²</h5>
								<h6 class="text-700">Grundstücksfläche</h6>
							</div>
						<?php } ?>
						<?php if($offerdetails->netFloorSpace != 0){ ?>
							<div class="col-6">
								<h5 class="mb-1 fw-bold"><?=number_format($offerdetails->netFloorSpace, 2, ',', '.'); ?> m²</h5>
								<h6 class="text-700">Netto-Grundfläche</h6>
							</div>
						<?php } ?>
						<?php if($offerdetails->numberOfRooms != 0){ ?>
							<div class="col-6">
								<h5 class="mb-1 fw-bold"><?=number_format($offerdetails->numberOfRooms, 0, ',', '.'); ?></h5>
								<h6 class="text-700">Zimmer</h6>
							</div>
						<?php } ?>
					</div>
					
				</div>
			</div>
		</div>
		
	<?php } ?>

</div>