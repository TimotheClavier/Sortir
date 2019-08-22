$(document).ready( function() {
  $('select').addClass('browser-default custom-select');
  $('[data-toggle="popover-hover"]').popover({
    html: true,
    trigger: 'hover',
    placement: 'bottom',
    content: function () { return '<img src="' + $(this).data('img') + '" />'; }
  });
  $('#data-table').DataTable({
    "scrollX": true,
    "autoWidth": true
  });
  $('.dataTables_length').addClass('bs-select');

  Notiflix.Confirm.Init();
  Notiflix.Notify.Init({
    position: 'right-bottom',
    autoWidth: true
  });
})

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

function placeConfirm() {
  Notiflix.Confirm.Show(
    'Attention',
    'Voulez vous vraiment supprimer ce site ?' +
        'Toutes les sorties sur ce site seront supprimmer également.',
    'Oui',
    'Non',
    function() {
      $('#form_delete_place').submit();
    }
  );
}

function cityConfirm() {
  Notiflix.Confirm.Show(
    'Attention',
    'Voulez vous vraiment supprimer ce site ?' +
    'Toutes les sorties de cette ville seront supprimmer également.',
    'Oui',
    'Non',
    function() {
      $('#form_delete_city').submit();
    }
  );
}

function userConfirm(id) {
  Notiflix.Confirm.Show(
    'Attention',
    'Voulez vous vraiment supprimer cet Utilisateur ?',
    'Oui',
    'Non',
    function() {
      $('#form_delete_user' + id).submit();
    }
  );
}

function notifyError( message ) {
  Notiflix.Notify.Failure(message);
}
