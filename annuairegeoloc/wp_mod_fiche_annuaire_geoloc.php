<?php
//liste_fournisseur

global $wpdb;
$current_user = wp_get_current_user();
$role = $current_user->roles;

//echo get_bloginfo("language");
//fr-FR  en-GB

require_once("catalogue_functions.php");

if ( ( wp_verify_nonce ( $_GET['securite_nonce'], 'Dj6&DSéDK7dDDjj&' ) ) AND ( $role[0] == "administrator" ) ) { 
		//echo "c'est ok !!";

      // ********************** FOURNISSEUR **********************


    if  ( $_POST['action_modification_fiche'] == "modification_fiche_membre" ) {

//print "phase 1";

        $error = "";

        if ( ( $_POST['membre_nom'] == "" ) OR ($_POST['membre_prenom'] == "" ) OR ($_POST['membre_adresse'] == "" ) OR ($_POST['membre_cp'] == "" ) OR ($_POST['membre_ville'] == "" ) OR ($_POST['membre_pays'] == "" ) OR ($_POST['membre_tel'] == "" ) OR ($_POST['membre_specialite'] == "" ) ) {
            $error .= __('Veuillez remplir tous les champs !','wp_catalogue_fournisseur')."<br />";
        }

        if ($error == "") {
//print "phase 2";
            if ( isset ( $_POST['securite_nonce'] ) ) {

                if ( wp_verify_nonce ( $_POST['securite_nonce'], 'Dj6&DSéDK7dDDjj&' ) ) {
                     //print "phase 3";
                    // Le formulaire est validé et sécurisé, suite du traitement
                
                    /** ******************************************************************************************* **/
                    /** ************************** ENREGISTREMENT DE LA PHOTO DU MEMBRE *************************** **/
                    /** ******************************************************************************************* **/


                    $today = date("Y-m-d h:i:s");

                    $_POST['membre_nom']                =     sanitize_text_field( $_POST['membre_nom'] );
                    $_POST['membre_prenom']             =     sanitize_text_field( $_POST['membre_prenom'] );
                    $_POST['membre_adresse']            =     sanitize_text_field( $_POST['membre_adresse'] );
                    $_POST['membre_cp']                 =     sanitize_text_field( $_POST['membre_cp'] );
                    $_POST['membre_ville']              =     sanitize_text_field( $_POST['membre_ville'] );
                    $_POST['membre_pays']               =     sanitize_text_field( $_POST['membre_pays'] );
                    $_POST['membre_tel']                =     sanitize_text_field( $_POST['membre_tel'] );
                    $_POST['membre_specialite']         =     sanitize_text_field( $_POST['membre_specialite'] );
                    $_POST['membre_localisation']       =     sanitize_text_field( $_POST['membre_localisation'] );
                    $_POST['membre_email']              =     sanitize_text_field( $_POST['membre_email'] );
                    $_POST['membre_web']                =     sanitize_text_field( $_POST['membre_web'] );
                    $_POST['membre_web']                =     str_replace("http://","",$_POST['membre_web'] );
                    
                    $wpdb->update( 
                        $wpdb->prefix.'annuaire_geoloc',
                        array( 
                            'membre_nom' => $_POST['membre_nom'],
                            'membre_prenom' => $_POST['membre_prenom'],
                            'membre_adresse' => $_POST['membre_adresse'],
                            'membre_cp' => $_POST['membre_cp'],
                            'membre_ville' => $_POST['membre_ville'],
                            'membre_pays' => $_POST['membre_pays'],
                            'membre_tel' => $_POST['membre_tel'],
                            'membre_specialite' => $_POST['membre_specialite'],
                            'membre_localisation' => $_POST['membre_localisation'],
                            'membre_email' => $_POST['membre_email'],
                            'membre_web' => $_POST['membre_web']
                        ), 
                        array( 
                            "id" => $_POST['id'] 
                    ));

                    /** ************************ Traitement de la photo ********************** **/

                    if(!empty($_FILES["membre_photo"]["name"])) { 
                        $upload_file = wp_upload_bits($_FILES['membre_photo']['name'], null, file_get_contents($_FILES['membre_photo']['tmp_name']));


                        //debug_to_console(addslashes($upload_file['file']));
                        //debug_to_console($upload_file['url']);
                        //debug_to_console($upload_file['type']);
                        //debug_to_console($upload_file['error']);

                        //

                        if (!$upload_file['error']) {


                            $chaine = addslashes($upload_file['file']);
                            $test = explode("wp-content",$chaine);
                            $url_image = "/wp-content".$test[1];
                            debug_to_console($url_image);

                            $wp_filetype = wp_check_filetype($filename, null );
                            $attachment = array(
                                'post_mime_type' => $wp_filetype['type'],
                                'post_parent' => $parent_post_id,
                                'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
                                'post_content' => '',
                                'post_status' => 'inherit'
                            );
                            $attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], $parent_post_id );
                            if (!is_wp_error($attachment_id)) {
                                require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                                $attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
                                wp_update_attachment_metadata( $attachment_id,  $attachment_data );
                            }

                            // REQUETE SQL

                            $wpdb->update( 
                                $wpdb->prefix.'annuaire_geoloc',
                                array( 
                                    'membre_photo' => $url_image
                                ), 
                                array( 
                                    "id" => $_POST['id'] 
                            ));

                        }

                    }

                    /** ************************ Suppression de l'image si checkbox cochée ********************** **/
                    if ($_POST['sup_photo'] == "suppression_photo") {

                        $wpdb->update( 
                            $wpdb->prefix.'annuaire_geoloc',
                            array( 
                                'membre_photo' => NULL
                            ), 
                            array( 
                                "id" => $_POST['id'] 
                        ));
                    
                    }

                    /** ******************************************************************************************* **/

                    echo "<SCRIPT LANGUAGE='JavaScript'>document.location.href='?page=annuaire_geolocalise&rep=mod_ok'</SCRIPT>";
                    
                }
            
            } else {
                echo __('Erreur dans le formulaire','wp_catalogue_fournisseur'); exit; // le formulaire est refusé
            }

        }

        if (($error) != "") { echo "<div class='error notice'><p>".$error."</p></div>"; }
    }

