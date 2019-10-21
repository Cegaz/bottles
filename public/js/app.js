function applyFilters(table) {
  let area = $(".filter[name='area']").val();
      let year = $(".filter[name='year']").val();
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
    $.post('/vins/modifier', {id: wineId}, data => {
        $('#modalEditWine').find('.modal-content').html(data);
    });
}

function initFilters(table) {
    $('.filter').on('change', function() {
        applyFilters(table);
    });

    $(".checkbox-color").on("click", function (e) {
        $(this).toggleClass('checkbox-checked');
        let $checkbox = $(this).find('input[type="checkbox"]');
        $checkbox.prop("checked",!$checkbox.prop("checked"));
        e.preventDefault();
        applyFilters(table);
    });
}