<?php
// get config
class FFestateViewCore extends API
{
	// load widget of estateview
	public function widget()
	{
		// get mapping
		$data["mapping"] = json_decode(FF_ESTATEVIEW_SALESAUTOMATE_MAPPING, true);
		$data["search"]["path"] = get_bloginfo('wpurl') . '/' . FF_PLUGIN_ROUTE . '/' . FF_ESTATEVIEW_ROUTE;

		// get set title
		$result['title'] = "Immobilien Suche";
		$result['content'] = $this->get_html("widget", FF_ESTATEVIEW_THEME, $data);

		return $result;
	}

	// Add meta tags to single property header
	public function ff_add_meta_to_header() {
		if(defined('FF_META_TITLE')) {
			echo '<meta property="og:title" content="'.FF_META_TITLE.'">';
			echo '<meta property="og:image:alt" content="'.FF_META_TITLE.'">';
			echo '<meta property="twitter:title" content="'.FF_META_TITLE.'">';
		}
		
		if(defined('FF_META_DESCRIPTION')) {
			echo '<meta property="og:type" content="article" />';
			echo '<meta name="description" content="'.FF_META_DESCRIPTION.'">';
			echo '<meta property="og:description" content="'.FF_META_DESCRIPTION.'">';
			echo '<meta property="twitter:description" content="'.FF_META_DESCRIPTION.'">';
		}

		if(defined('FF_META_IMAGE')) {
			echo '<meta property="og:image" content="'.FF_META_IMAGE.'">';
			echo '<meta property="twitter:image" content="'.FF_META_IMAGE.'">';
		}
	}

	// get estate details
	public function get_estate_details($schemaId, $id)
	{
		if (!empty($id) and !empty($schemaId))
		{
			// get estate details
			$results[0] = $this->get_entity_by_id($schemaId, sanitize_key($id));

			// if(!empty($results[0]["securitydeposit"]["values"][0])) {
			// 	// test if string contains 'letters'
			// 	if(!preg_match("#[^0-9.,]#", $results[0]["securitydeposit"]["values"][0])){
			// 		$results[0]["securitydeposit"]["values"][0] .= " €";
			// 	}
			// }

			// if (isset($results[0]['onlineImage']) && isset($results[0]['onlineImage']['values']))
			// {
			// 	$results[0]['onlineImage']['values'] = $this->sortOnlineImages($results[0]['onlineImage']['values']);
			// }
			// $schema = $this->get_schemas_by_name($results[0]['_metadata']['documentType']);

			// $data["mapping"] = json_decode(FF_ESTATEVIEW_SALESAUTOMATE_MAPPING, true);

			// // Schemas mapping for fields with different captions but the same field names
			// if($schema["name"] == "office_surgery_rent") {
			// 	$data["mapping"]["details"]["default"]["details"]["pricesqm"]["caption"] = "Mietpreis pro m²";
			// 	$data["mapping"]["details"]["default"]["details"]["securitydeposit"]["unit"] = "";
			// }

			// if($schema["name"] == "flat_rent") {
			// 	$data["mapping"]["details"]["default"]["details"]["completiondate"]["caption"] = "Verfügbar ab";
			// }

			// // check if data for availableFrom field is a date string
			// if(!empty($results[0]['availableFrom']['values'][0]) && is_numeric( strtotime( $results[0]['availableFrom']['values'][0] ) )) {
			// 	//if so trim it and change field type to text
			// 	$results[0]['availableFrom']['values'][0] = substr($results[0]['availableFrom']['values'][0], 0, 10);
			// 	$data["mapping"]["details"]["default"]["details"]["availableFrom"]["type"] = "text";
			// }
			
			// get fields
			// $fields = $this->getFields($data["mapping"]["details"], $schema["name"], $results, false);

			// if (!empty($fields))
			// {
			// 	$data["page"]["results"] = $fields;
			// }
			// else
			// {
			// 	$fields = $this->getFields($data["mapping"]["details"], "default", $results, false);
			// 	if (!empty($fields))
			// 	{
			// 		$data["page"]["results"] = $fields;
			// 	}
			// 	else
			// 	{
			// 		$result['title'] = "Fehler 404";
			// 		$this->show_404();
			// 	}
			// }

			// remove related estates which are not included in the WordPress portal
			// if(!empty($data["page"]["results"][$id]["related"])) {
			// 	// fetch properties
			// 	$data["mapping"] = json_decode(FF_ESTATEVIEW_SALESAUTOMATE_MAPPING, true);
			// 	$data["estatesorting"] = get_option("ff-estateView-estate-sorting");
			// 	$wordpress_estates = $this->get_search($data, $attr);

			// 	// remove properties which are not included
			// 	$data["page"]["results"][$id]["related"] = array_intersect_key($data["page"]["results"][$id]["related"], $wordpress_estates["search"]["results"]);
			// }

			// check if estate is still published
			$publshed = API::get_portals_publsidhed_estates(sanitize_key($id) , FF_ESTATEVIEW_PUBLISH);

			// set seo slug
			if (!empty(FF_ESTATEVIEW_SEO_SLUG))
			{
				foreach ($data["page"]["results"] as $key => $entity)
				{
					if (!empty($data["page"]["results"][$key]["related"]))
					{
						$data["page"]["results"][$key]["related"] = $this->add_seo_slug($data["page"]["results"][$key]["related"], FF_ESTATEVIEW_SEO_SLUG);
					}
				}
			}

			// attached new multimedia images to entity
			$multimedia = API::get_estate_images(array_keys($data["page"]["results"]));

			// TODO: DELETE WORKAROUND MAIN_IMAGE CHECK
			if (!empty($multimedia["entities"]["0"]["assignments"]) && !empty($multimedia["entities"]["0"]["assignments"]["main_image"]))
			{
				foreach ($data["page"]["results"] as $key => $entity)
				{
					foreach ($multimedia["entities"] as $images)
					{
						if (!empty($images["assignments"]))
						{
							$data["page"]["results"][$key]["multimedia"] = $images["assignments"];
						}
					}
				}
			}

			$viewedCookiePolicy = isset($_COOKIE["viewed_cookie_policy"]) && $_COOKIE["viewed_cookie_policy"] === "yes";
			$acceptedNecessaryCookiePolicy = isset($_COOKIE['cookielawinfo-checkbox-necessary']) && $_COOKIE['cookielawinfo-checkbox-necessary'] === 'yes';

			$data["isPolicyCookieAccepted"]   = $viewedCookiePolicy && $acceptedNecessaryCookiePolicy;
			$data["page"]["url"]              = get_bloginfo('wpurl') . '/' . FF_PLUGIN_ROUTE . '/' . FF_ESTATEVIEW_ROUTE;
			$data["page"]["schema"]           = $schema["name"];
			$data["color"]["primary"]         = FF_PRIMARY_COLOR;
			$data["color"]["secondary"]       = FF_SECONDARY_COLOR;
			$data["api"]["cloudimage"]["url"] = FF_CLOUDIMAGE_IO_URL;
			$data["api"]["maps"]["key"]       = FF_GG_API_MAPS;
			$data["legal"]["imprint"]         = FF_IMPRINT_URL;
			$data["legal"]["privacy"]         = FF_PRIVACY_URL;
			$data["finance"]                  = get_option("ff-estateView-show-finance-calculator");
			$data["socialmedia"]              = get_option('ff-estateView-show-socialmedia-links');
			$data["imagepath"]                = plugin_dir_url(dirname(__FILE__)) . "/estateView/assets/img/" . FF_ESTATEVIEW_THEME;
			$data["currentUrl"]               = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$data["company"]				    			= $this->get_company_data();
			$data["property_slider"]		  		= get_option("ff-estateView-select-slider");
			$data["property_headline"]        = get_option("ff-estateView-headline");
			$data["privacy_page_url"]				  = get_option("ff-privacy-url");
			$data["ff_maps_default_provider"]	= get_option("ff-maps-default");

			// delete street if not published
			if (!empty($publshed) && (empty($publshed["showAddress"]) or $publshed["showAddress"] == 0))
			{
				unset($data["page"]["results"][$id]["facts"]["addresses"]["value"]["street"]);
			}

			if(!isset($data["page"]["results"][$id]["facts"]["addresses"]["value"]["street"])) {
				// area highlight 

				if(isset($data["page"]["results"][$id]["facts"]["addresses"]["value"]["geolocation"]["latitude"])) {
					$latitude = $data["page"]["results"][$id]["facts"]["addresses"]["value"]["geolocation"]["latitude"];
				}
				if(isset($data["page"]["results"][$id]["facts"]["addresses"]["value"]["geolocation"]["longitude"])) {
					$longitude = $data["page"]["results"][$id]["facts"]["addresses"]["value"]["geolocation"]["longitude"];
				}
				
				if(isset($latitude) && isset($longitude)) {
					$estate_geo_shape = API::fetch_shape_data($latitude, $longitude);
					$estate_geo_shape = json_decode($estate_geo_shape);
				}

				if(isset($estate_geo_shape)) {
					if(!count($estate_geo_shape) > 0) {
						// set new var to not display the map
						$data["ff_maps_no_highlight"]	= 1;
					}
				}
			}

			// change view to frame if set
			if (!empty($_GET["iframe"]) and $_GET["iframe"] == "1")
			{
				$data["frame"] = "1";
			}

			// adds contact form to estate details if SMTP Data avadible
			if ((!empty(FF_MAIL_FROM) and !empty(FF_MAIL_SERVER) and !empty(FF_MAIL_USER) and !empty(FF_MAIL_PASS)) or (get_option("ff-nylas-account")))
			{
				$data["show"]["contactFrom"] = true;
			}
			else
			{
				$data["show"]["contactFrom"] = false;
			}

			if ($results[0]["status"]["values"][0] === "active" && !empty($publshed))
			{
				// get html
				if (!empty($results[0]["headline"]["values"][0]))
				{
					$result['title'] = $results[0]["headline"]["values"][0];
				} else {
					$result['title'] = "Immobilie";
				}

				$meta_title = API::get_company_data();
				$meta_title = $meta_title['companyName'];

				if($meta_title) {
					define('FF_META_TITLE', $meta_title);
				}
				
				$meta_description = $result['title'];

				if($meta_description) {
					define('FF_META_DESCRIPTION', $meta_description);
				}
				
				if(!empty($data['page']['results'][$id]['mainImage']['mainImage'][0]['value'])) {
					$meta_image = $data['page']['results'][$id]['mainImage']['mainImage'][0]['value'];
				} elseif (!empty($data['page']['results'][$id]['mainImage']['onlineImage'][0]['value'])) {
					$meta_image = $data['page']['results'][$id]['mainImage']['onlineImage'][0]['value'];
				}

				if(!empty($meta_image)) {
					define('FF_META_IMAGE', $meta_image);
				}

				if(defined('FF_META_TITLE') or defined('FF_META_DESCRIPTION') or define('FF_META_IMAGE')) {
					add_action('wp_head', array($this, 'ff_add_meta_to_header'), -1);
				}


				$result['content'] = $this->get_html("page-details", FF_ESTATEVIEW_THEME, $data);

				return $result;
			}
			else
			{
				$result['title'] = "Fehler 404";
				$this->show_404();
			}
		}
		else
		{
			$result['title'] = "Fehler 404";
			$this->show_404();
		}
	}

