<?php

    $current_user = wp_get_current_user();
    $role = $current_user->roles;
    $user_connect_id = $current_user->ID;

    $urlGet = explode("?",$_SERVER['REQUEST_URI']);
    $urlSansLeGet = $urlGet[0];

    if ($role[0] == "administrator") {
        //print "ok";
    } else {
        wp_die();
    }

    /**
     * @example Safe usage: $current_user = wp_get_current_user();
     * if ( !($current_user instanceof WP_User) )
     *     return;
     */
    /*
    echo 'Username: ' . $current_user->user_login . '<br />';
    echo 'User email: ' . $current_user->user_email . '<br />';
    echo 'User first name: ' . $current_user->user_firstname . '<br />';
    echo 'User last name: ' . $current_user->user_lastname . '<br />';
    echo 'User display name: ' . $current_user->display_name . '<br />';
    echo 'User ID: ' . $current_user->ID . '<br />';

    echo 'User ROLE: ' . $role[0]."<br />";
    */

require_once("catalogue_functions.php");

$_POST['user_email'] = trim($_POST['user_email']);

?>

<div class="wrapp">


    <STYLE type="text/css">
        .form-field input { width: 25em !important; }

        #wrapper {
            width: 600px;
            height: 250px;
            margin-bottom:20px;
        }

        #map {
            height: 100%;
        }
    </STYLE>

<?php


//debug_to_console($role[0]);

    /**
    *
    * TRAINTEMENT DES REPONSES DU SCRIPT DE MODIFICION/SUPPRESSIONS DE FICHE
    *
    */

    if ($_GET['rep'] == 'mod_ok') {
        echo "<div class='updated notice'><p>".__('La fiche membre a été modifiée avec succès !','wp_catalogue_fournisseur')."</p></div>";
    } elseif ($_GET['rep'] == 'sup_ok') {
        echo "<div class='updated notice'><p>".__('La fiche membre a été supprimée avec succès !','wp_catalogue_fournisseur')."</p></div>";
    }

    /**
     *
     * TRAITEMENT DU FORMULAIRE DE REMPLISSAGE DE FICHE
     *
     */

global $wpdb;


