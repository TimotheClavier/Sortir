$(document).ready(function () {

  var filtre = []

  $('select').addClass('browser-default custom-select')
  $('[data-toggle="popover-hover"]').popover({
    html: true,
    trigger: 'hover',
    placement: 'bottom',
    content: function () {
      return '<img src="' + $(this).data('img') + '" />'
    }
  })
  $('#data-table-place').DataTable()
  $('#data-table-city').DataTable()
  $('.dataTables_length').addClass('bs-select')

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
    position:'right-top',
    distance: '15%'
  })
  Notiflix.Confirm.Init({})
  Notiflix.Loading.Init({});

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

  $('#selectVille').on('change', function () {
    var nb = $('#selectVille option:selected').attr('id')
    var entryPlaces = $('[data-entry-place]')
    var entryElements = $('[data-entry-info]')

    var entryPlace =
      $.map(entryPlaces, item => $(item).data('entryPlace'))
    var entryIds =
      $.map(entryElements, item => $(item).data('entryInfo'))

    entryPlace.forEach(function (val, ind) {
      if (val.city == nb) {
        entryIds.forEach(function (value, index) {
          var res = ''
          if (val.id != value.place_id) {
            res = 'none'
          } else {
            res = ''
          }
          var id = '#' + value.id
          $(id).css('display', res)
        })

      } else if (nb == 'reset') {
        entryIds.forEach(function (value, index) {
          var id = '#' + value.id
          $(id).css('display', '')

        })
      }

    })

  })

  $('.checkfiltre').on('click', function () {
    var entryElements = $('[data-entry-info]')
    var userElements = $('[data-entry-user]')
    var user = $('[data-entry-us]')

    var entryIds =
      $.map(entryElements, item => $(item).data('entryInfo'))

    var userIds =
      $.map(userElements, item => $(item).data('entryUser'))

    var userId =
      $.map(user, item => $(item).data('entryUs'))

    checkFiltre(this, entryIds, userIds, userId)
  })

  function checkFiltre (elem, trips, userIds, user) {

    var idReq = ''

    if ($(elem).is(':checked')) {
      filtre[elem.name] = elem.name

    } else {
      filtre[elem.name] = ''

    }

    filtre.forEach(function (value, index,) {
      if (value !== '') {
        idReq = idReq + value
      }

    })

    switch (idReq) {
      case '':
        trips.forEach(function (value, index) {

          var id = '#' + value.id
          $(id).css('display', '')

        })
        break
      //sorti ou je suis orga
      case '1':
        trips.forEach(function (value, index) {
          if (value.organizer != user[index].id) {
            var id = '#' + value.id
            $(id).css('display', 'none')
          } else {
            var id = '#' + value.id
            $(id).css('display', '')
          }

        })
        break
      //sorti ou je suis inscrit
      case '2':
        trips.forEach(function (value, index) {
          if (userIds[index].idTrip != value.id) {

            var id = '#' + value.id
            $(id).css('display', 'none')
          } else {
            var id = '#' + value.id
            $(id).css('display', '')
          }

        })
        break
      case '3':
        trips.forEach(function (value, index) {
          if (userIds[index].idTrip == value.id) {

            var id = '#' + value.id
            $(id).css('display', 'none')
          } else {
            var id = '#' + value.id
            $(id).css('display', '')
          }

        })
        break
      case '4':
        trips.forEach(function (value, index) {
          if (value.sysdate > value.sortie) {
            var id = '#' + value.id
            $(id).css('display', 'none')
          } else {
            var id = '#' + value.id
            $(id).css('display', '')
          }

        })
        break
      case '12':
        trips.forEach(function (value, index) {
          if (value.organizer != user[index].id) {
            console.log(value.id)
            var id = '#' + value.id
            $(id).css('display', 'none')
          }
          if (userIds[index].idTrip != value.id) {
            console.log(value.id)
            var id = '#' + value.id
            $(id).css('display', 'none')
          }

        })
        break
      case '123':
        trips.forEach(function (value, index) {
          if (value.organizer != user[index].id) {
            var id = '#' + value.id
            $(id).css('display', 'none')
          } else {
            var id = '#' + value.id
            $(id).css('display', '')
          }

        })
        break
      case '124':
        trips.forEach(function (value, index) {
          if (value.organizer != user[index].id) {
            console.log(value.id)
            var id = '#' + value.id
            $(id).css('display', 'none')
          }
          if (userIds[index].idTrip != value.id) {
            console.log(value.id)
            var id = '#' + value.id
            $(id).css('display', 'none')
          }
          if (value.sysdate > value.sortie) {
            var id = '#' + value.id
            $(id).css('display', 'none')
          }

        })
        break
      case '1234':
        trips.forEach(function (value, index) {
          if (value.organizer != user[index].id) {
            console.log(value.id)
            var id = '#' + value.id
            $(id).css('display', 'none')
          }
          if (value.sysdate > value.sortie) {
            var id = '#' + value.id
            $(id).css('display', 'none')
          }

        })
        break
      case '13':
        trips.forEach(function (value, index) {
          if (value.organizer != user[index].id) {
            var id = '#' + value.id
            $(id).css('display', 'none')
          }
          if (userIds[index].idTrip == value.id) {

            var id = '#' + value.id
            $(id).css('display', 'none')
          }
        })
        break
      case '134':
        trips.forEach(function (value, index) {
          if (value.organizer != user[index].id) {
            console.log(value.id)
            var id = '#' + value.id
            $(id).css('display', 'none')
          }
          if (userIds[index].idTrip == value.id) {

            var id = '#' + value.id
            $(id).css('display', 'none')
          }
          if (value.sysdate > value.sortie) {
            var id = '#' + value.id
            $(id).css('display', 'none')
          }

        })
        break
      case '14':
        trips.forEach(function (value, index) {
          if (value.organizer != user[index].id) {
            console.log(value.id)
            var id = '#' + value.id
            $(id).css('display', 'none')
          }
          if (value.sysdate > value.sortie) {
            var id = '#' + value.id
            $(id).css('display', 'none')
          }

        })
        break
      case '23':
        trips.forEach(function (value, index) {

          var id = '#' + value.id
          $(id).css('display', '')

        })
        break
      case '234':
        trips.forEach(function (value, index) {
          if (value.sysdate > value.sortie) {
            var id = '#' + value.id
            $(id).css('display', 'none')
          } else {
            var id = '#' + value.id
            $(id).css('display', '')
          }

        })
        break
      case '24':
        trips.forEach(function (value, index) {
          if (userIds[index].idTrip != value.id) {
            console.log(value.id)
            var id = '#' + value.id
            $(id).css('display', 'none')
          }
          if (value.sysdate > value.sortie) {
            var id = '#' + value.id
            $(id).css('display', 'none')
          }

        })
        break
      case '34':
        trips.forEach(function (value, index) {
          if (userIds[index].idTrip == value.id) {

            var id = '#' + value.id
            $(id).css('display', 'none')
          }
          if (value.sysdate > value.sortie) {
            var id = '#' + value.id
            $(id).css('display', 'none')
          }

        })
        break

    }
  }
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

function placeConfirm () {
  Notiflix.Confirm.Show(
    'Attention',
    'Voulez vous vraiment supprimer ce site ?' +
    'Toutes les sorties sur ce site seront supprimmer également.',
    'Oui',
    'Non',
    function () {
      $('#form_delete_place').submit()
    }
  )
}

function cityConfirm () {
  Notiflix.Confirm.Show(
    'Attention',
    'Voulez vous vraiment supprimer ce site ?' +
    'Toutes les sorties de cette ville seront supprimmer également.',
    'Oui',
    'Non',
    function () {
      $('#form_delete_city').submit()
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




