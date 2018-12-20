<?php

//************************* DECLARATION HEADER ************************

$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );

Global $wpdb;


//************************* DECLARATION FUNCTIONS ***********************

function affiche_pays_fiche($code_pays_fiche) {
  global $wpdb;

  $results = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."cat_pays WHERE alpha2 LIKE '".$code_pays_fiche."'", OBJECT );

  if (get_bloginfo("language") == 'fr-FR') {

    return $results->nom_fr_fr;

      } else {

    return $results->nom_en_gb;

  }
}


//************************* DECLARATION DE LA CHAINE OBJECT AJAX SERVANT A L'AFFICHAGE DU TABLEAU ***********************

$dataset = "{
  \"data\": [";

$results = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."cat_fournisseur ORDER BY entreprise_nom", OBJECT );
  foreach ( $results as $result ) {
    $nom = trim(stripslashes($result->contact_nom));
    $tel = trim(stripslashes($result->contact_tel));
    $gsm = trim(stripslashes($result->contact_gsm));

    $chaine = ""; $chaineSpe = "";

    $spes = explode(";",$result->specialite);
    foreach ( $spes as $value ) {

      switch($value) {
        case "0,0" : $chaine .= __('Autres','wp_catalogue_fournisseur')." | "; break;

        case "1,0" : $chaine .= __('Environnement, énergie, traitement des eaux','wp_catalogue_fournisseur')." | "; break;
        case "1,1" : $chaine .= __('Travaux maritimes et portuaires','wp_catalogue_fournisseur')." | "; break;
        case "1,2" : $chaine .= __('Géotechnique, VRD','wp_catalogue_fournisseur')." | "; break;
        case "1,3" : $chaine .= __('Modélisations et simulations','wp_catalogue_fournisseur')." | "; break;
        case "1,4" : $chaine .= __('Aménagement de zones foncières, études économiques et spécialisées','wp_catalogue_fournisseur')." | "; break;
        case "1,5" : $chaine .= __('Etudes ICPE, Assistance à Maitrise d\'Ouvrage','wp_catalogue_fournisseur')." | "; break;
        case "1,6" : $chaine .= __('Contrôles, épreuves, normalisation','wp_catalogue_fournisseur')." | "; break;
        case "1,7" : $chaine .= __('Construction de bâtiments, hangars, stockages, architectes','wp_catalogue_fournisseur')." | "; break;

        case "2,0" : $chaine .= __('Transporteur routier','wp_catalogue_fournisseur')." | "; break;
        case "2,1" : $chaine .= __('Transporteur ferroviaire','wp_catalogue_fournisseur')." | "; break;
        case "2,2" : $chaine .= __('Transporteur aérien','wp_catalogue_fournisseur')." | "; break;
        case "2,3" : $chaine .= __('Transporteur maritime','wp_catalogue_fournisseur')." | "; break;
        case "2,4" : $chaine .= __('Transport fluvial','wp_catalogue_fournisseur')." | "; break;
        case "2,5" : $chaine .= __('Intermodalité','wp_catalogue_fournisseur')." | "; break;
        case "2,6" : $chaine .= __('Transitaires, prestataires logistiques','wp_catalogue_fournisseur')." | "; break;
        case "2,7" : $chaine .= __('Zones logistiques (opérateurs publics et privés)','wp_catalogue_fournisseur')." | "; break;
        case "2,8" : $chaine .= __('Port maritime ou fluvial','wp_catalogue_fournisseur')." | "; break;
        case "2,9" : $chaine .= __('Expertise maritime et marchandises','wp_catalogue_fournisseur')." | "; break;
        case "2,10" : $chaine .= __('Assurances et professions juridiques','wp_catalogue_fournisseur')." | "; break;
        case "2,11" : $chaine .= __('Surveillance et sécurité','wp_catalogue_fournisseur')." | "; break;

        case "3,0" : $chaine .=  __('Equipements portuaires et de manutention, chariots élévateurs, grues, outillage, accastillage, élingues, chaines, palonniers et assimilés','wp_catalogue_fournisseur')." | "; break;
        case "3,1" : $chaine .= __('Pompes, filtres, tuyauterie (activités liquides)','wp_catalogue_fournisseur')." | "; break;
        case "3,2" : $chaine .= __('Location et vente de véhicules, remorques, wagons, conteneurs','wp_catalogue_fournisseur')." | "; break;
        case "3,3" : $chaine .= __('Systèmes de pesage','wp_catalogue_fournisseur')." | "; break;
        case "3,4" : $chaine .= __('Constructeurs et réparateurs spécialisés','wp_catalogue_fournisseur')." | "; break;
        case "3,5" : $chaine .= __('Emballage, protection et traitement des marchandises','wp_catalogue_fournisseur')." | "; break;
        case "3,6" : $chaine .= __('Ecoles et formation','wp_catalogue_fournisseur')." | "; break;
        case "3,7" : $chaine .= __('Communication, relations médias, photographes','wp_catalogue_fournisseur')." | "; break;
        case "3,8" : $chaine .= __('Electricité industrielle, plomberie, peinture, travaux','wp_catalogue_fournisseur')." | "; break;
        case "3,9" : $chaine .= __('Eléments en béton et assimilés','wp_catalogue_fournisseur')." | "; break;
        case "3,10" : $chaine .= __('Equipements de Lutte contre l’incendie','wp_catalogue_fournisseur')." | "; break;

        case "4,0" : $chaineSpe .= __('Vracs liquides','wp_catalogue_fournisseur')." | "; break;
        case "4,1" : $chaineSpe .= __('Vracs secs','wp_catalogue_fournisseur')." | "; break;
        case "4,2" : $chaineSpe .= __('Conventionnel et roro','wp_catalogue_fournisseur')." | "; break;
        case "4,3" : $chaineSpe .= __('Conteneurs','wp_catalogue_fournisseur')." | "; break;
        case "4,4" : $chaineSpe .= __('Marchandises dangereuses','wp_catalogue_fournisseur')." | "; break;

      }

    }

    $dataset .= '
    {
      "entreprise": "'.$result->entreprise_nom.'",
      "activite": "'.substr($chaine, 0, -3).'",
      "specialite": "'.substr($chaineSpe, 0, -3).'",
      "adresse": "'.$result->entreprise_adr.'",
      "adresse2": "'.$result->entreprise_adr2.'",
      "ville": "'.$result->entreprise_ville.'",
      "pays": "'.affiche_pays_fiche($result->entreprise_pays).'",
      "description": "'.$result->entreprise_description.'",
      "web": "'.$result->entreprise_web.'",
      "contact": "'.$result->contact_nom.'",
      "titre": "'.$result->contact_titre.'",
      "tel": "'.$result->contact_tel.'",
      "gsm": "'.$result->contact_gsm.'",
      "email": "'.$result->contact_email.'"
    },
';

  }