if ($role[0] == "administrator") {           // ********************** FOURNISSEUR **********************


    if ( isset ( $_POST['creation_fiche_membre'] ) AND ( $_POST['action_creation_fiche'] == "creation_fiche_membre" ) ) {

        $error = "";

                    $_POST['membre_nom']                =     sanitize_text_field( $_POST['membre_nom'] );
                    $_POST['membre_prenom']             =     sanitize_text_field( $_POST['membre_prenom'] );
                    $_POST['membre_adresse']            =     sanitize_text_field( $_POST['membre_adresse'] );
                    $_POST['membre_cp']                 =     sanitize_text_field( $_POST['membre_cp'] );
                    $_POST['membre_ville']              =     sanitize_text_field( $_POST['membre_ville'] );
                    $_POST['membre_pays']               =     sanitize_text_field( $_POST['membre_pays'] );
                    $_POST['membre_tel']                =     esc_attr( $_POST['membre_tel'] );
                    $_POST['membre_specialite']         =     sanitize_text_field( $_POST['membre_specialite'] );
                    $_POST['membre_localisation']       =     sanitize_text_field( $_POST['membre_localisation'] );
                    $_POST['membre_email']              =     sanitize_text_field( $_POST['membre_email'] );
                    $_POST['membre_web']                =     sanitize_text_field( $_POST['membre_web'] );
                    $_POST['membre_web']                =     str_replace("http://","",$_POST['membre_web'] );

        if ( ( $_POST['membre_nom'] == "" ) OR ($_POST['membre_prenom'] == "" ) OR ($_POST['membre_adresse'] == "" ) OR ($_POST['membre_cp'] == "" ) OR ($_POST['membre_ville'] == "" ) OR ($_POST['membre_pays'] == "" ) OR ($_POST['membre_tel'] == "" ) OR ($_POST['membre_specialite'] == "" ) ) {
            $error .= __('Veuillez remplir tous les champs !','wp_catalogue_fournisseur')."<br />";
        }

        if ($error == "") {

            if ( isset ( $_POST['securite_nonce'] ) ) {

                if ( wp_verify_nonce ( $_POST['securite_nonce'], 'securite-nonce' ) ) {

                    // Le formulaire est validé et sécurisé, suite du traitement


                    /** ******************************************************************************************* **/
                    /** ************************** ENREGISTREMENT DE LA PHOTO DU MEMBRE *************************** **/
                    /** ******************************************************************************************* **/


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
                        }

                    } else {
                        $url_image = NULL;
                    }
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
                    $wpdb->insert(
                        $wpdb->prefix.'annuaire_geoloc',
                        array(
                            'id' => 'NULL',
                            'date_enregistrement' => $today,
                            'membre_nom' => $_POST['membre_nom'],
                            'membre_prenom' => $_POST['membre_prenom'],
                            'membre_adresse' => $_POST['membre_adresse'],
                            'membre_cp' => $_POST['membre_cp'],
                            'membre_ville' => $_POST['membre_ville'],
                            'membre_pays' => $_POST['membre_pays'],
                            'membre_tel' => $_POST['membre_tel'],
                            'membre_specialite' => $_POST['membre_specialite'],
                            'membre_localisation' => $_POST['membre_localisation'],
                            'membre_photo' => $url_image,
                            'membre_email' => $_POST['membre_email'],
                            'membre_web' => $_POST['membre_web']
                        ),
                        array(
                            '%d',
                            '%d',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s'
                    ));

                    echo "<div class='updated notice'><p>".__('La fiche a été enregistrée avec succès !','wp_catalogue_fournisseur')."</p></div>";




                }

            } else {
                echo __('Erreur dans le formulaire','wp_catalogue_fournisseur'); exit; // le formulaire est refusé
            }

        }

        if (($error) != "") { echo "<div class='error notice'><p>".$error."</p></div>"; }

    }
}



if ($role[0] == "administrator") { // ********************** ADMINISTRATEUR **********************

$results = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."annuaire_geoloc ORDER BY membre_nom, membre_prenom", OBJECT );
//echo "Nbr de résultat :". $wpdb->num_rows."<br />";

?>

