<?php
// get config

class FFvaluationCore extends API
{
	
	public function widget()
	{
		$data["mapping"] = json_decode(FF_VALUATION_SALESAUTOMATE_MAPPING, true);
	
		
		$result['title'] 	= "Was ist Ihre Immobilie wert?";
		$result['content'] 	= $this->get_html("widget", FF_VALUATION_THEME, $this->get_search($data));
		return $result;
	
	}
	
 	public function get_overview()
	{
		$data["mapping"] = json_decode(FF_VALUATION_SALESAUTOMATE_MAPPING, true);
	
		
		$result['title'] 	= "Was ist Ihre Immobilie wert?";
		$result['content'] 	= $this->get_html("page-overview", FF_VALUATION_THEME, $this->get_search($data));
		return $result;
	
	}
		
	protected function get_search($data = NULL, $page = 1, $max_results = 9)
	{
		if(!empty($data))
		{		
			// default
			$data["search"]["search"]["schema"] = "default";
	
			// get search params
			if( !empty($_GET))
			{	
				foreach($_GET as $key => $value){
					
					if($key != "iframe")
					{
						$data["search"]["search"][sanitize_text_field($key)] = esc_html(sanitize_text_field($value));
					}	
				}
			}
			
			// get list
			$data = $this->get_search_result($data);
			
			// retrun data
			return $data;
			
		}
		else
		{
			return;
		}	
	}
	
	// return search
    protected function get_search_result($data = NULL, $page = 1, $max_results = FF_VALUATION_MAX_RESULT)
    {
        if (!empty($data["search"]["search"])){

			$schemaId = "searchprofiles";

			// set page
			if(!empty($data["search"]["search"]["page"]))
			{
				$page = $data["search"]["search"]["page"];
			}
			
			// set token
			$token = md5(date("d.m.y h"));
			
			if(!empty($_GET["iframe"]) and $_GET["iframe"] == "1")
			{
				$data["frame"]	= "1";
			}		
			
			$data["score"] 					= $this->get_score($data["search"]["search"],$token);
			$data["token"] 					= $token;
			$data["path"]		    		= plugin_dir_url(dirname(__FILE__)) . "/valuation/assets/img/" . FF_VALUATION_THEME . "/";
			$data["color"]["primary"]		= FF_PRIMARY_COLOR;
			$data["color"]["secondary"]		= FF_SECONDARY_COLOR;
			$data["search"]["path"] 		= get_bloginfo('wpurl') . '/' . FF_PLUGIN_ROUTE . '/' . FF_VALUATION_ROUTE;
			$data["legal"]["imprint"]		= FF_IMPRINT_URL;
			$data["legal"]["privacy"]		= FF_PRIVACY_URL;
			$data["legal"]["reply"]			= (!empty(get_option("ff-nylas-account")))? FF_VALUATION_REPLY_ADDRESS : NULL;

			return $data;
        }
    }
	
	
	
	
	
	 // return search_query
    protected function get_score($data = NULL)
	{



		// set token
		$token = md5(date("d.m.y h"));

		if(!empty($data["estatetype"]) && $data["token"] == $token)
		{
			
			// estate type
			$result["category"] = $data["estatetype"];
			
			// date
			$result["date"] = date("c");						
		
			// construction year
			if(!empty($data["yearofconstruction"]))
			{
				$result["construction_year"] = intval($data["yearofconstruction"]);
			}
			
			// living area 
			if(!empty($data["livingarea"]))
			{
				$result["living_area"] = floatval($data["livingarea"]);
			}
			
			// elevator
			if(!empty($data["elevator"]))
			{
				if($data["elevator"] == "ja")
				{
					$result["elevator"]	= TRUE;
				}
				else
				{
					$result["elevator"]	= FALSE;
				}	
			}
			
			// garages
			if(!empty($data["garages"]))
			{
				if($data["garages"] == "ja")
				{
					$result["garages"]	= TRUE;
				}
				else
				{
					$result["garages"]	= FALSE;
				}	
			}
			
			if(!empty($data["lat"]) && !empty($data["lng"]))
			{
				$result["coordinates"]["lat"]	= floatval($data["lat"]);
				$result["coordinates"]["lng"]	= floatval($data["lng"]);
			}
			
			if(!empty($data["street"]) AND !empty($data["zip"]) )
			{
				$result["address"]["nation"]		= "DE";
				$result["address"]["street"]		= $data["street"];
				$result["address"]["house_number"]	= $data["street_number"];
				$result["address"]["zip"]			= $data["zip"];
				$result["address"]["town"]			= $data["town"];
			}
			
	
					
			// call API
			if(!empty($result))
			{
				// aufgrund fehldend vertrags aktuell nur demo daten sobald vertrag geschlossen wieder einkommentieren
				$data['valuation']['data'] 		= API::get_estate_valuation($result);
				$data['rent']['data'] 			= API::get_estate_valuation_rent($result);

				
				// get purchase ipi
				$i = 0;
				$p_ipi = API::get_estate_valuation_ipi($result);
				
				// set diff data
				if(!empty($p_ipi["values"])){
					foreach (array_slice($p_ipi["values"], -12, 12, true) as $key => $row) {
						$data['valuation']['growth']['data'][$i] = $row;
						$i++;
					}
				

					// set yearly diff
					$data['valuation']['growth']['start'] = $data['valuation']['growth']['data'][0];
					$data['valuation']['growth']['end'] = $data['valuation']['growth']['data'][11];
				}
		
	
				// get rent ipi
				$i = 0;
				$p_ipi =  API::get_estate_valuation_rent_ipi($result);
				
				if(!empty($p_ipi["values"])){
					foreach (array_slice($p_ipi["values"], -12, 12, true) as $key => $row) {
						$data['rent']['growth']['data'][$i] = $row;
						$i++;
					}
				
					// set yearly dif
					$data['rent']['growth']['start'] = $data['rent']['growth']['data'][0];
					$data['rent']['growth']['end'] = $data['rent']['growth']['data'][11];	
				}
				return $data;
			}	
			return;
		}
		return;

	}
	
