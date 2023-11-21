<?php 
?>

<div class="row mt-5 text-center">
	<div class="col-md-6">
		<?php if($agent->persphoto != ''){ ?>
			<img class="img-fluid EWestateView-default-agent-persphoto" src="<?=$agents_img_url; ?><?=$agent->id; ?>/<?=$agent->persphoto; ?>" />
		<?php }else if($agent->logo != ''){ ?>
			<img class="img-fluid EWestateView-default-agent-logo" src="<?=$agents_img_url; ?><?=$agent->id; ?>/<?=$agent->logo; ?>" />
		<?php } ?>
		<h4 class="EWestateView-default-agent-name"><?=$agent->gender==1?'Herr':''; ?><?=$agent->gender==2?'Frau':''; ?> <?=$agent->firstname; ?> <?=$agent->lastname; ?></h4>
	</div>

	<div class="col-md-6">
		<h3 class="EWestateView-default-agent-firma"><?=$agent->firma; ?></h3>
		
		<?php if($agent->persphoto != '' && $agent->logo != ''){ ?>
			<img class="img-fluid img-fluid EWestateView-default-agent-logo" src="<?=$agents_img_url; ?><?=$agent->id; ?>/<?=$agent->logo; ?>" />
		<?php } ?>

		<table class="table WestateView-default-agent-table">
			<tbody>
				<?php if($agent->web!=''){ ?>
					<tr>
						<th scope="row" class="EWestateView-default-agent-table-label">Webseite</th>
						<td class="EWestateView-default-agent-table-value"><a href="<?=$agent->web; ?>" target="_blank"><?=$agent->web; ?></a></td>
					</tr>
				<?php } ?>

				<?php if($agent->tel!=''){ ?>
					<tr>
						<th scope="row" class="EWestateView-default-agent-table-label">Telefonnummer</th>
						<td class="EWestateView-default-agent-table-value"><a href="tel:<?=$agent->tel; ?>"><?=$agent->tel; ?></a></td>
					</tr>
				<?php } ?>

				<?php if($agent->handy!=''){ ?>
					<tr>
						<th scope="row" class="EWestateView-default-agent-table-label">Mobilnummer</th>
						<td class="EWestateView-default-agent-table-value"><a href="tel:<?=$agent->handy; ?>"><?=$agent->handy; ?></a></td>
					</tr>
				<?php } ?>

				<tr>
					<th scope="row" class="EWestateView-default-agent-table-label">E-Mail-Adresse</th>
					<td class="EWestateView-default-agent-table-value"><a href="mailto:<?=$agent->email; ?>"><?=$agent->email; ?></a></td>
				</tr>

				<tr>
					<th scope="row" class="EWestateView-default-agent-table-label">Adresse</th>
					<td class="EWestateView-default-agent-table-value"><a href="<?=$gmaplink_agent; ?>" target="_blank"><?=$agent->street; ?>, <?=$agent->zip; ?> <?=$agent->city; ?></a></td>
				</tr>
			</tbody>
		</table>
		
	</div>
</div>