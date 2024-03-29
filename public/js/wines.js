let tableWines;

$(function() {
    $.post('/get-navbar', (resp) => {
        $('#navbar').html(resp);
        initFilters(tableWines);
    });
});

tableWines = $('#table_bottles').DataTable({
    ajax: '/vins/datatable',
    rowId: 'id',
    language: {
        url: "/js/dataTableLanguage.json",
    },
    order: [[9, "desc"]],
    sorting: true,
    dom: 'rt',
    responsive: true,
    pageLength: 100,
    columns: [
        {data: 'actions', title: ''},
        {data: 'name', title: 'vin'},
        {data: 'color', title: 'couleur', name: 'color'},
        {data: 'origin', title: 'région', name: 'origin'},
        {data: 'year', title: 'millésime', name: 'year'},
        {data: 'dluo', title: 'date limite', name: 'dluo'},
        {data: 'comment', title: 'commentaire'},
        {data: 'rate', title: 'note'},
        {data: 'quantity', title: ''},
        {data: 'createdDate', name: 'createdDate'}
    ],
    columnDefs: [
        { orderable: false, targets: 0 },
        { visible: false, targets: 9 }
    ],
    paging: false,
    initComplete: function() {
        $('.search-box').on('keyup', function() {
            tableWines.search(this.value).draw();
        });
    }
});

function plusMinusBottle($elem, plusAction) {
    // pour résoudre pb modif DOM datatable responsive
    let $tr = $elem.closest('tr');
    if ($tr.hasClass('child')) {
        $tr = $tr.prev();
    }
    let id = tableWines.row($tr).id();

    $.post('/vins/plus-bouteille', {id: id, plus: plusAction}, data => {
        tableWines.ajax.reload();
    });
}