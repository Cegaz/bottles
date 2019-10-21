 let tableWines = $('#table_bottles').DataTable({
    ajax: '/vins/datatable',
    language: {
       url: "/js/dataTableLanguage.json",
    },
    order: [[4, "asc"]],
    sorting: true,
    dom: 'lrtip',
    paging: false,
    responsive: true,
    info: false,
    columns: [
       {data: 'actions', title: ''},
       {data: 'name', title: 'vin'},
       {data: 'color', title: 'couleur', name: 'color'},
       {data: 'origin', title: 'région', name: 'origin'},
       {data: 'year', title: 'millésime', name: 'year'},
       {data: 'dluo', title: 'date limite'},
       {data: 'comment', title: 'commentaire'},
       {data: 'rate', title: 'note'},
       {data: 'quantity', title: ''},
    ]
 });

function plusMinusBottle($elem, wineId, plusAction) {
    $.post('/vins/plus-bouteille', {id: wineId, plus: plusAction}, data => {
        $elem.closest('.bottles-quantity').html(data);
        tableWines.ajax.reload();
    });
}

 $(function() {
     initFilters(tableWines);
 });