	public function get_prospect($id = NULL)
    {
        // get contact data form API
        if (!empty($id)) {
            return $this->get_entity_by_id('contacts', sanitize_key($id));
        }
        return null;
    }

	// send Mail
    public function send_mail( $content = NULL)
    {
		if(!empty($content))
		{
			return API::send_mail_by_nylas(get_option("ff-nylas-account"),$content);
		}
		else
		{
			return false;
		}
		
    }

	// return template
    public function get_html($page = NULL, $template = "default", $data = NULL, $path = NULL)
    {
        // load module assets
        $this->loadCss(FF_VALUATION_THEME);

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
	
	
	
    // load css for plugin
    protected function loadCss($theme = 'default')
    {
		
		// load default css	
        wp_register_style('ff-valuation-Styles-' . $theme, plugins_url('/assets/css/' . $theme . '/ff-valuation-styles.css', __FILE__),'','1.0.1', false);
        wp_enqueue_style('ff-valuation-Styles-' . $theme);
		
		// load chosen css
        wp_register_style('FF-valuation-chosen-' . $theme, plugins_url('/assets/css/' . $theme . '/chosen.min.css', __FILE__), '', '1.0.1', false);
        wp_enqueue_style('FF-valuation-chosen-' . $theme);
		
        // load icons css
        wp_register_style('ff-valuation-icons-' . $theme, plugins_url('/assets/css/' . $theme . '/flaticon.css', __FILE__), '', '1.0.1', false);
        wp_enqueue_style('ff-valuation-icons-' . $theme);
		
		// load chosen
        wp_register_script('FF-valuation-chosen-' . $theme, plugins_url('/assets/js/' . $theme . '/chosen.jquery.min.js', __FILE__), '', '1.0.1', true);
        wp_enqueue_script('FF-valuation-chosen-' . $theme);// load chosen
       
		// load default js	
		wp_register_script('ff-valuation-Script-' . $theme, plugins_url('/assets/js/' . $theme . '/ff-valuation-script.js', __FILE__),'','1.0.1', true);
		wp_enqueue_script( 'ff-valuation-Script-' . $theme );
    
		wp_localize_script('ff-valuation-Script-' . $theme, 'ffdata', array(
                'ajaxurl' => admin_url('admin-ajax.php')
            )
        );
	}
}