	// get overview / estate list
	public function get_estate_overview($attr = NULL)
	{
		// get mapping
		$data["mapping"] = json_decode(FF_ESTATEVIEW_SALESAUTOMATE_MAPPING, true);
		
		// get set title
		$result['title']       = "Immobilien Suche";
		$data["estatesorting"] = get_option("ff-estateView-estate-sorting");

		// get content
		$data = $this->get_search($data, $attr);

		// attached new multimedia images to entity
		if (!empty($data["search"]["results"]))
		{
			$multimedia = API::get_estate_images(array_keys($data["search"]["results"]));

			if(!empty($multimedia)) {
				foreach ($multimedia["entities"] as $images)
				{
					if (!empty($images["assignments"]))
					{
						$data["search"]["results"][$images["entityId"]]["multimedia"] = $images["assignments"];
					}
				}
			}
		}

		$result["content"] = "";

		if (!empty($data))
		{
			$result["content"] = $this->get_html("page-overview", FF_ESTATEVIEW_THEME, $data);
		}

		return $result;
	}

	public function get_estate_slider($attr = NULL)
	{
		$result['title'] = 'Immobilien Slider';
		$data['mapping'] = json_decode(FF_ESTATEVIEW_SALESAUTOMATE_MAPPING, true);
		$data = $this->get_search($data, $attr);

		if (!empty($data['search']['results']))
		{
			$multimedia = API::get_estate_images(array_keys($data['search']['results']));
			foreach ($multimedia['entities'] as $images)
			{
				if (!empty($images['assignments']) && !empty($images['assignments']['main_image']))
				{
					$data['search']['results'][$images['entityId']]['multimedia'] = $images['assignments']['main_image'];
				}
			}
		}

		$result['content'] = '';
		if (!empty($data))
		{
			$result['content'] = $this->get_html('page-slider', FF_ESTATEVIEW_THEME, $data);
		}

		return $result;
	}

	// get sitemap
	public function get_estate_sitemap($type = NULL)
	{
		if (!empty($type))
		{
			// get mapping
			$data["mapping"] = json_decode(FF_ESTATEVIEW_SALESAUTOMATE_MAPPING, true);

			// get set title
			$result['title'] = "Immobilien Suche";

			// get content
			if (!empty($data))
			{
				$data = $this->get_search($data);
				$data['SEO'] = FF_ESTATEVIEW_SEO_SITEMAP;
			}

			// set seo slug
			if (!empty(FF_ESTATEVIEW_SEO_SLUG))
			{
				$data["search"]["results"] = $this->add_seo_slug($data["search"]["results"], FF_ESTATEVIEW_SEO_SLUG);
			}

			// get HTML
			if (!empty($data))
			{
				$result['content'] = $this->get_html("page-sitemap-" . $type, FF_ESTATEVIEW_THEME, $data);
			}
			else
			{
				$result['content'] = "";
			}
			return $result;
		}
		else
		{
			$this->show_404();
		}
	}