<br /><h1><?php echo __('Liste des membres','wp_catalogue_fournisseur'); ?></h1>



        <!-- ****************************************************************************************************************************************** -->
        <!-- ********************************************************* LISTE DES FICHES *************************************************************** -->

        <table class="widefat fixed" cellspacing="0">
            <thead>
            <tr>
                    <th id="columnname" class="manage-column column-columnname" scope="col"><?php echo __('Photo','wp_catalogue_fournisseur'); ?></th>
                    <th id="columnname" class="manage-column column-columnname" scope="col"><?php echo __('Nom','wp_catalogue_fournisseur'); ?></th>
                    <th id="columnname" class="manage-column column-columnname" scope="col"><?php echo __('Prénom','wp_catalogue_fournisseur'); ?></th>
                    <th id="columnname" class="manage-column column-columnname" scope="col"><?php echo __('Adresse','wp_catalogue_fournisseur'); ?></th>
                    <th id="columnname" class="manage-column column-columnname" scope="col"><?php echo __('CP','wp_catalogue_fournisseur'); ?></th>
                    <th id="columnname" class="manage-column column-columnname" scope="col"><?php echo __('Ville','wp_catalogue_fournisseur'); ?></th>
                    <th id="columnname" class="manage-column column-columnname" scope="col"><?php echo __('Tél','wp_catalogue_fournisseur'); ?></th>
            </tr>
            </thead>

            <tbody>

                <?php $nonce = wp_create_nonce('Dj6&DSéDK7dDDjj&'); foreach ( $results as $result ) { if (!empty($result->membre_photo)) { $photo = $result->membre_photo; } else { $photo = "/wp-content/uploads/2017/12/anonyme.jpg"; } ?>

<!-- /wordpress_creation_plugin -->

                <tr class="alternate">
                    <td class="column-columnname"><img src='<?php echo $photo; ?>'' style='width:50px;'>
                        <div class="row-actions">
                            <span><a href="?id=<?php echo $result->id; ?>&page=action_annuaire_geolocalise&securite_nonce=<?php echo $nonce; ?>&action=modification">Modifier</a> |</span>
                            <span><a class="myDelete" id="<?php echo $result->id; ?>" href="#">Supprimer</a></span>
                        </div>
                    </td>
                    <td class="column-columnname"><?php echo trim(stripslashes($result->membre_nom)); ?></td>
                    <td class="column-columnname"><?php echo trim(stripslashes($result->membre_prenom)); ?></td>
                    <td class="column-columnname"><?php echo trim(stripslashes($result->membre_adresse)); ?></td>
                    <td class="column-columnname"><?php echo trim(stripslashes($result->membre_cp)); ?></td>
                    <td class="column-columnname"><?php echo trim(stripslashes($result->membre_ville)); ?></td>
                    <td class="column-columnname"><?php echo trim(stripslashes($result->membre_tel)); ?></td>
                </tr>

                <?php } ?>

            </tbody>
        </table>
