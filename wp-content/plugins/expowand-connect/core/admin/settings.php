<?php 
	
	/**********************
	 * Add Admin panel
	 **********************/
	 
	add_action('admin_menu', function() {
		add_menu_page( 'EXPOWAND Connect settings', 'EXPOWAND', 'manage_options', 'ff-plugin', 'plugin_page'  );
		
		if ( empty ( $GLOBALS['admin_page_hooks']['ff-plugin'] ) ) 
		{
			add_menu_page( 'EXPOWAND Connect settings', 'Einstellungen', 'manage_options', 'ff-plugin', 'plugin_page'  );
		}

		wp_register_style('FF-admin-styles', plugins_url('/ff-admin-styles.css', __FILE__), '', '1.0.0', false);
    wp_enqueue_style('FF-admin-styles');
		wp_register_script('FF-admin-scripts', plugins_url('/ff-admin-scripts.js', __FILE__), '', '1.0.0', true);
		wp_enqueue_script('FF-admin-scripts');


		wp_localize_script('FF-admin-scripts', 'ffdata', array(
                'ajaxurl' => admin_url('admin-ajax.php')
            )
        );
	});

	// register Settings
	add_action( 'admin_init', function() {

		//params
		$data = json_decode(FF_ADMIN_SETTINGS,true);
		if (!empty($data)) {

			foreach($data["modules"] as $cat) {
					foreach($cat["fields"] as $module){
						if(!empty($module["fields"]))
						{
							foreach($module["fields"] as $id => $group)
							{
								register_setting( 'plugin-settings', $id );
							}
						}
					}
				}
			}
	});
	 
	// render HTML of Settings	 
	function plugin_page() {

	?>
	
		<?php // Load Plugin configuration assistent. ?>
		<div class="wrap ff-module">
			<form action="options.php" method="post" enctype="multipart/form-data">

			<?php
				$catLast = "";
				settings_fields( 'plugin-settings' );
				do_settings_sections( 'plugin-settings' );
			?>
		
			<?php if (!empty(FF_ADMIN_SETTINGS)): ?>
				<img width="200px"; src="data:image/png;base64,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" />

				<?php $data = json_decode(FF_ADMIN_SETTINGS,true); ?>

				<?php
					// Check for plugins which block the loading of this plugin.

					$blocked_by_plugin = json_decode(FF_ADMIN_SETTINGS,true);
					if(!empty($blocked_by_plugin["blocked_by_plugin"])){
						foreach($blocked_by_plugin["blocked_by_plugin"] as $check_key => $check)
						{
							// check if plugin exisit
							if(!empty(is_plugin_active($check_key.'.php'))){
								$option = get_option($check["check"]["option_name"], 'default_value');

								if(!empty($option))
								{
								   if(!empty($check["check"]["path"]["level1"])) {
										 if(!empty($check["check"]["path"]["level2"])){
											 if(!empty($check["check"]["path"]["level3"])){
												 if(!empty($check["check"]["path"]["level4"])){
													$plugin_setting = $option[$check["check"]["path"]["level1"]][$check["check"]["path"]["level2"]][$check["check"]["path"]["level3"]][$check["check"]["path"]["level4"]];
												 }
												 else
												 {
													$plugin_setting = $option[$check["check"]["path"]["level1"]][$check["check"]["path"]["level2"]][$check["check"]["path"]["level3"]];
												 };
											 }
											 else
											 {
												$plugin_setting = $option[$check["check"]["path"]["level1"]][$check["check"]["path"]["level2"]];
											 };
										}
										else
										{
											$plugin_setting = $option[$check["check"]["path"]["level1"]];
										};
									};

									if($plugin_setting == $check["check"]["value"])
									{
										?>
											<div class="ff-setting-modules">
												<div class="ff-setting-blocked-by">
													<div class="ff-setting-module-title ff-setting-done">
														<div class="ff-setting-error-point ">!</div>
														<?= $check["name"] ?>
													</div>
													<p>
														<?= $check["error"] ?>
													</p>
												</div>
											</div>
										<?php
									}
								};
							};
						};
					};

				?>




					<?php foreach($data["modules"] as $catKey => $cat): ?>

						<?php
							// check requiert fields are valide
							if(!empty($cat["requiert"]))
							{
								foreach($cat["requiert"] as $requiert)
								{
									if(empty(get_option($requiert)))
									{

										$class_disabled = "ff-setting-disabled";
										break;
									}
								}
							}
							else
							{
								$class_disabled = "";
							}
						?>

						<div class="ff-setting-modules <?= $class_disabled ?>">
							<?php if($catKey !== $catLast): ?>
								<div class="ff-setting-category">
									<?= $cat["title"] ?>
								</div>
								<?php $catLast = $catKey ?>
							<?php endif ?>


							<?php foreach($cat["fields"] as $key => $module): ?>

								<?php
									// check requiert fields are valide
									foreach($module["requiert"] as $requiert)
									{
										$result = false;
										if ($requiert === 'ff-valuationMaster-token') {
											if (class_exists('API')) {
												$API = new API();
												$result = $API->get_entitlement('LEAD_MASTER');
											}
										}
										if (!empty(get_option($requiert)) || !empty($result))
										{
											$status = "<span style=\"color:#b7ce5b\">Vollständig eingerichtet</span>";
											$class = "ff-setting-done";
											$id = "";
										}
										else
										{
											$status = "<span style=\"color:#666\">Einrichtung offen</span>";
											$class 	= "";
											$id 	= "next-step";
											break;
										}
									}
								?>
								<div  id="<?= $id ?>" class="ff-setting-module">
									<div  class="ff-setting-module-box <?= (empty($class_disabled))?"ff-settings-opener":""?> ">
										<div class="ff-setting-module-title <?= $class ?>">
											<div class="ff-setting-module-point ">
												<?= $module["point"] ?>
											</div>
											<?= $module["title"] ?>
										</div>
										<div class="ff-setting-module-status">
											<?= $status ?>
										</div>
									</div>
									<div class="ff-setting-module-box-content ff-setting-close">
										<div>
											<div class="ff-setting-module-content">
													<div>
														<?= $module["description"] ?>
													</div>

													<?php if(!empty($module["fields"])): ?>
														<div>
															<div class="ff-settings-form">
																<?php foreach($module["fields"] as $fieldkey => $field): ?>
																	<div class="ff-settings-field">
																		<div>
																			<b><?= $field["title"] ?></b>
																			<?php if(!empty($field["requiert"])):?>
																				<span class="ff-setting-requiert"> Pflichtfeld </span>
																			<?php endif; ?>
																		</div>
																		<div class="ff-setting-field-type-<?= $field["type"] ?>">
																			<?php if(!empty($field["default"])): ?>
																				<?= getFieldType($field["type"], $fieldkey, $field["default"]) ?>
																			<?php elseif(!empty($field["options"])): ?>
																				<?= getFieldType($field["type"], $fieldkey, null, $field["options"]) ?>
																			<?php else: ?>
																				<?= getFieldType($field["type"], $fieldkey) ?>
																			<?php endif ?>
																		</div>
																		<div>
																			<?php if(!empty($field["description"])): ?>
																				<?= $field["description"] ?>
																			<?php endif ?>
																		</div>
																	</div>
																<?php endforeach ?>
															</div>
														</div>
														<?php if(!empty($module["save_label"])): ?>
															<input type="submit" class="ff-setting-submit" value="<?= $module["save_label"] ?>" />
														<?php else: ?>
															<input type="submit" class="ff-setting-submit" value="Speichern" />
														<?php endif ?>
													<?php endif ?>

											</div>
											<div class="ff-setting-module-faq">
												<?php if (!empty($module["faq"]["title"])): ?>
													<h3><?= $module["faq"]["title"] ?></h3>
												<?php endif; ?>

												<?php if (!empty($module["faq"]["video"])): ?>
													<iframe src="<?= $module["faq"]["video"] ?>" scrolling="no" height="200px" width="100%"></iframe>
												<?php endif; ?>

												<?php if (!empty($module["faq"]["content"])): ?>
													<p><?= $module["faq"]["content"] ?></p>
												<?php endif; ?>
											</div>
										</div>
									</div>


									<?php if (!empty($module["integration"]["possibleIntegrations"])): ?>
										<?php
											if(!empty(get_option('ff-'.$key.'-route') or !empty(defined('FF_'.strtoupper($key).'_ROUTE'))))
											{
												$ffmodule_url = get_home_url()."/";
												$ffmodule_url .= (!empty(get_option('ff-plugin-route')))? get_option('ff-plugin-route'): constant('FF_PLUGIN_ROUTE');
												$ffmodule_url .="/";
												$ffmodule_url .= (!empty(get_option('ff-'.$key.'-route')))? get_option('ff-'.$key.'-route'):constant('FF_'.strtoupper($key).'_ROUTE');
											}
										?>


										<div class="ff-Integration <?= $class ?>">
											<div class="ff-possibleIntegration">

												<?php if (!empty($module["integration"]["possibleIntegrations"]["url"]) && !empty($ffmodule_url)): ?>
													<div>
														<h3><?= $module["integration"]["possibleIntegrations"]["url"]["title"] ?></h3>
														<p><?= $module["integration"]["possibleIntegrations"]["url"]["description"] ?></p>
														<a target="_blank" href="<?= $ffmodule_url ?>"><?= $ffmodule_url ?></a>
													</div>
												<?php endif; ?>

												<?php if (!empty($module["integration"]["possibleIntegrations"]["iframe"]) && !empty($ffmodule_url)): ?>
													<div>
														<h3><?= $module["integration"]["possibleIntegrations"]["iframe"]["title"] ?></h3>
														<p><?= $module["integration"]["possibleIntegrations"]["iframe"]["description"] ?></p>
														<code>&lt;iframe src="<?= $ffmodule_url."?iframe=1" ?>" width="100%" height="2000px" style="border:0;" scrolling="auto" &gt;&lt;/iframe&gt;</code>
													</div>
												<?php endif; ?>

												<?php if (!empty($module["integration"]["possibleIntegrations"]["shortcode"]["value"])): ?>
													<div>
														<h3><?= $module["integration"]["possibleIntegrations"]["shortcode"]["title"] ?></h3>
														<p><?= $module["integration"]["possibleIntegrations"]["shortcode"]["description"] ?></p>
														<code><?= $module["integration"]["possibleIntegrations"]["shortcode"]["value"] ?></code>
													</div>
												<?php endif; ?>

												<?php if (!empty($module["integration"]["possibleIntegrations"]["sitemap"]["value"])): ?>
													<div>
														<h3><?= $module["integration"]["possibleIntegrations"]["sitemap"]["title"] ?></h3>
														<p><?= $module["integration"]["possibleIntegrations"]["sitemap"]["description"] ?></p>
														<a target="_blank" href="<?= $ffmodule_url ?><?= $module["integration"]["possibleIntegrations"]["sitemap"]["value"] ?>.xml"><?= $ffmodule_url ?><?= $module["integration"]["possibleIntegrations"]["sitemap"]["value"] ?>.xml</a><br/>
														<a target="_blank" href="<?= $ffmodule_url ?><?= $module["integration"]["possibleIntegrations"]["sitemap"]["value"] ?>.txt"><?= $ffmodule_url ?><?= $module["integration"]["possibleIntegrations"]["sitemap"]["value"] ?>.txt</a>
													</div>
												<?php endif; ?>
											</div>
										</div>
									<?php endif; ?>

								</div>
							<?php endforeach; ?>
						</div>
					<?php endforeach; ?>
			<?php else: ?>
				Config file not found
			<?php endif; ?>

		  </form>
		</div>
	<?php

}

