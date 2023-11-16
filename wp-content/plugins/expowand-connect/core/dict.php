<?php
class ExpowandDictionary{
    
	public static $purpose_options =
		[ 'RENT_LIVING'     => 'Wohnimmobilie/Wohngrundstück'
		, 'RENT_COMMERCIAL' => 'Gewerbeimmobilie/Gewerbegrundstück'
		, 'BUY_LIVING'      => 'Wohnimmobilie/Wohngrundstück'
		, 'BUY_COMMERCIAL'  => 'Wohnimmobilie/Wohngrundstück'
		, 'BUY_INVESTMENT'  => 'Anlageimmobilie'
		];

	public static $category_options_residential =
		[ 'APARTMENT'             => 'Wohnung'
		, 'HOUSE'                 => 'Haus'
		, 'LIVING_BUSINESS_HOUSE' => 'Wohn- und Geschäftshaus'
		, 'LIVING_SITE'           => 'Wohngrundstück'
		, 'GARAGE'                => 'Garage/Stellplatz'
		, 'APARTMENT_INT'         => 'Wohnung (Ausland)'
		, 'HOUSE_INT'             => 'Haus (Ausland)'
		, 'LIVING_SITE_INT'       => 'Wohngrundstück (Ausland)'
		];

	public static $category_options_commertial =
		[ 'OFFICE'          => 'Büro/Praxis'
		, 'GASTRONOMY'      => 'Gastronomie/Hotel'
		, 'INDUSTRY'        => 'Halle/Produktion'
		, 'STORE'           => 'Einzelhandel'
		, 'SPECIAL_PURPOSE' => 'Spezialgewerbe'
		, 'TRADE_SITE'      => 'Gewerbegrundstück'
		, 'TRADE_SITE_INT'  => 'Gewerbegrundstück (Ausland)'
		];

	const TYPE_FOR_SALE = 0;
	const TYPE_FOR_RENT = 1;

	public static $types_str =
		[ self::TYPE_FOR_SALE => 'Kauf'
		, self::TYPE_FOR_RENT => 'Miete'
		];

	const YN_ARR =
		[ 'YES'            => 'Ja'
		, 'NOT_APPLICABLE' => 'Keine Angabe'
		];

	const YNX_ARR =
		[ 'YES'            => 'Ja'
		, 'NO'             => 'Nein'
		, 'NOT_APPLICABLE' => 'Keine Angabe'
		];

	public static $thirdStepSelection_options_buy =
		[ 'APARTMENT_BUY'       => 'Wohnung'
		, 'HOUSE_BUY'           => 'Haus'
		, 'LIVING_BUY_SITE'     => 'Wohngrundstück'
		, 'GARAGE_BUY'          => 'Garage/Stellplatz'
		, 'APARTMENT_BUY_INT'   => 'Wohnung (Ausland)'
		, 'HOUSE_BUY_INT'       => 'Haus (Ausland)'
		, 'LIVING_BUY_SITE_INT' => 'Wohngrundstück (Ausland)'
		];

	public static $thirdStepSelection_options_rent =
		[ 'APARTMENT_RENT'           => 'Wohnung'
		, 'HOUSE_RENT'               => 'Haus'
		, 'LIVING_RENT_SITE'         => 'Wohngrundstück'
		, 'GARAGE_RENT'              => 'Garage/Stellplatz'
		, 'SHORT_TERM_ACCOMMODATION' => 'Möbliertes Wohnen/Wohnen auf Zeit'
		];

	public static $apartmentType_options =
		[ 'ROOF_STOREY'         => 'Dachgeschoss'
		, 'LOFT'                => 'Loft'
		, 'MAISONETTE'          => 'Maisonette'
		, 'PENTHOUSE'           => 'Penthouse'
		, 'TERRACED_FLAT'       => 'Terrassenwohnung'
		, 'GROUND_FLOOR'        => 'Erdgeschosswohnung'
		, 'APARTMENT'           => 'Etagenwohnung'
		, 'RAISED_GROUND_FLOOR' => 'Hochparterre'
		, 'HALF_BASEMENT'       => 'Souterrain'
		, 'OTHER'               => 'Sonstige'
		, 'NO_INFORMATION'      => 'Keine Angabe'
		];

	public static $constructionPhase_options =
		[ 'NO_INFORMATION'     => 'Keine Angabe'
		, 'PROJECTED'          => 'Haus In Planung'
		, 'UNDER_CONSTRUCTION' => 'Haus Im Bau'
		, 'COMPLETED'          => 'Haus Fertig Gestellt'
		];

