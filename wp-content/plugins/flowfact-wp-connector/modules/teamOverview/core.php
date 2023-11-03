<?php
// get config

class FFteamOverviewCore extends API
{
	
	// load widget of estateview
    public function widget()
    {
		$result = $this->get_team_overview();
		return $result;    
    }
	
	public function get_team_overview(){
    	$data['users'] = $this->get_users_no_cache();

        if(get_option("ff-teamoverview-blocked") && is_array(json_decode(get_option("ff-teamoverview-blocked")))){
            // deactivate blocked users
            $jsonArray = json_decode(get_option("ff-teamoverview-blocked"), true);

            // sort users array

            $newArrayForSorting = [];
            foreach($jsonArray as $jsonkey => $jsonsingle) {

                foreach($data['users'] as $userkey => $user) {

                    if($user['id'] == $jsonsingle['id']) {
                        $newArrayForSorting[$jsonkey] = $user;
                    }
                }
            }

            $data['users'] = $newArrayForSorting;


            // deactivate blocked users
            $blockedIds = array();
            foreach($jsonArray as $item){
                if($item['class'] == 'ff-false'){
                    $blockedIds[] = $item['id'];
                }
            }

            $blockedIdsString = implode(", ", $blockedIds);

            foreach($data['users'] as $key => $row)
            {
                if(strpos($blockedIdsString, $row["id"]) !== false)
                {
                    unset($data['users'][$key]);
                }	
            }
        } else {
            
            foreach($data['users'] as $key => $row)
            {	
                if($row["active"] != 1) {
                    unset($data['users'][$key]);
                } else {
                    if($row["active"] = 1) {
                        if(strpos(get_option("ff-teamoverview-blocked"), $row["id"]) !== false)
                        {
                            unset($data['users'][$key]);
                        }
                    }
                }
            }
        }

        if(!$data['users'] || !is_array($data['users'])){ 
            return false;
        }
	
        $html = $this->get_html("widget",'default', $data);
		return  $html;
	}


    // return template
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

    // load css for plugin
    protected function loadCss($theme = 'default')
    {
        // load css
        wp_register_style('FF-TeamOverview-' . $theme, plugins_url('/assets/css/' . $theme . '/ff-teamoverview-style.css', __FILE__), '', '1.0.0', false);
        wp_enqueue_style('FF-TeamOverview-' . $theme);

        // load js
       
        wp_register_script('FF-TeamOverview-' . $theme, plugins_url('/assets/js/' . $theme . '/ff-teamoverview-script.js', __FILE__), array('jquery-core'), '1.0.0', true);
		wp_enqueue_script('FF-TeamOverview-' . $theme);

    }
}
 
