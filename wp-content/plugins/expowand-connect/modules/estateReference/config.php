<?php 

		
	/*********************
	*   estatereference
	*********************/
	
	
	// estatereference setting
	(!empty(get_option('ff-estatereference-theme')))? define('FF_estatereference_THEME', get_option('ff-estatereference-theme') ) : define('FF_estatereference_THEME', 'default' ) ;
	(!empty(get_option('ff-estatereference-sa-version')))? define('FF_ESTATEREFERENCE_SALESAUTOMATE_VIEW', get_option('ff-estatereference-sa-version') ) : define('FF_ESTATEREFERENCE_SALESAUTOMATE_VIEW', '1.0.0' ) ;
	
	// estatereference search setting
	(!empty(get_option('ff-estatereference-max-result')))? define('FF_ESTATEREFERENCE_MAX_RESULT', get_option('ff-estatereference-max-result') ) : define('FF_ESTATEREFERENCE_MAX_RESULT', 9 ) ;
	
	// estatereference salesautomat setting
	$mapping = '{
		"sort":{
			"default": {
				"date": {
					"caption":	"Aktuellste zuerst",
					"selected":	false,
					"fields":[
						{
							"sort":"DESC",
							"field":"_metadata.lastModifiedTimestamp"
						}
					]	
				}
			}
		},
		"list":	{
			"default":{
				"id": {
					"caption":	"Kennung",
					"type":		"id",
					"unit":		""
				},
				"mainImage":{
					"mainImage": {
						"caption":	"Titlebild",
						"type":		"image",
						"unit":		""
					}
				},
				"price": {
					"immoprice": {
						"caption":	"Kaufpreis",
						"type":		"number",
						"unit":		"€"
					},
					"baseRent": {
						"caption":	"Miete",
						"type":		"number",
						"unit":		"€"
					}
				},
				"tradetype":{
					"tradeType": {
						"caption":	"Vermarktungsart",
						"type":		"option",
						"unit":		"",
						"option":	{
							"0": "Kauf",
							"1": "Miete",
							"2": "Kauf"
						}	
					}
				},
				"details":{
					"name": {
						"caption":	"Kennung",
						"type":		"text",
						"unit":		""
					},
					"livingSpace": {
						"caption":	"Wohnfläche",
						"type":		"number_formatted",
						"unit":		"m²"
					},
					"gastroarea": {
						"caption":	"Gastronomische Fläche",
						"type":		"area",
						"unit":		"m²"
					},
					"totalFloorSpace": {
						"caption":	"Gesamtfläche",
						"type":		"area",
						"unit":		"m²"
					},
					"numbersOfRooms": {
						"caption":	"Zimmer",
						"type":		"number",
						"unit":		""
					},
					"plotArea": {
						"caption":	"Grundstück",
						"type":		"number_formatted",
						"unit":		"m²"
					},
					"availableat": {
						"caption":	"Verfügbar ab",
						"type":		"date",
						"unit":		""
					}
				},
				"estatetype":{
					"estatetype": {
						"caption":	"Immobilienart",
						"type":		"option",
						"unit":		"",
						"option":	{
							"15EM":"Ein/Mehrfamilienhaus (ZV)",
							"16EM":"EinfamilienhausMitEinliegerwohnung (Typ)",
							"15GE":"Gewerbe/Anlage (ZV)",
							"16HO":"Holzhaus (Typ)",
							"14LO":"Loft (WG)",
							"14TE":"Terrassenwohnung (WG)",
							"16":"Typenhäuser (Typ)",
							"02REND":"Reihenendhaus",
							"02ZSTAD":"Stadthaus",
							"02ZREST":"Resthof",
							"02ZLAND":"Landhaus",
							"02ZBERG":"Berghütte",
							"02ZCHAL":"Chalet",
							"02ZSTRA":"Strandhaus",
							"02ZLAUB":"Laube/Datsche/Gartenhaus",
							"02ZAPAH":"Apartmenthaus",
							"02ZHERR":"Herrenhaus",
							"02ZFINC":"Finca",
							"02ZRUST":"Rustico",
							"02ZFERT":"Fertighaus",
							"03INDU":"Baugrund für Industrie",
							"03GEMI":"Gemischte Nutzung",
							"03GEWE":"Gewerbepark",
							"03SOND":"Sondernutzung",
							"03SEEL":"Seeliegenschaft",
							"05EINHA":"Einzelhandelsladen",
							"05VERBR":"Verbrauchermarkt",
							"01ZAPART":"Apartment",
							"01ZFERIE":"Ferienwohnung",
							"01ZGALER":"Galerie",
							"08GASTW":"Gastronomie mit Wohnung",
							"08BEHERB":"Weitere Beherbergungsbetriebe",
							"08RAUCH":"Raucherlokal",
							"08EINRA":"Einraumlokal",
							"07PROD":"Produktion",
							"07FREIF":"Freiflächen",
							"09AUSS":"Aussiedlerhof",
							"09GART":"Gartenbau",
							"09ACKE":"Ackerbau",
							"09VIEH":"Viehwirtschaft",
							"09JAGD":"Jagd- u. Forstwirtschaft",
							"09TEICH":"Teich- u. Fischwirtschaft",
							"09SCHEU":"Scheunen",
							"09SONST":"Sonstige Landwirtschaftsimmobilien",
							"09JAGDR":"Jagdrevier",
							"11DOPP":"Doppelgarage",
							"11BOOT":"Bootsliegeplatz",
							"11EINZE":"Einzelgarage",
							"11STROM":"Parkplatz-Strom",
							"04VERB":"Verbrauchermärkte (Invest)",
							"04PFLEG":"Pflegeheim (Invest)",
							"04SANAT":"Sanatorium (Invest)",
							"04SENIO":"Seniorenheim (Invest)",
							"04CBETRE":"Betreutes Wohnen (Invest)",
							"20":"Freizeitimmobilien gewerblich",
							"20SPORT":"Sportanlagen",
							"20VERGN":"Vergnügungspark/-Center",
							"21":"Sonstige Typen",
							"21PARKH":"Parkhaus",
							"21TANKS":"Tankstelle",
							"21KRANK":"Krankenhaus",
							"21SONST":"Sonstige",
							"15GR":"Grundstück (ZV)",
							"02SON":"sonstige Häuser",
							"01LOFT":"Loftwohnung",
							"02MFH":"Mehrfamilienhaus",
							"08HOTG":"Hotel garni",
							"01ETAG":"Etagenwohnung",
							"02DZH":"Zweifamilienhaus",
							"07LKÜ":"Kühlhaus",
							"03BE":"Baugrund für Ein- / Zweifamilienhäuser",
							"07LKÜR":"Kühlregallager",
							"08GAHS":"Gästehaus",
							"07LFR":"Lager mit Freifläche",
							"07L":"Lagerfläche",
							"01ZO":"sonstiger Wohnungstyp",
							"08":"Gastronomie / Beherbergungen",
							"06A1":"Loft",
							"06":"Büro / Praxis / Ausstellungsräume",
							"04W03":"Mehrfamilienhaus (Invest.)",
							"07LH":"Lagerhalle",
							"04":"Anlage-/Investmentobjekte",
							"07SF":"Servicefläche",
							"07LSP":"Speditionslager",
							"07W":"Werkstatt",
							"02ZB":"besondere Immobilie",
							"09R":"Reiterhof",
							"01MAIS":"Maisonettewohnung",
							"03GL":"Grundstück Land und Forstwirtschaft",
							"03":"Grundstücke",
							"10GE":"Gewerbeeinheit",
							"10S":"Spezialobjekt",
							"02LBH":"Bauernhaus",
							"03GG":"Baugrund für Gewerbe",
							"08HOT":"Hotel",
							"06A":"Atelier",
							"09":"Land- / Forstwirtschaft",
							"02":"Häuser",
							"04W04":"Wohnanlage (Invest.)",
							"07":"Prod./ Lager / Gewerbehallen",
							"08GAE":"Gaststätte",
							"04W02":"Einfamilienhaus (Invest.)",
							"01PENT":"Penthouse",
							"02EFH":"Einfamilienhaus",
							"04W01":"Eigentumswohnung (Invest.)",
							"02REH":"Reihenhaus",
							"04EZ":"Einkaufszentrum (Invest.)",
							"03GF":"Baugrund für Freizeit",
							"02VIL":"Villa",
							"04GA":"Gaststätte (Invest.)",
							"06B":"Büro",
							"04K":"Freizeitanlage (Invest.)",
							"08REST":"Restaurant",
							"01":"Wohnungen",
							"08PENS":"Pension",
							"01DACH":"Dachgeschosswohnung",
							"04W05":"Wohn-/Geschäftshaus (Invest.)",
							"05":"Läden/SB-Märkte",
							"02DHH":"Doppelhaushälfte",
							"06BE":"Büroetage",
							"10":"Sonstige Immobilie",
							"07H":"Halle",
							"01TERR":"Terrassenwohnung",
							"01GERD":"Erdgeschosswohnung",
							"06PH":"Praxishaus",
							"06G":"Gewerbezentrum",
							"05F":"Verkaufshalle",
							"08B":"Bar",
							"07LHO":"Hochregallager",
							"07HI":"Industriehalle",
							"07HIF":"Industriehalle mit Freifläche",
							"10F":"Freizeitanlage",
							"10G":"Gewerbefläche",
							"06BH":"Bürohaus",
							"06BG":"Bürozentrum",
							"06BL":"Büro & Lagergebäude",
							"06P":"Praxis",
							"06PE":"Praxisetage",
							"05A":"Ausstellungsfläche",
							"05E1":"Einkaufszentrum",
							"05E2":"Kaufhaus",
							"05K":"Kiosk",
							"05L":"Laden",
							"11C":"Carport",
							"02REHM":"Reihenmittelhaus",
							"10W":"Werkstatt (Produktion)",
							"14DA":"Dachgeschoss (WG)",
							"16FA":"Fachwerkhaus (Typ)",
							"14MA":"Maisonette (WG)",
							"14PE":"Penthouse (WG)",
							"16DO":"Doppelhaus (Typ)",
							"15GA":"Garage/Sonstige (ZV)",
							"14SU":"Souterrain (WG)",
							"01ZROHD":"Rohdachboden (AUT)",
							"01ZATTIK":"Attikawohnung",
							"05E":"SB-Markt",
							"05LV":"Verkaufsfläche",
							"08C":"Cafe",
							"08D":"Diskothek",
							"08HOTA":"Hotelanwesen",
							"09A1":"Anwesen",
							"09A2":"Bauernhof",
							"09W":"Weingut",
							"04GWE":"Gewerbeeinheit (Invest.)",
							"04EG":"Geschäftshaus (Invest.)",
							"04GH":"Halle/Lager (Invest.)",
							"04E":"Laden/Verkaufsfläche (Invest.)",
							"04ES":"Supermarkt (Invest.)",
							"04B":"Bürogebäude (Invest.)",
							"04GWA":"Gewerbeanwesen (Invest.)",
							"04S":"Servicecenter (Invest.)",
							"04Z":"Sonstiges (Invest.)",
							"04GAH":"Hotel (Invest.)",
							"04GWI":"Industrieanwesen (Invest.)",
							"15EI":"Eigentumswohnung (ZV)",
							"16EI":"Einfamilienhaus (Typ)",
							"16LA":"Landhaus (Typ)",
							"14SO":"Sonstige (WG)",
							"16SV":"Stadtvilla (Typ)",
							"14":"WG-Zimmer (WG)",
							"15":"Zwangsversteigerung (ZV)",
							"16ZW":"Zweifamilienhaus (Typ)",
							"12A":"Appartement",
							"08F":"Ferienbungalows",
							"02EFE":"Einfamilienhaus mit Einliegerwohnung",
							"11G":"Garage",
							"11T":"Tiefgaragenstellplatz",
							"10GP":"Gewerbepark",
							"12H":"Haus",
							"12W":"Wohnung (WAZ)",
							"12Z":"Zimmer (WAZ)",
							"12":"Wohnen auf Zeit (WAZ)",
							"16BL":"Blockhaus (Typ)",
							"11S":"Stellplatz",
							"07A":"Ausstellungsfläche (Produktion)",
							"02BNG":"Bungalow",
							"02BURG":"Burg/Schloss",
							"06BUGE":"Büro- u. Geschäftsgebäude",
							"11D":"Duplex",
							"11":"Garage Stellplatz",
							"01HP":"Hochparterre",
							"11P":"Parkhausstellplatz",
							"02REHE":"Reiheneckhaus",
							"01SOUT":"Souterrain",
							"06WOGE":"Wohn- u. Geschäftsgebäude",
							"14ER":"Erdgeschoss (WG)",
							"08Beher":"Beherbergungsbetrieb",
							"03indust":"Baugrundstück für Wohnungen und Industrie",
							"03dup":"Baugrundstück für freistehende und Duplexhäuser",
							"03BM":"Baugrund für Mehrfamilienhäuser",
							"03BWG":"Baugrund für Wohnen und Gewerbe",
							"03GS":"Grundstück für Soziales",
							"04GW":"Industrie- und Gewerbeimmobilien (Invest.)",
							"04HI":"Handelsimmobilien (Invest.)",
							"04HIF":"Fachmarktzentrum (Invest.)",
							"04HIK":"Kaufhaus (Invest.)",
							"04SI":"Sozialimmobilien (Invest.)",
							"04SIB":"Betreutes Wohnen (Invest.)",
							"04SIP":"Pflegeheim (Invest.)",
							"04SIK":"Klinik (Invest.)",
							"04W":"Wohnimmobilien (Invest.)",
							"04ZP":"Parkhaus (Invest.)",
							"09F":"Fischzucht",
							"08PENT":"Boardinghaus",
							"11Z":"Sonstiges",
							"01LOFT":"Loftwohnung",
							"08HOTG":"Hotel garni",
							"01ETAG":"Etagenwohnung",
							"02DZH":"Zweifamilienhaus",
							"07LKÜ":"Kühlhaus",
							"03BE":"Baugrund für Ein- / Zweifamilienhäuser",
							"07LKÜR":"Kühlregallager",
							"08GAHS":"Gästehaus",
							"07LFR":"Lager mit Freifläche",
							"07L":"Lagerfläche",
							"01ZO":"Sonstiger Wohnungstyp",
							"08":"Hotellerie- und Gastronomieflächen",
							"06A1":"Loft",
							"06":"Gewerbeflächen",
							"04W03":"Mehrfamilienhaus (Invest.)",
							"07LH":"Lagerhalle",
							"04":"Anlage-/Investmentobjekte",
							"07SF":"Servicefläche",
							"07LSP":"Speditionslager",
							"07W":"Werkstattsfläche",
							"02ZB":"Besondere Immobilie",
							"09R":"Reiterhof",
							"01MAIS":"Maisonettewohnung",
							"03GL":"Grundstück Land und Forstwirtschaft",
							"03":"Grundstücke",
							"10GE":"Gewerbeeinheit",
							"10S":"Spezialobjekt",
							"02LBH":"Bauernhaus",
							"03GG":"Baugrund für Gewerbe",
							"08HOT":"Hotel",
							"06A":"Atelier",
							"09":"Land- / Forstwirtschaft",
							"02":"Häuser",
							"04W04":"Wohnanlage (Invest.)",
							"07":"Produktions- und Lagerflächen",
							"08GAE":"Gaststätte",
							"04W02":"Einfamilienhaus (Invest.)",
							"01PENT":"Penthouse",
							"02EFH":"Einfamilienhaus",
							"04W01":"Eigentumswohnung (Invest.)",
							"02REH":"Reihenhaus",
							"04HIE":"Einkaufszentrum (Invest.)",
							"03GF":"Baugrund für Freizeit",
							"02MFH":"Mehrfamilienhaus",
							"02EFE":"Einfamilienhaus mit Einliegerwohnung",
							"11G":"Garage",
							"11T":"Tiefgaragenstellplatz",
							"11S":"Stellplatz",
							"12A":"Apartment",
							"08F":"Ferienimmobilie",
							"10GP":"Gewerbepark",
							"12H":"Haus",
							"12W":"Wohnung",
							"12Z":"Zimmer",
							"07A":"Ausstellungsfläche (Produktion)",
							"02BNG":"Bungalow",
							"06BUGE":"Büro- und Geschäftsgebäude",
							"11C":"Carport",
							"11D":"Duplex",
							"11":"Garage Stellplatz",
							"01HP":"Hochparterre",
							"11P":"Parkhausstellplatz",
							"02REHM":"Reihenmittelhaus",
							"01SOUT":"Souterrain",
							"06WOGE":"Wohn- und Geschäftsgebäude",
							"02REHE":"Reiheneckhaus",
							"10W":"Werkstatt (Produktion)",
							"02VIL":"Villa",
							"04GA":"Gaststätte / Gasthaus (Invest.)",
							"06B":"Bürofläche",
							"04ZF":"Freizeitimmobilie (Invest.)",
							"08REST":"Restaurant",
							"01":"Wohnungen",
							"08PENS":"Pension",
							"01DACH":"Dachgeschosswohnung",
							"04W05":"Wohn-/Geschäftshaus (Invest.)",
							"05":"Einzelhandelsflächen",
							"02DHH":"Doppelhaushälfte",
							"06BE":"Büroetage",
							"10":"Sonstige Objekte",
							"07H":"Halle",
							"01TERR":"Terrassenwohnung",
							"01GERD":"Erdgeschosswohnung",
							"06PH":"Praxishaus",
							"06G":"Gewerbezentrum",
							"05F":"Verkaufshalle",
							"08B":"Bar",
							"07LHO":"Hochregallager",
							"07HI":"Produktionsfläche",
							"07HIF":"Industriehalle mit Freifläche",
							"10F":"Freizeitanlage",
							"10G":"Gewerbefläche",
							"06BH":"Bürohaus",
							"06BG":"Bürozentrum",
							"06BL":"Büro und Lagergebäude",
							"06P":"Praxis",
							"06PE":"Praxisfläche",
							"05A":"Ausstellungsfläche",
							"05E1":"Einkaufszentrum",
							"05F1":"Factory Outlet",
							"05E2":"Kaufhaus",
							"05K":"Kiosk",
							"05L":"Laden",
							"05E":"SB-Markt",
							"05LV":"Verkaufsfläche",
							"08C":"Cafe",
							"08D":"Diskothek",
							"08HOTA":"Hotelanwesen",
							"09A1":"Anwesen",
							"09A2":"Bauernhof",
							"09W":"Weingut",
							"04GWE":"Gewerbeeinheit (Invest.)",
							"04GWG":"Geschäftshaus, Handel, Büro (Invest.)",
							"04GWH":"Halle/Lager (Invest.)",
							"04HIL":"Laden/Verkaufsfläche (Invest.)",
							"04HIS":"Supermarkt (Invest.)",
							"04GWB":"Bürogebäude (Invest.)",
							"04GWA":"Gewerbeanwesen (Invest.)",
							"04GWS":"Servicecenter (Invest.)",
							"04Z":"Sonstiges (Invest.)",
							"04GAH":"Hotel (Invest.)",
							"04GWI":"Industrieanwesen (Invest.)",
							"02BURG":"Burg/Schloss",
							"02SON":"Sonstige Häuser",
							"12":"Wohnen auf Zeit"
						}
					}
				},
				"addresses": {
					"addresses": {
						"caption":	"Lage",
						"type":		"addresses",
						"unit":		""
					}
				},
				"geo":{
					"longitude":{
						"caption":	"longitude",
						"type":		"text",
						"unit":		""
					},
					"latitude":{
						"caption":	"latitude",
						"type":		"text",
						"unit":		""
					}
				}
			}
		}
	}';
	define('FF_ESTATEREFERENCE_SALESAUTOMATE_MAPPING', $mapping);
	define('FF_ESTATEREFERENCE_SALESAUTOMATE_PUBLISH_FLAG', 'showAsReference' );






