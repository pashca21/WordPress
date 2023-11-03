<?php 

		
	/*********************
	*   recommendation
	*********************/
	
	
	// recommendation setting
	(!empty(get_option('ff-recommendation-theme')))? define('FF_RECOMMENDATION_THEME', get_option('ff-recommendation-theme') ) : define('FF_RECOMMENDATION_THEME', 'default' ) ;
	(!empty(get_option('ff-recommendation-sa-version')))? define('FF_RECOMMENDATION_SALESAUTOMATE_VIEW', get_option('ff-recommendation-sa-version') ) : define('FF_RECOMMENDATION_SALESAUTOMATE_VIEW', '1.0.0' ) ;
	(!empty(get_option('ff-recommendation-show-stars')))? define('FF_RECOMMENDATION_SHOW_STARS', get_option('ff-recommendation-show-stars') ) : define('FF_RECOMMENDATION_SHOW_STARS', true ) ;
	
	// recommendation search setting
	(!empty(get_option('ff-recommendation-max-result')))? define('FF_RECOMMENDATION_MAX_RESULT', get_option('ff-recommendation-max-result') ) : define('FF_RECOMMENDATION_MAX_RESULT', 9 ) ;
	
	// recommendation salesautomat setting
	$mapping = '{
		"sort":{
			"customer_recommendation": {
				"date": {
					"caption":	"Neuste Bewertungen zuerst",
					"sort":	"desc",
					"selected":	false,
					"field":"_metadata.timestamp"
				}
			}
		},
		"list":	{
			"customer_recommendation":{
				"id": {
					"caption":	"Kennung",
					"type":		"id",
					"unit":		""
				},
				"details": {
					"creatorName": {
						"caption":	"Name",
						"type":		"text",
						"unit":		""
					},
					"creatorText": {
						"caption":	"Mitteilung",
						"type":		"text",
						"unit":		""
					},
					"creatorSalutation": {
						"caption":	"Anrede",
						"type":		"text",
						"unit":		""
					},
					"ratingTotal": {
						"caption":	"Bewertung",
						"type":		"number",
						"unit":		""
					}
				},
				"mainImage":{
					"creatorImageLink": {
						"caption":	"Titlebild",
						"type":		"text",
						"unit":		""
					},
					"creatorGifLink": {
						"caption":	"video",
						"type":		"text",
						"unit":		""
					}
				},
				"video":{
					"creatorVideoLink": {
						"caption":	"video",
						"type":		"text",
						"unit":		""
					}
				}
			}
		}
	}';
	define('FF_RECOMMENDATION_SALESAUTOMATE_MAPPING', $mapping);
	define('FF_RECOMMENDATION_SALESAUTOMATE_PUBLISH_FLAG', 'publish' );
	define('FF_RECOMMENDATION_SALESAUTOMATE_SCHEMA', 'customer_recommendation' );






