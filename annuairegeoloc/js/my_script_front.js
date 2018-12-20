jQuery(document).ready(function($){
	function format ( doctor ) {
        return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
            '<tr>'+
                '<td>Description:</td>'+
                '<td>'+doctor.description+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td>Adresse:</td>'+
                '<td>'+doctor.adresse+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td>Adresse 2:</td>'+
                '<td>'+doctor.adresse2+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td>Ville:</td>'+
                '<td>'+doctor.ville+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td>Titre:</td>'+
                '<td>'+doctor.titre+'</td>'+
            '</tr>'+

            '<tr>'+
                '<td>Contact:</td>'+
                '<td>'+doctor.contact+'</td>'+
            '</tr>'+

            '<tr>'+
                '<td>Email:</td>'+
                '<td><a href="mailto:'+d.email+'">'+doctor.email+'</a></td>'+
            '</tr>'+

            '<tr>'+
                '<td>Web:</td>'+
                '<td><a href="'+d.web+'" target="_Blank">'+d.web+'</a></td>'+
            '</tr>'+

            '<tr>'+
                '<td>Tél:</td>'+
                '<td>'+d.tel+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td>Mobile:</td>'+
                '<td>'+d.gsm+'</td>'+
            '</tr>'+
        '</table>';
    }

    var myChemin = window.location.pathname;
    //var strArray = myChemin.split("/");
    //var FileObjectsPath = "/"+strArray[1]+"/wp-content/plugins/wp_catalogue_fournisseur/objects.php";
    var FileObjectsPath = "http://pln.webmaster-independant.com/wp-content/plugins/wp_catalogue_fournisseur/objects.php";

    if ($("#table_fournisseurs").data("language2") == "French") {

        var table = $('#table_fournisseurs').DataTable( {
            //"ajax": FileObjectsPath,
            "ajax": FileObjectsPath,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
            },
            "columns": [
                {
                    "className":      'details-control',
                    "orderable":      true,
                    "data":           null,
                    "defaultContent": ''
                },
                { "data": "entreprise" },
                { "data": "pays" },
                { "data": "activite" },
                { "data": "specialite" }

            ],
            "order": [[0, 'asc']]
        } );

    } else {

        var table = $('#table_fournisseurs').DataTable( {
            //"ajax": FileObjectsPath,
            "ajax": FileObjectsPath,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/English.json"
            },
            "columns": [
                {
                    "className":      'details-control',
                    "orderable":      true,
                    "data":           null,
                    "defaultContent": ''
                },
                { "data": "entreprise" },
                { "data": "pays" },
                { "data": "activite" },
                { "data": "specialite" }

            ],
            "order": [[0, 'asc']]
        } );
    }

    // Add event listener for opening and closing details
    $('#table_fournisseurs tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );

        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
    } );

})
