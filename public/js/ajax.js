$(document).ready(function () {
    $('#trip_city').change( function(){
        let city = $(this).val();
        $.ajax({
            url:'/sorties/ajax_request_place',
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
});