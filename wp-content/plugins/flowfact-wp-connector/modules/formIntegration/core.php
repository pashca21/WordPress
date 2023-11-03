<?php
class FFformIntegrationCore extends API
{
    function init()
    {
        // load module assets
        add_action('wp_enqueue_scripts', array(
            $this,
            'frondend_enqueue'
        ));
    }

    function widget($instance)
    {
        // get entries
        return API::get_all_integration();
    }

    function get_default_form($instance)
    {

        $data["legal"]["imprint"] = FF_IMPRINT_URL;
        $data["legal"]["privacy"] = FF_PRIVACY_URL;

        // adds contact form to estate details if SMTP Data avadible
        if (!empty(get_option("ff-nylas-account")) or (!empty(FF_MAIL_FROM) and !empty(FF_MAIL_SERVER) and !empty(FF_MAIL_USER) and !empty(FF_MAIL_PASS)))
        {
            $data["show"]["contactFrom"] = true;
        }
        else
        {
            $data["show"]["contactFrom"] = false;
        }
        return $this->get_html("default-form", "default", $data);
    }

    // return template
    public function get_html($page = NULL, $template = "default", $data = NULL, $path = NULL)
    {
        // load module assets
        $this->loadCss('default');

        if (!empty($page))
        {
            // set path
            if (empty($path))
            {
                $path = plugin_dir_path(__FILE__) . "templates/view/" . $template;
            }

            if (file_exists($path . '/' . $page . '.html'))
            {
                $loader = new Twig_Loader_Filesystem($path);
                $twig = new Twig_Environment($loader);
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

    // enqueue frontend style
    public function loadCss($theme = 'default')
    {

        // load default css
        wp_register_style('FF-formIntegration-Styles-' . $theme, plugins_url('/assets/css/' . $theme . '/ff-formIntegration-styles.css', __FILE__) , '', '1.0.0', false);
        wp_enqueue_style('FF-formIntegration-Styles-' . $theme);

        // load default js
        wp_register_script('FF-formIntegration-Script-' . $theme, plugins_url('/assets/js/' . $theme . '/ff-formIntegration-script.js', __FILE__) , '', '1.0.0', true);
        wp_enqueue_script('FF-formIntegration-Script-' . $theme);

        // load WPADMIN js
        wp_localize_script('FF-formIntegration-Script-' . $theme, 'ffdata', array(
            'ajaxurl' => admin_url('admin-ajax.php')
        ));
    }
}

	// contact Estate
function ajaxdefaultformfunctiont()
{

	if (!empty($_POST))
	{
		$data["email"]["salutation"] = esc_html(sanitize_text_field($_POST["salutation"]));
		$data["email"]["lastName"]   = esc_html(sanitize_text_field($_POST["lastName"]));
		$data["email"]["phone"]      = esc_html(sanitize_text_field($_POST["phone"]));
		$data["email"]["email"]      = esc_html(sanitize_email($_POST["email"]));
		$data["email"]["street"]     = esc_html(sanitize_text_field($_POST["street"]));
		$data["email"]["zip"]        = esc_html(sanitize_text_field($_POST["zip"]));
		$data["email"]["town"]       = esc_html(sanitize_text_field($_POST["town"]));
		$data["email"]["message"]    = esc_html(sanitize_textarea_field($_POST["message"]));
		$data["legal"]["phone"]      = esc_html(sanitize_text_field($_POST["legalPhone"]));
		$data["legal"]["store"]      = esc_html(sanitize_text_field($_POST["legalStore"]));
		$data["legal"]["privacy"]    = esc_html(sanitize_text_field($_POST["legalPrivacy"]));

		if (empty(get_option("ff-nylas-account"))) {
				global $ts_mail_errors;
				global $ffphpmailer;
				if (!is_object($ffphpmailer) || !is_a($ffphpmailer, 'PHPMailer'))
				{ // check if $phpmailer object of class PHPMailer exists
						// if not - include the necessary files
						require_once ABSPATH . WPINC . '/class-phpmailer.php';
						require_once ABSPATH . WPINC . '/class-smtp.php';
						$ffphpmailer = new PHPMailer(true);
				}
				$FFformIntegrationCore = new FFformIntegrationCore();
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
				$ffphpmailer->SMTPDebug = 1;
				$ffphpmailer->SMTPOptions = array(
						'ssl' => array(
								'verify_peer' => false,
								'verify_peer_name' => false,
								'allow_self_signed' => true
						)
				);
				$ffphpmailer->Subject = "FLOWFACT - Eine neue Anfrage ist auf " . get_home_url() . " eingegangen";
				$ffphpmailer->SingleTo = true;
				$ffphpmailer->ContentType = 'text/html';
				$ffphpmailer->IsHTML(true);
				$ffphpmailer->CharSet = 'utf-8';
				$ffphpmailer->ClearAllRecipients();
				$ffphpmailer->AddAddress(FF_MAIL_FROM);
				$ffphpmailer->Body = $FFformIntegrationCore->get_html("email-default-contact", FF_FORMINTEGRATION_THEME, $data, plugin_dir_path(__FILE__) . "/templates/email/" . FF_FORMINTEGRATION_THEME . "/");

				if (!$ffphpmailer->send())
				{
						echo "Mailer Error: " . $ffphpmailer->ErrorInfo;
				}
				else
				{
						echo "Message sent!";
				}
		} else {
			$FFformIntegrationCore = new FFformIntegrationCore();
			$content["subject"] = "FLOWFACT - Eine neue Anfrage ist auf " . get_home_url() . " eingegangen";
			$content["from"][0]["name"] = get_option("ff-nylas-account");
			$content["from"][0]["email"] = get_option("ff-nylas-account");
			if(!empty(FF_MAIL_FROM)) {
				$content["to"][0]["name"] = FF_MAIL_FROM;
				$content["to"][0]["email"] = FF_MAIL_FROM;
			} else {
				$content["to"][0]["name"] = get_option("ff-nylas-account");
				$content["to"][0]["email"] = get_option("ff-nylas-account");
			}
			
			$content["body"] = $FFformIntegrationCore->get_html("email-default-contact", FF_FORMINTEGRATION_THEME, $data, plugin_dir_path(__FILE__) . "/templates/email/" . FF_FORMINTEGRATION_THEME . "/");
			
			$FFformIntegrationCore->send_mail($content);
		}
	}
	wp_die();
}

add_action('wp_ajax_nopriv_ajaxdefaultformfunctiont', 'ajaxdefaultformfunctiont');
add_action('wp_ajax_ajaxdefaultformfunctiont', 'ajaxdefaultformfunctiont');
