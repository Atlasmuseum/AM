<?php

/**
 * @file
 * @ingroup API
 */

if ( !defined( 'API_EXCHANGE' ) ) {
  die( "This file is part of the app exchange API. It is not a valid entry point.\n" );
}

### path to the API as seen on the local filesystem.
$app_g_IP = dirname( __FILE__ ) . '/';

### URL to Atlas Museum
$app_g_BASE_URL = 'http://publicartmuseum.net/w/webservice';

### API php script name
$app_g_API_SCRIPT = 'api.php';

### URL to API php script
$app_g_API_URL = $app_g_BASE_URL.'/'.$app_g_API_SCRIPT;

### URL to images directory
$app_g_IMAGES_URL = 'http://publicartmuseum.net/w/images';

###
# id and pass for api access
# must be provided for every transaction
###
$app_g_WEBSERVICE_ID = 'appli';
$app_g_WEBSERVICE_PASS = 'test123';

### Whether an action needs webservice id control
$app_g_WEBSERVICE_REQUIRED = array(
	'createnotice' => true,
	'extractxmlwiki' => true,
	'extractcredits' => true,
	'getnoticedata' => true,
	'noticefield' => true,
	'setnoticedata' => true,
	'validuser' => true,
);

### Whether an action needs login
$app_g_LOGIN_REQUIRED = array(
	'createnotice' => true,
	'extractcredits' => true,
	'extractxmlwiki' => false,
	'getnoticedata' => true,
	'noticefield' => false,
	'setnoticedata' => true,
	'validuser' => true,
);

$app_g_COOKIEPREFIX = '';
$app_g_SESSIONID = '';


### Field types
$app_g_FIELDTYPE = array(
	'artiste' => 'texte',
	'titre' => 'texte',
	'sous_titre' => 'texte',
	'description' => 'texte',
	'institution' => 'texte',
	'commissaires' => 'texte',
	'commanditaires' => 'texte',
	'programme' => 'texte',
	'partenaires_prives' => 'texte',
	'partenaires_publics' => 'texte',
	'collaborateurs' => 'texte',
	'maitrise_œuvre' => 'texte',
	'maitrise_oeuvre_deleguee' => 'texte',
	'maîtrise_ouvrage' => 'texte',
	'maitrise_ouvrage_deleguee' => 'texte',
	'proprietaire' => 'texte',
	'numero_inventaire' => 'texte',
	'inauguration' => 'date',
	'precision_date' => 'texte',
	'restauration' => 'texte',
	'conservation' => 'texte',
	'precision_etat_conservation' => 'texte',
	'autre_precision_etat_conservation' => 'texte',
	'contexte_production' => 'texte',
	'fin' => 'texte',
	'nature' => 'liste',
	'type_art' => 'liste',
	'precision_type_art' => 'texte',
	'periode_art' => 'liste',
	'mouvement_artistes' => 'liste',
	'precision_mouvement_artistes' => 'texte',
	'influences' => 'texte',
	'a_influence' => 'texte',
	'couleur' => 'liste',
	'precision_couleur' => 'texte',
	'materiaux' => 'liste',
	'precision_materiaux' => 'texte',
	'techniques' => 'texte',
	'forme' => 'texte',
	'symbole' => 'texte',
	'mot_cle' => 'texte',
	'hauteur' => 'texte',
	'longueur' => 'texte',
	'largeur' => 'texte',
	'diametre' => 'texte',
	'surface' => 'texte',
	'precision_dimensions' => 'texte',
	'source' => 'texte',
	'image_principale' => 'texte',
	'Has ImageGalerieConstruction' => 'texte',
	'Has ImageGalerieAutre' => 'texte',
	'Site nom' => 'texte',
	'Site lieu_dit' => 'texte',
	'Site adresse' => 'texte',
	'Site code_postal' => 'texte',
	'Site details' => 'texte',
	'Site ville' => 'texte',
	'Site region' => 'texte',
	'Site departement' => 'texte',
	'Site pays' => 'texte',
	'Site coordonnees' => 'coordonnées',
	'Site acces' => 'texte',
	'Site visibilite' => 'texte',
	'Site pmr' => 'liste',
	'Site urls' => 'texte',
	'Site pois' => 'texte'
);
