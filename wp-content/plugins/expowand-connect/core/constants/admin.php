<?php 
	
	define('EW_ADMIN_SETTINGS', '{
	"blocked_by_plugin":{
		"wp-maintenance-mode/wp-maintenance-mode":{
			"name":"WP Maintenance Mode",
			"error":"In Ihrem Wordpress ist das Plugin WP Maintenance Mode aktiviert. Dieses verhindert das Laden der einzelnen Module dieses Plugins, solange der Wartungsmodus des Plugins aktiviert ist. Bitte deaktivieren Sie den Wartungsmodus und testen Sie dann den Aufruf der Module erneut.",
			"check": {
				"option_name":"wpmm_settings",
				"path":{
					"level1":"general",
					"level2":"status",
					"level3":"",
					"level4":""
				},
				"value":"1"
			}
		},
		"wordpress-seo/wp-seo":{
			"name":"Yoast SEO",
			"error":"In Ihrem Wordpress ist das Plugin Yoast Seo aktiviert. Dieses verhindert das Laden der Sitemap.xml im Immobilien-Fenster. Bitte nutzen Sie alternativ die Sitemap.txt, um eine Indexierung in Google und Bing zu ermöglichen.",
			"check": {
				"option_name":"wpseo",
				"path":{
					"level1":"enable_xml_sitemap",
					"level2":"",
					"level3":"",
					"level4":""
				},
				"value":true
			}
		}
	},
	"modules":{
		"basic": {
			"title":"Grundeinstellungen",
			"fields": {
				"activation": {
					"point":"1",
					"title":"Aktivierung",
					"description":"Mit dem EXPOWAND Wordpress Connect haben Sie die Möglichkeit, Ihr Wordpress mit Ihrem EXPOWAND zu verbinden. <br /><br />Vom Service ausgenommen, bei Bedarf an Ihren Webdesigner wenden: <br />Änderungen an Layout und Design sowie Beeinträchtigungen durch andere Plugins, beziehungsweise Inkompatibilität und Beeinträchtigungen Ihrer Wordpressumgebung.<br />",
					"fields":{
						"ew-token": {
							"type":"password",
							"title":"Aktivierungsschlüssel",
							"description":"Bitte tragen Sie Ihren Aktivierungsschlüssel ein, um das Plugin zu aktivieren. Nach der Aktivierung erhalten Sie Zugriff auf sämtliche Funktionen. Den Aktivierungschlüssel für Ihr Plugin finden Sie <a href=\"https://expowand.de/account\" target=\"_blank\">hier</a><br><br><b>Bei einer Aktualisierung des Aktivierungsschlüssels muss das Plugin deaktiviert und erneut aktiviert werden!</b>",
							"requiert":"requiert"
						}
					},
					"faq": {},
					"requiert":["ew-token"]
						
				}
			}
		},
		"modules": {
			"requiert":["ew-token"],
			"title":"Modul Einrichtung",
			"fields": {
				"estatereference": {
					"point":"2",
					"title":"Immobilien Referenzen",
					"description":"Immobilienreferenzen schaffen Vertrauen. Hinter jedem Immobilienangebot steht ein Auftraggeber, der sich für den Immobilienmakler entschieden hat. Sobald eine Immobilie erfolgreich vermarktet ist, macht das Modul Immobilienreferenzen den nächsten Schritt ganz einfach: Per Mausklick wird das Objekt zur Referenz-Immobilie und steht umgehend online in Ihrem Immobilien-Referenzen-Bereich.<br /><br />Vom Service ausgenommen, bei Bedarf an Ihren Webdesigner wenden: <br />Änderungen an Layout und Design sowie Beeinträchtigungen durch andere Plugins, beziehungsweise Inkompatibilität und Beeinträchtigungen Ihrer Wordpressumgebung.<br />",
					"faq": {},
					"fields":{
						"ew-estatereference-max-result": {
							"type":"text",
							"title":"Maximale Immobilienreferenzen pro Seite",
							"default":"10",
							"description":"Die folgende Einstellung definiert, wieviele Immobilienreferenzen pro Seite angezeigt werden. Bitte bedenken Sie, dass eine hohe Anzahl zu längeren Ladezeiten führen kann.",
							"requiert":""
						}
					},
					"integration": {
						"title":"Modul einbinden",
						"description":"Folgende 3 Optionen stehen Ihnen für die Einbindung auf Ihrer Webseite zur Verfügung.",
						"possibleIntegrations": {
								"shortcode":{
								"title":"Als Shortcode ",
								"description":"Mittels Shortcode kann das Modul direkt in eine Seite eingebunden werden. Hierfür kopieren Sie bitte den Shortcode direkt in den Wordpress Editor an die Stelle, wo das Modul angezeigt werden soll.",
								"value":"[ew_estatereference_shortcode]"
							}
						}
					},
					"requiert":["ew-token"]
						
				}
			}
		}
	}
}');
