/**
 * Created by stellar on 2016.11.01..
 */

$('.glyphicon').hide();
$('#to-storage').hide();

$('#to-storage').val($('#from-storage').val());
$('#from-storage').on('change', function () {
    $('#to-storage').val($(this).val());
});

