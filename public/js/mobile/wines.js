function plusMinusBottle($elem, plusAction) {
    let $card = $elem.closest('.card-quantity');
    let id = $card.data('id');

    $.post('/vins/plus-bouteille', {id: id, plus: plusAction}, data => {
        $card.html(data);
    });
}