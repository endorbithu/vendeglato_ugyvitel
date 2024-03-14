/**
 * Created by stellar on 2016.10.31..
 */
$("form").append($("#ingr-transaction-fieldset"));
$("form").append($("#hiddenAmount"));
$('.modal-body').append($('[for="ingr-transaction-more-info"]'));
$('.modal-body').append($('#ingr-transaction-more-info'));
$('[for="ingr-transaction-more-info"]').attr('style', 'font-weight:normal');

$('#move-destination-list').hide();

$(".bulkaction").on('click', function () {
        $('#ingr-transaction-to-storage').attr('value', $(this).attr('data-more-info'));
        $('#move-destination-list').hide();
        //TODO: #169 csúnya:
        $('#ingr-transaction-ingr-transaction-type').attr('value', $(this).attr('data-url').split('/')[2]);
    }
);

$('#action-0').removeAttr('disabled');

$('.bulkaction').on('click', function () {
    $("#okButton").attr('value', 'Igen');
    $('.big-modal-text').text('');
});


$('#action-0').on('click', function () {
    if ($('#checkAll').prop('checked') == false) {
        $('#checkAll').trigger('click');
    }

    $('.big-modal-text').text($('#total-price').text());

    $("#okButton").attr('value', 'FIZETVE');
});

$('#action-1').on('click', function () {
    $('.big-modal-text').text($('#part-price').text());

    $("#okButton").attr('value', 'FIZETVE');
});


$('.datatable-toolbar').prepend($('#new-order'));

$('#action-4').on('click', function () {
    $('.big-modal-text').before($('#move-destination-list'));
    $('#move-destination-list').show();
});

var $moveUrl = $('#action-4').attr('data-url');
$('#destination-select').on('change', function () {
    $('#ingr-transaction-to-storage').attr('value', $(this).val());
    $('#destination-form').attr('action', $moveUrl + $(this).val());
});


$('#checkAll').on('change', function () {
    $('#dialogText').text('');
    countPrice();
});

$('.rowCheckbox').on('change', function () {
    countPrice();
});

$('#action-0').append($('#total-price'));
$('#action-0').removeClass('bulkaction');


$('#action-1').append($('#part-price'));

$('#ingr-transaction-more-info').addClass('width100');


function countPrice() {
    var $total = 0;
    $('.rowCheckbox').each(function () {
        if (this.checked) {
            $amount = $(this).parent().parent().parent().find('.order-item-price').val();
            $amountFloat = parseFloat($amount);
            $total = $total + $amountFloat; //TODO: #168 lapozós verzónál elf fog szállni
        }
    });
    $('#part-price-number').text(numberWithCommas($total));
}


function countTotalPrice() {
    var $total = 0;
    $('.rowCheckbox').each(function () {
        $amount = $(this).parent().parent().parent().find('.order-item-price').val();
        $amountFloat = parseFloat($amount);
        $total = $total + $amountFloat; //TODO: #168 lapozós verzónál elf fog szállni
        $('#part-price-number').text(numberWithCommas($total));
    });
    return $total;
}


function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
}




