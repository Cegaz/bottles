let tableWines;

$(function() {
    $.post('/get-navbar', {isMobile: isMobile()}, (resp) => {
        $('#navbar').html(resp);
        initFilters(tableWines);
    });
});

// $.get('/vins/mobile', function(data) {
//     $('#wines-content').html(data.list);
//     $('#filters').html(data.filters);
// });

function plusMinusBottle($elem, plusAction) {
    let $card = $elem.closest('.card-quantity');
    let id = $card.data('id');

    $.post('/vins/plus-bouteille', {id: id, plus: plusAction}, data => {
        $card.html(data);
    });
}