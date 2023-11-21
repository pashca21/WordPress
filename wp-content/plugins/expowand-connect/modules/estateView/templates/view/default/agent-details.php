<?php 
?>

<div class="row mt-5 text-center">
	<div class="col-md-6">
		<?php if($agent->persphoto != ''){ ?>
			<img class="img-fluid" src="<?=$agents_img_url; ?><?=$agent->id; ?>/<?=$agent->persphoto; ?>" style="max-height: 400px; width: 100%; object-fit: contain;" />
		<?php }else if($agent->logo != ''){ ?>
			<img class="img-fluid" src="<?=$agents_img_url; ?><?=$agent->id; ?>/<?=$agent->logo; ?>" style="max-height: 200px; width: 100%; object-fit: contain;" />
		<?php } ?>
		<h4 class="mb-1 mt-2"><?=$agent->gender==1?'Herr':''; ?><?=$agent->gender==2?'Frau':''; ?> <?=$agent->firstname; ?> <?=$agent->lastname; ?></h4>
	</div>

	<div class="col-md-6">

		<h3 class="fw-bold mb-3"><?=$agent->firma; ?></h3>
		
		<?php if($agent->persphoto != '' && $agent->logo != ''){ ?>
			<img class="img-fluid" src="<?=$agents_img_url; ?><?=$agent->id; ?>/<?=$agent->logo; ?>" style="max-height: 200px; width: 100%; object-fit: contain;" />
		<?php } ?>

		<table class="table mt-3 w-100" style="width: 100%!important;">
			<tbody>
				<?php if($agent->web!=''){ ?>
					<tr>
						<th scope="row" style="text-align: left;">Webseite</th>
						<td><a class="" href="<?=$agent->web; ?>" target="_blank"><?=$agent->web; ?></a></td>
					</tr>
				<?php } ?>

				<?php if($agent->tel!=''){ ?>
					<tr>
						<th scope="row" style="text-align: left;">Telefonnummer</th>
						<td><a class="" href="tel:<?=$agent->tel; ?>"><?=$agent->tel; ?></a></td>
					</tr>
				<?php } ?>

				<?php if($agent->handy!=''){ ?>
					<tr>
						<th scope="row" style="text-align: left;">Mobilnummer</th>
						<td><a class="" href="tel:<?=$agent->handy; ?>"><?=$agent->handy; ?></a></td>
					</tr>
				<?php } ?>

				<tr>
					<th scope="row" style="text-align: left;">E-Mail-Adresse</th>
					<td><a class="" href="mailto:<?=$agent->email; ?>"><?=$agent->email; ?></a></td>
				</tr>

				<tr>
					<th scope="row" style="text-align: left;">Adresse</th>
					<td><a class="" href="<?=$gmaplink_agent; ?>" target="_blank"><?=$agent->street; ?>, <?=$agent->zip; ?> <?=$agent->city; ?></a></td>
				</tr>
			</tbody>

		</table>
		
	</div>
</div>