	// get agent for spesific estate
	public function get_estate_agent($id = NULL)
	{
		// get contact data form API
		if (!empty($id))
		{

			$data = $this->get_entity_by_id('contacts', sanitize_key($id));

			// attached new multimedia images to entity
			$multimedia = API::get_estate_images([$id], "contacts?albumName=flowfact_client&showEmptyCategories=false&short=false");

			// TODO: DELETE WORKAROUND MAIN_IMAGE CHECK
			if (!empty($multimedia["entities"]["0"]["assignments"]["main_image"]))
			{
				foreach ($multimedia["entities"] as $images)
				{
					if (!empty($images["assignments"]))
					{
						$data["multimedia"] = $images["assignments"];
					}
				}
			}
			return $data;
		}

		return null;
	}

	// get related estate for spesific estate
	public function get_estate_related_estates($id = NULL, $fields = NULL)
	{
		// get contact data form API
		if (!empty($id))
		{

			//build Query
			$search["target"] = "ENTITY";
			$search["fetch"] = array();
			array_push($search["fetch"], "id");
			array_push($search["fetch"], "_metadata");
			foreach ($fields as $key => $schema)
			{
				array_push($search["fetch"], $key);
			}

			if (!empty(FF_ESTATEVIEW_SEO_SLUG))
			{
				$mapping = json_decode(FF_ESTATEVIEW_SALESAUTOMATE_MAPPING, true);
				foreach ($mapping["seo"]["default"]["data"] as $key => $schema)
				{
					array_push($search["fetch"], $key);
				}
			}

			$search["conditions"][0]["type"] = "HASFIELDWITHVALUE";
			$search["conditions"][0]["field"] = "parent";
			$search["conditions"][0]["value"] = $id;

			//call API
			$result = API::get_entities_by_search("estates", $search, 50, 1);

			foreach ($result["entries"] as $row => $estate)
			{
				foreach ($fields as $key => $field)
				{
					if (!empty($field))
					{
						if (!empty($estate["_metadata"]["id"]) && ($id != $estate["_metadata"]["id"]))
						{
							$estate_list[$estate["_metadata"]["id"]][$key] = API::get_formated_fields($key, $field, $estate);
						}
					}
				}
			}

			if (!empty(FF_ESTATEVIEW_SEO_SLUG) && !empty($result["entries"]))
			{

				foreach ($result["entries"] as $estate)
				{
					foreach ($estate as $row => $field)
					{
						if (!empty($field["values"][0]))
						{
							$estate_list[$estate["_metadata"]["id"]]["seo"][$row]["value"] = $field["values"][0];
						}
					}

				}

				$estate_list = $this->add_seo_slug($estate_list, FF_ESTATEVIEW_SEO_SLUG);
			}

			return $estate_list;
		}
		return null;
	}

	// get search query for dsl
	protected function get_search($data = NULL, $attr = NULL)
	{
		if (empty($data))
		{
			return;
		}
		// default
		$data["search"]["search"]["schema"] = "default";
		$data["search"]["search"]["show_search"] = "on";
		$data["search"]["search"]["show_map"] = "on";
		$data["api"]["maps"]["key"] = FF_GG_API_MAPS;

		// get search params
		if (!empty($_GET))
		{
			foreach ($_GET as $key => $value)
			{
				if ($key != "iframe")
				{
					$data["search"]["search"][sanitize_text_field($key) ] = esc_html(sanitize_text_field($value));
				}
			}
		} else {
			if ( get_option("ff-estateView-estate-sorting") == "price-ascending" ) {
				// Preis aufsteigend
				$data["search"]["search"]["sort"] = "price";
				$data["search"]["search"]["view"] = "list";
			} 
			elseif ( get_option("ff-estateView-estate-sorting") == "price-descending" ) {
				// Preis absteigend
				$data["search"]["search"]["sort"] = "price_up";
				$data["search"]["search"]["view"] = "list";
			} 
			else {
				$data["search"]["search"]["sort"] = "date";
				$data["search"]["search"]["view"] = "list";
			}
		}

		// overwrite Schema
		if (!empty($attr['schema']))
		{
			$data["search"]["search"]["schema"] = $attr['schema'];
		}
		if (!empty($attr['show_search']))
		{
			$data["search"]["search"]["show_search"] = strtolower($attr['show_search']);
		}

		if (!empty($attr['location']))
		{
			$data["search"]["search"]["location"] = $attr['location'];
		}
		// get list
		$data = $this->get_search_result($data);
		$fileData = get_file_data(dirname(__FILE__) . '/../../expowand-connect.php', ['Version' => 'Version'], 'plugin');
		$data['pluginVersion'] = $fileData['Version'];

		// get html
		return $data;
	}

	// return search
	protected function get_search_result($data = NULL, $page = 1)
	{
		if (!empty($data["search"]["search"]))
		{

			// set schema
			if (empty($data["search"]["search"]["schema"]) or $data["search"]["search"]["schema"] == "default")
			{
				$schemaId = "estates";
			}
			else
			{
				$schemaId = $data["search"]["search"]["schema"];
			}

			// set page
			if (!empty($data["search"]["search"]["ffpage"]))
			{
				$page = $data["search"]["search"]["ffpage"];
			}

			//get blocked estates (Deprecated)
			//$blockedEstates = $this->get_blocked_estates();
			
			// get search query
			if (!empty($data["search"]["search"]["view"]) and $data["search"]["search"]["view"] == "map")
			{
				$search = $this->get_search_query_list($data, $schemaId);
				$max_results = 1000;
			}
			else
			{
				$search = $this->get_search_query_list($data, $schemaId);
				$max_results = FF_ESTATEVIEW_MAX_RESULT;
			}
			
			// get entries
			$result = API::findEstatesBySchemaIdAndSearch($schemaId, $search, $max_results );

			$sortedEntities = $this->sortSearchResultsToNewest($search, $result);

			// replace entries by max count of entities for requested page
			$result['entries'] = $this->getMaxEntitiesForPage($sortedEntities, $max_results, $page);

			// if mapping for estate dosent exisit run again with default
			$fields = $this->getFields($data["mapping"]["list"], $data["search"]["search"]["schema"], $result["entries"], false);

			// get fields for list
			if (!empty($fields))
			{
				$data["search"]["results"] = $fields;
			}
			else
			{
				$data["search"]["results"] = $this->getFields($data["mapping"]["list"], "default", $result["entries"]);
			}

			if (!empty(FF_ESTATEVIEW_SEO_SLUG))
			{
				$data["search"]["results"] = $this->add_seo_slug($data["search"]["results"], FF_ESTATEVIEW_SEO_SLUG);
			}

			$totalCount = API::getCountOfEntitiesBySchemaId($schemaId, $search);
			// data for template
			$data["search"]["total_count"] = $totalCount;
			$data["search"]["page"] = $page;
			$data["search"]["page_max"] = ceil($totalCount / $max_results);
			$data["search"]["path"] = get_bloginfo('wpurl') . '/' . FF_PLUGIN_ROUTE . '/' . FF_ESTATEVIEW_ROUTE;
			$data["search"]["schema"] = $schemaId;
			$data["color"]["primary"] = FF_PRIMARY_COLOR;
			$data["color"]["secondary"] = FF_SECONDARY_COLOR;
			$data["api"]["cloudimage"]["url"] = FF_CLOUDIMAGE_IO_URL;
			$data["api"]["maps"]["key"] = FF_GG_API_MAPS;
			$data["api"]["maps"]["path"] = plugin_dir_url(dirname(__FILE__)) . "/estateView/assets/img/" . FF_ESTATEVIEW_THEME . "/";
 
			// change view to frame if set
			if (!empty($_GET["iframe"]) && $_GET["iframe"] == "1")
			{
				$data["frame"] = "1";
			}

			return $data;
		}
	}