	// contact Valuation
	function ajaxvaluationcontactfunctiont()
	{

		if(!empty($_POST))
		{
			
				
			$data["prospect"]["salutation"] 				= esc_html(sanitize_text_field($_POST["salutation"]));
			$data["prospect"]["firstName"] 					= esc_html(sanitize_text_field($_POST["firstName"]));
			$data["prospect"]["lastName"] 					= esc_html(sanitize_text_field($_POST["lastName"]));
			$data["prospect"]["phone"] 						= esc_html(sanitize_text_field($_POST["phone"]));
			$data["prospect"]["email"] 						= esc_html(sanitize_email($_POST["email"]));
			
			$data["property"]["estateType"] 				= esc_html(sanitize_text_field($_POST["estateType"]));
			$data["property"]["street"] 					= esc_html(sanitize_text_field($_POST["street"]));
			$data["property"]["streetNumber"] 				= esc_html(sanitize_text_field($_POST["streetNumber"]));
			$data["property"]["zip"] 						= esc_html(sanitize_text_field($_POST["zip"]));
			$data["property"]["town"] 						= esc_html(sanitize_text_field($_POST["town"]));
			$data["property"]["livingArea"] 				= esc_html(sanitize_text_field($_POST["livingarea"]));
			$data["property"]["rooms"] 						= esc_html(sanitize_text_field($_POST["rooms"]));
			$data["property"]["yearOfConstruction"] 		= esc_html(sanitize_text_field($_POST["yearofconstruction"]));
			
			$data["valuation"]["possiblePurchasePrice"]		= esc_html(sanitize_text_field($_POST["possiblePurchasePrice"]));
			$data["valuation"]["growthPurchasePrice"]		= esc_html(sanitize_text_field($_POST["growthPurchasePrice"]));
			$data["valuation"]["possibleRentPrice"] 		= esc_html(sanitize_text_field($_POST["possibleRentPrice"]));
			$data["valuation"]["growthRentPrice"]			= esc_html(sanitize_text_field($_POST["growthRentPrice"]));
			
			$data["legal"]["phone"]							= esc_html(sanitize_text_field($_POST["legalPhone"]));
			$data["legal"]["privacy"]						= esc_html(sanitize_text_field($_POST["legalPrivacy"]));

			$data["general"]["page"] 						= get_home_url();
			$data["general"]["createDate"] 					= date("d.m.y");

			
			
			if(empty(get_option("ff-nylas-account"))) {
				global $ts_mail_errors;
				global $ffphpmailer2;
				if ( !is_object( $ffphpmailer2 ) || !is_a( $ffphpmailer2, 'PHPMailer' ) ) { // check if $phpmailer object of class PHPMailer exists
					// if not - include the necessary files
					require_once ABSPATH . WPINC . '/class-phpmailer.php';
					require_once ABSPATH . WPINC . '/class-smtp.php';
					$ffphpmailer2 = new PHPMailer( true );
				}
				
				$FFvaluationCore = new FFvaluationCore();
				$ffphpmailer2->isSMTP();	
				$ffphpmailer2->ClearAttachments();
				$ffphpmailer2->ClearCustomHeaders();
				$ffphpmailer2->ClearReplyTos(); 
				$ffphpmailer2->IsHTML( true );
				$ffphpmailer2->Host 	 	= FF_MAIL_SERVER;
				$ffphpmailer2->Port 	 	= FF_MAIL_PORT; 
				$ffphpmailer2->Username 	= FF_MAIL_USER;
				$ffphpmailer2->Password 	= FF_MAIL_PASS; 
				$ffphpmailer2->From        	= FF_MAIL_FROM;
				$ffphpmailer2->FromName    	= FF_MAIL_FROM;
				$ffphpmailer2->SMTPAuth 	= true;
				$ffphpmailer2->SingleTo 	= true;
				$ffphpmailer2->SMTPSecure  	= false; 
				$ffphpmailer2->SMTPDebug   	= 0;
				$ffphpmailer2->SMTPOptions 	= array('ssl' => array('verify_peer' => false,'verify_peer_name' => false,'allow_self_signed' => true));
				$ffphpmailer2->Subject 		= "FLOWFACT - Eine neue kostenlose Marktwertanalyse wurde auf ".get_home_url()." angefordert";
				$ffphpmailer2->ContentType 	= 'text/html'; 
				$ffphpmailer2->CharSet 		= 'utf-8';
				$ffphpmailer2->ClearAllRecipients();	
				$ffphpmailer2->AddAddress(FF_VALUATION_REPLY_ADDRESS);
				$ffphpmailer2->Body = $FFvaluationCore->get_html("email-contact", FF_VALUATION_THEME, $data, plugin_dir_path(__FILE__) . "/templates/email/".FF_VALUATION_THEME."/");

			}
			else
			{
				
				$FFvaluationCore = new FFvaluationCore();
				$content["subject"] 			= "FLOWFACT - Eine neue kostenlose Marktwertanalyse wurde auf ".get_home_url()." angefordert";
				$content["from"][0]["name"] 	= get_option("ff-nylas-account");
				$content["from"][0]["email"] 	= get_option("ff-nylas-account");
				//$content["to"]["email"] 		= $_POST["ffreply"];
				$content["to"][0]["name"] 		= FF_VALUATION_REPLY_ADDRESS;
				$content["to"][0]["email"] 		= FF_VALUATION_REPLY_ADDRESS;
				$content["body"] 				= $FFvaluationCore->get_html("email-contact", FF_VALUATION_THEME, $data, plugin_dir_path(__FILE__) . "/templates/email/".FF_VALUATION_THEME."/");
				
				return $FFvaluationCore->send_mail($content);
				
			}
		}	

		return;		
	}


	add_action('wp_ajax_nopriv_ajaxvaluationcontactfunctiont','ajaxvaluationcontactfunctiont');
	add_action('wp_ajax_ajaxvaluationcontactfunctiont','ajaxvaluationcontactfunctiont');
 

	
