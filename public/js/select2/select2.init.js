/**
 * Created by stellar on 2016.09.21..
 */


$('.select2').each(function () {
    var $hasSelected = false;
    $("option", this).each(function () {
        if ($(this).attr('selected') == 'selected') {
            $hasSelected = true;
        }
    });


    if ($hasSelected == false) {
        $(this).val("");
    }

});

$('.select2').prepend('<option class="empty-option" value=""></option>');

$('.select2').select2({
    placeholder: "...",
    allowClear: true,
    width: 'resolve',
    theme: "bootstrap"
});




