<?php
?>

<div class="card mt-5">
<div class="card-body">
	<h4 class="mt-6">Kontaktanfrage</h4>

	<?php if($inquiry_success){ ?>
		<div class="alert alert-success" role="alert">
			<strong>Vielen Dank!</strong> Ihre Anfrage wurde erfolgreich versendet.
		</div>
	<?php }else{ ?>

	<form class="" action="<?=$pagePath; ?>" method="post" id="ew_inquiry_form" >
		<input type="hidden" id="ew_inquiry_offer_id" name="ew_inquiry_offer_id" value="<?=$offer->id; ?>" />
	
		<div class="row">
			<div class="col-md-2">
				<label class="form-label" for="ew_inquiry_gender">Anrede <span class="text-danger">*</span></label>
				<select class="form-select" id="ew_inquiry_gender" name="ew_inquiry_gender" required >
					<option value="" selected="" disabled="" >wählen</option>
					<option value="1" >Herr</option>
					<option value="2" >Frau </option>
				</select>
			</div>

			<div class="col-md-5">
				<label class="form-label" for="ew_inquiry_firstname">Vorname <span class="text-danger">*</span></label>
				<input class="form-control" id="ew_inquiry_firstname" name="ew_inquiry_firstname" type="text" placeholder="Vorname" value="" pattern="[a-zA-Z0-9 äöüÄÖÜ]+" required />
			</div>

			<div class="col-md-5">
				<label class="form-label" for="ew_inquiry_lastname">Nachname <span class="text-danger">*</span></label>
				<input class="form-control" id="ew_inquiry_lastname" name="ew_inquiry_lastname" type="text" placeholder="Nachname" value="" pattern="[a-zA-Z0-9 äöüÄÖÜ]+" required />
			</div>

			<div class="col-md-6 mt-3">
				<label class="form-label" for="ew_inquiry_email">E-Mail <span class="text-danger">*</span></label>
				<input class="form-control" id="ew_inquiry_email" name="ew_inquiry_email" type="text" placeholder="E-Mail" value="" required />
			</div>

			<div class="col-md-6 mt-3">
				<label class="form-label" for="ew_inquiry_tel">Telefonnummer <span class="text-danger">*</span></label>
				<input class="form-control" id="ew_inquiry_tel" name="ew_inquiry_tel" type="tel" placeholder="Telefonnummer" value="" required />
			</div>

			<div class="col-md-12 mt-3">
				<label class="form-label" for="ew_inquiry_message">Nachricht</label>
				<textarea class="form-control" id="ew_inquiry_message" name="ew_inquiry_message" rows="3" placeholder="" > </textarea>
			</div>

			<div class="col-md-10 mt-3">
				<div class="form-check">
					<input class="form-check-input" id="ew_inquiry_chk" name="ew_inquiry_chk" type="checkbox" value="1" required/>
					<label class="form-check-label" for="ew_inquiry_chk">Ich bestätige, dass ich Expose anfordern möchte <span class="text-danger">*</span></label>
				</div>
			</div>

			<div class="col-md-2 text-end mt-3">
				<button class="btn btn-primary" type="submit" form="ew_inquiry_form" >Speichern</button>
			</div>
		</div>
	</form>

	<?php } ?>

</div>
</div>
