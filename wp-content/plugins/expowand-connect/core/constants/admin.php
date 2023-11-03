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
				"activition": {
					"point":"1",
					"title":"Aktivierung",
					"description":"Mit dem EXPOWAND Wordpress Connect haben Sie die Möglichkeit, Ihr Wordpress mit Ihrem EXPOWAND zu verbinden. <br /><br />Vom Service ausgenommen, bei Bedarf an Ihren Webdesigner wenden: <br />Änderungen an Layout und Design sowie Beeinträchtigungen durch andere Plugins, beziehungsweise Inkompatibilität und Beeinträchtigungen Ihrer Wordpressumgebung.<br />",
					"fields":{
						"ff-token": {
							"type":"password",
							"title":"Aktivierungsschlüssel",
							"description":"Bitte tragen Sie Ihren Aktivierungsschlüssel ein, um das Plugin zu aktivieren. Nach der Aktivierung erhalten Sie Zugriff auf sämtliche Funktionen. Den Aktivierungschlüssel für Ihr Plugin finden Sie <a href=\"https://apps.flowfact.com/settings/portals\" target=\"_blank\">hier</a><br><br><b>Bei einer Aktualisierung des Aktivierungsschlüssels muss das Plugin deaktiviert und erneut aktiviert werden!</b>",
							"requiert":"requiert"
						}
					},
					"faq": {},
					"requiert":["ff-token"]
						
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
			"requiert":["ff-token"],
			"title":"Modul Einrichtung",
			"fields": {
				"estateview": {
					"point":"4",
					"title":"Immobilien Fenster",
					"description":"Das Immobilienfenster präsentiert Ihr gesamtes Immobilienangebot online und lässt sich schnell und einfach in Ihre Website implementieren. So präsentieren Sie Ihre Online-Angebote in neuen Perspektiven. Eine kurze Anleitung zum Gebrauch des Moduls, finden Sie <a href=\"https://service.flowfact.de/hc/de/articles/360000874338-Immobiliendarstellung-Immobilien-ver%C3%B6ffentlichen\" target=\"_blank\">hier</a>. <br /><br />Vom Service ausgenommen, bei Bedarf an Ihren Webdesigner wenden: <br />Änderungen an Layout und Design sowie Beeinträchtigungen durch andere Plugins, beziehungsweise Inkompatibilität und Beeinträchtigungen Ihrer Wordpressumgebung.<br />",
					"fields":{
						"ff-estateView-publish": {
							"type":"portal",
							"title":"Portal wählen",
							"description":"Bitte wählen Sie ein Portal, welches als Grundlage für die Anzeige auf Ihrer Webseite verwendet werden soll. Sämtliche aktive Immobilien, die dem Portal zugewiesen sind, werden automatisch im Immobilienfenster angezeigt.",
							"requiert":"requiert"
						},
						"ff-maps-default": {
							"title":"Wählen Sie einen Kartenanbieter:",
							"type":"possible_options",
							"options":[
							  {
									"key":"0",
									"label": "Keine Karten anzeigen"
								},
								{
									"key":"1",
									"label": "Google Maps (benötigt einen API Schlüssel!)"
								},
								{
									"key":"2",
									"label": "OpenStreetMap"
								}
							],
							"description":""
						},
						"ff-estateView-max-result":{
							"title":"Maximale Immobilien pro Seite",
							"type":"text",
							"description":"Die folgende Einstellung definiert, wieviele Immobilien pro Seite angezeigt werden. Bitte bedenken Sie, dass eine hohe Anzahl zu längeren Ladezeiten führen kann."
						},
						"ff-gg-api-maps": {
							"title":"MAPS API Key",
							"type":"password",
							"description":"Bitte tragen Sie Ihren Google Maps API-Key ein, um Google Maps und die standortbezogene Suche innerhalb der Immobiliensuche zu aktivieren. Weitere Informationen finden Sie <a target=\"_blank\" href=\"https://service.flowfact.de/hc/de/articles/360000866797\" target=\"_blank\">hier</a>.<br /> Die Anzeige von Google Maps stellt eine Verknüpfung zu Ihrer Datenschutzerklärung her. Bitte stellen Sie sicher, dass der Link zu Ihrer Datenschutzerklärung unter Punkt <b>(3) E-Mailkommunikation</b> korrekt hinterlegt ist. <br />"
						},
						"ff-estateView-seo-slug": {
							"title":"Seo URLs",
							"type":"possible_options",
							"options":[
							    {
									"key":"{headline}",
									"label": "Überschrift"
								},
								{
								"key":"{identifier}-{headline}",
									"label": "Kennung - Überschrift"
								}
							],
							"description":"Die folgende Einstellung erlaubt Ihnen, die URL einer Immobilie suchmaschinenoptimiert darzustellen."
						},
						"ff-estateView-estate-sorting": {
							"title":"Standard Sortierung für Immobilien",
							"type":"possible_options",
							"options":[
							  {
									"key":"newest-first",
									"label": "Aktuellste zuerst"
								},
								{
								"key":"price-ascending",
									"label": "Preis aufsteigend"
								},
								{
									"key":"price-descending",
									"label": "Preis absteigend"
								}
							],
							"description":"Mit dieser Einstellung können Sie bestimmen, wie Immobilien in der Immobilienübersicht standardmäßig sortiert werden sollen."
						},
						"ff-estateView-show-socialmedia-links": {
							"title":"Teilen Links anzeigen",
							"type":"possible_options",
							"options":[
							    {
									"key":"1",
									"label": "Ja"
								}
							],
							"description":"Die folgende Einstellung erlaubt es den Besuchern Ihrer Immobilie, diese als Empfehlungs-Link in sozialen Netzwerken zu teilen."
						},
						"ff-estateView-select-slider": {
							"title":"Slider auswählen",
							"type":"possible_options",
							"options":[
							  {
									"key":"1",
									"label": "FlowFact Slider"
								},
								{
									"key":"2",
									"label": "ImmoScout Slider"
								}
							]
						},
						"ff-estateView-headline": {
							"title":"Überschrift im Objekt anzeigen",
							"type":"possible_options",
							"options":[
							  {
									"key":"1",
									"label": "Ja"
								},
								{
									"key":"2",
									"label": "Nein"
								}
							]
						}
					},
					"faq": {},
					"integration": {
						"title":"Modul einbinden",
						"description":"Folgende 3 Optionen stehen Ihnen für die Einbindung auf Ihrer Webseite zur Verfügung.",
						"possibleIntegrations": {
							"iframe": {
								"title":"Als Code einbinden",
								"description":"Hinterlegen Sie den folgenden Code mittels eines Editors in einem HTML Dokument Ihrer Wahl, um das Modul anzuzeigen.",
								"value":""
							},
							"url":{
								"title":"Als Link einbinden",
								"description":"Nutzen Sie folgenden Link, um das Modul direkt in Ihrer Seite aufzurufen.",
								"value":""
							},
							"sitemap":{
								"title":"Sitemap URL",
								"description":"Nutzen Sie folgenden Link, um Ihre Immobilien in Google oder Bing zu indexieren.",
								"value":"/sitemap"
							},
							"shortcode":{
								"title":"Als Shortcode ",
								"description":"Mittels Shortcode kann das Modul direkt in eine Seite eingebunden werden. Hierfür kopieren Sie bitte den Shortcode direkt in den Wordpress Editor an die Stelle, wo das Modul angezeigt werden soll.",
								"value":"[ff_estateview_shortcode]"
							}
						}
					},
					"requiert":["ff-token","ff-estateView-publish"]
						
				},
				"estatereference": {
					"point":"5",
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
							"iframe": {
								"title":"Als Code einbinden",
								"description":"Hinterlegen Sie den folgenden Code mittels eines Editors in einem HTML Dokument Ihrer Wahl, um das Modul anzuzeigen.",
								"value":""
							},
							"url":{
								"title":"Als Link einbinden",
								"description":"Nutzen Sie folgenden Link, um das Modul direkt in Ihrer Seite aufzurufen.",
								"value":""
							},
							"shortcode":{
								"title":"Als Shortcode ",
								"description":"Mittels Shortcode kann das Modul direkt in eine Seite eingebunden werden. Hierfür kopieren Sie bitte den Shortcode direkt in den Wordpress Editor an die Stelle, wo das Modul angezeigt werden soll.",
								"value":"[ff_estatereference_shortcode]"
							}
						}
					},
					"requiert":["ff-token"]
						
				},
				"teamoverview": {
					"point":"6",
					"title":"Team Übersicht",
					"description":"Um Ihr Unternehmen bestmöglich präsentieren zu können, ist die Einbindung Ihres Teams mit allen relevanten Informationen erforderlich. Fügen Sie diese Informationen z. B. auf einer Seite \"Über uns\" ein, die dann die Visitenkarten aller in EXPOWAND aktiven Benutzer darstellt. <br /><br />Vom Service ausgenommen, bei Bedarf an Ihren Webdesigner wenden: <br />Änderungen an Layout und Design sowie Beeinträchtigungen durch andere Plugins, beziehungsweise Inkompatibilität und Beeinträchtigungen Ihrer Wordpressumgebung.<br />",
					"faq": {},
					"fields":{
						"ff-teamoverview-blocked": {
							"title":"Mitarbeiteranzeige konfigurieren",
							"type":"user",
							"description":"Mitarbeiter können per - drag and drop - sortiert oder ganz von der Anzeige ausgeschlossen werden.",
							"requiert":"",
							"default":"'.get_option('ff-nylas-account').'"
						}
					},
					"integration": {
						"title":"Modul einbinden",
						"description":"Mittels folgender Option kann das Modul in Ihrem Internetauftritt dargestellt werden.",
						"possibleIntegrations": {
							"shortcode":{
								"title":"Als Shortcode ",
								"description":"Mittels Shortcode kann das Modul direkt in eine Seite eingebunden werden. Hierfür kopieren Sie bitte den Shortcode direkt in den Wordpress Editor an die Stelle, wo das Modul angezeigt werden soll.",
								"value":"[ff_teamoverview_shortcode]"
							}
						}
					},
					"requiert":["ff-token"]		
				},
				"valuation": {
					"point":"7",
					"title":"Marktwertermittlung",
					"description":"Wenn potentielle Auftraggeber auf der Suche nach einem Immobilienmakler sind, um Ihre Immobilie professionell vermarkten zu lassen, vergleichen sie verschiedene Anbieter. <br /><br />Vom Service ausgenommen, bei Bedarf an Ihren Webdesigner wenden: <br />Änderungen an Layout und Design sowie Beeinträchtigungen durch andere Plugins, beziehungsweise Inkompatibilität und Beeinträchtigungen Ihrer Wordpressumgebung.<br />",
					"faq": {},
					"fields":{
						"ff-valuation-reply-address": {
							"title":"An welche E-Mailadresse sollen die Leads gesendet werden?",
							"type":"text",
							"description":"Die folgende Einstellung definiert, an welche E-Mailadresse die erhaltenen Leads dieses Modules gesendet werden sollen.",
							"requiert":"requiert",
							"default":"'.get_option('ff-nylas-account').'"
						}
					},
					"integration": {
						"title":"Modul einbinden",
						"description":"Folgende 3 Optionen stehen Ihnen für die Einbindung auf Ihrer Webseite zur Verfügung.",
						"possibleIntegrations": {
							"iframe": {
								"title":"Als Code einbinden",
								"description":"Hinterlegen Sie den folgenden Code mittels eines Editors in einem HTML Dokument Ihrer Wahl, um das Modul anzuzeigen.",
								"value":""
							},
							"url":{
								"title":"Als Link einbinden",
								"description":"Nutzen Sie folgenden Link, um das Modul direkt in Ihrer Seite aufzurufen.",
								"value":""
							},
							"shortcode":{
								"title":"Als Shortcode ",
								"description":"Mittels Shortcode kann das Modul direkt in eine Seite eingebunden werden. Hierfür kopieren Sie bitte den Shortcode direkt in den Wordpress Editor an die Stelle, wo das Modul angezeigt werden soll.",
								"value":"[ff_valuation_shortcode]"
							}
						}
					},
					"requiert":["ff-token","ff-valuation-reply-address"]
						
				},
				"valuationMaster": {
					"point":"8",
					"title":"Lead-Hunter",
					"description":"Wenn potentielle Auftraggeber auf der Suche nach einem Immobilienmakler sind, um Ihre Immobilie professionell vermarkten zu lassen, vergleichen sie verschiedene Anbieter.<br />",
					"save_label": "Speichern",
					"faq": {},
					"fields":{
						"ff-valuationMaster-reply-address": {
							"title":"An welche E-Mailadresse sollen die Leads gesendet werden?",
							"type":"text",
							"description":"Entnehmen Sie hier den Status, ob das Produkt erworben und von EXPOWAND für Ihren Vertrag aktiviert wurde. ",
							"requiert":"requiert",
							"default":"'.get_option('ff-nylas-account').'"
						},
						"ff-valuationMaster-token": {
							"title":"Aktivierung des Produktes",
							"type":"entitlement",
							"description":"Entnehmen Sie hier den Status, ob das Produkt erworben und von EXPOWAND für Ihren Vertrag aktiviert wurde.",
							"requiert":"requiert",
							"default":"LEAD_MASTER"
						},
						"ff-valuationMaster-captcha-show": {
							"title":"Captcha einschalten",
							"type":"possible_options",
							"options":[
							    {
									"key":"true",
									"label": "Ja"
								},
								{
								"key":"false",
									"label": "Nein"
								}
							]
						},
						"ff-valuationMaster-calltoaction-name": {
							"title":"Welcher Name soll angezeigt werden?",
							"type":"text",
							"description":"Geben Sie einen Namen an, der auf der Ergebnisseite im Leadhunter erscheinen soll. Dies kann auch ein Teamname sein.",
							"requiert":"",
							"default":""
						},
						"ff-valuationMaster-calltoaction-phone": {
							"title":"Soll eine Rufnummer angezeigt werden?",
							"type":"text",
							"description":"Geben Sie eine Rufnummer an, unter der Ihre potentiellen Kunden Sie schnell bei Rückfragen zur Bewertung erreichen können.",
							"requiert":"",
							"default":""
						},
						"ff-valuationMaster-calltoaction-agent-img": {
							"title":"Welches Bild soll angezeigt werden?",
							"type":"file",
							"description":"Laden Sie ein Bild hoch, welches auf der Ergebnisseite im Leadhunter angezeigt wird. Dies kann auch ein Teambild sein.",
							"requiert":"",
							"default":""
						},
						"ff-valuationMaster-customer-template-formatting-type": {
							"title":"Bitte geben Sie an, in welchem Format Sie Ihre E-Mail-Vorlage hinterlegen:",
							"type":"possible_options",
							"options":[
								{
									"key":"html",
									"label": "HTML"
								},
								{
									"key":"text",
									"label": "Einfacher Text"
								}
							],
							"description":"Um eine an Ihr CI angepasste E-Mailvorlage zu hinterlegen verwenden Sie bitte entsprechenden HTML-Code. In einfachen Text-E-Mails können Sie nur mit Leerzeichen und Umbrüchen arbeiten."
						},
						"ff-valuationMaster-customer-template": {
							"title":"Benachrichtigung an Eigentümer",
							"type":"textarea",
							"description":"Sie können hier direkt in HTML eine E-Mail-Antwort für Ihre Kunden einfügen, diese muss den Link zur Bewertung enthalten, einfach {{Link}} in Ihren Text einfügen. Die Schreibweise des Links muss genauso übernommen werden, also {{Link}}. Der Eigentümer erhält nach der Generierung des Leads automatisch eine E-Mail, diese enthält einen Link zur Marktwertermittlung.",
							"requiert":"",
							"default":""
						},
						"ff-valuationMaster-customer-template-signature": {
							"title":"E-Mail Signatur",
							"type":"textarea",
							"description":"Hier können Sie separat eine E-Mail Signatur im HTML-Format hinterlegen. Dies erlaubt es Ihnen Änderungen zentral an einer Stelle vorzunehmen ohne alle hinterlegten E-Mail Vorlagen ändern zu müssen.",
							"requiert":"",
							"default":""
						}
					},
					"integration": {
						"title":"Modul einbinden",
						"description":"Folgende 3 Optionen stehen Ihnen für die Einbindung auf Ihrer Webseite zur Verfügung.",
						"possibleIntegrations": {
							"iframe": {
								"title":"Als Code einbinden",
								"description":"Hinterlegen Sie den folgenden Code mittels eines Editors in einem HTML Dokument Ihrer Wahl, um das Modul anzuzeigen.",
								"value":""
							},
							"url":{
								"title":"Als Link einbinden",
								"description":"Nutzen Sie folgenden Link, um das Modul direkt in Ihrer Seite aufzurufen.",
								"value":""
							},
							"shortcode":{
								"title":"Als Shortcode ",
								"description":"Mittels Shortcode kann das Modul direkt in eine Seite eingebunden werden. Hierfür kopieren Sie bitte den Shortcode direkt in den Wordpress Editor an die Stelle, wo das Modul angezeigt werden soll.",
								"value":"[ff_valuationmaster_shortcode]"
							}
						}
					},
					"faq": {
						"title":"Hinweis zum Datenschutz",
						"content":"Mit der Nutzung des Lead-Hunters werden Daten vom Eigentümer sowie der von ihm angegebenen Immobilie verarbeitet. Diese Daten werden automatisch per E-Mail an die im Lead-Hunter hinterlegte E-Mail gesendet, zudem wird in Ihrem EXPOWAND der neue Lead in den Schemas (Kontakt, GDPR und Leads) erzeugt. In Ihrem Wordpress selbst werden zu keiner Zeit personenbezogene Daten gespeichert. Möglicherweise führt die Nutzung diese Plugins zu notwendigen Änderungen oder Ergänzungen, die Sie in Ihrer Datenschutzerklärung hinzufügen sollten. Folgende personenbezogenen Angaben können in den Modulen standardmäßig erfasst werden.<br/><br/><b>Erfassung einer Marktwertermittlung im Modul Lead-Hunter:</b><ul><li>Anrede (Pflicht)</li><li>Vorname (Pflicht)</li><li>Nachname (Pflicht)</li><li>Rufnummer (optional)</li><li>Straße  (optional)</li><li>PLZ (optional)</li><li>Ort (optional)</li><li>E-Mail-Adresse (Pflicht)</li><li>Anschrift der zu bewertenden Immobilie</li></ul><br/><br/>Bitte bedenken Sie, dass die Felder je nach Anpassung dieses Plugins durch Ihren Webdesigner abweichen können. <br/><br/>Des Weiteren Übermittelt das Plugin je nach gewählten Immobilienart folgende Angaben an unseren Dienstleister Sprengnetter GmbH zur Berechnung und Erstellung der Marktwertermittlung.<br/><br/><b>Dies Angaben sind:</b><br/><ul><li>Art der Immobilie</li><li>Anschrift der Immobilie  (Pflicht)</li><li>Wohnfläche (Pficht)</li><li>Grundstücksfläche (Pflicht)</li><li>Baujahr (Pflicht)</li><li>Ausstattungsangaben wie Anzahl Bäder, Fenster Isolierung (Pflicht)  </li></ul>",
						"video":""
					},
					"requiert":["ff-valuationMaster-token","ff-valuationMaster-reply-address"]
			
				}	
			}
		}
	}
}');