add_action('wp_ajax_nopriv_fftestphpmail', 'ff_test_phpmail');
add_action('wp_ajax_fftestphpmail', 'ff_test_phpmail');



function getFieldType($type = NULL, $field = NULL, $default = NULL, $options = null)
{
	if(!empty($type))
	{
		switch($type)
		{
		  case ("text"):
				return field_text($field, $default);
		  break;
		  case ("textarea"):
				return field_textarea($field, $default);
		  break;

		  case ("portal"):
				return field_portal($field);
		  break;

		  case ("number"):
				return field_number($field);
		  break;

		  case ("password"):
				return field_password($field);
		  break;

		  case ("nylas"):
				return field_nylas($field);
		  break;

		  case ("checkbox"):
				return field_checkbox($field);
		  break;

		  case ("entitlement"):
				return field_entitlement($field, $default);
		  break; 
		  
		  case ("color"):
				return field_color($field, $default);
		  break; 
		  
		  case ("user"):
				return user_list($field);
		  break;
		  
		  case ("possible_options"):
				return possible_options($field, $options);
		  break;

			case ("file"):
				return field_file($field, $default);
			break;
		}
	}
}


// get possible portlas
function field_portal($field = null)
{
	if(!empty($field))
	{
		// get API
		if (class_exists('API')) {
			$API = new API();
		}

		// get entries
		$result = $API->get_portals();
		if(!empty($result) && count($result) > 0)
		{
			$view = '<select name="'.$field.'" id="ff-estateView-publish" onload="setPortalId(this);" onchange="setPortalId(this);">';
				$view .= '<option value="">Bitte wählen</option>';
				foreach($result as $row){

					if(get_option("ff-estateView-publish") == $row['id']){
						$view .= '<option data-portal="'.$row['id'].'" selected value="'.$row['id'].'">'.$row['name'].'</option>';
						$Portalkey = $row['id'];
					}
					else
					{
						$view .= '<option data-portal="'.$row['id'].'" value="'.$row['id'].'">'.$row['name'].'</option>';
					}
				}
			$view .= '</select>';

			// retrun field
			return $view;
		}
	}
}