	public static $condition_options =
		[ 'NO_INFORMATION'                     => 'Keine Angabe'
		, 'FIRST_TIME_USE'                     => 'Erstbezug'
		, 'FIRST_TIME_USE_AFTER_REFURBISHMENT' => 'Erstbezug nach Sanierung'
		, 'MINT_CONDITION'                     => 'Neuwertig'
		, 'REFURBISHED'                        => 'Saniert'
		, 'MODERNIZED'                         => 'Modernisiert'
		, 'FULLY_RENOVATED'                    => 'Vollständig Renoviert'
		, 'WELL_KEPT'                          => 'Gepflegt'
		, 'NEED_OF_RENOVATION'                 => 'Renovierungsbedürftig'
		, 'NEGOTIABLE'                         => 'nach Vereinbarung'
		, 'RIPE_FOR_DEMOLITION'                => 'Abbruchreif'
		];

	public static $interiorQuality_options = 
		[ 'NO_INFORMATION' => 'Keine Angabe'
		, 'LUXURY'         => 'luxus'
		, 'SOPHISTICATED'  => 'gehoben'
		, 'NORMAL'         => 'normal'
		, 'SIMPLE'         => 'einfach'
		];

	public static $heatingType_options = 
		[ 'NO_INFORMATION'                 => 'Keine Angabe'
		, 'SELF_CONTAINED_CENTRAL_HEATING' => 'Etagenheizung'
		, 'STOVE_HEATING'                  => 'Ofenheizung'
		, 'CENTRAL_HEATING'                => 'Zentralheizung'
		, 'COMBINED_HEAT_AND_POWER_PLANT'  => 'Blockheizkraftwerk'
		, 'ELECTRIC_HEATING'               => 'Elektro-Heizung'
		, 'DISTRICT_HEATING'               => 'Fernwärme'
		, 'FLOOR_HEATING'                  => 'Fußbodenheizung'
		, 'GAS_HEATING'                    => 'Gas-Heizung'
		, 'WOOD_PELLET_HEATING'            => 'Holz-Pelletheizung'
		, 'NIGHT_STORAGE_HEATER'           => 'Nachtspeicherofen'
		, 'OIL_HEATING'                    => 'Öl-Heizung'
		, 'SOLAR_HEATING'                  => 'Solar-Heizung'
		, 'HEAT_PUMP'                      => 'Wärmepumpe'
		];

	public static $buildingEnergyRatingType_options = array(
		'NO_INFORMATION'     => 'Keine Angabe',
		'ENERGY_REQUIRED'    => 'Bedarfsausweis',
		'ENERGY_CONSUMPTION' => 'Verbrauchsausweis'
	);

	public static $energyCertificateAvailability_options = array(
		'NO_INFORMATION'    => 'Keine Angabe',
		'AVAILABLE'         => 'Endenergiebedarf',
		'NOT_AVAILABLE_YET' => 'Endenergieverbrauch',
		'NOT_REQUIRED'      => 'Dieses Gebäude unterliegt nicht den Anforderungen der EnEV'
	);

	public static $energyCertificateCreationDate_options = array(
		'NO_INFORMATION'    => 'Keine Angabe',
		'BEFORE_01_MAY_2014'=> 'bis 30. April 2014',
		'FROM_01_MAY_2014' 	=> 'ab 1. Mai 2014'
	);

	public static $parkingSpaceType_options = array(
		'NO_INFORMATION'     => 'Keine Angabe',
		'NO'     			 => 'nicht vorhanden',
		'GARAGE'             => 'Garage',
		'OUTSIDE'            => 'Außenstellplatz',
		'CARPORT'            => 'Carport',
		'DUPLEX'             => 'Duplex',
		'CAR_PARK'           => 'Parkhaus',
		'UNDERGROUND_GARAGE' => 'Tiefgarage'
	);

	public static $petsAllowed_options = array(
		'NO_INFORMATION' => 'Keine Angabe',
		'YES'            => 'Ja',
		'NO'             => 'Nein',
		'NEGOTIABLE'     => 'Verhandelbar'
	);

	public static $buildingType_options = array(
		'NO_INFORMATION'      => 'Keine Angabe',
		'SINGLE_FAMILY_HOUSE' => 'Einfamilienhaus',
		'MID_TERRACE_HOUSE'   => 'Reihenmittelhaus',
		'END_TERRACE_HOUSE'   => 'Reiheneckhaus',
		'MULTI_FAMILY_HOUSE'  => 'Mehrfamilienhaus',
		'BUNGALOW'            => 'Bungalow',
		'FARMHOUSE'           => 'Bauernhaus',
		'SEMIDETACHED_HOUSE'  => 'Doppelhaushälfte',
		'VILLA'               => 'Villa',
		'CASTLE_MANOR_HOUSE'  => 'Burg/Schloss',
		'SPECIAL_REAL_ESTATE' => 'Besondere Immobilie',
		'OTHER'               => 'Sonstiges'
	);

