$(document).ready( function() {
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

  $(".button-collapse").sideNav();
// SideNav Scrollbar Initialization
  var sideNavScrollbar = document.querySelector('.custom-scrollbar');
  var ps = new PerfectScrollbar(sideNavScrollbar);
  Notiflix.Confirm.Init();
  Notiflix.Notify.Init({});
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

function notifyError( message ) {
  Notiflix.Notify.Failure(message);
}
