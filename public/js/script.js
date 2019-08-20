$(document).ready( function() {
  $('select').addClass('browser-default custom-select');
  $('[data-toggle="popover-hover"]').popover({
    html: true,
    trigger: 'hover',
    placement: 'bottom',
    content: function () { return '<img src="' + $(this).data('img') + '" />'; }
  });
  $('#data-table-place').DataTable();
  $('.dataTables_length').addClass('bs-select');
  Notiflix.Confirm.Init();
})

function eventConfirm() {
  Notiflix.Confirm.Show(
    'Notiflix Confirm',
    'Do you agree with me?',
    'Yes',
    'No',
    function() {
      return true;
    },
    function() {
      return false;
    }
  );
}
