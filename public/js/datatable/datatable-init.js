/**
 * Created by stellar on 2016.09.21..
 */

//------------------------------------------------------
// Datatable engedélyezés
//------------------------------------------------------

var selectedId = [];
var table = $("#commonTable").DataTable({
    "language": {
        "url": "/js/datatables-lang/hu_HU.json"
    },
    "stateSave": true,
    "columnDefs": [{
        "width": "20px",
        "className": "text-center",
        "targets": 0,
        "searchable": false,
        "orderable": false,
        //"className": "dt-body-center",
        "render": function (data, type, full, meta) {
            return "<div class=\"coverCheckboxCont\">" +
                "<input type=\"checkbox\" class=\"rowCheckbox\" " +
                "value=\"" + $("<div/>").text(data).html() + "\" " +
                "id=\"cb" + $("<div/>").text(data).html().replace(/^\D+/g, "") + "\">" +
                "<div class=\"coverCheckbox\"></div></div>";
        }
    }],
    "order": [1, "asc"]
});
// Handle click on "Select all" control
$("#checkAll").on("click", function () {
    // Check/uncheck all checkboxes in the table
    var rows = table.rows({"search": "applied"}).nodes();
    $(".rowCheckbox", rows).prop("checked", this.checked);
    if(this.checked == true) {
        $(rows).addClass("selectedRow");
    } else {
        $(rows).removeClass("selectedRow");
    }
});

// Handle click on checkbox to set state of "Select all" control
$("#commonTable tbody").on("change", ".rowCheckbox", function () {
    // If checkbox is not checked
    if (!this.checked) {
        var el = $("#checkAll").get(0);
        // If "Select all" control is checked and has "indeterminate" property
        if (el && el.checked && ("indeterminate" in el)) {
            // Set visual state of "Select all" control
            // as "indeterminate"
            el.indeterminate = true;
        }
    }
});


//összes kijelölése
$("#checkAll").on("click", function (e) {
    var form = this;
    selectedId = [];

    // Iterate over all checkboxes in the table
    table.$(".rowCheckbox").each(function () {
        // If checkbox doesn"t exist in DOM
        if (!$.contains(document, this)) {
            // If checkbox is checked
            if (this.checked) {
                // Create a hidden element
                $(form).append(
                    $("<input>")
                        .attr("type", "hidden")
                        .attr("name", this.name)
                        .attr("class", "rowCheckbox")
                        .attr("id", "cb" + this.value)
                        .val(this.value)
                );
            }
        }
        var checkedStatus = this.checked;
        if (checkedStatus == true) {
            $(".bulkaction").removeAttr("disabled");
            $("#commonTable tbody").find("tr").addClass("selectedRow");
            selectedId.push($(this).val().replace(/^\D+/g, ""));



        } else {
            $("#commonTable tbody").find("tr").removeClass("selectedRow");
            $(".bulkaction").attr("disabled", "disabled");
            selectedId = [];


        }

    });

});


//Bulk action
//egy sorra kattintunk
$("#commonTable tbody").on("click", "tr", function () {
    $(".bulkaction").attr("disabled", "disabled");
    var data = table.row(this).data();
    var nr = data[0].replace(/^\D+/g, "");
    var checkBox = $(this).find(".rowCheckbox");
    checkBox.prop("checked", !(checkBox.prop("checked")));

    if (checkBox.is(":checked")) {
        $("#commonTable tbody").find("#tr" + nr).addClass("selectedRow");

        selectedId.push(checkBox.prop("value").replace(/^\D+/g, ""));
        var uniqueIds = [];
        $.each(selectedId, function (i, el) {
            if ($.inArray(el, uniqueIds) === -1) uniqueIds.push(el);
        });
        selectedId = uniqueIds;
    }
    else {
        $("#commonTable tbody").find("#tr" + nr).removeClass("selectedRow");

        var removeItem = checkBox.prop("value").replace(/^\D+/g, "");
        selectedId = jQuery.grep(selectedId, function (value) {
            return value != removeItem;
        });
    }

    if (selectedId.length > 0) {
        $(".bulkaction").removeAttr("disabled");
        $("#checkAll").prop("checked",false);
    }

});


//ha a bulkactionra kattintanak, akkor a tömbbe gyûjtött idket megcsináljuk urlnek
$(".bulkaction").on("click", function () {
    $(".bulkaction").attr("data-cid", $(".bulkaction").attr("data-url") + "?");

    $.each(selectedId, function (value) {
        $prefix = $(".bulkaction").attr("data-prefix");
        $(".bulkaction").attr("data-cid", $(".bulkaction").attr("data-cid") + $prefix + "=" + value + "&");
    });


});

var table = $("#tablaneve").DataTable({
    "language": {
        "url": "/js/datatables-lang/hu_HU.json"
    },
    "stateSave": true,
    "columnDefs": [{
        "width": "20px",
        "className": "text-center",
        "targets": 0,
        "searchable": false,
        "orderable": false,
        //"className": "dt-body-center",
        "render": function (data, type, full, meta) {
            return "<div class=\"coverCheckboxCont\">" +
                "<input type=\"checkbox\" class=\"rowCheckbox\" name=\"proba[]\" " +
                "value=\"" + $("<div/>").text(data).html() + "\" " +
                "id=\"cb" + $("<div/>").text(data).html().replace(/^\D+/g, "") + "\">" +
                "<div class=\"coverCheckbox\"></div></div>";
        }}],
    "order": [1, "asc"]
});


$(".pagination").on("click", function (e) {
    table.$(".extrainput").each(function () {
        // If checkbox doesn"t exist in DOM
        if (!$.contains(document, this)) {
            // Create a hidden element
            $(form).append(
                $("<input>")
                    .attr("type", "hidden")
                    .attr("name", this.name)
                    .attr("class", this.class)
                    .attr("id", this.id)
                    .val(this.value)
            );
        }
    });
});