?>


	<?php
	
	if ( isset($_GET['id']) AND ($_GET['action'] == 'modification') ) {
		$result = $wpdb->get_row( "SELECT * FROM ".$wpdb->prefix."annuaire_geoloc WHERE id = ".$_GET['id'], OBJECT );
		//echo "Nbr de résultat :". $wpdb->num_rows."<br />";

        $selec = $result->specialite;

        if (empty($result->membre_photo)) { $result->membre_photo = "/wp-content/uploads/2017/12/anonyme.jpg"; }

        function recherche_selec($var,$selec) {
            if (preg_match("#$var#", $selec)) {
                return TRUE;
            } else {
                return FALSE;
            }
        } 

	?>

		<h1><?php echo __('Modification de la fiche médecin','wp_catalogue_fournisseur'); ?></h1>

  		<STYLE type="text/css">
            .form-field input { width: 25em !important; }
            .form-field #sup_photo { width: 16px !important; }

            #wrapper {
                width: 600px;
                height: 250px;
                margin-bottom:20px;
            } 

            #map {
                height: 100%;
            }

        </STYLE>

        <div id="wrapper">
            <div id='map'></div>
        </div>

        <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">

        <input type="hidden" name="securite_nonce" value="<?php echo wp_create_nonce('Dj6&DSéDK7dDDjj&'); ?>"/>
        <input type="hidden" name="action_modification_fiche" value="modification_fiche_membre">
        <input type="hidden" name="membre_localisation" id="membre_localisation" value="<?php echo $_GET['membre_localisation']; ?>" maxlength="50">
        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">

        <table class="form-table">
        <tbody>
            <tr class="form-field form-required">
                <th scope="row"><label for="user_login"><?php echo __('Nom','wp_catalogue_fournisseur'); ?></label></th>
                <td><input name="membre_nom" type="text" id="membre_nom" value="<?php echo trim(stripslashes($result->membre_nom)); ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="100"></td>
            </tr>

            <tr class="form-field form-required">
                <th scope="row"><label for="user_login"><?php echo __('Prénom','wp_catalogue_fournisseur'); ?></label></th>
                <td><input name="membre_prenom" type="text" id="contact_prenom" value="<?php echo trim(stripslashes($result->membre_prenom)); ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="100"></td>
            </tr>

            <tr class="form-field form-required">
                <th scope="row"><label for="user_login"><?php echo __('Adresse','wp_catalogue_fournisseur'); ?></label></th>
                <td><input name="membre_adresse" type="text" id="membre_adresse" value="<?php echo trim(stripslashes($result->membre_adresse)); ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="200"></td>
            </tr>

            <tr class="form-field form-required">
                <th scope="row"><label for="user_login"><?php echo __('CP','wp_catalogue_fournisseur'); ?></label></th>
                <td><input name="membre_cp" type="text" id="membre_cp" value="<?php echo trim(stripslashes($result->membre_cp)); ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="100"></td>
            </tr>

            <tr class="form-field form-required">
                <th scope="row"><label for="user_login"><?php echo __('Ville','wp_catalogue_fournisseur'); ?></label></th>
                <td><input name="membre_ville" type="text" id="membre_ville" value="<?php echo trim(stripslashes($result->membre_ville)); ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="100"></td>
            </tr>

            <tr class="form-field form-required">
                <th scope="row"><label for="user_login"><?php echo __('Pays','wp_catalogue_fournisseur'); ?></label></th>
                <td><input name="membre_pays" type="text" id="membre_pays" value="<?php echo trim(stripslashes($result->membre_pays)); ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="100"></td>
            </tr>

            <tr class="form-field form-required">
                <th scope="row"><label for="user_login"><?php echo __('Spécialité','wp_catalogue_fournisseur'); ?></label></th>
                <td>
                    <select id="field_4" name="membre_specialite" aria-required="true">
                        <option value="Chirurgien Esthétique" <?php if ($result->membre_specialite == "Chirurgien Esthétique") {  echo "selected"; } ?>>Chirurgien Esthétique</option>
                        <option value="Dermatologue" <?php if ($result->membre_specialite == "Dermatologue") {  echo "selected"; } ?>>Dermatologue</option>
                        <option value="Gynécologue" <?php if ($result->membre_specialite == "Gynécologue") {  echo "selected"; } ?>>Gynécologue</option>
                        <option value="Médecin Esthétique" <?php if ($result->membre_specialite == "Médecin Esthétique") {  echo "selected"; } ?>>Médecin Esthétique</option>
                        <option value="Médecin Généraliste" <?php if ($result->membre_specialite == "Médecin Généraliste") {  echo "selected"; } ?>>Médecin Généraliste</option>
                    </select>
                </td>

            </tr>

            <tr class="form-field form-required">
                <th scope="row"><label for="user_login"><?php echo __('Tél','wp_catalogue_fournisseur'); ?></label></th>
                <td><input name="membre_tel" type="text" id="membre_tel" value="<?php echo trim(stripslashes($result->membre_tel)); ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="100"></td>
            </tr>
           
            <tr class="form-field form-required">
                <th scope="row"><label for="user_login"><?php echo __('Email','wp_catalogue_fournisseur'); ?></label></th>
                <td><input name="membre_email" type="text" id="membre_email" value="<?php echo trim(stripslashes($result->membre_email)); ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="100"></td>
            </tr>

            <tr class="form-field form-required">
                <th scope="row"><label for="user_login"><?php echo __('Site internet','wp_catalogue_fournisseur'); ?></label></th>
                <td><input name="membre_web" type="text" id="membre_web" value="<?php echo trim(stripslashes($result->membre_web)); ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="100"></td>
            </tr>

            <tr class="form-field form-required">
                <th scope="row"><label for="user_login"><?php echo __('Photo','wp_catalogue_fournisseur'); ?></label></th>
                <td><img src="<?php echo $result->membre_photo; ?>" style="width:150px;"><br />Supprimer la photo <label for="sup_photo"><input name="sup_photo" value="suppression_photo" id="sup_photo" type="checkbox"></label><br /><br /><input name="membre_photo" type="file" id="membre_photo" value="" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="100"></td>
            </tr>


        </tbody>
        </table>

        <p class="submit"><input type="submit" name="modification_fiche_entreprise" id="createusersub" class="button button-primary" value="<?php echo __('Modifier la fiche entreprise','wp_catalogue_fournisseur'); ?>">&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" id="retour" name="retour" class="button button-secondary" value="<?php echo __('Retour à la liste','wp_catalogue_fournisseur'); ?>"></p>
        </form>

