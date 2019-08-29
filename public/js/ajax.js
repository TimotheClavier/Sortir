$(document).ready(function () {
    getPlaces($('#trip_city').val());

    $('#trip_city').change( function(){
         getPlaces($(this).val());
    });

    $('#addCityModalBtn').click( function(){
        let libelle = $('#city_libelle').val();
        let zipCode = $('#city_PostalCode').val();
        $.ajax({
            url:'/sortir/public/ajax_add_city',
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
                $("#closeCityModalBtn").click();
            }
        })
   });

    $('#addPlaceModalBtn').click( function(){
        let libelle     = $('#ajax_place_libelle').val();
        let street      = $('#ajax_place_street').val();
        let latitude    = $('#ajax_place_latitude').val();
        let longitude   = $('#ajax_place_longitude').val();
        let city        = $('#trip_city').val();

        $.ajax({
            url:'/sortir/public/ajax_add_place',
            type: "POST",
            dataType: "json",
            data: {
                "libelle": libelle,
                "city": city,
                "street": street,
                "latitude": latitude,
                "longitude": longitude,
            },
            async: true,
            success: function (data)
            {
                console.log(data);
                $('#trip_place').empty();

                data.forEach(function(city) {
                    $('#trip_place').append(new Option(city.libelle, city.id));
                });
                Notiflix.Notify.Success('Modifications enregistrées !');
                $("#closePlaceModalBtn").click();
            }
        })
    });



});


function getPlaces(city) {
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
}