	public static $energyEfficiencyClass_options =
		[ 'NOT_APPLICABLE' => 'Keine Angabe'
		, 'A+' => 'A+'
		, 'A' => 'A'
		, 'B' => 'B'
		, 'C' => 'C'
		, 'D' => 'D'
		, 'E' => 'E'
		, 'F' => 'F'
		, 'G' => 'G'
		, 'H' => 'H'
		];

	public static $siteDevelopmentType_options = [
		'NO_INFORMATION'		=> 'Keine Angabe',
		'DEVELOPED'				=> 'Erschlossen',
		'DEVELOPED_PARTIALLY'	=> 'Teilerschlossen',
		'NOT_DEVELOPED' 		=> 'Unerschlossen'
	];

	public static $siteConstructibleType_options = [
		'NO_INFORMATION'		=> 'Keine Angabe',
		'CONSTRUCTIONPLAN'		=> 'Bebauung nach Bebauungsplan',
		'NEIGHBOURCONSTRUCTION'	=> 'Nachbarbebauung',
		'EXTERNALAREA'			=> 'Aussengebiet'
	];

	public static $recommendedUseType_options = [
		'NO_INFORMATION' 			=> 'Keine Angabe',
		'FUTURE_DEVELOPMENT_LAND' 	=> 'Bauerwartungsland',
		'TWINHOUSE' 				=> 'Doppelhaus',
		'SINGLE_FAMILY_HOUSE'		=> 'Einfamilienhaus',
		'GARAGE' 					=> 'Garagen',
		'GARDEN' 					=> 'Garten',
		'NO_DEVELOPMENT' 			=> 'Keine Bebauung',
		'APARTMENT_BUILDING' 		=> 'Mehrfamilienhaus',
		'ORCHARD' 					=> 'Obstpflanzung',
		'TERRACE_HOUSE' 			=> 'Reihenhaus',
		'PARKING_SPACE' 			=> 'Stellplätze',
		'VILLA' 					=> 'Villa',
		'FORREST' 					=> 'Wald'
	];

	public static $officeType_options = array(
		'NO_INFORMATION'                 => 'Keine Angabe',
		'LOFT'                           => 'Loft',
		'STUDIO'                         => 'Atelier',
		'OFFICE'                         => 'Büro',
		'OFFICE_FLOOR'                   => 'Büroetage',
		'OFFICE_BUILDING'                => 'Bürohaus',
		'OFFICE_CENTRE'                  => 'Bürozentrum',
		'OFFICE_STORAGE_BUILDING'        => 'Büro-/ Lagergebäude',
		'SURGERY'                        => 'Praxis',
		'SURGERY_FLOOR'                  => 'Praxisetage',
		'SURGERY_BUILDING'               => 'Praxishaus',
		'COMMERCIAL_CENTRE'              => 'Gewerbezentrum',
		'LIVING_AND_COMMERCIAL_BUILDING' => 'Wohn- und Geschäftsgebäude',
		'OFFICE_AND_COMMERCIAL_BUILDING' => 'Büro- und Geschäftsgebäude'
	);

	public static $lanCables_options = array(
		'NO_INFORMATION'	=> 'Keine Angabe',
		'YES'				=> 'Ja',
		'NO'				=> 'Nein',
		'BY_APPOINTMENT'	=> 'nach Vereinbarung'
	);

	public static $airConditioning_options = array(
		'NO_INFORMATION'	=> 'Keine Angabe',
		'YES'				=> 'Ja',
		'NO'				=> 'Nein',
		'BY_APPOINTMENT'	=> 'nach Vereinbarung'
	);

	public static $officeRentDuration_options = array(
		'LONG_TERM'	=> 'länger als 2 Jahre',
		'WEEKLY'	=> 'wochenweise',
		'MONTHLY'	=> 'monatsweise',
		'YEARLY'	=> '1-2 Jahre'
	);

