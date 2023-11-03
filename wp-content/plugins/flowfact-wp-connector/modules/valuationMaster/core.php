<?php
// get config

class FFvaluationMasterCore extends API {
	// Lead Hunter cookie 
	public function __construct() {
		// Call a method to add the filter when the object is instantiated
		$this->set_leadhunter_cookie_exp();
	}

	public function set_leadhunter_cookie_exp() {
		add_filter( 'auth_cookie_expiration', array($this, 'myplugin_cookie_expiration'));
	}

	public function myplugin_cookie_expiration( $expiration, $user_id, $remember ) {
		return $remember ? $expiration : 86400;
	}

	public function get_result()
	{
        // restart session 
        if( !session_id() ) {
            session_start();
        }

		if(!empty($_POST))
		{
			if(!empty($_POST["street"]) && $_POST["street"] != "34 Watts road" && !empty($_POST["house_number"]) && $_POST["house_number"] != "34 Watts road" && !empty($_POST["zip"]) && !empty($_POST["town"]) && !empty($_POST["captcha"]) && $_POST["captcha"] == $_SESSION["captcha"])

			{
				$data["score"] 					= $this->get_score($_POST);
				$data["path"]		    		= plugin_dir_url(dirname(__FILE__)) . "valuationMaster/assets/img/" . FF_VALUATIONMASTER_THEME . "/";
				$data["color"]["primary"]		= FF_PRIMARY_COLOR;
				$data["color"]["secondary"]		= FF_SECONDARY_COLOR;
				$data["search"]["path"] 		= get_bloginfo('wpurl') . '/' . FF_PLUGIN_ROUTE . '/' . FF_VALUATIONMASTER_ROUTE;
				$data["legal"]["imprint"]		= FF_IMPRINT_URL;
				$data["legal"]["privacy"]		= FF_PRIVACY_URL;
				$data['imagepath']				= plugin_dir_url(dirname(__FILE__)) . "valuationMaster/assets/img/" . FF_VALUATIONMASTER_THEME ;
                $data['agentphoto_url']         = get_option("ff-valuationMaster-calltoaction-agent-img");
				$data['logging']				= true;
                $data['company']                = $this->get_company_data();

				$GLOBALS['valuationMasterSent'] = true;

				// return data
				$result['title'] 	= "Was ist Ihre Immobilie wert?";	
				$result['content'] 	= $this->get_html("page-score", FF_VALUATIONMASTER_THEME, $data);
				return $result;
			}
			else
			{
				$this->show_404();
			}	
		}
		else
		{
			$this->show_404();
		}	
	}

	public function get_overview()
	{	
		if(!empty($_GET["type"]))
		{
			$data['type']	=  $_GET["type"];

            $session_captcha_str = $this->randomString();

            if( !session_id() ) {
                session_start();
                $_SESSION['captcha'] = $session_captcha_str;
            }
		}

        if(!empty($_GET["iframe"])) {
            if($_GET["iframe"] == 1) {
                $data["iframe"] = true;
            }
        }
		
		if(!empty(API::get_entitlement('LEAD_MASTER'))) {

			$data["mapping"] 	= json_decode(FF_VALUATIONMASTER_SALESAUTOMATE_MAPPING, true);
			$data['path']		= get_bloginfo('wpurl') . '/' . FF_PLUGIN_ROUTE . '/' . FF_VALUATIONMASTER_ROUTE ;
            $data['captcha']    = get_option("ff-valuationMaster-captcha-show");
			$data['imagepath']	= plugin_dir_url(dirname(__FILE__)) . "valuationMaster/assets/img/" . FF_VALUATIONMASTER_THEME ;
			$data['gdprUrl']    = FF_PRIVACY_URL;
            $data["color"]["primary"]		= FF_PRIMARY_COLOR;
        	$data["color"]["secondary"]		= FF_SECONDARY_COLOR;
			if(!empty($_SESSION['captcha'])) {
				$data['captchaStr'] = $_SESSION['captcha'];
			}
			$result['title'] 	= "Was ist Ihre Immobilie wert?";
			$result['content'] 	= $this->get_html("page-overview", FF_VALUATIONMASTER_THEME, $data);

			return $result;
		}
		return;
	}

