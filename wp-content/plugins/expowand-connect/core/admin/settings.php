<?php 
	
	/**********************
	 * Add Admin panel
	 **********************/
	 
	add_action('admin_menu', function() {
		add_menu_page( 'EXPOWAND Connect settings', 'EXPOWAND', 'manage_options', 'ew-plugin', 'plugin_page'  );
		
		if ( empty ( $GLOBALS['admin_page_hooks']['ew-plugin'] ) ) 
		{
			add_menu_page( 'EXPOWAND Connect settings', 'Einstellungen', 'manage_options', 'ew-plugin', 'plugin_page'  );
		}

		wp_register_style('EW-admin-styles', plugins_url('/ew-admin-styles.css', __FILE__), '', '1.0.0', false);
    	wp_enqueue_style('EW-admin-styles');
		wp_register_script('EW-admin-scripts', plugins_url('/ew-admin-scripts.js', __FILE__), '', '1.0.0', true);
		wp_enqueue_script('EW-admin-scripts');

		// force load Jquery
		wp_enqueue_script( 'jquery');    

		wp_localize_script('EW-admin-scripts', 'ewdata', array(
                'ajaxurl' => admin_url('admin-ajax.php')
            )
        );
	});

	// register Settings
	add_action( 'admin_init', function() {

		//params
		$data = json_decode(EW_ADMIN_SETTINGS,true);
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
		<div class="wrap ew-module">
			<form action="options.php" method="post" enctype="multipart/form-data">

			<?php
				$catLast = "";
				settings_fields( 'plugin-settings' );
				do_settings_sections( 'plugin-settings' );
			?>
		
			<?php if (!empty(EW_ADMIN_SETTINGS)): ?>
				<h1>EXPOWAND Connect</h1>

				<?php $data = json_decode(EW_ADMIN_SETTINGS,true); ?>

				<?php
					// Check for plugins which block the loading of this plugin.

					$blocked_by_plugin = json_decode(EW_ADMIN_SETTINGS,true);
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
											<div class="ew-setting-modules">
												<div class="ew-setting-blocked-by">
													<div class="ew-setting-module-title ew-setting-done">
														<div class="ew-setting-error-point ">!</div>
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

										$class_disabled = "ew-setting-disabled";
										break;
									}
								}
							}
							else
							{
								$class_disabled = "";
							}
						?>

						<div class="ew-setting-modules <?= $class_disabled ?>">
							<?php if($catKey !== $catLast): ?>
								<div class="ew-setting-category">
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
										if (!empty(get_option($requiert)) || !empty($result))
										{
											$status = "<span style=\"color:#b7ce5b\">Vollständig eingerichtet</span>";
											$class = "ew-setting-done";
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
								<div  id="<?= $id ?>" class="ew-setting-module">
									<div  class="ew-setting-module-box <?= (empty($class_disabled))?"ew-settings-opener":""?> ">
										<div class="ew-setting-module-title <?= $class ?>">
											<div class="ew-setting-module-point ">
												<?= $module["point"] ?>
											</div>
											<?= $module["title"] ?>
										</div>
										<div class="ew-setting-module-status">
											<?= $status ?>
										</div>
									</div>
									<div class="ew-setting-module-box-content ew-setting-close">
										<div>
											<div class="ew-setting-module-content">
													<div>
														<?= $module["description"] ?>
													</div>

													<?php if(!empty($module["fields"])): ?>
														<div>
															<div class="ew-settings-form">
																<?php foreach($module["fields"] as $fieldkey => $field): ?>
																	<div class="ew-settings-field">
																		<div>
																			<b><?= $field["title"] ?></b>
																			<?php if(!empty($field["requiert"])):?>
																				<span class="ew-setting-requiert"> Pflichtfeld </span>
																			<?php endif; ?>
																		</div>
																		<div class="ew-setting-field-type-<?= $field["type"] ?>">
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
															<input type="submit" class="ew-setting-submit" value="<?= $module["save_label"] ?>" />
														<?php else: ?>
															<input type="submit" class="ew-setting-submit" value="Speichern" />
														<?php endif ?>
													<?php endif ?>

											</div>
											<div class="ew-setting-module-faq">
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
											if(!empty(get_option('ew-'.$key.'-route') or !empty(defined('EW_'.strtoupper($key).'_ROUTE'))))
											{
												$ewmodule_url = get_home_url()."/";
												$ewmodule_url .= (!empty(get_option('ew-plugin-route')))? get_option('ew-plugin-route'): constant('EW_PLUGIN_ROUTE');
												$ewmodule_url .="/";
												$ewmodule_url .= (!empty(get_option('ew-'.$key.'-route')))? get_option('ew-'.$key.'-route'):constant('EW_'.strtoupper($key).'_ROUTE');
											}
										?>


										<div class="ew-Integration <?= $class ?>">
											<div class="ew-possibleIntegration">

												<?php if (!empty($module["integration"]["possibleIntegrations"]["url"]) && !empty($ewmodule_url)): ?>
													<div>
														<h3><?= $module["integration"]["possibleIntegrations"]["url"]["title"] ?></h3>
														<p><?= $module["integration"]["possibleIntegrations"]["url"]["description"] ?></p>
														<a target="_blank" href="<?= $ewmodule_url ?>"><?= $ewmodule_url ?></a>
													</div>
												<?php endif; ?>

												<?php if (!empty($module["integration"]["possibleIntegrations"]["iframe"]) && !empty($ewmodule_url)): ?>
													<div>
														<h3><?= $module["integration"]["possibleIntegrations"]["iframe"]["title"] ?></h3>
														<p><?= $module["integration"]["possibleIntegrations"]["iframe"]["description"] ?></p>
														<code>&lt;iframe src="<?= $ewmodule_url."?iframe=1" ?>" width="100%" height="2000px" style="border:0;" scrolling="auto" &gt;&lt;/iframe&gt;</code>
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
														<a target="_blank" href="<?= $ewmodule_url ?><?= $module["integration"]["possibleIntegrations"]["sitemap"]["value"] ?>.xml"><?= $ewmodule_url ?><?= $module["integration"]["possibleIntegrations"]["sitemap"]["value"] ?>.xml</a><br/>
														<a target="_blank" href="<?= $ewmodule_url ?><?= $module["integration"]["possibleIntegrations"]["sitemap"]["value"] ?>.txt"><?= $ewmodule_url ?><?= $module["integration"]["possibleIntegrations"]["sitemap"]["value"] ?>.txt</a>
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

		  case ("number"):
				return field_number($field);
		  break;

		  case ("password"):
				return field_password($field);
		  break;

		  case ("checkbox"):
				return field_checkbox($field);
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