<br />

        <div id="wrapper">
            <div id='map'></div>
        </div>

        <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">

        <input type="hidden" name="securite_nonce" value="<?php echo wp_create_nonce('securite-nonce'); ?>"/>
        <input type="hidden" name="action_creation_fiche" value="creation_fiche_membre">
        <input type="hidden" name="membre_localisation" id="membre_localisation" value="" maxlength="50">

        <table class="form-table">
        <tbody>
            <tr class="form-field form-required">
                <th scope="row"><label for="user_login"><?php echo __('Nom','wp_catalogue_fournisseur'); ?></label></th>
                <td><input name="membre_nom" type="text" id="membre_nom" value="<?php if ($_POST['membre_nom'] != "" AND $error != "") {  echo trim(stripslashes($_POST['membre_nom'])); } ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="100"></td>
            </tr>

            <tr class="form-field form-required">
                <th scope="row"><label for="user_login"><?php echo __('Prénom','wp_catalogue_fournisseur'); ?></label></th>
                <td><input name="membre_prenom" type="text" id="contact_prenom" value="<?php if ($_POST['membre_prenom'] != "" AND $error != "") {  echo trim(stripslashes($_POST['membre_prenom'])); } ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="100"></td>
            </tr>

            <tr class="form-field form-required">
                <th scope="row"><label for="user_login"><?php echo __('Adresse','wp_catalogue_fournisseur'); ?></label></th>
                <td><input name="membre_adresse" type="text" id="membre_adresse" value="<?php if ($_POST['membre_adresse'] != "" AND $error != "") {  echo trim(stripslashes($_POST['membre_adresse'])); } ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="200"></td>
            </tr>

            <tr class="form-field form-required">
                <th scope="row"><label for="user_login"><?php echo __('CP','wp_catalogue_fournisseur'); ?></label></th>
                <td><input name="membre_cp" type="text" id="membre_cp" value="<?php if ($_POST['membre_cp'] != "" AND $error != "") {  echo trim(stripslashes($_POST['membre_cp'])); } ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="100"></td>
            </tr>

            <tr class="form-field form-required">
                <th scope="row"><label for="user_login"><?php echo __('Ville','wp_catalogue_fournisseur'); ?></label></th>
                <td><input name="membre_ville" type="text" id="membre_ville" value="<?php if ($_POST['membre_ville'] != "" AND $error != "") {  echo trim(stripslashes($_POST['membre_ville'])); } ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="100"></td>
            </tr>

            <tr class="form-field form-required">
                <th scope="row"><label for="user_login"><?php echo __('Pays','wp_catalogue_fournisseur'); ?></label></th>
                <td><input name="membre_pays" type="text" id="membre_pays" value="<?php if ($_POST['membre_pays'] != "" AND $error != "") {  echo trim(stripslashes($_POST['membre_pays'])); } ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="100"></td>
            </tr>

            <tr class="form-field form-required">
                <th scope="row"><label for="user_login"><?php echo __('Spécialité','wp_catalogue_fournisseur'); ?></label></th>
                <td>
                    <select id="field_4" name="membre_specialite" aria-required="true">
                        <option value="Chirurgien Esthétique" <?php if ($_POST['membre_specialite'] == "Chirurgien Esthétique") {  echo "selected"; } ?>>Chirurgien Esthétique</option>
                        <option value="Dermatologue" <?php if ($_POST['membre_specialite'] == "Dermatologue") {  echo "selected"; } ?>>Dermatologue</option>
                        <option value="Gynécologue" <?php if ($_POST['membre_specialite'] == "Gynécologue") {  echo "selected"; } ?>>Gynécologue</option>
                        <option value="Médecin Esthétique" <?php if ($_POST['membre_specialite'] == "Médecin Esthétique") {  echo "selected"; } ?>>Médecin Esthétique</option>
                        <option value="Médecin Généraliste" <?php if ($_POST['membre_specialite'] == "Médecin Généraliste") {  echo "selected"; } ?>>Médecin Généraliste</option>
                    </select>
                </td>

            </tr>

            <tr class="form-field form-required">
                <th scope="row"><label for="user_login"><?php echo __('Tél','wp_catalogue_fournisseur'); ?></label></th>
                <td><input name="membre_tel" type="text" id="membre_tel" value="<?php if ($_POST['membre_tel'] != "" AND $error != "") {  echo trim(stripslashes($_POST['membre_tel'])); } ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="100"></td>
            </tr>

            <tr class="form-field form-required">
                <th scope="row"><label for="user_login"><?php echo __('Email','wp_catalogue_fournisseur'); ?></label></th>
                <td><input name="membre_email" type="text" id="membre_email" value="<?php if ($_POST['membre_email'] != "" AND $error != "") {  echo trim(stripslashes($_POST['membre_email'])); } ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="100"></td>
            </tr>

            <tr class="form-field form-required">
                <th scope="row"><label for="user_login"><?php echo __('Site internet','wp_catalogue_fournisseur'); ?></label></th>
                <td><input name="membre_web" type="text" id="membre_web" value="<?php if ($_POST['membre_web'] != "" AND $error != "") {  echo trim(stripslashes($_POST['membre_web'])); } ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="100"></td>
            </tr>

            <tr class="form-field form-required">
                <th scope="row"><label for="user_login"><?php echo __('Photo','wp_catalogue_fournisseur'); ?></label></th>
                <td><input name="membre_photo" type="file" id="membre_photo" value="" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="100"></td>
            </tr>


        </tbody>
        </table>

        <p class="submit"><input type="submit" name="creation_fiche_membre" id="createusersub" class="button button-primary" value="Enregistrer la Fiche"></p>
        </form>

        <br />
        <!-- ****************************************************************************************************************************************** -->
        <!-- ****************************************************** FIN LISTE DES FICHES ************************************************************** -->

<?php } ?>






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
console.log("je code avec le cul")
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyARDGq8x8xjHCCHruL2JjMLEmzFZpF7lTo&callback=initMap"></script>

<div id="dialog" title="Confirmation Required">
  <?php echo __('Voulez-vous vraiment supprimer cette fiche ?','wp_catalogue_fournisseur'); ?>
</div>​


<?php //print_r($wpdb->queries); ?>

</div>
