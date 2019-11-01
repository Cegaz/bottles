$(function() {
    initFilters(table);
});

let table = $('#table_bottles').DataTable({
    ajax: '/vins/datatable',
    rowId: 'id',
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

function plusMinusBottle($elem, plusAction) {
    let id = table.row($elem.closest('tr')).id();
    $.post('/vins/plus-bouteille', {id: id, plus: plusAction}, data => {
        $elem.closest('.bottles-quantity').html(data);
        table.ajax.reload();
    });
}