<script>

    var membreAdresse = document.getElementById('membre_adresse');
    var membreVille = document.getElementById('membre_ville');
    var membrePays = document.getElementById('membre_pays');
    var membreCP = document.getElementById('membre_cp');

    function initMap() {

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 5,
            center: {lat: 46.63877550000001, lng: 2.8901759999999967}
        });
        var geocoder = new google.maps.Geocoder();

        membreAdresse.addEventListener("change", function() {
            geocodeAddress(geocoder, map);
        });

        membreVille.addEventListener("change", function() {
            geocodeAddress(geocoder, map);
        });

        membrePays.addEventListener("change", function() {
            geocodeAddress(geocoder, map);
        });

        if ( membreAdresse.value != "" && membreVille.value != "" && membrePays.value != "") {
            geocodeAddress(geocoder, map);
            console.log(membreAdresse.value);
        }

    }

    function geocodeAddress(geocoder, resultsMap) {

        if ( membreAdresse.value != "" && membreVille.value != "" && membrePays.value != "") {

            var address2 = membreAdresse.value+" "+membreVille.value+" "+membrePays.value;

            geocoder.geocode({'address': address2}, function(results, status) {
                if (status === 'OK') {
                    resultsMap.setCenter(results[0].geometry.location);
                    document.getElementById('membre_localisation').value = results[0].geometry.location;
                    var marker = new google.maps.Marker({
                        map: resultsMap,
                        position: results[0].geometry.location
                    });
                } else {
                    alert('Géocodage non trouvé: ' + status);
                    membreAdresse.value = "";
                    membreVille.value = "";
                    membrePays.value = "";
                    membreCP.value = "";
                }
            });
        
        }
    }