	// return search_query for map
	protected function get_search_query_map($data = NULL, $schemaId = NULL)
	{
		$search["target"] = "ENTITY";
		$i = 0;

		// result fields
		$search["fetch"] = array();
		array_push($search["fetch"], "id");
		array_push($search["fetch"], "_metadata");
		array_push($search["fetch"], "latitude");
		array_push($search["fetch"], "longitude");
		array_push($search["fetch"], "estatetype");
		array_push($search["fetch"], "rent");
		array_push($search["fetch"], "tenancy");
		array_push($search["fetch"], "purchaseprice");
		array_push($search["fetch"], "price_on_request");
		if (!empty(FF_ESTATEVIEW_SEO_SLUG))
		{
			$mapping = json_decode(FF_ESTATEVIEW_SALESAUTOMATE_MAPPING, true);
			foreach ($mapping["seo"]["default"]["data"] as $key => $schema)
			{
				array_push($search["fetch"], $key);
			}
		}

		// get published Estates
		$publshed = API::get_portals_publsidhed_estates_by_portal_id(FF_ESTATEVIEW_PUBLISH);

		$search["conditions"][$i]["type"] = "AND";
		$search["conditions"][$i]["conditions"][0]["type"] = "ENTITYID";
		$search["conditions"][$i]["conditions"][0]["values"][] = "00000000-0000-0000-0000-000000000001";

		if (!empty($publshed))
		{
			foreach ($publshed as $publshed_estate)
			{
				$search["conditions"][$i]["conditions"][0]["values"][] = $publshed_estate['entityId'];
			}
		}
		$i++;

		// get published Estates
		$publshed = API::get_portals_publsidhed_estates_by_portal_id(FF_ESTATEVIEW_PUBLISH);

		$search["conditions"][$i]["type"] = "AND";
		$search["conditions"][$i]["conditions"][0]["type"] = "ENTITYID";
		$search["conditions"][$i]["conditions"][0]["values"][] = "00000000-0000-0000-0000-000000000001";

		if (!empty($publshed))
		{
			foreach ($publshed as $publshed_estate)
			{
				$search["conditions"][$i]["conditions"][0]["values"][] = $publshed_estate['entityId'];
			}
		}

		foreach ($data["search"]["search"] as $field => $value)
		{

			// set schema
			$schema = $data["search"]["search"]["schema"];

			// block search form unnessary fields
			$block = array(
				"show_search",
				"show_map",
				"schema",
				"sort",
				"ffpage",
				"view",
				"lat",
				"lng",
				"price",
				"country",
				"location"
			);
			if (in_array($field, $block) !== true)
			{
				if (!empty($value))
				{

					// get prefix if not empty used for schema spesific fields to select search from or to a value
					if (!empty($data["mapping"]["search"][$schema][$field]["prefix"]))
					{
						$prefix = $data["mapping"]["search"][$schema][$field]["prefix"];
					}

					if (!empty($prefix))
					{
						$search["conditions"][$i]["type"] = "HASFIELDWITHVALUE";
						$search["conditions"][$i]["field"] = $field . "." . $prefix;
						if ($prefix === "from")
						{
							$search["conditions"][$i]["operator"] = "GREATER_EQUAL";
						}
						else
						{
							$search["conditions"][$i]["operator"] = "LESS_EQUAL";
						}
						$search["conditions"][$i]["value"] = $value;
						$i++;
					}
					else
					{
						$search["conditions"][$i]["type"] = "HASFIELDWITHVALUE";
						$search["conditions"][$i]["field"] = $field;
						$search["conditions"][$i]["value"] = $value;
						$i++;
					}
				}
			}
		}

		if (!empty($data["search"]["search"]["location"]) && empty($data["search"]["search"]["lat"]) && empty($data["search"]["search"]["lng"]))
		{
			$countrys = explode(',', $data["search"]["search"]["location"]);

			if (count($countrys) > 0)
			{
				$search["conditions"][$i]["type"] = "or";
				$a = 0;

				foreach ($countrys as $row)
				{
					$search["conditions"][$i]["conditions"][$a]["type"] = "HASFIELDWITHVALUE";
					$search["conditions"][$i]["conditions"][$a]["field"] = "addresses.country";
					$search["conditions"][$i]["conditions"][$a]["value"] = str_replace("\u00df", "ß", $row);
					$a++;
				}
				$i++;

			}
			else
			{
				$search["conditions"][$i]["type"] = "AND";
				$search["conditions"][$i]["conditions"][0]["type"] = "HASFIELDWITHVALUE";
				$search["conditions"][$i]["conditions"][0]["field"] = "addresses.country";
				$search["conditions"][$i]["conditions"][0]["value"] = $data["search"]["search"]["location"];
				$i++;
			}
		}
		else
		{
			if (!empty($data["search"]["search"]["lat"]))
			{
				$search["conditions"][$i]["type"] = "AND";
				$search["conditions"][$i]["conditions"][0]["type"] = "HASFIELDWITHVALUE";
				$search["conditions"][$i]["conditions"][0]["field"] = "latitude";
				$search["conditions"][$i]["conditions"][0]["operator"] = "GREATER_EQUAL";
				$search["conditions"][$i]["conditions"][0]["value"] = $data["search"]["search"]["lat"] - 0.05;

				$search["conditions"][$i]["conditions"][1]["type"] = "HASFIELDWITHVALUE";
				$search["conditions"][$i]["conditions"][1]["field"] = "latitude";
				$search["conditions"][$i]["conditions"][1]["operator"] = "LESS_EQUAL";
				$search["conditions"][$i]["conditions"][1]["value"] = $data["search"]["search"]["lat"] + 0.05;

				$i++;
			}

			if (!empty($data["search"]["search"]["lng"]))
			{
				$search["conditions"][$i]["type"] = "AND";
				$search["conditions"][$i]["conditions"][0]["type"] = "HASFIELDWITHVALUE";
				$search["conditions"][$i]["conditions"][0]["field"] = "longitude";
				$search["conditions"][$i]["conditions"][0]["operator"] = "GREATER_EQUAL";
				$search["conditions"][$i]["conditions"][0]["value"] = $data["search"]["search"]["lng"] - 0.05;

				$search["conditions"][$i]["conditions"][1]["type"] = "HASFIELDWITHVALUE";
				$search["conditions"][$i]["conditions"][1]["field"] = "longitude";
				$search["conditions"][$i]["conditions"][1]["operator"] = "LESS_EQUAL";
				$search["conditions"][$i]["conditions"][1]["value"] = $data["search"]["search"]["lng"] + 0.05;
				$i++;
			}
		}

		// only published
		$search["conditions"][$i]["type"] = "AND";
		$search["conditions"][$i]["conditions"][0]["type"] = "HASFIELDWITHVALUE";
		$search["conditions"][$i]["conditions"][0]["field"] = "status";
		$search["conditions"][$i]["conditions"][0]["value"] = "active";
		$i++;

		if (!empty($data["search"]["search"]["price"]))
		{
			$search["conditions"][$i]["type"] = "OR";
			$search["conditions"][$i]["conditions"][0]["type"] = "HASFIELDWITHVALUE";
			$search["conditions"][$i]["conditions"][0]["field"] = "purchaseprice";
			$search["conditions"][$i]["conditions"][0]["operator"] = "LESS_EQUAL";
			$search["conditions"][$i]["conditions"][0]["value"] = $data["search"]["search"]["price"];

			$search["conditions"][$i]["conditions"][1]["type"] = "HASFIELDWITHVALUE";
			$search["conditions"][$i]["conditions"][1]["field"] = "rent";
			$search["conditions"][$i]["conditions"][1]["operator"] = "LESS_EQUAL";
			$search["conditions"][$i]["conditions"][1]["value"] = $data["search"]["search"]["price"];
			$i++;
		}

		return $search;
	}

