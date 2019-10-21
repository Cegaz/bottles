let tableHistoric = $('#table_historic').DataTable({
    ajax: '/vins/historique/datatable',
    language: {
       url: "/js/dataTableLanguage.json",
    },
    sorting: true,
    dom: 'lrtip',
    paging: false,
    responsive: true,
    info: false,
    columns: [
        {data: 'actions', title: ''},
        {data: 'color', title: 'couleur', name: 'color'},
        {data: 'origin', title: 'région', name: 'origin'},
        {data: 'year', title: 'millésime', name: 'year'},
        {data: 'name', title: 'vin'},
        {data: 'comment', title: 'commentaire'},
        {data: 'rate', title: 'note'}
    ]
});

$(function() {
    initFilters(tableHistoric);
});