	public function get_results_by_url()
	{
        if(!empty($_GET) && $_GET["name"]) {
            $url = get_bloginfo('wpurl') . '/' . FF_PLUGIN_ROUTE . '/' . FF_VALUATIONMASTER_ROUTE.'/report/'.basename($_SERVER['REQUEST_URI']);
            //echo "<br> full url check :<br>" .$url . "<br><br>";
            
            $url = explode("&name=",$url);
            $url = $url[0];
            //echo "new check : " . $url;

            $check = substr(($url), 0, -40);
        } else {
            $check = substr((get_bloginfo('wpurl') . '/' . FF_PLUGIN_ROUTE . '/' . FF_VALUATIONMASTER_ROUTE.'/report/'.basename($_SERVER['REQUEST_URI'])), 0, -40);
        }

		$check = md5($check.get_option("ff-token"));

		if(!empty($_GET))
		{
			if(!empty($check) && !empty($_GET["verify"]) && $check == $_GET["verify"] && !empty(API::get_entitlement('LEAD_MASTER')))
			{
				if( !empty($_GET["street"]) && !empty($_GET["house_number"]) && !empty($_GET["zip"]) && !empty($_GET["town"]))
				{
                    $data["score"] 					= $this->get_score($_GET, false);

                    if(!empty($_GET["name"])) {
                        $data["score"]["customer_name"] = $_GET["name"];
                    } else {
                        $data["score"]["customer_name"] = "";
                    }

					$data["token"] 					= $_GET["verify"];
					$data["path"]		    		= plugin_dir_url(dirname(__FILE__)) . "/valuationMaster/assets/img/" . FF_VALUATIONMASTER_THEME . "/";
					$data["color"]["primary"]		= FF_PRIMARY_COLOR;
					$data["color"]["secondary"]		= FF_SECONDARY_COLOR;
					$data["search"]["path"] 		= get_bloginfo('wpurl') . '/' . FF_PLUGIN_ROUTE . '/' . FF_VALUATIONMASTER_ROUTE;
					$data["legal"]["imprint"]		= FF_IMPRINT_URL;
					$data["legal"]["privacy"]		= FF_PRIVACY_URL;
					$data['imagepath']				= plugin_dir_url(dirname(__FILE__)) . "/valuationMaster/assets/img/" . FF_VALUATIONMASTER_THEME ;
                    $data['agentphoto_url']         = get_option("ff-valuationMaster-calltoaction-agent-img");
					$data['logging']				= false;
                    $data['company']                = $this->get_company_data();
								
					// return data
					$result['title'] 	= "Was ist Ihre Immobilie wert?";	
					$result['content'] 	= $this->get_html("page-score", FF_VALUATIONMASTER_THEME, $data);

					return $result;
				}
				else
				{
					$this->show_404();
				}	
			}	
			else
			{
				$this->show_404();
			}
		}
		else
		{
			$this->show_404();
		}
	}	
	
