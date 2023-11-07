<?php 
	
	define('FF_ADMIN_SETTINGS', '{
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
							"description":"Bitte tragen Sie Ihren Aktivierungsschlüssel ein, um das Plugin zu aktivieren. Nach der Aktivierung erhalten Sie Zugriff auf sämtliche Funktionen. Den Aktivierungschlüssel für Ihr Plugin finden Sie <a href=\"https://apps.flowfact.com/settings/portals\" target=\"_blank\">hier</a><br><br><b>Bei einer Aktualisierung des Aktivierungsschlüssels muss das Plugin deaktiviert und erneut aktiviert werden!</b>",
							"requiert":"requiert"
						}
					},
					"faq": {},
					"requiert":["ew-token"]
						
				},
				"ci": {
					"point":"2",
					"title":"Farben",
					"description":"Folgende Einstellungen definieren das Farbschema der einzelnen Module.",
					"fields":{
						"ff-primary-color": {
							"title":"Primärfarbe",
							"type":"color",
							"description":"Die Primärfarbe wird verwendet, um das Layout der einzelnen Module an Ihren Internetauftritt und Ihre Firmenfarben anzupassen.",
							"requiert":"requiert",
							"default":"#666666"
						},
						"ff-secoundary-color":{
							"title":"Sekundärfarbe",
							"type":"color",
							"description":"Die Sekundärfarbe wird verwendet, um das Layout der einzelnen Module an Ihren Internetauftritt und Ihre Firmenfarben anzupassen.",
							"requiert":"requiert",
							"default":"#ffffff"
						}
					},
					"faq": {},
					"requiert":["ff-primary-color"]
						
				},
				"communication": {
					"point":"3",
					"title":"E-Mailkommunikation",
					"description":"Folgende Einstellungen definieren, über welche E-Mailadresse das Plugin Nachrichten versenden darf. Bitte bedenken Sie, dass die E-Mailkommunikation (z.B. bei Immobilienanfragen oder Kontaktanfragen) solange deaktivert bleibt, bis Sie ein entsprechendes Konto für den Versand gewählt haben.",
					"fields":{
						"ff-nylas-account": {
							"type":"nylas",
							"title":"E-Mailadresse",
							"description":"Bitte wählen Sie eine E-Mailadresse, über die versendet werden soll.",
							"requiert":"requiert"
						},
						"ff-privacy-url":{
							"title":"URL zu Ihrer Datenschutzerklärung",
							"type":"text",
							"description":"Die URL wird zur DSGVO Konformität Ihrer Formulare benötigt."
						},
						"ff-imprint-url": {
							"title":"URL zu Ihrem Impressum",
							"type":"text",
							"description":"Die URL wird zur DSGVO Konformität Ihrer Formulare benötigt."
						}
					},
					"faq": {
						"title":"Hinweis zum Datenschutz",
						"content":"Mit der Hinterlegung Ihrer E-Maildaten schalten Sie die E-Mail Funktion im EXPOWAND Wordpress Connect frei. Dies ermöglicht Ihnen, Kontaktanfragen zu Immobilien oder Leads aus der Wertermittlung zu erfassen und an eine von Ihnen angegebene E-Mail-Adresse senden zu lassen. Wir empfehlen Ihnen, Ihre Datenschutzerklärung in Bezug der Nutzung dieses Plugins zu prüfen und ggf. zu erweitern. Des Weiteren sollten Sie von Zeit zu Zeit Ihre Datenschutzerklärung auf jeweilige Änderungen überprüfen, besonders nach einer Aktualisierung dieses Plugins. Möglicherweise gibt es Änderungen oder neue vorgeschlagene Informationen, die Sie in Ihrer Datenschutzerklärung hinzufügen sollten. Folgende personenbezogenen Angaben können in den Modulen standardmäßig erfasst werden.<br/><br/><b>Erfassung einer Kontaktanfrage im Modul Immobilienfenster:</b><ul><li>Anrede (Pflicht)</li><li>Vorname (optional)</li><li>Nachname (Pflicht)</li><li>Rufnummer (optional)</li><li>Straße  (optional)</li><li>PLZ (optional)</li><li>Ort (optional)</li><li>E-Mail-Adresse (Pflicht)</li><li>Mitteilung des Ausfüllenden (Pflicht)</li></ul><br/><br/><b>Anforderung Marktwertanalyse im Modul Immobilien-Marktwert:</b><br/><br/><ul><li>Anrede (Pflicht)</li><li>Vorname (optional)</li><li>Nachname (Pflicht)</li><li>Rufnummer (optional)</li><li>E-Mail-Adresse (Pflicht)</li><li>Anschrift der zu bewertenden Immobilie</li></ul><br/><br/>Bitte bedenken Sie, dass die Felder je nach Anpassung dieses Plugins durch Ihren Webdesigner abweichen können.",
						"video":""
					},
					"requiert":[ "ff-nylas-account"]
						
				}		
			}
		},
		"modules": {
			"requiert":["ew-token"],
			"title":"Modul Einrichtung",
			"fields": {
				"estatereference": {
					"point":"4",
					"title":"Immobilien Referenzen",
					"description":"Immobilienreferenzen schaffen Vertrauen. Hinter jedem Immobilienangebot steht ein Auftraggeber, der sich für den Immobilienmakler entschieden hat. Sobald eine Immobilie erfolgreich vermarktet ist, macht das Modul Immobilienreferenzen den nächsten Schritt ganz einfach: Per Mausklick wird das Objekt zur Referenz-Immobilie und steht umgehend online in Ihrem Immobilien-Referenzen-Bereich. Eine kurze Anleitung zum Gebrauch des Moduls, finden Sie <a href=\"https://service.flowfact.de/hc/de/articles/360000874358-Referenzen-anzeigen\" target=\"_blank\">hier</a>. <br /><br />Vom Service ausgenommen, bei Bedarf an Ihren Webdesigner wenden: <br />Änderungen an Layout und Design sowie Beeinträchtigungen durch andere Plugins, beziehungsweise Inkompatibilität und Beeinträchtigungen Ihrer Wordpressumgebung.<br />",
					"faq": {},
					"fields":{
						"ff-estatereference-max-result": {
							"type":"text",
							"title":"Maximale Immobilienreferenzen pro Seite",
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