// get possible nylas accounts
function field_nylas($field = NULL)
{

	if(!empty($field))
	{
		if (class_exists('API')) {
			$API = new API();
		}

		$result = $API->get_all_nylas_accounts();
		$view = '<select name="'.$field.'">';
			$view .= '<option value="">Bitte wählen</option>';
			if(!empty($result["emails"]))
			{
				foreach($result["emails"] as $account) {
					if(!empty($account["billingStatus"]) && $account["billingStatus"] == "paid")
					{
						if(get_option($field) == $account["email"]){
							$view .= '<option selected value="'.$account["email"].'">'.$account["email"].'</option>';
						}
						else
						{
							$view .= '<option value="'.$account["email"].'">'.$account["email"].'</option>';
						}
					}
				}
			}

		$view .= '</select>';
		return $view;
	}

}

// get ENTITLEMENT feedback
function field_entitlement($field = NULL, $default = NULL)
{

	if(!empty($field) && !empty($default))
	{
		if (class_exists('API')) {
			$API = new API();
		}
		
		add_option( $field, false);
		$result = $API->get_entitlement($default);
		
			if(!empty($result))
			{
				update_option( $field, true);
				return '<div class="ff-entitlement-registration-active"><span>Produkt aktiviert</span></div>';
			}
			else
			{
				update_option( $field, false);
				
				$view = '<p>Um den Lead-Hunter nutzen zu können, muss das Produkt aktiviert werden. Weitere Informationen zur Aktivierung finden Sie <a href="https://www.flowfact.de/leadhunter" target="_blank">hier</a>.</p>';
				$view .= '<br/>';
				$view .= '<div class="ff-entitlement-registration-open"><span>Produkt nicht gebucht</span></div>';
				
				return $view;
				
			}
		
	}
	return;
}