	// return search_query
    protected function get_score($data = NULL, $logging = true)
	{
        if (empty($data)) {
            return;
        }
        // estate type
        $result["category"] = $data["type"];

        // date
        $result["date"] = date("c");
        $deeplink = get_bloginfo('wpurl') . '/' . FF_PLUGIN_ROUTE . '/' . FF_VALUATIONMASTER_ROUTE . "/report/";
        $deeplink .= "?type=" . $data["type"];


        // construction year
        if (!empty($data["yearofconstruction"])) {
            $result["construction_year"] = intval($data["yearofconstruction"]);
            $deeplink .= "&yearofconstruction=" . $data["yearofconstruction"];
        }

        // plot area
        if (!empty($data["plot_area"])) {
            $result["plot_area"] = floatval($data["plot_area"]);
            $deeplink .= "&plot_area=" . $data["plot_area"];
        }

        // living area
        if (!empty($data["livingarea"])) {
            $result["living_area"] = floatval($data["livingarea"]);
            $deeplink .= "&livingarea=" . $data["livingarea"];
        }

        // elevator
        if (!empty($data["elevator"])) {
            if ($data["elevator"] == "ja") {
                $result["elevator"] = TRUE;
                $deeplink .= "&elevator=ja";
            } else {
                $result["elevator"] = FALSE;
                $deeplink .= "&elevator=nein";
            }
        }


        if (!empty($data["floor"])) {
            $result["floor"] = $data["floor"]; //ONLY ETW |INT
            $deeplink .= "&elevator=" . $data["floor"];
        }


        // garages
        if (!empty($data["garages"])) {
            if ($data["garages"] == "ja") {
                $result["garages"] = TRUE;
                $deeplink .= "&garage=ja";
            } else {
                $result["garages"] = FALSE;
                $deeplink .= "&garage=nein";
            }
        }

        if (!empty($data["lat"]) && !empty($data["lng"])) {
            $result["coordinates"]["lat"] = floatval($data["lat"]);
            $result["coordinates"]["lng"] = floatval($data["lng"]);
        }

        if (!empty($data["street"]) and !empty($data["zip"])) {
            $result["address"]["nation"] = "DE";
            $result["address"]["street"] = $data["street"];
            $result["address"]["house_number"] = $data["house_number"];
            $result["address"]["zip"] = $data["zip"];
            $result["address"]["town"] = $data["town"];

            $deeplink .= "&street=" . urlencode($data["street"]);
            $deeplink .= "&house_number=" . urlencode($data["house_number"]);
            $deeplink .= "&zip=" . urlencode($data["zip"]);
            $deeplink .= "&town=" . urlencode($data["town"]);
        }

        $result["trend"] = TRUE;

        if (!empty($data["construction"])) {
            $result["construction"] = $data["construction"];
            $deeplink .= "&construction=" . $data["construction"];
        }

        if (!empty($data["guesttoilet"])) {
            $result["equipment"]["guest_toilet"] = $data["guesttoilet"]; // GAESTE_WC
            $deeplink .= "&guesttoilet=" . $data["guesttoilet"];
        }

        if (!empty($data["outdoor_parking_space"])) {
            $result["equipment"]["outdoor_parking_space"] = $data["outdoor_parking_space"]; //ONLY EFH, MFH, ETW | BOOL
            $deeplink .= "&outdoor_parking_space=" . $data["outdoor_parking_space"];
        }

        if (!empty($data["heating"])) {
            $result["equipment"]["heating"] = $data["heating"]; // FUSSBODENHEIZUNG
            $deeplink .= "&heating=" . $data["heating"];
        }

        if (!empty($data["residential_area"])) {
            $result["equipment"]["residential_area"] = $data["residential_area"]; // KEINBALKON, WINTERGARTEN
            $deeplink .= "&residential_area=" . $data["residential_area"];
        }

        if (!empty($data["windows"])) {
            $result["equipment"]["windows"] = $data["windows"]; // EINFACH
            $deeplink .= "&windows=" . $data["windows"];
        }

        if (!empty($data["roof_covering"])) {
            $result["equipment"]["roof_covering"] = $data["roof_covering"]; // Satteldach
            $deeplink .= "&roof_covering=" . $data["roof_covering"];
        }

        if (!empty($data["insulated_exterior_walls"])) {
            $result["equipment"]["insulated_exterior_walls"] = $data["insulated_exterior_walls"]; // METALL
            $deeplink .= "&insulated_exterior_walls=" . $data["insulated_exterior_walls"];
        }

        if (!empty($data["store_room"])) {
            $result["equipment"]["store_room"] = $data["store_room"]; // KEINER
            $deeplink .= "&store_room=" . $data["store_room"];
        }

        if (!empty($data["bath_room"])) {
            $result["equipment"]["bath_room"] = $data["bath_room"]; // EIN_BAD
            $deeplink .= "&bath_room=" . $data["bath_room"];
        }

        if (!empty($data["bath"])) {
            $result["equipment"]["bath"] = $data["bath"]; // KEINER
            $deeplink .= "&bath=" . $data["bath"];
        }

        if (!empty($data["floor_number"])) {
            $result["equipment"]["floor_number"] = $data["floor_number"]; // KEINER
            $deeplink .= "&floor_number=" . $data["floor_number"];
        }

        if (!empty($data["insulated_exterior_walls"])) {
            $result["equipment"]["insulated_exterior_walls"] = $data["insulated_exterior_walls"]; // AUSSENWAENDE_GEDAEMMT
            $deeplink .= "&insulated_exterior_walls=" . $data["insulated_exterior_walls"];
        }

        if (!empty($data["surface_mounted_installation"])) {
            $result["equipment"]["surface_mounted_installation"] = $data["surface_mounted_installation"]; // LEITUNGEN_NICHT_AUF_PUTZ
            $deeplink .= "&surface_mounted_installation=" . $data["surface_mounted_installation"];
        }


        // call API
        if (!empty($result)) {
            $data['estate']['data'] = $result;
            $data['valuation']['data'] = API::get_estate_valuation($result);
            $data['valuation']['quality'] = API::get_estate_valuation_by_quality($result);
            $data['rent']['data'] = API::get_estate_valuation_rent($result);

            // get purchase ipi
            $i = 0;
            $p_ipi = API::get_estate_valuation_ipi($result);

            // set diff data
            if (!empty($p_ipi["values"])) {
                foreach (array_slice($p_ipi["values"], -24, 17, true) as $key => $row) {
                    $data['valuation']['growth']['data'][$i] = $row;
                    $i++;
                }

                foreach (array_slice($p_ipi["values"], -12, 12, true) as $key => $row) {
                    $data['valuation']['growth']['trend']["label"][$i] = $row;
                    $i++;
                }

                foreach (array_slice($p_ipi["values"], -12, 5, true) as $key => $row) {
                    $data['valuation']['growth']['trend']["now"][$i] = $row;
                    $i++;
                }

                foreach (array_slice($p_ipi["values"], -8, 8, true) as $key => $row) {
                    $data['valuation']['growth']['trend']["predict"][$i] = $row;
                    $i++;
                }

                // set yearly diff
                $data['valuation']['growth']['start'] = $data['valuation']['growth']['data'][0];
                $data['valuation']['growth']['end'] = $data['valuation']['growth']['data'][11];
            }

            $p_ipi = API::get_estate_valuation_rent_ipi($result);

            // set diff data
            if (!empty($p_ipi["values"])) {
                foreach (array_slice($p_ipi["values"], -24, 17, true) as $key => $row) {
                    $data['rent']['growth']['data'][$i] = $row;
                    $i++;
                }

                foreach (array_slice($p_ipi["values"], -12, 12, true) as $key => $row) {
                    $data['rent']['growth']['trend']["label"][$i] = $row;
                    $i++;
                }

                foreach (array_slice($p_ipi["values"], -12, 5, true) as $key => $row) {
                    $data['rent']['growth']['trend']["now"][$i] = $row;
                    $i++;
                }

                foreach (array_slice($p_ipi["values"], -8, 8, true) as $key => $row) {
                    $data['rent']['growth']['trend']["predict"][$i] = $row;
                    $i++;
                }

                // set yearly diff
                $data['rent']['growth']['start'] = $data['valuation']['growth']['data'][0];
                $data['rent']['growth']['end'] = $data['valuation']['growth']['data'][11];
            }


            // show call to action
            $data["calltoaction"]["show"] = get_option("ff-valuationMaster-calltoaction-show");
            $data["calltoaction"]["phone"] = get_option("ff-valuationMaster-calltoaction-phone");
            $data["calltoaction"]["name"] = get_option("ff-valuationMaster-calltoaction-name");
            $data["calltoaction"]["email"] = get_option("ff-valuationMaster-reply-address");
            $data['calltoaction']['isSchemaAvailable'] = API::isSchemaGroupAvailable('leads');

            // create checksum
            $deeplink .= "&verify=" . md5($deeplink . get_option("ff-token"));

            // add name & second name to deeplink
            $deeplink = $deeplink . '&name=' .$data["customer_salutation"] . ' ' . $data["customer_name"];
            // replace spaces
            $deeplink = str_replace(' ', '%20', $deeplink);

            if (!empty(get_option("ff-valuationMaster-customer-template")) and $logging === true) {

                // get Template
                $a = get_option("ff-valuationMaster-customer-template");

                if (!empty(get_option("ff-valuationMaster-customer-template-signature"))) {
                    $a .= "<br><br>" . get_option("ff-valuationMaster-customer-template-signature");
                }

                if(get_option("ff-valuationMaster-customer-template-formatting-type") == "text") {
                    $a = nl2br($a);
                }

                // send Mail
                $content["subject"] = "Herzlichen Dank für Ihre Anfrage.";
                $content["from"][0]["name"] = get_option("ff-valuationMaster-reply-address");
                $content["from"][0]["email"] = get_option("ff-valuationMaster-reply-address");
                $content["to"][0]["name"] = $data["customer_email"];
                $content["to"][0]["email"] = $data["customer_email"];
                $content["body"] = str_replace("{{Link}}", $deeplink, $a);       

                // send Mail
                $this->send_mail($content);
            }


            if (!empty(get_option("ff-valuationMaster-reply-address")) and $logging === true) {
                // create deeplink
                $data["path"] = $deeplink;

                // render owner E-Mail
                $ownerMail = $this->get_html("email-contact", FF_VALUATIONMASTER_THEME, $data, plugin_dir_path(__FILE__) . "/templates/email/" . FF_VALUATIONMASTER_THEME . "/");

                // send Mail
                $content["subject"] = "FLOWFACT - Neuer Eigentümerlead über " . get_home_url() . " eingegangen";
                $content["from"][0]["name"] = get_option("ff-nylas-account");
                $content["from"][0]["email"] = get_option("ff-nylas-account");
                $content["to"][0]["name"] = get_option("ff-valuationMaster-reply-address");
                $content["to"][0]["email"] = get_option("ff-valuationMaster-reply-address");
                $content["body"] = $ownerMail;

                // send Mail
                $this->send_mail($content);

                if ($logging === true) {
                    // check address
                    $check = $this->contact_dublicate_check($data["customer_email"]);

                    // store lead
                    $data["history"] = $this->store_lead($data, $check, $ownerMail);
                }
            }
        }
        return $data;
    }

