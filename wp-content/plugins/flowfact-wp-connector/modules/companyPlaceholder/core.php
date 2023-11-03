<?php


class FFcompanyPlaceholderCore extends API
{
	public function update_company_data()
    {
        $lastSync = get_option('ff-companyPlaceholder-last-synch');
		if(date("Y-m-d H:i:s",strtotime($lastSync)) <=  date("Y-m-d H:i:s"))
		{
			add_option('ff-companyPlaceholder-last-synch', date("Y-m-d H:i:s", strtotime("+4 hours")));
			update_option('ff-companyPlaceholder-last-synch', date("Y-m-d H:i:s", strtotime("+4 hours")));
			
			add_option('ff-companyPlaceholder-data', json_encode(API::get_company_data(), JSON_UNESCAPED_UNICODE));
			update_option('ff-companyPlaceholder-data', json_encode(API::get_company_data(), JSON_UNESCAPED_UNICODE));
		}

		return;
    }	
}


	

add_shortcode( 'COMPANY', 'ff_placeholder' );
add_shortcode( 'FFSTD', 'ff_standardPlaceholder' );


function ff_standardPlaceholder($atts) {
  
	if(!empty($atts))
	{
		foreach($atts as $field)
		{
			if($field == 'WMA_SAMPLE_PDF')
			{
				return 	"https://download.flowfact.me/wp-demo-content/Wohnmarktanalyse-Muster.pdf";	
			}	
			
			if($field == 'WMA_SAMPLE_PREVIEW_JPG')
			{
				return 	"https://download.flowfact.me/wp-demo-content/wma.jpg";	
			}	
				
		}
		
	}

}


function ff_placeholder($atts) {
   if(!empty($atts))
	{
		$FFcompanyPlaceholderCore = new FFcompanyPlaceholderCore();
		$FFcompanyPlaceholderCore->update_company_data();
	
		$data = json_decode(get_option('ff-companyPlaceholder-data'), true);
	
		foreach($atts as $field)
		{
			if($field == 'CONTACT_PERSON')
			{
				return "";	
			}
			
			if($field == 'NAME')
			{
				return $data["companyName"];	
			}
			
			if($field == 'ADDITION')
			{
				return "";	
			}	
			
			if($field == 'STREET')
			{
				return $data["companyStreet"];
			}	
			
			if($field == 'ZIP')
			{
				return $data["companyPostcode"];	
			}
			
			if($field == 'TOWN')
			{
				return $data["companyCity"];	
			}	
			
			if($field == 'COUNTRY')
			{
				return "";
			}	
			
			if($field == 'PHONE')
			{
				return $data["companyPhoneInfo"];
			}	
			
			if($field == 'FAX')
			{
				return $data["companyFax"];	
			}	
			
			if($field == 'EMAIL')
			{
				return $data["companyMailInfo"];	
			}	
			
			if($field == 'URL')
			{
				return $data["companyUrl"];	
			}	
			
			if($field == 'VAT')
			{
				return 	"";	
			}	
			
			if($field == 'VAT_ID')
			{
				return 	$data["companyUstId"];	
			}	
			
			if($field == 'COMMERCIALREGISTRY_LOCATION')
			{
				return 	$data["companyHrbPlace"];	
			}	
			
			if($field == 'COMMERCIALREGISTRY_NUMBER')
			{
				return 	$data["companyHrb"];	
			}	
			
			if($field == 'COMMERCIALREGISTRY_DISTRICTCOURT')
			{
				return 	"";	
			}	
			
			if($field == 'DIRECTOR1')
			{
				return 	"";	
			}
			
			if($field == 'DIRECTOR2')
			{
				return 	"";	
			}	
						
			if($field == 'DIRECTOR3')
			{
				return 	"";	
			}	
			
			if($field == 'SOCIAL_TWITTER')
			{
				return $data["companyUrlTwitter"];	
			}	
			
			if($field == 'SOCIAL_GOOGLE')
			{
				return $data["companyUrlGoogle"];	
			}	
			
			if($field == 'SOCIAL_LINKEDIN')
			{
				return $data["companyUrlLinkedin"];	
			}
			
			if($field == 'SOCIAL_FACEBOOK')
			{
				return $data["companyUrlFacebook"];	
			}	
		}
		
	}
}


