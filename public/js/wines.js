let tableWines;

$(function() {
    $('#isMobile').val(isMobile);

    $.post('/get-navbar', {isMobile: isMobile()}, (resp) => {
        $('#navbar').html(resp);
        initFilters(tableWines);
    });
});

if (!isMobile()) {
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
} else {
    $.get('/vins/mobile', function(data) {
        $('#wines-content').html(data.list);
        $('#filters').html(data.filters);
    });
}

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