$dataset = substr($dataset, 0, -2);
$dataset .= "  ]
}";

echo $dataset;

/*
print '



{
  "data": [
    {
      "id": "1",
      "entreprise": "Entreprise 1",
      "activite": "'.substr($chaine, 0, -3).'",
      "specialite": "'.substr($chaineSpe, 0, -3).'",
      "contact": "Mr Doe",
      "tel": "046856589",
      "pays": "France",
      "adresse": "18 rue garancière",
      "description": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur suscipit nulla vitae erat vulputate facilisis. Nullam tincidunt pulvinar quam non placerat. Curabitur rutrum ligula ut vestibulum varius. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis cras amet.",
      "email": "contact@webmaster-independant.com"
    },
    {
      "id": "1",
      "entreprise": "Entreprise 1",
      "activite": "Travaux maritimes et portuaires | Contrôles, épreuves, normalisation | Construction de bâtiments, hangars, stockages, architectes",
      "specialite": "",
      "contact": "Mr Doe",
      "tel": "'.$gsm.'",
      "pays": "France",
      "adresse": "18 rue garancière",
      "description": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur suscipit nulla vitae erat vulputate facilisis. Nullam tincidunt pulvinar quam non placerat. Curabitur rutrum ligula ut vestibulum varius. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis cras amet.",
      "email": "contact@webmaster-independant.com"
    }
  ]
}



';*/

?>