	// return search query for list
	protected function get_search_query_list($data = NULL, $schemaId = NULL, $blockedEstates = Null)
	{
		$i = 0;
		if (!empty($data))
		{
			$search["target"] = "ENTITY";
			$search["fetch"] = array();

			foreach ($data["mapping"]["list"] as $key => $schema)
			{

				if ($key == $data["search"]["search"]["schema"])
				{
					array_push($search["fetch"], "id");
					array_push($search["fetch"], "_metadata");

					foreach ($schema as $fields)
					{
						foreach ($fields as $key2 => $field)
						{
							array_push($search["fetch"], $key2);
						}

						if (!empty(FF_ESTATEVIEW_SEO_SLUG))
						{
							$mapping = json_decode(FF_ESTATEVIEW_SALESAUTOMATE_MAPPING, true);
							foreach ($mapping["seo"]["default"]["data"] as $key => $schema)
							{
								array_push($search["fetch"], $key);
							}
						}
					}
				}
			}

			// get published Estates
			$publshed = API::get_portals_publsidhed_estates_by_portal_id(FF_ESTATEVIEW_PUBLISH);

			$search["conditions"][$i]["type"] = "AND";
			$search["conditions"][$i]["conditions"][0]["type"] = "ENTITYID";
			$search["conditions"][$i]["conditions"][0]["values"][] = "00000000-0000-0000-0000-000000000001";

			if (!empty($publshed))
			{
				usort($publshed, function ($published1, $published2)
				{
					$date1 = $published1['onlineSince'];
					$date2 = $published2['onlineSince'];
					if ($date1 === $date2)
					{
						return 0;
					}
					return $date1 > $date2 ? -1 : 1;
				});
				foreach ($publshed as $publshed_estate)
				{
					$search["conditions"][$i]["conditions"][0]["values"][] = $publshed_estate['entityId'];
				}
			}
			$i++;

			// search for field
			if (!empty($data["search"]["search"]))
			{

				foreach ($data["search"]["search"] as $field => $value)
				{

					// set schema
					$schema = $data["search"]["search"]["schema"];

					// block search form unnessary fields
					$block = array(
						"show_search",
						"show_map",
						"schema",
						"sort",
						"ffpage",
						"view",
						"lat",
						"lng",
						"price",
						"country",
						"location"
					);
					if (in_array($field, $block) !== true)
					{
						if (!empty($value))
						{

							// get prefix if not empty used for schema spesific fields to select search from or to a value
							if (!empty($data["mapping"]["search"][$schema][$field]["prefix"]))
							{
								$prefix = $data["mapping"]["search"][$schema][$field]["prefix"];
							}

							if (!empty($prefix))
                            {
                                $search["conditions"][$i]["type"] = "HASFIELDWITHVALUE";
                                $search["conditions"][$i]["field"] = $field . "." . $prefix;
                                if ($prefix === "from")
                                {
                                    $search["conditions"][$i]["operator"] = "GREATER_EQUAL";
                                }
                                else
                                {
                                    $search["conditions"][$i]["operator"] = "LESS_EQUAL";
                                }
                                $search["conditions"][$i]["value"] = $value;
                                $i++;
                            }
                            else
                            {
                                $search["conditions"][$i]["type"] = "HASFIELDWITHVALUE";
                                $search["conditions"][$i]["field"] = $field;
                                $search["conditions"][$i]["value"] = $value;
                                $i++;
                            }
                        }
                    }
                }

                if (!empty($data["search"]["search"]["location"]) && empty($data["search"]["search"]["lat"]) && empty($data["search"]["search"]["lng"]))
                {
                    $countrys = explode(',', $data["search"]["search"]["location"]);

                    if (count($countrys) > 0)
                    {
                        $search["conditions"][$i]["type"] = "or";
                        $a = 0;

                        foreach ($countrys as $row)
                        {
                            $search["conditions"][$i]["conditions"][$a]["type"] = "HASFIELDWITHVALUE";
                            $search["conditions"][$i]["conditions"][$a]["field"] = "addresses.country";
                            $search["conditions"][$i]["conditions"][$a]["value"] = str_replace("\u00df", "ß", $row);
                            $a++;
                        }
                        $i++;

                    }
                    else
                    {
                        $search["conditions"][$i]["type"] = "AND";
                        $search["conditions"][$i]["conditions"][0]["type"] = "HASFIELDWITHVALUE";
                        $search["conditions"][$i]["conditions"][0]["field"] = "addresses.country";
                        $search["conditions"][$i]["conditions"][0]["value"] = $data["search"]["search"]["location"];
                        $i++;
                    }
                }
                else
                {
                    if (!empty($data["search"]["search"]["lat"]))
                    {
                        $search["conditions"][$i]["type"] = "AND";
                        $search["conditions"][$i]["conditions"][0]["type"] = "HASFIELDWITHVALUE";
                        $search["conditions"][$i]["conditions"][0]["field"] = "latitude";
                        $search["conditions"][$i]["conditions"][0]["operator"] = "GREATER_EQUAL";
                        $search["conditions"][$i]["conditions"][0]["value"] = $data["search"]["search"]["lat"] - 0.05;

                        $search["conditions"][$i]["conditions"][1]["type"] = "HASFIELDWITHVALUE";
                        $search["conditions"][$i]["conditions"][1]["field"] = "latitude";
                        $search["conditions"][$i]["conditions"][1]["operator"] = "LESS_EQUAL";
                        $search["conditions"][$i]["conditions"][1]["value"] = $data["search"]["search"]["lat"] + 0.05;

                        $i++;
                    }

                    if (!empty($data["search"]["search"]["lng"]))
                    {
                        $search["conditions"][$i]["type"] = "AND";
                        $search["conditions"][$i]["conditions"][0]["type"] = "HASFIELDWITHVALUE";
                        $search["conditions"][$i]["conditions"][0]["field"] = "longitude";
                        $search["conditions"][$i]["conditions"][0]["operator"] = "GREATER_EQUAL";
                        $search["conditions"][$i]["conditions"][0]["value"] = $data["search"]["search"]["lng"] - 0.05;

                        $search["conditions"][$i]["conditions"][1]["type"] = "HASFIELDWITHVALUE";
                        $search["conditions"][$i]["conditions"][1]["field"] = "longitude";
                        $search["conditions"][$i]["conditions"][1]["operator"] = "LESS_EQUAL";
                        $search["conditions"][$i]["conditions"][1]["value"] = $data["search"]["search"]["lng"] + 0.05;
                        $i++;
                    }
                }

                if (!empty($data["search"]["search"]["price"]))
                {
                    $search["conditions"][$i]["type"] = "OR";
                    $search["conditions"][$i]["conditions"][0]["type"] = "HASFIELDWITHVALUE";
                    $search["conditions"][$i]["conditions"][0]["field"] = "purchaseprice";
                    $search["conditions"][$i]["conditions"][0]["operator"] = "LESS_EQUAL";
                    $search["conditions"][$i]["conditions"][0]["value"] = $data["search"]["search"]["price"];

                    $search["conditions"][$i]["conditions"][1]["type"] = "HASFIELDWITHVALUE";
                    $search["conditions"][$i]["conditions"][1]["field"] = "rent";
                    $search["conditions"][$i]["conditions"][1]["operator"] = "LESS_EQUAL";
                    $search["conditions"][$i]["conditions"][1]["value"] = $data["search"]["search"]["price"];
                    $i++;
                }
            }

            // only published
            $search["conditions"][$i]["type"] = "AND";
            $search["conditions"][$i]["conditions"][0]["type"] = "HASFIELDWITHVALUE";
            $search["conditions"][$i]["conditions"][0]["field"] = "status";
            $search["conditions"][$i]["conditions"][0]["value"] = "active";
            $i++;

            // sorting search
            if (!empty($data["mapping"]["sort"]))
            {
                if (!empty($schemaId))
                {

                    if (!empty($data["search"]["search"]["sort"]))
                    {
                        $sort = $data["search"]["search"]["sort"];
                        if (!empty($data["mapping"]["sort"]["default"][$sort]))
                        {
                            $a = 0;
                            foreach ($data["mapping"]["sort"]["default"][$sort]["fields"] as $field)
                            {
                                $search["sorts"][$a]["field"] = $field["field"];
                                $search["sorts"][$a]["direction"] = $field["sort"];

                                $a++;
                            }
                        }
                    }
                    else
                    {
                        if (!empty($data["mapping"]["sort"]["default"]))
                        {
                            $a = 0;
                            $sortDefaults = array_slice($data["mapping"]["sort"]["default"], 0, 1);
                            $element = array_shift($sortDefaults);

                            if (!empty($element))
                            {
                                foreach ($element["fields"] as $field)
                                {
                                    $search["sorts"][$a]["field"] = $field["field"];
                                    $search["sorts"][$a]["direction"] = $field["sort"];

                                    $a++;
                                }
                            }
                        }
                    }
                }
            }

            return $search;
        }
    }

