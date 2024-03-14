/**
 * Created by stellar on 2016.10.14..
 */

var transactionUrl = $("#transaction-type-link").attr('value');

$("#from-storage").on('change', function () {
    $("#forward-button").attr('href', transactionUrl + '/' + this.value + '/' + $("#to-storage").val());
});

$("#to-storage").on('change', function () {
    $("#forward-button").attr('href', transactionUrl + '/' + $("#from-storage").val() + '/' + this.value);
});

