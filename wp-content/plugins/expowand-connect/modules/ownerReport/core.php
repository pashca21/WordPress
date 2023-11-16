<?php
class FFownerReportCore extends API{



	protected function get_page($page,$data){
		$data['path'] = get_bloginfo('wpurl') . '/' . EW_PLUGIN_ROUTE . '/' . FF_OWNERREPORT_ROUTE;
		return $this->get_html("page-{$page}", 'default', $data);
	}

	public function get_login_page($data=array('logged_out'=>false, 'failed'=>false)){
		$data['title'] = 'Login';
		$data['path'] = get_bloginfo('wpurl') . '/' . EW_PLUGIN_ROUTE . '/' . FF_OWNERREPORT_ROUTE;
		$data['content']=$this->get_page('login', $data);
		
		if($this->is_user_logged_in()){

			$data['content']='<h2>You are logged in.</h2>';
		}
		return $data;
	}
	
    protected function get_allowed_estates(){
		$estates=API::get_estates_by_objecttracking($this->get_user_objectracking());
		foreach($estates['entries'] as $estate){
			$allowed_estates[]=$estate['_metadata']['id'];
		}
		return $allowed_estates;
    }
	public function get_owner_report($entity_id){
		$allowed_estates = $this->get_allowed_estates();
		if(in_array($entity_id, $allowed_estates)){
			$data['title']= 'Single Report';
			$data['content']= $this->get_estates_single_view($entity_id);
		}else{
			$data['title'] = "Key does not have access to this property";
			$data['content']= $this->get_page('forbidden',array('forbidden'));
		}
		return $data;
	}

	public function handle_login($input=null){
		$entities=API::get_estates_by_objecttracking($input);
		
		if($entities['totalCount']==0||!$entities){
			$data['failed']='true';
			$data['content']=$this->get_login_page($data);
			return $data;
		}
		
		if($entities){		
			$data = array();
			$this->login_user($input);
			$entities['entries'] = $this->get_formatted_fields($entities);			
			if(!empty($entities['entries']['ID']) and  $entities['totalCount'] == 1){
				$entity_id = $entities['entries']['ID'];
				$data['title']='Single Report';
				$data['content']= $this->get_estates_single_view($entity_id);
				return $data;
			}
			else{
				$data['title']='Estate List';
				$data['content']= $this->get_estates_list_view($entities);
				return $data;
			}
			
		}
	}
	public function get_report_or_list(){
		$entities=API::get_estates_by_objecttracking($this->get_user_objectracking());
		
		if($entities){		
		$data = array();
		$entities['entries'] = $this->get_formatted_fields($entities);
		
		if(empty($entities['entries']['ID']) and $entities['totalCount']==1){
			$entity_id=$entities['entries']['ID'];
			$data['title']='Single Report';
			$data['content']= $this->get_estates_single_view($entity_id);
			return $data;
		}
		else{
			$data['title']='Estate List';
			$data['content']= $this->get_estates_list_view($entities);
			return $data;
		}
		
		}


	}
	public function get_estates_single_view($entity_id){
		$data=$this->get_reportdata_by_id($entity_id);
		return $this->get_page('estate-report',$data);
		
		
	} 
	public function get_estates_list_view($entities){
		return $this->get_page('estate-list',$entities);
	}

	public function is_user_logged_in(){
		if(!empty($_SESSION['ff_owner_code'])){
			return true;
		}
		return false;	
	}

	public function login_user($code){
		$_SESSION['ff_owner_code']=$code;
		return true;
	}

	public function get_user_objectracking(){
		if($_SESSION['ff_owner_code']){
			return $_SESSION['ff_owner_code'];
		}
		else{
			return false;
		}

	}

	public function logout_user(){
	
		return session_destroy();

	}

	public function get_formatted_fields($entities){
		$data["mapping"] = json_decode(FF_OWNERREPORT_MAPPING, true);
		$mapped_fields = array();

		foreach ($data["mapping"]["list"] as $key => $schema) {

	
			foreach ( $schema as $fields) {


				foreach ( $fields as $key2 => $field) 
				{
					$mapped_fields[$key2]=$field;
					
				}
			}

		}

		foreach ($entities["entries"] as $row => $estate )
		{	

			foreach($mapped_fields as $key => $field )
			{

				if(!empty($field))
				{
					if(!empty($estate["_metadata"]["id"]) && ($key != $estate["_metadata"]["id"]))
					{
						$estate_list[$estate["_metadata"]["id"]][$key] = API::get_formated_fields($key, $field, $estate);	
					}	
				}
			}
			$estate_list[$estate["_metadata"]["id"]]['schema']= $estate["_metadata"]["schema"];
	
		}
	return $estate_list;
	}

	

	public function get_html($page = NULL, $template = "default", $data = NULL, $path = NULL)
	{
        // load module assets
  
		$this->loadCss(FF_TEAMOVERVIEW_THEME);
		

		if (!empty($data) && !empty($page)) {
            // set path/

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

	public function loadCss($theme = 'default') {

		wp_register_style('FF-Ownerreport-Login-Styles-' . $theme, plugins_url('/assets/css/' . $theme . '/ff-ownerreport-login-styles.css', __FILE__),'','1.0.0', false);
		wp_enqueue_style('FF-Ownerreport-Login-Styles-' . $theme);


		wp_register_style('FF-Ownerreport-Styles-' . $theme, plugins_url('/assets/css/' . $theme . '/ff-ownerreport-styles.css', __FILE__),'','1.0.0', false);
		wp_enqueue_style('FF-Ownerreport-Styles-' . $theme);


		wp_enqueue_script( 'jquery');    



		// load chartist	
		wp_register_style('FF-Ownerreport-Styles-Chartist-' . $theme, plugins_url('/assets/css/' . $theme . '/chartist/chartist.min.css', __FILE__),'','1.0.0', false);
		wp_enqueue_style('FF-Ownerreport-Styles-Chartist-' . $theme);
		wp_register_script('FF-Ownerreport-Script-Chartist-' . $theme, plugins_url('/assets/js/' . $theme . '/chartist/chartist.min.js', __FILE__),'','1.0.0', true);
		wp_enqueue_script( 'FF-Ownerreport-Script-Chartist-' . $theme );

		wp_register_script('FF-Ownerreport-Script-Chartist-legend-' . $theme, plugins_url('/assets/js/' . $theme . '/chartist-legend/chartist-plugin-legend.js', __FILE__),'','1.0.0', true);
		wp_enqueue_script( 'FF-Ownerreport-Script-Chartist-legend-' . $theme );

		wp_register_script('FF-Ownerreport-Script-' . $theme, plugins_url('/assets/js/' . $theme . '/ff-ownerreport-script.js', __FILE__),'','1.0.0', true);
		wp_enqueue_script( 'FF-Ownerreport-Script-' . $theme );

	}	






}