	// check for duplicate entities	
	protected function contact_dublicate_check($email =  NULL) {
	    if (empty($email)) {
	        return false;
        }

        // result fields
        $search["target"] = "ENTITY";
        $search["fetch"] = array();
        array_push($search["fetch"], "id");
        array_push($search["fetch"], "_metadata");

        $search["conditions"][0]["type"] = "AND";
        $search["conditions"][0]["conditions"][0]["type"]  = "HASFIELDWITHVALUE";
        $search["conditions"][0]["conditions"][0]["field"] = "emails";
        $search["conditions"][0]["conditions"][0]["value"] = esc_html(sanitize_email($email));

        $result = API::get_entities_by_search("contacts", $search, 1, 1);

        if (empty($result) or count($result["entries"]) == 0)
        {
            return false;
        }

        return $result["entries"][0]["id"];
	}

	// return template
    protected function store_lead($data = NULL, $contactID = NULL, $ownerMail = NULL)
    {
		if(!empty($data))
		{
			$history = NULL;
			// get and update existing contact -> add required owner tag when not already set
			if (!empty($contactID)) {
				$entity = API::get_entity_by_id("contacts", $contactID);
				if (!in_array("owner", $entity["tags"]["values"])) {
					$entity["tags"]["values"][] = "owner";
					API::modify_entity("contacts", $contactID, ["tags" => $entity["tags"]]);
				}
			}

			// create contact
			if(empty($contactID))
			{
			    $customerNames = explode(' ', $data["customer_name"]);
				$entityContact["emails"]["values"][0] 				= $data["customer_email"];
				$entityContact["salutation"]["values"][0] 			= $data["customer_salutation"];
				$entityContact["lastName"]["values"][0] 			= array_pop($customerNames); // when customer has multiple pre names we use the last item as family name
				$entityContact["firstName"]["values"][0] 			= implode(' ', $customerNames);
				$entityContact["addresses"]["values"][0]["zipcode"]	= $data["customer_zip"];
				$entityContact["addresses"]["values"][0]["city"] 	= $data["customer_town"];
				$entityContact["addresses"]["values"][0]["street"] 	= $data["customer_street"];
				$entityContact["addresses"]["values"][0]["type"] 	= "private";
				$entityContact["phones"]["values"][0]["number"] 	= $data["customer_phone"];
				$entityContact["phones"]["values"][0]["type"] 		= "private";
				$entityContact["tags"]["values"][0]                 = "owner";
				
				$contactID = API::create_contact("contacts", $entityContact);
			}
			
			
			// create Estate
			if(!empty($contactID)){
				$entityEstate["internaldescription"]["values"][0] 																					= "Eigentümerlead ".$data["street"]." ".$data["house_number"]." - ".$data["zip"]."".$data["town"];
				(!empty($contactID))?															$entityEstate["landlord"]["values"][0] 				= $contactID: "";
				(!empty($data["rooms"]))?														$entityEstate["rooms"]["values"][0] 				= $data["rooms"]: "";
				(!empty($data["livingarea"]))?													$entityEstate["livingarea"]["values"][0] 			= $data["livingarea"]: "";
				(!empty($data["plot_area"]))?													$entityEstate["plotarea"]["values"][0] 				= $data["plot_area"]: "";
				(!empty($data["yearofconstruction"]))?											$entityEstate["yearofconstruction"]["values"][0] 	= $data["yearofconstruction"]: "";
				(!empty($data["floor_number"]))?												$entityEstate["no_of_floors"]["values"][0] 			= $data["floor_number"]: "";
				(!empty($data["heating"]))?														$entityEstate["typeofheating"]["values"][0] 		= $data["heating"]: ""; // Todo --> add values
				//(!empty($data["guesttoilet"] && $data["guesttoilet"] != "KEIN_GAESTE_WC"))?		$entityEstate["guesttoilet"]["values"][0] 			= true: ""; // Todo --> map false
				(!empty($data["bath_room"]))?													$entityEstate["numberbathrooms"]["values"][0] 		= 1 : "";
				(!empty($data["zip"]))?															$entityEstate["addresses"]["values"][0]["zipcode"]	= $data["zip"]: "";
				(!empty($data["town"]))?														$entityEstate["addresses"]["values"][0]["city"] 	= $data["town"]: "";
				(!empty($data["street"]) && !empty($data["house_number"]))?						$entityEstate["addresses"]["values"][0]["street"] 	= $data["street"]." ".$data["house_number"]: "";
				switch ($data['type']) {
					case ('ETW'):
						$schema = 'flat_purchase';
						$estateType = '01';
						break;
					case ('EFH'):
						$schema = 'house_purchase';
						$estateType = '02EFH';
						break;
					case ('GRD'):
						$schema = 'land_purchase';
						$estateType = '03';
						break;
					case ('MFH'):
						$schema = 'house_purchase';
						$estateType = '02MFH';
						break;
					default:
						$schema = null;
						$estateType = null;
				}

				$entityEstate['estatetype'] = $estateType;
				$entityEstate['status']['values'][0] = 'inactive';
				$estateID = API::create_contact($schema, $entityEstate);
			}

			// create Lead
			if(!empty($contactID))
			{
				$entityLead["owner"]["values"][0] 									 = $contactID;
				(!empty($estateID))? $entityLead["estate"]["values"][0] 	 		 = $estateID  :"";
				$entityLead["receivedDateTime"]["values"][0] 						 = strtotime(date("d.m.Y H:i"));
				$entityLead["ownershipStructure"]["values"][0] 						 = "owner";
				$entityLead["message"]["values"][0] 						 		 = $ownerMail;
				$entityLead["evaluationType"]["values"][0] 					         = "sprengnetterFive2Click";
				$entityLead["evaluationValue"]["values"][0] 					 	 = $data["valuation"]["data"]["value"];
				$entityLead["source"]["values"][0] 						 			 = "homepage";
	
				( $data["type"] == "ETW")?  $entityLead["propertyType"]["values"][0] = "flat" :"";
				( $data["type"] == "EFH")?  $entityLead["propertyType"]["values"][0] = "house" :"";
				( $data["type"] == "GRD")?  $entityLead["propertyType"]["values"][0] = "plot" :"";
				( $data["type"] == "MFH")?  $entityLead["propertyType"]["values"][0] = "investment" :"";

				$lead = API::create_contact("realEstateOwnerLeads", $entityLead);	

				$history = get_bloginfo('wpurl') . '/' . FF_PLUGIN_ROUTE . '/' . FF_VALUATIONMASTER_ROUTE."/call/?token=".md5($lead)."&id=".$lead."&email=".get_option("ff-valuationMaster-reply-address");
			}
			
			// Sanitizing $_SERVER
			if  (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
                $clientIp = $_SERVER['HTTP_CLIENT_IP'];
            } elseif  (array_key_exists('REMOTE_ADDR', $_SERVER)) {
                $clientIp = $_SERVER['REMOTE_ADDR'];
            } else {
			    $clientIp =  'N/A';
            }


			// create DSGVO entity
			if(!empty($contactID))
			{
				$entityGDPR["contact"]["values"][0] 					= $contactID;
				$entityGDPR["consentDateTime"]["values"][0] 			= strtotime(date("d.m.Y H:i"));
				$entityGDPR["displayPurpose"]["values"][0] 				= "Verarbeitung der eigenen Angaben zur Person und der Immobilie im Rahmen der Erstellung einer Marktwertermittlung.";
				$entityGDPR["purpose"]["values"][0] 					= "leadmaster";
				$entityGDPR["ipAddress"]["values"][0] 					= $clientIp;
				$entityGDPR["source"]["values"][0] 						= home_url();
				$entityGDPR["note"]["values"][0] 						= "Erstellt am ". date("d.m.Y H:i") ."Uhr über ".home_url();
				$entityGDPR["estate"]["values"][0] 						= $estateID;
		
				API::create_contact("gdpr_consents", $entityGDPR);
			}

		}

		return $lead;
	}	

