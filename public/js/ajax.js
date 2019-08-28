$(document).ready(function () {
    $('#trip_city').change( function(){
        let city = $(this).val();
        $.ajax({
            url:'/sortir/public/sorties/ajax_request_place',
            type: "POST",
            dataType: "json",
            data: {
                "city": city
            },
            async: true,
            success: function (data)
            {
                console.log(data);

                $('#trip_place').empty()

                data.forEach(function(place) {
                    $('#trip_place').append(new Option(place.libelle, place.id));
                });

            }
        })
    });

    $('#addCityModalBtn').click( function(){
        let libelle = $('#trip_formCity_libelle').val();
        let zipCode = $('#trip_formCity_PostalCode').val();
        $.ajax({
            url:'/ajax_add_city',
            type: "POST",
            dataType: "json",
            data: {
                "libelle": libelle,
                "zipCode": zipCode
            },
            async: true,
            success: function (data)
            {

                $('#trip_place').empty();
                $('#trip_city').empty();

                data.forEach(function(city) {
                    $('#trip_city').append(new Option(city.libelle, city.id));
                });
                Notiflix.Notify.Success('Modifications enregistrées !');
            }
        })
   });

});