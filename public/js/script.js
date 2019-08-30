$(document).ready(function () {
  $('.pagination').children(':first').children(':first').html('<i class=\'fas fa-angle-left fa-2x\'></i>')
  $('.pagination').children(':first').attr('style', 'margin-top:-7px')
  $('.pagination').children(':last').children(':last').html('<i class=\'fas fa-angle-right fa-2x\'></i>')
  $('.pagination').children(':last').attr('style', 'margin-top:-7px')

  $('select').addClass('browser-default custom-select')
  $('[data-toggle="popover-hover"]').popover({
    html: true,
    trigger: 'hover',
    placement: 'bottom',
    content: function () { return '<img src="' + $(this).data('img') + '" />' }
  })
  $('#data-table').DataTable({
    'scrollX': true,
    'autoWidth': true
  })
  $('.dataTables_length').addClass('bs-select')

  Notiflix.Notify.Init({
    position: 'right-top',
    distance: '15%'
  })
  Notiflix.Confirm.Init({})
  Notiflix.Loading.Init({})

  $('#search').on('keyup', function () {
    var tab = []
    var entryElements = $('[data-entry-info]')
    var entryIds =
      $.map(entryElements, item => $(item).data('entryInfo'))

    entryIds.forEach(function (value, index) {
      if (value.name.toLowerCase().match($('#search').val())) {
        tab[index] = value
        var id = '#' + value.id
        $(id).css('display', '')
      } else {
        var id = '#' + value.id
        $(id).css('display', 'none')
      }
    })

  })

})

function eventConfirm () {
  Notiflix.Confirm.Show(
    'Attention',
    'Voulez vous vraiment supprimer cette sortie ?',
    'Oui',
    'Non',
    function () {
      $('#form_delete_trip').submit()
    }
  )
}

function placeConfirm (id) {
  Notiflix.Confirm.Show(
    'Attention',
    'Voulez vous vraiment supprimer ce site ?' +
    'Toutes les sorties sur ce site seront supprimmer également.',
    'Oui',
    'Non',
    function () {
      $('#form_delete_place' + id).submit()
    }
  )
}

function cityConfirm (id) {
  Notiflix.Confirm.Show(
    'Attention',
    'Voulez vous vraiment supprimer ce site ?' +
    'Toutes les sorties de cette ville seront supprimmer également.',
    'Oui',
    'Non',
    function () {
      $('#form_delete_city' + id).submit()
    }
  )
}

function userConfirm (id) {
  Notiflix.Confirm.Show(
    'Attention',
    'Voulez vous vraiment supprimer cet Utilisateur ?',
    'Oui',
    'Non',
    function () {
      $('#form_delete_user' + id).submit()
    }
  )
}

function notifyError (message) {
  Notiflix.Notify.Failure(message)
}