    // Ajax Functions
    public function ajaxcallback()
    {
        if(!empty($_POST))
        {
            $content["subject"] 			= "FLOWFACT - Rückruf zu einem Eigentümerlead über ".get_home_url()." eingegangen";
            $content["from"][0]["name"] 	= get_option("ff-nylas-account");
            $content["from"][0]["email"] 	= get_option("ff-nylas-account");
            $content["to"][0]["name"] 		= get_option("ff-valuationMaster-reply-address");
            $content["to"][0]["email"] 		= get_option("ff-valuationMaster-reply-address");

            $content["leadId"]      = $_POST["leadID"];

            $data = $_POST;
            $path = plugin_dir_path(__FILE__) . "/templates/email/".FF_VALUATIONMASTER_THEME."/";
            $page = 'email-callback';

            if (!empty($data) && !empty($page)) {
                if (file_exists($path . '/' . $page . '.html')) {
                    $loader = new Twig_Loader_Filesystem($path);
                    $twig = new Twig_Environment($loader);
                    $html = $twig->render($page . '.html', $data);
                    //return $html;
                    $content["body"] =  $html;
                } else {
                    return false;
                }
            } else {
                return false;
            }

            $content["body"] =  $html;

            API::send_mail_by_nylas(get_option("ff-nylas-account"),$content);

            $result['type'] = 'success';
        }	
        else {
            $result['type'] = 'error';
        }
        $result = json_encode($result);
        echo $result;
        wp_die();
    }

