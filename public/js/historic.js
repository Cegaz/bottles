$(function() {
    initFilters(tableHistoric);
});

let tableHistoric = $('#table_historic').DataTable({
    ajax: '/vins/historique/datatable',
    rowId: 'id',
    language: {
       url: "/js/dataTableLanguage.json",
    },
    sorting: true,
    dom: 'rt',
    responsive: true,
    columns: [
        {data: 'actions', title: ''},
        {data: 'name', title: 'vin'},
        {data: 'color', title: 'couleur', name: 'color'},
        {data: 'origin', title: 'région', name: 'origin'},
        {data: 'year', title: 'millésime', name: 'year'},
        {data: 'comment', title: 'commentaire'},
        {data: 'rate', title: 'note'}
    ]
});