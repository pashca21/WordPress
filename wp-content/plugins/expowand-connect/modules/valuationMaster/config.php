<?php 

		
	/*********************
	*   valuationMaster
	*********************/
	
	// valuationMaster setting
	(!empty(get_option('ff-valuationMaster-theme')))? define('FF_VALUATIONMASTER_THEME', get_option('ff-valuationMaster-theme') ) : define('FF_VALUATIONMASTER_THEME', 'default' ) ;
	(!empty(get_option('ff-valuationMaster-reply-address')))? define('FF_VALUATIONMASTER_REPLY_ADDRESS', get_option('ff-valuationMaster-reply-address') ) : define('FF_VALUATIONMASTER_REPLY_ADDRESS', '' ) ;
	
	// valuationMaster salesautomat setting
	$mapping = '
	{
		"possibleTypes": [
			{
				"name": "EFH",
				"caption": "Haus",
				"icon": "EFH.png"
			},
			{
				"name": "MFH",
				"caption": "Mehrfamilien&shy;haus",
				"icon": "MFH.png"
			},
			{
				"name": "ETW",
				"caption": "Wohnung",
				"icon": "ETW.png"
			},
			{
				"name": "GRD",
				"caption": "Grundstück",
				"icon": "BGS.png"
			}
		],
		"process": {
			"EFH": {
				"name": "Haus",
				"phases": [
					{
						"id": "2",
						"name": "Angaben zur Immobilie",
						"layout": "grid",
						"description": "Folgende Angaben helfen uns, Ihre Immobilie besser bewerten zu können.",
						"fields": [
							{
								"name": "livingarea",
								"caption": "Wohnfläche",
								"type": "range",
								"icon": "LIVINGAREA.png",
								"requiert": true,
								"value": 60,
								"min": 1,
								"max": 1000,
								"unit": "m²"
							},
							{
								"name": "plot_area",
								"caption": "Grundstücksfläche",
								"type": "range",
								"icon": "BGS.png",
								"requiert": true,
								"value": 100,
								"min": 1,
								"max": 5000,
								"unit": "m²"
							},
							{
								"name": "yearofconstruction",
								"caption": "Baujahr",
								"type": "range",
								"icon": "CONSTRUCTION.png",
								"requiert": true,
								"value": 1990,
								"min": 1850,
								"max": 2021,
								"unit": ""
							}
						]
					},
					{
						"id": "3",
						"layout": "addresses",
						"name": "Lage Ihrer Immobilie",
						"description": "Um den Wert der Immobilie ermitteln zu können, benötigen wir die genaue Lage der Immobilie",
						"fields": [
							{
								"name": "street",
								"caption": "Straße",
								"type": "text",
								"requiert": true
							},
							{
								"name": "house_number",
								"caption": "Hausnummer",
								"type": "text",
								"requiert": true
							},
							{
								"name": "zip",
								"caption": "Postleitzahl",
								"type": "text",
								"requiert": true
							},
							{
								"name": "town",
								"caption": "Ort",
								"type": "text",
								"requiert": true
							}
						]
					},
					{
						"id": "4",
						"layout": "contact",
						"name": "Angaben zu Ihrer Person",
						"description": "Um eine vollständige Wertermittlung Ihrer Immobilie erstellen zu können, benötigen wir folgende Angaben von Ihnen.",
						"fields": [
							{
								"name": "customer_salutation",
								"caption": "Anrede",
								"type": "option",
								"icon": "",
								"requiert": true,
								"option": [
									{
										"caption": "Herr",
										"value": "Herr"
									},
									{
										"caption": "Frau",
										"value": "Frau"
									},
									{
										"caption": "Divers",
										"value": "Divers"
									}
								]
							},
							{
								"name": "customer_name",
								"caption": "Vor- und Zuname",
								"type": "text",
								"requiert": true
							},
							{
								"name": "customer_phone",
								"caption": "Rufnummer",
								"type": "text",
								"requiert": true
							},
							{
								"name": "customer_email",
								"caption": "E-Mailadresse",
								"type": "text",
								"requiert": true
							},
							{
								"name": "customer_legal1",
								"label": "Ich habe die <a href=\"%url_to_gdpr%\" target=\"_blank\">Datenschutzbestimmung</a> zur Kenntnis genommen.",
								"type": "checkbox",
								"requiert": true
							},
							{
								"name": "customer_legal2",
								"label": "Ich stimme der Kontaktaufnahme sowie der Verarbeitung meiner Angaben zu. Ich bin damit einverstanden, dass ich kontaktiert (per E-Mail oder Telefon) und meine Angaben zu diesem Zweck gespeichert werden.",
								"type": "checkbox",
								"requiert": true
							}
						]
					}
				]
			},
			"MFH": {
				"name": "Haus",
				"phases": [
					{
						"id": "2",
						"name": "Angaben zur Immobilie",
						"layout": "grid",
						"description": "Folgende Angaben helfen uns, Ihre Immobilie besser bewerten zu können.",
						"fields": [
							{
								"name": "livingarea",
								"caption": "Wohnfläche",
								"type": "range",
								"icon": "LIVINGAREA.png",
								"requiert": true,
								"value": 60,
								"min": 1,
								"max": 1000,
								"unit": "m²"
							},
							{
								"name": "plot_area",
								"caption": "Grundstücksfläche",
								"type": "range",
								"icon": "BGS.png",
								"requiert": true,
								"value": 100,
								"min": 1,
								"max": 5000,
								"unit": "m²"
							},
							{
								"name": "yearofconstruction",
								"caption": "Baujahr",
								"type": "range",
								"icon": "CONSTRUCTION.png",
								"requiert": true,
								"value": 1990,
								"min": 1850,
								"max": 2021,
								"unit": ""
							}
						]
					},
					{
						"id": "3",
						"layout": "addresses",
						"name": "Lage Ihrer Immobilie",
						"description": "Um den Wert der Immobilie ermitteln zu können, benötigen wir die genaue Lage der Immobilie",
						"fields": [
							{
								"name": "street",
								"caption": "Straße",
								"type": "text",
								"requiert": true
							},
							{
								"name": "house_number",
								"caption": "Hausnummer",
								"type": "text",
								"requiert": true
							},
							{
								"name": "zip",
								"caption": "Postleitzahl",
								"type": "text",
								"requiert": true
							},
							{
								"name": "town",
								"caption": "Ort",
								"type": "text",
								"requiert": true
							}
						]
					},
					{
						"id": "4",
						"layout": "contact",
						"name": "Angaben zu Ihrer Person",
						"description": "Um eine vollständige Wertermittlung Ihrer Immobilie erstellen zu können, benötigen wir folgende Angaben von Ihnen.",
						"fields": [
							{
								"name": "customer_salutation",
								"caption": "Anrede",
								"type": "option",
								"icon": "",
								"requiert": true,
								"option": [
									{
										"caption": "Herr",
										"value": "Herr"
									},
									{
										"caption": "Frau",
										"value": "Frau"
									},
									{
										"caption": "Divers",
										"value": "Divers"
									}
								]
							},
							{
								"name": "customer_name",
								"caption": "Vor- und Zuname",
								"type": "text",
								"requiert": true
							},
							{
								"name": "customer_phone",
								"caption": "Rufnummer",
								"type": "text",
								"requiert": true
							},
							{
								"name": "customer_email",
								"caption": "E-Mailadresse",
								"type": "text",
								"requiert": true
							},
							{
								"name": "customer_legal1",
								"label": "Ich habe die <a href=\"%url_to_gdpr%\" target=\"_blank\">Datenschutzbestimmung</a> zur Kenntnis genommen.",
								"type": "checkbox",
								"requiert": true
							},
							{
								"name": "customer_legal2",
								"label": "Ich stimme der Kontaktaufnahme sowie der Verarbeitung meiner Angaben zu. Ich bin damit einverstanden, dass ich kontaktiert (per E-Mail oder Telefon) und meine Angaben zu diesem Zweck gespeichert werden.",
								"type": "checkbox",
								"requiert": true
							}
						]
					}
				]
			},
			"ETW": {
				"name": "Haus",
				"phases": [
					{
						"id": "2",
						"name": "Angaben zur Immobilie",
						"layout": "grid",
						"description": "Folgende Angaben helfen uns, Ihre Immobilie besser bewerten zu können.",
						"fields": [
							{
								"name": "livingarea",
								"caption": "Wohnfläche",
								"type": "range",
								"icon": "LIVINGAREA.png",
								"requiert": true,
								"value": 60,
								"min": 1,
								"max": 500,
								"unit": "m²"
							},
							{
								"name": "rooms",
								"caption": "Zimmerzahl",
								"type": "range",
								"icon": "ROOMS.png",
								"requiert": true,
								"value": 0,
								"min": 0,
								"max": 25
							},
							{
								"name": "yearofconstruction",
								"caption": "Baujahr",
								"type": "range",
								"icon": "CONSTRUCTION.png",
								"requiert": true,
								"value": 1990,
								"min": 1850,
								"max": 2021,
								"unit": ""
							}
						]
					},
					{
						"id": "3",
						"layout": "addresses",
						"name": "Lage Ihrer Immobilie",
						"description": "Um den Wert der Immobilie ermitteln zu können, benötigen wir die genaue Lage der Immobilie",
						"fields": [
							{
								"name": "street",
								"caption": "Straße",
								"type": "text",
								"requiert": true
							},
							{
								"name": "house_number",
								"caption": "Hausnummer",
								"type": "text",
								"requiert": true
							},
							{
								"name": "zip",
								"caption": "Postleitzahl",
								"type": "text",
								"requiert": true
							},
							{
								"name": "town",
								"caption": "Ort",
								"type": "text",
								"requiert": true
							}
						]
					},
					{
						"id": "4",
						"layout": "contact",
						"name": "Angaben zu Ihrer Person",
						"description": "Um eine vollständige Wertermittlung Ihrer Immobilie erstellen zu können, benötigen wir folgende Angaben von Ihnen.",
						"fields": [
							{
								"name": "customer_salutation",
								"caption": "Anrede",
								"type": "option",
								"icon": "",
								"requiert": true,
								"option": [
									{
										"caption": "Herr",
										"value": "Herr"
									},
									{
										"caption": "Frau",
										"value": "Frau"
									},
									{
										"caption": "Divers",
										"value": "Divers"
									}
								]
							},
							{
								"name": "customer_name",
								"caption": "Vor- und Zuname",
								"type": "text",
								"requiert": true
							},
							{
								"name": "customer_phone",
								"caption": "Rufnummer",
								"type": "text",
								"requiert": true
							},
							{
								"name": "customer_email",
								"caption": "E-Mailadresse",
								"type": "text",
								"requiert": true
							},
							{
								"name": "customer_legal1",
								"label": "Ich habe die <a href=\"%url_to_gdpr%\" target=\"_blank\">Datenschutzbestimmung</a> zur Kenntnis genommen.",
								"type": "checkbox",
								"requiert": true
							},
							{
								"name": "customer_legal2",
								"label": "Ich stimme der Kontaktaufnahme sowie der Verarbeitung meiner Angaben zu. Ich bin damit einverstanden, dass ich kontaktiert (per E-Mail oder Telefon) und meine Angaben zu diesem Zweck gespeichert werden.",
								"type": "checkbox",
								"requiert": true
							}
						]
					}
				]
			},
			"GRD": {
				"name": "Grundstück",
				"phases": [
					{
						"id": "2",
						"name": "Angaben zum Grundstück",
						"layout": "grid",
						"description": "Folgende Angaben helfen uns, Ihre Immobilie besser bewerten zu können.",
						"fields": [
							{
								"name": "plot_area",
								"caption": "Grundstücksfläche",
								"type": "range",
								"icon": "BGS.png",
								"requiert": true,
								"value": 100,
								"min": 1,
								"max": 5000,
								"unit": "m²"
							}
						]
					},
					{
						"id": "3",
						"layout": "addresses",
						"name": "Lage Ihres Grundstücks",
						"description": "Um den Wert des Grundstücks ermitteln zu können, benötigen wir die genaue Lage.",
						"fields": [
							{
								"name": "street",
								"caption": "Straße",
								"type": "text",
								"requiert": true
							},
							{
								"name": "house_number",
								"caption": "Hausnummer",
								"type": "text",
								"requiert": true
							},
							{
								"name": "zip",
								"caption": "Postleitzahl",
								"type": "text",
								"requiert": true
							},
							{
								"name": "town",
								"caption": "Ort",
								"type": "text",
								"requiert": true
							}
						]
					},
					{
						"id": "4",
						"layout": "contact",
						"name": "Angaben zu Ihrer Person",
						"description": "Um eine vollständige Wertermittlung Ihrer Immobilie erstellen zu können, benötigen wir folgende Angaben von Ihnen.",
						"fields": [
							{
								"name": "customer_salutation",
								"caption": "Anrede",
								"type": "option",
								"icon": "",
								"requiert": true,
								"option": [
									{
										"caption": "Herr",
										"value": "Herr"
									},
									{
										"caption": "Frau",
										"value": "Frau"
									},
									{
										"caption": "Divers",
										"value": "Divers"
									}
								]
							},
							{
								"name": "customer_name",
								"caption": "Vor- und Zuname",
								"type": "text",
								"requiert": true
							},
							{
								"name": "customer_phone",
								"caption": "Rufnummer",
								"type": "text",
								"requiert": true
							},
							{
								"name": "customer_email",
								"caption": "E-Mailadresse",
								"type": "text",
								"requiert": true
							},
							{
								"name": "customer_legal1",
								"label": "Ich habe die <a href=\"%url_to_gdpr%\" target=\"_blank\">Datenschutzbestimmung</a> zur Kenntnis genommen.",
								"type": "checkbox",
								"requiert": true
							},
							{
								"name": "customer_legal2",
								"label": "Ich stimme der Kontaktaufnahme sowie der Verarbeitung meiner Angaben zu. Ich bin damit einverstanden, dass ich kontaktiert (per E-Mail oder Telefon) und meine Angaben zu diesem Zweck gespeichert werden.",
								"type": "checkbox",
								"requiert": true
							}
						]
					}
				]
			}
		}
	}
	';
	define('FF_VALUATIONMASTER_SALESAUTOMATE_MAPPING', $mapping);







