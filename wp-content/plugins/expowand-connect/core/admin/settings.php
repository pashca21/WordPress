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
				<img width="200px"; src="" />

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