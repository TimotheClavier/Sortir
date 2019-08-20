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

  $(".button-collapse").sideNav();
// SideNav Scrollbar Initialization
  var sideNavScrollbar = document.querySelector('.custom-scrollbar');
  var ps = new PerfectScrollbar(sideNavScrollbar);
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