    public function ajaxsubmitagentform()
    {
        if(!empty($_POST))
        {
            // send Mail
            $content["subject"] 		 = "FLOWFACT - Nachricht vom Client von " .get_home_url();
            $content["from"][0]["name"]  = get_option("ff-nylas-account");
            $content["from"][0]["email"] = get_option("ff-nylas-account");
            $content["to"][0]["name"] 	 = get_option("ff-valuationMaster-reply-address");
            $content["to"][0]["email"] 	 = get_option("ff-valuationMaster-reply-address");
            // data coming from AJAX
            $content["name"]    = $_POST["name"];
            $content["email"]   = $_POST["email"];
            $content["phone"]   = $_POST["clientPhone"];
            $content["path"]    = $_POST["path"];
            $content["message"] = $_POST["msg"];

            $content["leadId"]      = $_POST["leadID"];

            $data = $_POST;
            $path = plugin_dir_path(__FILE__) . "/templates/email/".FF_VALUATIONMASTER_THEME."/";
            $page = 'email-question';

            if (!empty($data) && !empty($page)) {
                if (file_exists($path . '/' . $page . '.html')) {
                    $loader = new Twig_Loader_Filesystem($path);
                    $twig = new Twig_Environment($loader);
                    $html = $twig->render($page . '.html', $data);
                    //return $html;
                    $content["body"] =  $html;
                } else {
                    return false;
                }
            } else {
                return false;
            }

            $content["body"] =  $html;

            API::send_mail_by_nylas(get_option("ff-nylas-account"),$content);

            $result['type'] = 'success';
        }	
        else {
            $result['type'] = 'error';
        }
        $result = json_encode($result);
        echo $result;
        wp_die();
    }
	
