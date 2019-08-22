$(document).ready( function() {

  var filtre = [];

  $('select').addClass('browser-default custom-select');
  $('[data-toggle="popover-hover"]').popover({
    html: true,
    trigger: 'hover',
    placement: 'bottom',
    content: function () { return '<img src="' + $(this).data('img') + '" />'; }
  });
  $('#data-table-place').DataTable();
  $('#data-table-city').DataTable();
  $('.dataTables_length').addClass('bs-select');

  Notiflix.Confirm.Init();
  Notiflix.Notify.Init({});



  $('.checkfiltre').on('click',function () {
    var entryElements = $('[data-entry-info]');
    var userElements = $('[data-entry-user]');
    var user = $('[data-entry-us]');



    var entryIds =
        $.map(entryElements, item => $(item).data('entryInfo'));

    var userIds =
        $.map(userElements, item => $(item).data('entryUser'));

    var userId =
        $.map(user, item => $(item).data('entryUs'));


    checkFiltre(this, entryIds, userIds,userId);
  });

  function checkFiltre(elem, trips,userIds, user){


    var idReq = "";

    if($(elem).is(':checked')){
      filtre[elem.name] = elem.name;

    }
    else{
      filtre[elem.name]= "";

    }

    filtre.forEach(function (value, index,) {
      if(value !== ""){
        idReq = idReq + value;
      }

    });

    switch (idReq) {
      case "":
        trips.forEach(function (value,index) {

          var id = "#"+value.id;
          $(id).css('display','');

        });
        break;
        //sorti ou je suis orga
      case "1":
        trips.forEach(function (value,index) {
            if(value.organizer !== user[index].id){
              console.log(value.id);
              var id = "#"+value.id;
              $(id).css('display','none');
            }

          });
        break;
        //sorti ou je suis inscrit
      case "2":
        trips.forEach(function (value,index) {
          if(userIds[index].trip_id !== value.id){
            var id = "#"+value.id;
            $(id).css('display','none');
          }

        });
        break;
      case "3":
        break;
      case "4":
        break;
      case "12":
        break;
      case "123":
        break;
      case "124":
        break;
      case "1234":
        break;
      case "13":
        break;
      case "134":
        break;
      case "14":
        break;
      case "23":
        break;
      case "234":
        break;
      case "24":
        break;
      case "34":
        break;




    }
  }


});



function eventConfirm() {
  Notiflix.Confirm.Show(
    'Attention',
    'Voulez vous vraiment supprimer cette sortie ?',
    'Oui',
    'Non',
    function() {
      $('#form_delete_trip').submit();
    }
  );
}

function notifyError( message ) {
  Notiflix.Notify.Failure(message);
}


