<?php 

?> 

<form id="form_search_offers" autocomplete="off" action="<?=$searchPath; ?>" method="get" >
	<input type="hidden" name="do_filter" id="do_filter" value="" />
	<input type="hidden" name="page_number" id="page_number" value="<?=$list->page; ?>" />

	<div class="row w-100 g-3 mb-5">
		<div class="col">
			<label class="form-label" for="type">Angebotsart</label>
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
		
		<div class="col">
			<label class="form-label" for="category">Kategorie</label>
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

		<div class="col">
			<label class="form-label" for="sort">Sortierung</label>
			<select class="form-select" id="sort" name="sort">
				<?php foreach(ExpowandDictionary::$sort_options as $key => $val){ ?>
					<option value="<?=$key; ?>" <?=( $list->sort==$key)?"selected='selected'":""; ?>>
						<?=$val; ?>
					</option>
				<?php } ?>
			</select>
		</div>

		<div class="col text-end align-text-bottom position-relative">
			<div class="position-absolute bottom-0 end-0">
				<a class="btn btn-phoenix-primary px-3" type="button" href="<?=$searchPath; ?>">Alle</a>
				<button class="btn btn-primary px-3" type="submit">Suchen</button>
			</div>
		</div>
	
	</div>

</form>