	// send Mail
    public function send_mail( $content)
    {
			return API::send_mail_by_nylas(get_option("ff-nylas-account"),$content);
    }

    // Company Info
    public function get_company_info(){
    	$data['users'] = $this->get_users();

		foreach($data['users'] as $key => $row)
		{
			if(strpos(get_option("ff-teamoverview-blocked"), $row["id"]) !== false)
			{
				unset($data['users'][$key]);
			}	
		}

		if(!$data['users'] ||  !is_array($data['users'])){ 
			return false;
		}
	
        $html = $this->get_html("widget",'default', $data);
		return  $html;
	}
		
	// return template
    public function get_html($page = NULL, $template = "default", $data = NULL, $path = NULL)
    {
        // load module assets
        $this->loadCss(FF_VALUATIONMASTER_THEME);

        if (!empty($data) && !empty($page)) {
            // set path
			if(empty($path)){ $path = plugin_dir_path(__FILE__) . "templates/view/" . $template;}
			
            if (file_exists($path . '/' . $page . '.html')) {
                $loader = new Twig_Loader_Filesystem($path);
                $twig = new Twig_Environment($loader);
                $html = $twig->render($page . '.html', $data);
                return $html;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
	
	// show 404
	protected function show_404()
    {
        global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		get_template_part( 404 ); 
		exit();
    }	

    // load css for plugin
    protected function loadCss($theme = 'default')
    {
		// load default css	
        wp_register_style('ff-valuationmaster-Styles-' . $theme, plugins_url('/assets/css/' . $theme . '/ff-valuationmaster-styles.css', __FILE__),'','1.0.1', false);
        wp_enqueue_style('ff-valuationmaster-Styles-' . $theme);
		
		// load default js	
		wp_register_script('ff-valuationmaster-Script-' . $theme, plugins_url('/assets/js/' . $theme . '/ff-valuationmaster-script.js', __FILE__),'','1.0.2', true);
		wp_enqueue_script( 'ff-valuationmaster-Script-' . $theme );
    
		wp_localize_script('ff-valuationmaster-Script-' . $theme, 'ffdata', array(
                'ajaxurl' => admin_url('admin-ajax.php')
            )
        );

        // Leaflet Maps
        // CSS
        wp_register_style('leaflet', 'https://unpkg.com/leaflet@1.7.1/dist/leaflet.css',true);
        wp_enqueue_style('leaflet');

        // JS
		wp_register_script('leaflet','https://unpkg.com/leaflet@1.7.1/dist/leaflet.js', array('jquery'), '3.3.5', true );
		wp_enqueue_script( 'leaflet' );
	}

    function randomString($length = 32, $string= "0123456789abcdefghijklmnopqrstuvwxyz" ) {
        return substr(str_shuffle( $string), 0, $length);
    }
}

add_action( 'wp_ajax_ajaxcallback', [ 'FFvaluationMasterCore', 'ajaxcallback' ] );
add_action( 'wp_ajax_nopriv_ajaxcallback', [ 'FFvaluationMasterCore', 'ajaxcallback' ] );

add_action( 'wp_ajax_ajaxsubmitagentform', [ 'FFvaluationMasterCore', 'ajaxsubmitagentform' ] );
add_action( 'wp_ajax_nopriv_ajaxsubmitagentform', [ 'FFvaluationMasterCore', 'ajaxsubmitagentform' ] );