// get possible nylas accounts
function user_list($field = NULL)
{

	if(!empty($field))
	{
		if (class_exists('API')) {
			$API = new API();
		}

		$result = $API->get_users_no_cache();

		if(!empty($result))
		{
			$view ="";
			$view .= "<ul id='user-list'>";

				if(get_option("ff-teamoverview-blocked") && is_array(json_decode(get_option("ff-teamoverview-blocked")))){

					$jsonArray = json_decode(get_option("ff-teamoverview-blocked"), true);

					$newArrayForSorting = [];
					foreach($jsonArray as $jsonkey => $jsonsingle) {

							foreach($result as $userkey => $user) {
									if($user['id'] == $jsonsingle['id']) {
											$newArrayForSorting[$jsonkey] = $user;
									}
							}
					}

					$result = $newArrayForSorting;

					$blockedIds = array();
					foreach($jsonArray as $item){
							if($item['class'] == 'ff-false'){
									$blockedIds[] = $item['id'];
							}
					}

					$blockedIdsString = implode(", ", $blockedIds);

					$view .="";
				
					foreach($result as $row)
					{
						if (strpos($blockedIdsString, $row["id"]) !== false) 
						{
							$view .="<li class='user-item'><div class='ff-teamoverview-user ff-false' data-id='".$row["id"]."'>";
						}
						else
						{
							$view .="<li draggable='true' class='user-item'><div class='ff-teamoverview-user ff-true' data-id='".$row["id"]."'>";
						}
							$view .="<div>";
								if($row["firstname"]){
									$view .= "<span>".$row["firstname"]."</span>";
								}
								
								if($row["lastname"]){
									$view .= "<span>".$row["lastname"]."</span>";
								}
							
							$view .="</div>";
							$view .="
								<div class='ff-teamoverview-user-hide' style='margin-left:auto; margin-top: -2px; margin-right: 5px;'>
									<div>ausblenden</div> 
									<div>anzeigen</div>
								</div>";

							$view .="
								<svg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
									<rect x='4' y='6' width='16' height='2' fill='#333333'/>
									<rect x='4' y='11' width='16' height='2' fill='#333333'/>
									<rect x='4' y='16' width='16' height='2' fill='#333333'/>
								</svg>
							";
						$view .="</li>";
					}
				} else {
					// work around to do not display inactive user as active from the old version
					foreach($result as $row)
					{
						if($row["active"] == 1) {
							// checking if team member was blocked in the previous version
							if(strpos(get_option($field), $row["id"]) === false) {
								//get_option("ff-teamoverview-blocked")
								$view .="<li draggable='true' class='user-item'><div class='ff-teamoverview-user ff-true' data-id='".$row["id"]."'>";

								$view .="<div>";
								if($row["firstname"]){
									$view .= "<span>".$row["firstname"]."</span>";
								}
								
								if($row["lastname"]){
									$view .= "<span>".$row["lastname"]."</span>";
								}
							
								$view .="</div>";
								$view .="
									<div class='ff-teamoverview-user-hide' style='margin-left:auto; margin-top: -2px; margin-right: 5px;'>
										<div>ausblenden</div> 
										<div>anzeigen</div>
									</div>";

								$view .="
									<svg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
										<rect x='4' y='6' width='16' height='2' fill='#333333'/>
										<rect x='4' y='11' width='16' height='2' fill='#333333'/>
										<rect x='4' y='16' width='16' height='2' fill='#333333'/>
									</svg>
								";
								$view .="</li>";
							} else {
								$view .="<li class='user-item'><div class='ff-teamoverview-user ff-false' data-id='".$row["id"]."'>";

								$view .="<div>";
								if($row["firstname"]){
									$view .= "<span>".$row["firstname"]."</span>";
								}
								
								if($row["lastname"]){
									$view .= "<span>".$row["lastname"]."</span>";
								}
							
								$view .="</div>";
								$view .="
									<div class='ff-teamoverview-user-hide' style='margin-left:auto; margin-top: -2px; margin-right: 5px;'>
										<div>ausblenden</div> 
										<div>anzeigen</div>
									</div>";

								$view .="
									<svg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
										<rect x='4' y='6' width='16' height='2' fill='#333333'/>
										<rect x='4' y='11' width='16' height='2' fill='#333333'/>
										<rect x='4' y='16' width='16' height='2' fill='#333333'/>
									</svg>
								";
								$view .="</li>";
							}
						}
					}

					foreach($result as $row)
					{	
						if($row["active"] != 1) {
							$view .="<li class='user-item'><div class='ff-teamoverview-user ff-false' data-id='".$row["id"]."'>";

							$view .="<div>";
							if($row["firstname"]){
								$view .= "<span>".$row["firstname"]."</span>";
							}
							
							if($row["lastname"]){
								$view .= "<span>".$row["lastname"]."</span>";
							}
						
							$view .="</div>";
							$view .="
								<div class='ff-teamoverview-user-hide' style='margin-left:auto; margin-top: -2px; margin-right: 5px;'>
									<div>ausblenden</div> 
									<div>anzeigen</div>
								</div>";

							$view .="
								<svg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
									<rect x='4' y='6' width='16' height='2' fill='#333333'/>
									<rect x='4' y='11' width='16' height='2' fill='#333333'/>
									<rect x='4' y='16' width='16' height='2' fill='#333333'/>
								</svg>
							";
							$view .="</li>";
						}
					}
				}

			$view .= "</ul>";
			$view .="<input type='hidden' id='ff-teamoverview-user' name='".$field."' value='".get_option($field)."' />";
		}
		return $view;
	}

}