    // return fields
    protected function getFields($data = NULL, $schema = NULL, $results = NULL, $seoNeeded = true, $blockSeo = true)
    {
        // defind field
        $field = [];

        if (!empty($data) && !empty($schema) && !empty($results))
        {
            foreach ($data as $key => $row)
            {
                foreach ($results as $result)
                {
                    if ($schema == $key)
                    {
                        foreach ($row as $key2 => $row2)
                        {
                            foreach ($row2 as $key3 => $row3)
                            {
                                if (!empty($row3["type"]))
                                {
                                    if (!empty($result[$key3]["values"]))
                                    {
                                        if ($row3["type"] == "related")
                                        {

                                            $temp = $this->get_estate_related_estates($result[$key3]["values"][0], $row3["fields"]);
                                            if (!empty($temp[$result["id"]]))
                                            {
                                                unset($temp[$result["id"]]);
                                            }
                                            $field[$result["id"]]["related"] = $temp;
                                        }
                                        elseif ($row3["type"] == "id")
                                        {
                                            $field[$result["id"]]["id"] = $key2;
                                        }
                                        elseif ($row3["type"] == "metadata")
                                        {
                                            $field[$result["id"]]["metadata"] = $key2;
                                        }
                                        elseif ($row3["type"] == "contact")
                                        {
                                            $field[$result["id"]]["contact"] = $this->get_estate_agent($result[$key3]["values"][0]);
                                        }
                                        else
                                        {
                                            $field[$result["id"]][$key2][$key3] = API::get_formated_fields($key3, $row3, $result, $key2);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if (!empty(FF_ESTATEVIEW_SEO_SLUG) && !empty($seoNeeded) && !empty($blockSeo))
                    {
                        foreach ($result as $key6 => $seoValue)
                        {
                            if (!empty($seoValue["values"][0]))
                            {
                                $field[$result["id"]]["seo"][$key6]["value"] = $seoValue["values"][0];

                            }
                        }
                    }
                }
            }

            return $field;
        }
    }

    // return template
    public function get_html($page = NULL, $template = "default", $data = NULL, $path = NULL)
    {
        // load module assets
        $this->loadCss(FF_ESTATEVIEW_THEME);

        if (!empty($data) && !empty($page))
        {
            // set path
            if (empty($path))
            {
                $path = plugin_dir_path(__FILE__) . "templates/view/" . $template;
            }

            if (file_exists($path . '/' . $page . '.html'))
            {
                $loader = new Twig_Loader_Filesystem($path);
                $twig = new \Twig\Environment($loader);
                $html = $twig->render($page . '.html', $data);
                return $html;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    // send Mail
    public function send_mail($content)
    {
        return API::send_mail_by_nylas(get_option("ff-nylas-account") , $content);
    }


    // add  seo slug to url
    public function add_seo_slug($data = NULL, $slug = NULL)
    {

        if (!empty($data) && !empty($slug))
        {
            foreach ($data as $id => $estate)
            {
                $search = array();

                foreach ($estate as $category)
                {
                    foreach ($category as $key => $field)
                    {
                        if (!empty($field["value"]))
                        {
                            $search += array("{" . $key . "}" => $field["value"]);
                        }
                    }
                }

                foreach($search as $key => $item) {
                    if($key == "{identifier}") {
                        $prop_id = $item; 
                    }

                    if($key == "{headline}") {
                        $prop_headline = $item; 
                    }
                }

                if($slug == "{identifier}-{headline}") {
                    if(!empty($prop_id)) {
                        $str = $prop_id;
                    }
                    if(!empty($prop_headline)) {
                        $str = $prop_id .'-'. $prop_headline;
                    }
                } elseif ($slug == "{headline}") {
                    if(!empty($prop_headline)) {
                        $str = $prop_headline;
                    } else {
                        if(!empty($prop_id)) {
                            $str = $prop_id;
                        }
                    }
                }

                $str = preg_replace('/[^A-Za-z0-9_äÄöÖüÜß\-\s]/', '', $str);

                // str replace to ö ä ü ß
                $find = array(
                    "ä",
                    "Ä",
                    "ö",
                    "Ö",
                    "ü",
                    "Ü",
                    "ß"
                );
                $replace = array(
                    "ae",
                    "Ae",
                    "oe",
                    "Oe",
                    "ue",
                    "Ue",
                    "ss"
                );
                $str = strtolower(str_replace($find, $replace, $str));

                $data[$id]["seo"]["slug"]["value"] = $str = str_replace("+", "-", urlencode($str));
            }
        }
        return $data;

    }

    // get portal ID
    public function get_portal_data($id = NULL)
    {
        if (!empty($id))
        {
            $portal = API::get_portals_data($id);
            return $portal["portalKey"];
        }
        else
        {
            return false;
        }

    }

    // get blocked estates from DB
    /*protected function get_blocked_estates()
    {
        // get IP
        $ip = $this->get_IP();
    
        if (!empty($ip)) {
            global $wpdb;
            $sql = "select entityId from {$wpdb->prefix}ff_customer_cache where customerIp ='" . md5($ip) . "' and value='blocked' ";
            return $wpdb->get_results($sql);
    
        } else {
            return false;
        }
    
    }*/

    // get IP
    protected function get_IP($ip = "")
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    // show 404
    protected function show_404()
    {
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        get_template_part(404);
        exit();
    }

    // load css for plugin
    protected function loadCss($theme = 'default')
    {
        // load slick css
        wp_register_style('FF-EstateView-slick-' . $theme, plugins_url('/assets/css/' . $theme . '/slick.css', __FILE__) , '', '1.0.0', false);
        wp_enqueue_style('FF-EstateView-slick-' . $theme);

        // load icons css
        wp_register_style('FF-EstateView-icons-' . $theme, plugins_url('/assets/css/' . $theme . '/flaticon.css', __FILE__) , '', '1.0.0', false);
        wp_enqueue_style('FF-EstateView-icons-' . $theme);

        // load chosen css
        wp_register_style('FF-EstateView-chosen-' . $theme, plugins_url('/assets/css/' . $theme . '/chosen.min.css', __FILE__) , '', '1.0.0', false);
        wp_enqueue_style('FF-EstateView-chosen-' . $theme);

        // load slick theme css
        wp_register_style('FF-EstateView-slick-theme-' . $theme, plugins_url('/assets/css/' . $theme . '/slick-theme.css', __FILE__) , '', '1.0.0', false);
        wp_enqueue_style('FF-EstateView-slick-theme-' . $theme);

        // load thickbox from wp
        wp_enqueue_style('thickbox', '/' . WPINC . '/js/thickbox/thickbox.css', null, '1.0');
        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');

        // load default css
        wp_register_style('FF-EstateView-Styles-' . $theme, plugins_url('/assets/css/' . $theme . '/ff-estateview-styles.css', __FILE__) , '', '1.0.2', false);
        wp_enqueue_style('FF-EstateView-Styles-' . $theme);

        // load google maps js
        if (!empty(FF_GG_API_MAPS))
        {
            // load google maps cluster js
            wp_register_script('FF-EstateView-mapscluster-' . $theme, plugins_url('/assets/js/' . $theme . '/markerclusterer.js', __FILE__) , '', '1.0.0', true);
            wp_enqueue_script('FF-EstateView-mapscluster-' . $theme);

            wp_register_script('FF-EstateView-maps-' . $theme, 'https://maps.googleapis.com/maps/api/js?key=' . FF_GG_API_MAPS . '&libraries=places&callback=initMap', '', '1.0.0', true);
            wp_enqueue_script('FF-EstateView-maps-' . $theme);

            wp_register_script('FF-EstateView-maps-script' . $theme, plugins_url('/assets/js/' . $theme . '/map-scripts.js', __FILE__) , '', '1.0.0', true);
            wp_enqueue_script('FF-EstateView-maps-script' . $theme);
        }

        // load slick js
        wp_register_script('FF-EstateView-slick-' . $theme, plugins_url('/assets/js/' . $theme . '/slick.js', __FILE__) , '', '1.0.0', true);
        wp_enqueue_script('FF-EstateView-slick-' . $theme);

        // load chosen
        wp_register_script('FF-EstateView-chosen-' . $theme, plugins_url('/assets/js/' . $theme . '/chosen.jquery.min.js', __FILE__) , '', '1.0.0', true);
        wp_enqueue_script('FF-EstateView-chosen-' . $theme);

        // load default js
        wp_register_script('FF-EstateView-Script-' . $theme, plugins_url('/assets/js/' . $theme . '/ff-estateview-script.js', __FILE__) , '', '1.0.2', true);
        wp_enqueue_script('FF-EstateView-Script-' . $theme);

        wp_localize_script('FF-EstateView-Script-' . $theme, 'ffdata', array(
            'ajaxurl' => admin_url('admin-ajax.php')
        ));

        // load Slider Pro
        // css 
        wp_register_style('FF-EstateView-slider-pro', plugins_url('/assets/css/slider-pro-master/slider-pro.min.css', __FILE__) , '', '1.0.0', false);
        wp_enqueue_style('FF-EstateView-slider-pro');

        // JS
				wp_register_script('FF-EstateView-slider-pro-js', plugins_url('/assets/js/slider-pro-master/jquery.sliderPro.min.js', __FILE__) , '', '1.0.0', true);
        wp_enqueue_script('FF-EstateView-slider-pro-js');

        // Leaflet Maps
        // CSS
        wp_register_style('leaflet', 'https://unpkg.com/leaflet@1.7.1/dist/leaflet.css',true);
        wp_enqueue_style('leaflet');

        // JS
 		wp_register_script('leaflet','https://unpkg.com/leaflet@1.7.1/dist/leaflet.js', array('jquery'), '3.3.5', true );
 		wp_enqueue_script( 'leaflet' );
    }

    private function sortSearchResultsToNewest($search, $result)
    {
        if (empty($search) || empty($search['sorts']) || empty($result))
        {
            return [];
        }

        $searchSortOrder = reset($search['sorts']);

        if ($searchSortOrder['field'] !== '_metadata.lastModifiedTimestamp')
        {
            return $result['entries'];
        }

        $entityOrder = [];
        foreach ($search['conditions'][0]['conditions'][0]['values'] as $entityId)
        {
            foreach ($result['entries'] as $entry)
            {
                if ($entry['id'] === $entityId)
                {
                    $entityOrder[] = $entry;
                    break;
                }
            }
        }
        return array_replace($result['entries'], $entityOrder);
    }

    private function getMaxEntitiesForPage($entities = [], $maxResults = 15, $page = 1)
    {
        $chunked = array_chunk($entities, $maxResults);
        if (!array_key_exists($page - 1, $chunked))
        {
            return [];
        }
        return $chunked[$page - 1];
    }

    private function sortOnlineImages($images)
    {
        if (empty($images))
        {
            return [];
        }

        usort($images, function ($image1, $image2)
        {
            if ($image1['position'] === $image2['position'])
            {
                return 0;
            }
            return $image1['position'] > $image2['position'] ? 1 : -1;
        });
        return $images;
    }
}

// contact Estate
function ajaxcontactfunctiont()
{
    if (!empty($_POST))
    {

        $FFestateViewCore = new FFestateViewCore();

        // get portalkey of portal
        $data["portalkey"] = $FFestateViewCore->get_portal_data(get_option("ff-estateView-publish"));
        $data["estate"]["identifier"] = esc_html(sanitize_text_field($_POST["estateIdentifier"]));
        $data["estate"]["leadUrl"] = esc_html(sanitize_text_field($_POST["estateLeadUrl"]));
        $data["estate"]["headline"] = esc_html(sanitize_text_field($_POST["estateHeadline"]));
        $data["estate"]["id"] = esc_html(sanitize_text_field($_POST["estateId"]));
        $data["portal"]["id"] = get_option('ff-estateView-portal-key');
        $data["prospect"]["salutation"] = esc_html(sanitize_text_field($_POST["salutation"]));
        $data["prospect"]["firstName"] = esc_html(sanitize_text_field($_POST["firstName"]));
        $data["prospect"]["lastName"] = esc_html(sanitize_text_field($_POST["lastName"]));
        $data["prospect"]["phone"] = esc_html(sanitize_text_field($_POST["phone"]));
        $data["prospect"]["email"] = esc_html(sanitize_email($_POST["email"]));
        $data["prospect"]["street"] = esc_html(sanitize_text_field($_POST["street"]));
        $data["prospect"]["zip"] = esc_html(sanitize_text_field($_POST["zip"]));
        $data["prospect"]["town"] = esc_html(sanitize_text_field($_POST["town"]));
        $data["message"] = esc_html(sanitize_textarea_field($_POST["message"]));
        $data["general"]["page"] = get_home_url();
        $data["general"]["createDate"] = date("d.m.y");
        $data["general"]["portal"] = get_home_url();
        $data["legal"]["phone"] = esc_html(sanitize_text_field($_POST["legalPhone"]));
        $data["legal"]["privacy"] = esc_html(sanitize_text_field($_POST["legalPrivacy"]));

        if (empty(get_option("ff-nylas-account")))
        {

            global $ts_mail_errors;
            global $ffphpmailer;
            if (!is_object($ffphpmailer) || !is_a($ffphpmailer, 'PHPMailer'))
            { // check if $phpmailer object of class PHPMailer exists
                // if not - include the necessary files
                require_once ABSPATH . WPINC . '/class-phpmailer.php';
                require_once ABSPATH . WPINC . '/class-smtp.php';
                $ffphpmailer = new PHPMailer(true);
            }

            $ffphpmailer->isSMTP();
            $ffphpmailer->ClearAttachments();
            $ffphpmailer->ClearCustomHeaders();
            $ffphpmailer->ClearReplyTos();
            $ffphpmailer->Host = FF_MAIL_SERVER;
            $ffphpmailer->Port = FF_MAIL_PORT;
            $ffphpmailer->Username = FF_MAIL_USER;
            $ffphpmailer->Password = FF_MAIL_PASS;
            $ffphpmailer->From = FF_MAIL_FROM;
            $ffphpmailer->FromName = get_home_url();
            $ffphpmailer->SMTPAuth = true;
            $ffphpmailer->SMTPSecure = false;
            $ffphpmailer->SMTPDebug = 0;
            $ffphpmailer->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $ffphpmailer->Subject = "FLOWFACT - Eine neue Kontaktanfrage ist auf " . get_home_url() . " eingegangen";
            $ffphpmailer->SingleTo = true;
            $ffphpmailer->ContentType = 'text/html';
            $ffphpmailer->IsHTML(true);
            $ffphpmailer->CharSet = 'utf-8';
            $ffphpmailer->ClearAllRecipients();
            $ffphpmailer->AddAddress($_POST["ffreply"]);
            $ffphpmailer->Body = $FFestateViewCore->get_html("email-estate-contact", FF_ESTATEVIEW_THEME, $data, plugin_dir_path(__FILE__) . "/templates/email/" . FF_ESTATEVIEW_THEME . "/");
            $ffphpmailer->addStringAttachment($FFestateViewCore->get_html("openimmofeedback", FF_ESTATEVIEW_THEME, $data, plugin_dir_path(__FILE__) . "templates/xml/") , 'feedback.xml');

            if (!$ffphpmailer->send())
            {
                //echo "Mailer Error: " . $ffphpmailer->ErrorInfo;
                
            }
            else
            {
                //echo "Message sent!";
                
            }

            return;

        }
        else
        {

            $content["subject"] = "FLOWFACT - Eine neue Kontaktanfrage ist auf " . get_home_url() . " eingegangen";
            $content["from"][0]["name"] = get_option("ff-nylas-account");
            $content["from"][0]["email"] = get_option("ff-nylas-account");
            $content["to"][0]["name"] = $_POST["ffreply"];
            $content["to"][0]["email"] = $_POST["ffreply"];
            $content["body"] = $FFestateViewCore->get_html("email-estate-contact", FF_ESTATEVIEW_THEME, $data, plugin_dir_path(__FILE__) . "/templates/email/" . FF_ESTATEVIEW_THEME . "/");

            return $FFestateViewCore->send_mail($content);
        }
    }
}

add_action('wp_ajax_nopriv_ajaxcontactfunctiont', 'ajaxcontactfunctiont');
add_action('wp_ajax_ajaxcontactfunctiont', 'ajaxcontactfunctiont');