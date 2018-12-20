<?php

/*
Plugin Name: Annuaire de Membres Géolocalisés
Plugin URI: http://www.webmaster-independant.com
Description: Un plugin d'affichage de Membres Géolocalisés
Version: 1.0
Author: Cyril Fiorio
Author URI: http://www.webmaster-independant.com
License: GPL2
Text Domain: wp_annuaire_geoloc
*/


//******************************************************************
// ACTIVATION DES L'INSTALLATION
// Création des tables à l'installation de l'extension
// Création du nouveau rôle "fournisseur"
// Création de la nouvelle capacité "edit_fiche_fournisseur"


function annuaire_geoloc_create_db() {

  global $wpdb;

  $charset_collate = $wpdb->get_charset_collate();
  $table_name = $wpdb->prefix . 'annuaire_geoloc';

  $sql = "CREATE TABLE $table_name (
    id smallint(5) NOT NULL AUTO_INCREMENT,
    date_enregistrement datetime NOT NULL,
    membre_nom varchar(50) DEFAULT '' NOT NULL,
    membre_prenom varchar(50) DEFAULT '' NOT NULL,
    membre_adresse varchar(70) DEFAULT '' NOT NULL,
    membre_cp varchar(15) DEFAULT '' NOT NULL,
    membre_ville varchar(50) DEFAULT '' NOT NULL,
    membre_pays varchar(70) DEFAULT '' NOT NULL,
    membre_tel varchar(20) DEFAULT '' NOT NULL,
    membre_specialite varchar(50) DEFAULT '' NOT NULL,
    membre_localisation varchar(50) DEFAULT '' NOT NULL,
    membre_photo varchar(100),
    PRIMARY KEY  (id)
  ) $charset_collate;";

  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  dbDelta( $sql );

}

register_activation_hook( __FILE__, 'annuaire_geoloc_create_db', 1 );

// *******************************************************************

function annuaire_geoloc_desinstallation() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'annuaire_geoloc';
    $sql = "DROP TABLE IF EXISTS $table_name";
    $wpdb->query($sql);
}

register_uninstall_hook(__FILE__, 'annuaire_geoloc_desinstallation');

// *******************************************************************

function annuaire_geoloc_deactivate() {

}

register_deactivation_hook( __FILE__, 'annuaire_geoloc_deactivate' );

// *******************************************************************

function wp_fiche_abonne_geoloc_page(){
  add_menu_page( 'Administration de l\'annuaire de membres', 'Annuaire Médecins Géolocalisés', 'activate_plugins', 'annuaire_geolocalise', 'function_fiche_abonne_geoloc','',65 );
  add_submenu_page( NULL,'Actions sur la liste de l\'annuaire', 'Action annuaire geoloc', 'activate_plugins', 'action_annuaire_geolocalise', 'function_action_abonne_geoloc');
}

add_action('admin_menu','wp_fiche_abonne_geoloc_page' );



function function_fiche_abonne_geoloc() {

  if ( $role[0] == "administrator" )  {
    wp_die( __('Vous n\'avez pas les permissions suffisantes pour accéder à cette page','wp_catalogue_fournisseur') ); //You do not have sufficient permissions to access this page
  }

  require_once("wp_fiche_annuaire_geoloc.php");
}



function function_action_abonne_geoloc() {

  if ( $role[0] == "administrator" )  {
    wp_die( __('Vous n\'avez pas les permissions suffisantes pour accéder à cette page','wp_catalogue_fournisseur') );
  }

  require_once("wp_mod_fiche_annuaire_geoloc.php");
}

 
// Fonction rajoutée afin de rendre le plugin traduisible
/*
function wp_catalogue_fournisseur_load_text_domain() {
  load_plugin_textdomain( 'wp_catalogue_fournisseur', false, dirname(plugin_basename( __FILE__  ))."/languages/" );
}

add_action( 'plugins_loaded', 'wp_catalogue_fournisseur_load_text_domain' );
*/

?>