	public static $storeType_options = array(
		'NO_INFORMATION'      	=> 'Keine Angabe',
		'SHOWROOM_SPACE' 		=> 'Ausstellungsfläche',
		'SHOPPING_CENTRE'  		=> 'Einkaufszentrum',
		'FACTORY_OUTLET'   		=> 'Factory Outlet',
		'DEPARTMENT_STORE' 		=> 'Kaufhaus',
		'KIOSK' 				=> 'Kiosk',
		'STORE'           		=> 'Laden',
		'SELF_SERVICE_MARKET'	=> 'SBMarkt',
		'SALES_AREA'    		=> 'Verkaufsfläche',
		'SALES_HALL'  			=> 'Verkaufshalle'
	);

	public static $supplyType_options = array(
		'NO_INFORMATION'      	=> 'Keine Angabe',
		'DIRECT_APPROACH' 		=> 'direkter Zugang',
		'NO_DIRECT_APPROACH'  	=> 'keine direkte Anfahrt',
		'CAR_APPROACH'   		=> 'PKW-Zufahrt',
		'APPROACH_TO_THE_FRONT' => 'von Vorn',
		'APPROACH_TO_THE_BACK' 	=> 'von Hinten',
		'FULL_TIME'           	=> 'Ganztägig',
		'FORENOON'				=> 'Vormittags'
	);

	public static $industryType_options = array(
		'NO_INFORMATION'      			=> 'Keine Angabe',
		'SHOWROOM_SPACE' 				=> 'Ausstellungsfläche',
		'HALL'   						=> 'Halle',
		'HIGH_LACK_STORAGE'   			=> 'Hochregallager',
		'INDUSTRY_HALL'  				=> 'Industriehalle',
		'INDUSTRY_HALL_WITH_OPEN_AREA' 	=> 'Industriehalle mit freifläche',
		'COLD_STORAGE'           		=> 'Kühlhaus',
		'MULTIDECK_CABINET_STORAGE' 	=> 'Kühlregallager',
		'STORAGE_WITH_OPEN_AREA'    	=> 'Lager mit freifläche',
		'STORAGE_AREA'  				=> 'Lagerfläche',
		'STORAGE_HALL' 					=> 'Lagerhalle',
		'SERVICE_AREA'              	=> 'Servicefläche',
		'SHIPPING_STORAGE'          	=> 'Speditionslager',
		'REPAIR_SHOP'               	=> 'Werkstatt'
	);

	public static $locationClassificationType_options = array(
		'NO_INFORMATION'	=> 'Keine Angabe',
		'CLASSIFICATION_A' 	=> 'A-Lage',
		'CLASSIFICATION_B'  => 'B-Lage',
		'SHOPPING_CENTRE'   => 'Einkaufszentrum'
	);
	
	public static $flooringType_options = array(
		'NO_INFORMATION'    => 'Keine Angabe',
		'CONCRETE' 			=> 'Beton',
		'EPOXY_RESIN'   	=> 'Epoxidharz',
		'TILES'   			=> 'Fliesen',
		'PLANKS'  			=> 'Dielen',
		'LAMINATE' 			=> 'Laminat',
		'PARQUET'           => 'Parkett',
		'PVC' 				=> 'PVC',
		'CARPET'    		=> 'Teppichboden',
		'ANTISTATIC_FLOOR'	=> 'Teppichboden Antistatisch',
		'OFFICE_CARPET' 	=> 'Teppichfliesen Stuhlrollenfest',
		'STONE'             => 'Stein',
		'CUSTOMIZABLE'      => 'Nach Wunsch',
		'WITHOUT'           => 'Ohne Bodenbelag'
	);

	public static $firingTypes_options = 
		[ 'NO_INFORMATION'   => 'Keine Angabe'
		, 'GEOTHERMAL'       => 'Erdwärme'
		, 'SOLAR_HEATING'    => 'Solarheizung'
		, 'PELLET_HEATING'   => 'Pelletheizung'
		, 'GAS'              => 'Gas'
		, 'OIL'              => 'Öl'
		, 'DISTRICT_HEATING' => 'Fernwärme'
		, 'ELECTRICITY'      => 'Strom'
		, 'COAL'             => 'Kohle'
		, 'ACID_GAS'		 => 'Erdgas leicht'
		, 'SOUR_GAS' 		 => 'Erdgas schwer'
		, 'LIQUID_GAS' 		 => 'Flüssiggas'
		, 'STEAM_DISTRICT_HEATING' => 'Fernwärme Dampf'
		, 'WOOD' 			 => 'Holz'
		, 'WOOD_CHIPS' 		 => 'Holz-Hackschnitzel'
		, 'COAL_COKE'        => 'Kohle/Koks'
		, 'LOCAL_HEATING' 	 => 'Nahwärme'
		, 'HEAT_SUPPLY'		 => 'Wärmelieferung'
		];

}
