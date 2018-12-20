<?php

    function debug_to_console( $data ) {
        
        if ( is_array( $data ) )
            $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
        else
            $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";
        
        echo $output;
    }

    function affiche_select_pays($pays_choisi) {
        global $wpdb;

        $option_select = "";

        if (get_bloginfo("language") == 'fr-FR') {
            
            $results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."cat_pays ORDER BY nom_fr_fr", OBJECT );
            
            foreach ( $results as $result ) {
                if ($pays_choisi ==  $result->alpha2) { $optionSelected = " selected='selected' "; } else { $optionSelected = ""; }
                $option_select .= "<option value='". $result->alpha2."'".$optionSelected.">".$result->nom_fr_fr."</option>";
            }

        } else {
            
            $results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."cat_pays ORDER BY nom_en_gb", OBJECT );

            foreach ( $results as $result ) {
                if ($pays_choisi == $result->alpha2) { $optionSelected = " selected='selected' "; } else { $optionSelected = ""; }
                $option_select .= "<option value='".$result->alpha2."'".$optionSelected.">".$result->nom_en_gb."</option>";
            }
        
        }

        return $option_select;

    }


    function affiche_pays_fiche2($code_pays_fiche) {
      global $wpdb;

      $results = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."cat_pays WHERE alpha2 LIKE '".$code_pays_fiche."'", OBJECT );

      if (get_bloginfo("language") == 'fr-FR') {

        return $results->nom_fr_fr;

          } else {

        return $results->nom_en_gb;

      }
    }



    function clean_file_name($str, $charset='utf-8')
    {

        $nom = substr(  $str, 0, -4);
        $extension = substr(  strrchr($str, '.')  ,1);
        
        $nom = htmlentities($nom, ENT_NOQUOTES, $charset);
        $nom = strtolower($nom);
        $nom = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $nom);
        $nom = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $nom); // pour les ligatures e.g. '&oelig;'
        $nom = preg_replace('#&[^;]+;#', '', $nom); // supprime les autres caractères
        $interdit = array(".", ",", ":", "!", "?", "/", "(", ")", " ", "--", "---", "'");
        $nom = str_replace($interdit, "-", $nom);

        return $nom.".".$extension;
    }

/**
* La fonction darkroom() renomme et redimensionne les photos envoyées lors de l'ajout d'un objet.
* @param $img String Chemin absolu de l'image d'origine.
* @param $to String Chemin absolu de l'image générée (.jpg).
* @param $width Int Largeur de l'image générée. Si 0, valeur calculée en fonction de $height.
* @param $height Int Hauteur de l'image génétée. Si 0, valeur calculée en fonction de $width.
* Si $height = 0 et $width = 0, dimensions conservées mais conversion en .jpg
*/
function redimImage($img, $to, $width = 0, $height = 0) {
 
    $dimensions = getimagesize($img);
    $ratio      = $dimensions[0] / $dimensions[1];
 
    // Calcul des dimensions si 0 passé en paramètre
    if($width == 0 && $height == 0){
        $width = $dimensions[0];
        $height = $dimensions[1];
    }elseif($height == 0){
        $height = round($width / $ratio);
    }elseif ($width == 0){
        $width = round($height * $ratio);
    }
 
    if($dimensions[0] > ($width / $height) * $dimensions[1]){
        $dimY = $height;
        $dimX = round($height * $dimensions[0] / $dimensions[1]);
        $decalX = ($dimX - $width) / 2;
        $decalY = 0;
    }
    if($dimensions[0] < ($width / $height) * $dimensions[1]){
        $dimX = $width;
        $dimY = round($width * $dimensions[1] / $dimensions[0]);
        $decalY = ($dimY - $height) / 2;
        $decalX = 0;
    }
    if($dimensions[0] == ($width / $height) * $dimensions[1]){
        $dimX = $width;
        $dimY = $height;
        $decalX = 0;
        $decalY = 0;
    }
 
    // Création de l'image avec la librairie GD

        $pattern = imagecreatetruecolor($width, $height);
        $type = mime_content_type($img);
        switch (substr($type, 6)) {
            case 'jpeg':
                $image = imagecreatefromjpeg($img);
                break;
            case 'gif':
                $image = imagecreatefromgif($img);
                break;
            case 'png':
                $image = imagecreatefrompng($img);
                break;
        }
        imagecopyresampled($pattern, $image, 0, 0, 0, 0, $dimX, $dimY, $dimensions[0], $dimensions[1]);
        imagedestroy($image);

        switch (substr($type, 6)) {
            case 'jpeg':
                imagejpeg($pattern, $to, 80);
                break;
            case 'gif':
                imagegif($pattern, $to);
                break;
            case 'png':
                imagepng($pattern, $to, 8);
                break;
        }



        return TRUE;

}


/*
    $extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );

    $extension_upload = strtolower(  substr(  strrchr($_FILES['file']['name'], '.')  ,1)  );

    if ( in_array($extension_upload,$extensions_valides) ) {
        $nomfichiervign = clean_file_name($_FILES['file']['name']); 
        
        if (move_uploaded_file($_FILES['file']['tmp_name'], "../".DOSSIER_PORTFOLIO.$nomfichiervign)) {
            
            redimImage("../".DOSSIER_PORTFOLIO.$nomfichiervign, "../".DOSSIER_PORTFOLIO."vign/".$nomfichiervign, $width = 150, $height = 0);

            $res2 = $GLOBALS["db"] -> query(" INSERT INTO imgx_portfolio VALUES (NULL,'".$_POST['idPolice']."','".$nomfichiervign."','".$ordre_max."')");

        }

    }
*/


?>