jQuery(document).ready(function($){


    $('#retour').on("click", function(e) {
        e.preventDefault();
        document.location.href='?page=annuaire_geolocalise';
    });

    $('#dialog').dialog({
        autoOpen: false,
        bgiframe: true,
        resizable: false,
        modal: true,
        title: 'Suppression de fiche'
    });

    function delete_fiche(id)
    {
      $('#dialog').dialog('option','buttons',{
        'Suppression de fiche': function() {
          $(this).dialog('close');
          document.location.href='?&id='+id+'&page=action_annuaire_geolocalise&securite_nonce=<?php echo $nonce; ?>&action=suppression';
        },
        'Annuler': function() {
          $(this).dialog('close');
        }
      });
        $('#dialog').dialog('open');
        return false;
    }

    $('.myDelete').on("click", function(e) {
        e.preventDefault();
        var id = $(this).attr("id");
        delete_fiche(id);
    });


});

</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyARDGq8x8xjHCCHruL2JjMLEmzFZpF7lTo&callback=initMap"></script>

<?php

	}

	if ( isset($_GET['id']) AND ($_GET['action'] == 'suppression') ) {

		$wpdb->delete( $wpdb->prefix.'annuaire_geoloc',
		                 [ 'ID' => $_GET['id'] ],
		                 [ '%d' ] );

        
        echo "<SCRIPT LANGUAGE='JavaScript'>document.location.href='?page=annuaire_geolocalise&rep=sup_ok'</SCRIPT>";

	}

} else {
	wp_die();
}

?>