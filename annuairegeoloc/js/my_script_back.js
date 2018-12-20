jQuery(document).ready(function($){

	var nombreCaractere = $("#entreprise_description").val().length;
	var msg = nombreCaractere+" / 300";
	$('#compteur').text(msg);

	$( "#test_de_click" ).on( "click", function() {
		jQuery.ajax({
			url : alink.ajax_url,
			type : 'post',
			data : {
				action : 'mon_action',
				param: 'coucou'
			},
			success : function( response ) {
				alert(response)
			}
		});
	});


	$("#entreprise_description").keyup(function() {
	 
	    var nombreCaractere = $("#entreprise_description").val().length;

	    // On soustrait le nombre limite au nombre de caractère existant
	
	    if (nombreCaractere > 300)
	    {
	    	$("#entreprise_description").val($("#entreprise_description").val().substring(0, 300));
	    	nombreCaractere = 300;
		}

	    $('#compteur').text(nombreCaractere+" / 300");
	 
	    // On écris le nombre de caractère en rouge si celui si est inférieur à 0 
	    // La limite est donc dépasse
	 
	});


      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 8,
          center: {lat: -34.397, lng: 150.644}
        });
        
        var geocoder = new google.maps.Geocoder();

      }


      function geocodeAddress(geocoder, resultsMap) {
        var address = document.getElementById('address').value;
        geocoder.geocode({'address': address}, function(results, status) {
          if (status === 'OK') {
            alert(results[0].geometry.location);
            resultsMap.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
              map: resultsMap,
              position: results[0].geometry.location
            });
          } else {
            alert('Geocode was not successful for the following reason: ' + status);
          }
        });
      }



});