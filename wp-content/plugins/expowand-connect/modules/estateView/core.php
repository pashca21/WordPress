<?php
include_once __DIR__."/../../core/dict.php";
class FFestateViewCore extends API{

	public function widget() {
		$data["mapping"] = json_decode(FF_ESTATEVIEW_SALESAUTOMATE_MAPPING, true);
		$data["search"]["path"] = get_bloginfo('wpurl') . '/' . EW_PLUGIN_ROUTE . '/' . FF_ESTATEVIEW_ROUTE;

		$result['title'] = "Immobilien Suche";
		$result['content'] = $this->get_html("widget", EW_ESTATEVIEW_THEME, $data);
		return $result;
	}

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

	public function get_estate_details($schemaId, $id) {
		if (empty($id) || empty($schemaId)) { return false; }

		$offer = $this->get_entity_cache('offer_'.$id);
		$offerdetails = $this->get_entity_cache('offerdetails_'.$id);

		//print("<pre>".print_r($offerdetails,true)."</pre>");exit;

		// $results[0] = $this->get_entity_by_id($schemaId, sanitize_key($id));
		// print_r($results[0]->estates[0]->offer);exit;
		// $offer = $results[0]->estates[0]->offer;
		// $offerdetails = $results[0]->estates[0]->offerdetails;

		if (!empty($offer->name)) {
			$result['title'] = $offer->name;
		} else {
			$result['title'] = "Immobilie";
		}

		$meta_title = $offer->name;

		if($meta_title) {
			define('FF_META_TITLE', $meta_title);
		}
		
		$meta_description = $offer->name;

		if($meta_description) {
			define('FF_META_DESCRIPTION', $meta_description);
		}
		
		$meta_image = $offerdetails->mainPic;

		if(!empty($meta_image)) {
			define('FF_META_IMAGE', $meta_image);
		}

		if(defined('FF_META_TITLE') or defined('FF_META_DESCRIPTION') or defined('FF_META_IMAGE')) {
			add_action('wp_head', array($this, 'ff_add_meta_to_header'), -1);
		}

		$data = new stdClass();
		$data->offer = $offer;
		$data->offerdetails = $offerdetails;

		$result['content'] = $this->get_html("page-details", EW_ESTATEVIEW_THEME, $data);
		// print_r($result);exit;

		return $result;

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
			$result["content"] = $this->get_html("page-overview", EW_ESTATEVIEW_THEME, $data);
		}