// get formated select field
function field_checkbox($field = NULL)
{
	if(!empty($field))
	{
		$view = '<select style="width:100%" name="'.$field.'">';
			$view .= (!empty(get_option($field)) AND get_option($field) == "1")? '<option value="0" selected >Nein</option>':'<option value="0" >Nein</option>';
			$view .= (!empty(get_option($field)) AND get_option($field) == "1")? '<option value="1" selected >Ja</option>':'<option value="1" >Ja</option>';
		$view .= '</select>';
		return $view;
	}
	
	
}

// get formated possible_options field
function possible_options($field = NULL, $options=null)
{
	if(!empty($field) &&!empty( $options))
	{
		$view = '<select name="'.$field.'">';
				$view .= '<option value="">Bitte wählen</option>';
				
				
		
			foreach($options as $row)
			{
				if(!empty(get_option($field))  && get_option($field) == $row['key'])
				{
					$view .= '<option value="'.$row['key'].'" selected >'.$row['label'].'</option>';
				}
				else
				{
					$view .= '<option value="'.$row['key'].'">'.$row['label'].'</option>';
				}
			
				
			}
		$view .= '</select>';
		return $view;
	}
}

// get formated checkbox field
function field_text($field = NULL , $default = NULL)
{
	if(!empty($field))
	{
			if(!empty(get_option($field)))
			{
				return '<input name="'.$field.'" type="text"  value="'.get_option($field).'" />';
			}
			elseif(!empty($default))
			{
				return '<input name="'.$field.'" type="text"  value="'.$default.'" />';
			}
			else
			{
				return '<input name="'.$field.'" type="text"  value="" />';
			}
	}
}

