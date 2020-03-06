function applyFilters(table) {
  let area = $(".filter[name='area']").val();
  let year = $(".filter[name='year']").val();
  let dluo = $(".filter[name='dluo']").val();
  let $colors = $(".checkbox-checked");

  let colors = '';
  $colors.each(function(index, elem) {
    colors += $(elem).data('value') + '|';
  });
  colors = colors.slice(0,-1);

  table
      .columns('origin:name')
      .search(area)
      .columns('year:name')
      .search(year)
      .columns('dluo:name')
      .search(dluo)
      .columns('color:name')
      .search(colors, true, false)
      .draw();
}

function newWine() {
    $.post('/vins/creer', data => {
        $('#modalNewBottle').find('.modal-content').html(data);
    });
}

function editWine(wineId) {
    $.get('/vins/modifier/' + wineId, data => {
        $('#modalEditWine').find('.modal-content').html(data);
    });
}

function initFilters(table) {
    if (isMobile()) {
        $(".checkbox-color").on("click", function () {
            $(this).toggleClass('checkbox-checked');
            let $checkboxesChecked = $('.checkbox-checked');
            let colors = [];
            $checkboxesChecked.each(function() {
                colors.push($(this).data('value'));
            });
            let selectorToHide = '.wine-card';
            let selectorToDisplay = [];
            colors.forEach((elem) => {
                selectorToHide += '[data-color!="' + elem + '"]';
                selectorToDisplay.push('[data-color="' + elem + '"]');
            });

            if (colors.length > 0) {
                $(selectorToHide).addClass('d-none');
                $(selectorToDisplay.join(',')).removeClass('d-none');
            } else {
                $('.wine-card').removeClass('d-none');
            }
        });
    } else {
        $('.filter').on('change', function () {
            applyFilters(table);
        });

        $(".checkbox-color").on("click", function (e) {
            $(this).toggleClass('checkbox-checked');
            let $checkbox = $(this).find('input[type="checkbox"]');
            $checkbox.prop("checked", !$checkbox.prop("checked"));
            e.preventDefault();
            applyFilters(table);
        });
    }
}

function initSearchBox(table) {
  $('.search-box').keyup(function() {
    if (this.value.length == 0) {
      $(this).addClass('font-awesome');
    } else {
      $(this).removeClass('font-awesome');
    }
    table.search(this.value).draw();
  });
}
  
function addRemoveStar($star, plusAction) {
    let table = $star.closest('table').DataTable();

      // pour résoudre pb modif DOM datatable responsive
    let $tr = $star.closest('tr');
    if ($tr.hasClass('child')) {
      $tr = $tr.prev();
    }
    let id = table.row($tr).id();

    $.post('/vins/plus-etoile', {id: id, plus: plusAction}, data => {
        $star.closest('td').html(data);
        table.ajax.reload();
    });
}

function isMobile() {
  const toMatch = [
    /Android/i,
    /webOS/i,
    /iPhone/i,
    /iPad/i,
    /iPod/i,
    /BlackBerry/i,
    /Windows Phone/i
  ];

  return toMatch.some((toMatchItem) => {
    return navigator.userAgent.match(toMatchItem);
  });
}