		return $result;
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
			$data["search"]["path"] = get_bloginfo('wpurl') . '/' . EW_PLUGIN_ROUTE . '/' . FF_ESTATEVIEW_ROUTE;
			$data["search"]["schema"] = $schemaId;
			$data["color"]["primary"] = FF_PRIMARY_COLOR;
			$data["color"]["secondary"] = FF_SECONDARY_COLOR;
			$data["api"]["cloudimage"]["url"] = FF_CLOUDIMAGE_IO_URL;
			$data["api"]["maps"]["key"] = FF_GG_API_MAPS;
			$data["api"]["maps"]["path"] = plugin_dir_url(dirname(__FILE__)) . "/estateView/assets/img/" . EW_ESTATEVIEW_THEME . "/";
 
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
    public function get_html($page = NULL, $template = "default", $data = NULL, $path = NULL) {
		// print_r($data);exit;
        if (empty($data) && empty($page)) {
			return false;
		}

		if (empty($path)) {	
			$path = plugin_dir_path(__FILE__) . "templates/view/" . $template; 
		}

		if (!file_exists($path . '/' . $page . '.php')) {
			return false;
		}				

        $this->loadCss(EW_ESTATEVIEW_THEME);		

		// print("<pre>".print_r($data,true)."</pre>");exit;
		$html = '';
		ob_start();
		$results = $data;
		include($path . '/' . $page . '.php');
		$html = ob_get_contents();
		ob_end_clean();
		// print("<pre>".print_r($html,true)."</pre>");exit;
		return $html;
    }

   
	protected function loadCss($theme = 'default') {
		// JS
		wp_register_script('BS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js');
		wp_enqueue_script('BS');

		// CSS
		wp_register_style('BS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css');
		wp_enqueue_style('BS');

		// force load Jquery
		wp_enqueue_script( 'jquery');    
		
        // // load slick css
        // wp_register_style('FF-EstateView-slick-' . $theme, plugins_url('/assets/css/' . $theme . '/slick.css', __FILE__) , '', '1.0.0', false);
        // wp_enqueue_style('FF-EstateView-slick-' . $theme);

        // // load icons css
        // wp_register_style('FF-EstateView-icons-' . $theme, plugins_url('/assets/css/' . $theme . '/flaticon.css', __FILE__) , '', '1.0.0', false);
        // wp_enqueue_style('FF-EstateView-icons-' . $theme);

        // // load chosen css
        // wp_register_style('FF-EstateView-chosen-' . $theme, plugins_url('/assets/css/' . $theme . '/chosen.min.css', __FILE__) , '', '1.0.0', false);
        // wp_enqueue_style('FF-EstateView-chosen-' . $theme);

        // // load slick theme css
        // wp_register_style('FF-EstateView-slick-theme-' . $theme, plugins_url('/assets/css/' . $theme . '/slick-theme.css', __FILE__) , '', '1.0.0', false);
        // wp_enqueue_style('FF-EstateView-slick-theme-' . $theme);

        // // load thickbox from wp
        // wp_enqueue_style('thickbox', '/' . WPINC . '/js/thickbox/thickbox.css', null, '1.0');
        // wp_enqueue_script('thickbox');
        // wp_enqueue_style('thickbox');

        // // load default css
        // wp_register_style('FF-EstateView-Styles-' . $theme, plugins_url('/assets/css/' . $theme . '/ff-estateview-styles.css', __FILE__) , '', '1.0.2', false);
        // wp_enqueue_style('FF-EstateView-Styles-' . $theme);

        // // load google maps js
        // if (!empty(FF_GG_API_MAPS))
        // {
        //     // load google maps cluster js
        //     wp_register_script('FF-EstateView-mapscluster-' . $theme, plugins_url('/assets/js/' . $theme . '/markerclusterer.js', __FILE__) , '', '1.0.0', true);
        //     wp_enqueue_script('FF-EstateView-mapscluster-' . $theme);

        //     wp_register_script('FF-EstateView-maps-' . $theme, 'https://maps.googleapis.com/maps/api/js?key=' . FF_GG_API_MAPS . '&libraries=places&callback=initMap', '', '1.0.0', true);
        //     wp_enqueue_script('FF-EstateView-maps-' . $theme);

        //     wp_register_script('FF-EstateView-maps-script' . $theme, plugins_url('/assets/js/' . $theme . '/map-scripts.js', __FILE__) , '', '1.0.0', true);
        //     wp_enqueue_script('FF-EstateView-maps-script' . $theme);
        // }

        // // load slick js
        // wp_register_script('FF-EstateView-slick-' . $theme, plugins_url('/assets/js/' . $theme . '/slick.js', __FILE__) , '', '1.0.0', true);
        // wp_enqueue_script('FF-EstateView-slick-' . $theme);

        // // load chosen
        // wp_register_script('FF-EstateView-chosen-' . $theme, plugins_url('/assets/js/' . $theme . '/chosen.jquery.min.js', __FILE__) , '', '1.0.0', true);
        // wp_enqueue_script('FF-EstateView-chosen-' . $theme);

        // // load default js
        // wp_register_script('FF-EstateView-Script-' . $theme, plugins_url('/assets/js/' . $theme . '/ff-estateview-script.js', __FILE__) , '', '1.0.2', true);
        // wp_enqueue_script('FF-EstateView-Script-' . $theme);

        // wp_localize_script('FF-EstateView-Script-' . $theme, 'ffdata', array(
        //     'ajaxurl' => admin_url('admin-ajax.php')
        // ));

        // load Slider Pro
        // css 
        // wp_register_style('FF-EstateView-slider-pro', plugins_url('/assets/css/slider-pro-master/slider-pro.min.css', __FILE__) , '', '1.0.0', false);
        // wp_enqueue_style('FF-EstateView-slider-pro');

        // JS
		// wp_register_script('FF-EstateView-slider-pro-js', plugins_url('/assets/js/slider-pro-master/jquery.sliderPro.min.js', __FILE__) , '', '1.0.0', true);
        // wp_enqueue_script('FF-EstateView-slider-pro-js');

        // Leaflet Maps
        // CSS
        // wp_register_style('leaflet', 'https://unpkg.com/leaflet@1.7.1/dist/leaflet.css',true);
        // wp_enqueue_style('leaflet');

        // // JS
 		// wp_register_script('leaflet','https://unpkg.com/leaflet@1.7.1/dist/leaflet.js', array('jquery'), '3.3.5', true );
 		// wp_enqueue_script( 'leaflet' );
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
            $ffphpmailer->Body = $FFestateViewCore->get_html("email-estate-contact", EW_ESTATEVIEW_THEME, $data, plugin_dir_path(__FILE__) . "/templates/email/" . EW_ESTATEVIEW_THEME . "/");
            $ffphpmailer->addStringAttachment($FFestateViewCore->get_html("openimmofeedback", EW_ESTATEVIEW_THEME, $data, plugin_dir_path(__FILE__) . "templates/xml/") , 'feedback.xml');

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
            $content["body"] = $FFestateViewCore->get_html("email-estate-contact", EW_ESTATEVIEW_THEME, $data, plugin_dir_path(__FILE__) . "/templates/email/" . EW_ESTATEVIEW_THEME . "/");

            return $FFestateViewCore->send_mail($content);
        }
    }
}

add_action('wp_ajax_nopriv_ajaxcontactfunctiont', 'ajaxcontactfunctiont');
add_action('wp_ajax_ajaxcontactfunctiont', 'ajaxcontactfunctiont');