// get formated checkbox field
function field_textarea($field = NULL , $default = NULL)
{
	if(!empty($field))
	{
			if(!empty(get_option($field)))
			{
				return '<textarea style="border: 2px solid #e0e0e0; width: 100%; margin: 10px 0;" name="'.$field.'" rows="5">'.get_option($field).'</textarea>';
			}
			elseif(!empty($default))
			{
				return '<textarea style="border: 2px solid #e0e0e0; width: 100%; margin: 10px 0;" name="'.$field.'" rows="5">'.$default.'</textarea>';
			}
			else
			{
				return '<textarea style="border: 2px solid #e0e0e0; width: 100%; margin: 10px 0;" name="'.$field.'" rows="5"></textarea>';
			}
	}
}

// get formated text field
function field_password($field = NULL)
{
	if(!empty($field))
	{
		return '<input autocomplete="new-password"  name="'.$field.'" type="password"  value="'.get_option($field).'" />';
	}
}

// get formated text field
function field_color($field = NULL, $default = Null)
{
	if(!empty($field))
	{
		if(!empty(get_option($field)))
		{
			$view = '<input class="ff-colorpicker" style="padding: 0px; height: 34px;" name="'.$field.'" type="text"  value="'.get_option($field).'" />';
		}
		else
		{
			$view = '<input class="ff-colorpicker" style="padding: 0px; height: 34px;" name="'.$field.'" type="text"  value="" />';
		}

		$view .= '<script type="text/javascript">';
			$view .= 'jQuery(".ff-colorpicker").colorPicker(/* optinal options */);';
		$view .= '</script>';

		return $view;
	}
}

// get formated number field
function field_number($field = NULL)
{
	if(!empty($field))
	{
		return '<input name="'.$field.'" type="number"  value="'.get_option($field).'" />';
	}
}

// get file input
function field_file($field = NULL , $default = NULL)
{
	if(!empty($field))
	{
			if(!empty(get_option($field)))
			{
				return '<img src="'.get_option($field).'" width="100" height="auto" id="profilepicture">
								<input type="hidden" name="'.$field.'" value="'.get_option($field).'" />
								<button class="button wpse-228085-upload">Upload</button>';
			}
			elseif(!empty($default))
			{
				return '<img src="'.get_option($default).'" width="100" height="auto" id="profilepicture">
								<input type="hidden" name="'.$field.'" value="'.get_option($field).'" />
								<button class="button wpse-228085-upload">Upload</button>';
			}
			else
			{
				return '<img src="'.get_option($default).'" width="100" height="auto" id="profilepicture">
								<input type="hidden" name="'.$field.'" value="'.get_option($field).'" />
								<button class="button wpse-228085-upload">Upload</button>';
			}			
	}
}



	add_action('admin_enqueue_scripts', function(){
    /*
    if possible try not to queue this all over the admin by adding your settings GET page val into next
    if( empty( $_GET['page'] ) || "my-settings-page" !== $_GET['page'] ) { return; }
    */
    wp_enqueue_media();
});