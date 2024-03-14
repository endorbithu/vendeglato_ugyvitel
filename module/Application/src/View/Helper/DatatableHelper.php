<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.22.
 * Time: 4:52
 */

namespace Application\View\Helper;


use Application\Model\RetrieveByDatatableModel;
use Zend\View\Helper\AbstractHelper;

/**
 * Ez az osztály csak úgy működik, hogyha az első oszlopban van a numerikus! ID-ja az adott rekordnak.
 * Class DatatableHelper
 * @package Application\View\Helper
 */
class DatatableHelper extends AbstractHelper
{
    const SELECT = 'select';
    const CHECKBOX = 'checkbox';

    private $tableIdName;
    private $headers;
    private $datas;
    private $orderColumn;
    private $orderColumnDirection;
    private $checkbox; //a setSelectable('checkbox')-nál lesz true
    private $select; //a setSelectable('select')-nél lesz true
    private $selectedRows = array(); //előre megadhatjuk melyiket Xszelje be /jelenítse meg
    private $actions = array(); //actionök, ehhez gombok is társulnak meg form is
    private $extraInputs = array(); //addinputtal megadott inputok
    private $naked; //ha a csupasz táblázatra van csak szükségünk
    private $form = true; //bool legyen-e form vagy csak mezők


    /**
     *render
     */
    public function render()
    {
        $render = '<div id="' . $this->tableIdName . '-datatableblock">';

        $render .= ((!empty($this->checkbox) || !empty($this->extraInputs) || !empty($this->select)) && $this->form) ?
            '<form id="' . $this->tableIdName . '-form" method="post">' : '';

        $render .= '<div class="datatable-toolbar">';
        foreach ($this->actions as $action) $render .= $action;
        $render .= '</div>';

        $render .= (!empty($this->select)) ? '<div>' . $this->getSelect2Field() . '</div>' : "";

        $render .= '<div id="' . $this->tableIdName . '-relative" style="position: relative;">';
        $render .= '<div id="' . $this->tableIdName . '-filler" class="datatable-screen-filler">';

        $render .= '
        <table id="' . $this->tableIdName . '" class="dataTable table table-bordered table-hover width100">
            <thead class="font-small-bold">
            <tr>';

        $render .= $this->getHeader();

        $render .= '
            </tr>
            </thead>
            <tbody class="font-small-regular">';

        $render .= (empty($this->select)) ? $this->getRow() : "";
        $render .= '</tbody></table>';

        $render .= '</div>';
        $render .= '</div>';

        $render .= '<div id="hiddeninputs">';
        $render .= (!empty($this->extraInputs)) ? $this->renderInputHidden() : '';
        $render .= (!empty($this->selectedRows)) ? $this->renderSelectedHidden() : '';
        $render .= '</div>';


        $render .= $this->getJavascriptBlock();
        $render .= $this->getModalPopup();


        $render .= ((!empty($this->checkbox) || !empty($this->extraInputs) || !empty($this->select)) && $this->form) ?
            '</form>' : '';

        $render .= '</div>';

        return $render;
    }


    public function renderFromModel(RetrieveByDatatableModel $model)
    {

        $this->initTable($model->getName(), $model->getHeader(), $model->getData(), $model->getOrderColumn(), $model->getOrderColumnDir());

        if (!empty($model->getSelectable())) $this->addSelectable($model->getSelectable());
        if (!empty($model->getSelectedRow())) $this->addSelectedRow($model->getSelectedRow());
        if (!empty($model->getAction())) $this->addAction($model->getAction());
        if (!empty($model->getInput())) $this->addInput($model->getInput());
        if (!empty($model->getNaked())) $this->setNaked($model->getNaked());

        return $this->render();

    }


    /**
     * @param $tableIdName -> <table id=
     * @param array $headers (header címkék)
     * @param array $datas -> adatok (két dimenziós tömb)
     * @param null $orderColumn -> melyik oszlop szerint rendezze
     */
    public function initTable($tableIdName, array $headers, array $datas, $orderColumn = null, $orderColumnDirection = null)
    {
        $this->view->headLink()->prependStylesheet($this->view->basePath() . '/css/datatable/dataTables.bootstrap.css');
        $this->view->headScript()
            ->prependFile($this->view->basePath('/js/datatable/dataTables.bootstrap.js'))
            ->prependFile($this->view->basePath('/js/datatable/jquery.dataTables.min.js'));

        $this->headers = $headers;
        $this->datas = $datas;
        $this->orderColumn = (empty($orderColumn)) ? '1' : $orderColumn;
        $this->orderColumnDirection = (empty($orderColumnDirection)) ? 'asc' : $orderColumnDirection;
        $this->tableIdName = $tableIdName;
    }


    public function addSelectable($selectableType)
    {
        if (!empty($selectableType))
            $this->$selectableType = true;
    }


    public function noFormTag()
    {
        $this->form = false;
    }

    /**
     * @param array $actions
     */
    public function addAction($actions = [])
    {

        foreach ($actions as $action) {

            if (!array_key_exists('moreInfo', $action)) $action['moreInfo'] = '';

            $actionHtml = '
        <a class="btn btn-sm btn-default bulkaction"
           id="action-' . (string)count($this->actions) . '"
           data-title="' . $action['name'] . '"
           data-url="' . $action['actionUrl'] . '"
           data-warning-text="' . $action['warningText'] . '"
           data-more-info="' . $action['moreInfo'] . '"
            data-keyboard="true"
            data-toggle="modal"
            data-cid=""
            data-cname=""
            href="#Modal"
                >
            <span class="glyphicon glyphicon-' . $action['icon'] . '"></span>
            ' . $action['name'] . '
        </a>';

            $this->actions[] = $actionHtml;
        }
    }


    /**
     * @param array $inputs
     */

    //public function addInput($inputType, $inputName, $whichColumn, $whichColumnIsValue = null)
    public function addInput($inputs = [])
    {
        foreach ($inputs as $input) {
            $this->extraInputs[$input['whichColumn']] = $input;
        }
        return;
    }


    public function addSelectedRow($selectedRows = [])
    {
        foreach ($selectedRows as $selectedRow) {
            $this->selectedRows[$selectedRow] = $selectedRow;
        }

        return;
    }


    public function setNaked()
    {
        $this->naked = true;
    }




    //----------------------------------------------------------------------------
    //---- SEGÉDFÜGGVÉNYEK  ------------------------------------------------------
    //----------------------------------------------------------------------------


    protected function getExtraInput($whichColumn, $id, $valueFromColumn = "")
    {
        $input = $this->extraInputs[$whichColumn];

        //TODO: #133 tesztelni
        if ($input['inputType'] == "select") {
            $extraInput = '<select name="' . $input['inputName'] . '[' . $id . ']" id="in-' . $whichColumn . $id . '" class="select2 extrainput">';
            if (!empty($input['value'])) {
                foreach ($input['value'] as $opt) $extraInput .= '<option value="' . $opt[0] . '">' . strip_tags($opt[1]) . '</option>';
            }
            $extraInput .= '</select>';

            return $extraInput;

        }

        $extraInput = '<input type="' . $input['inputType'] . '"id="in-' . $whichColumn . '-' . $id . '" step="0.01" class="form-control extrainput" value="' . $valueFromColumn . '" >';
        return $extraInput;

    }


    /**
     * A datatable miatt kell hidden inputokat is generálni
     * @param $whichColumn
     * @param $id
     * @return string
     */
    protected function getInputHidden($whichColumn, $id, $valueFromColumn = "")
    {
        $input = $this->extraInputs[$whichColumn];
        $extraInput = '<input name="' . $input['inputName'] . '[' . $id . ']" type="hidden" id="hin-' . $whichColumn . '-' . $id . '"class="extrainputhidden eih-' . $id . '" value="' . $valueFromColumn . '"  >';
        return $extraInput;
    }


    protected function renderInputHidden()
    {
        $hiddenRend = '';
        foreach ($this->datas as $key => $row) {
            foreach ($this->headers as $key2 => $column) {
                if (array_key_exists($key2, $this->extraInputs)) {
                    $valueFromColumn = null;
                    if (!empty($this->extraInputs[$key2]['valueColumn'])) {
                        $valueFromColumn = $row[$this->extraInputs[$key2]['valueColumn']];
                    }
                    $hiddenRend .= $this->getInputHidden($key2, $row[0], $valueFromColumn);
                }
            }
        }
        return $hiddenRend;
    }


    protected function renderSelectedHidden()
    {

        $selectedHidden = '';
        foreach ($this->selectedRows as $key => $val) {
            $selectedHidden .= '<input type = "hidden" name = "' . $this->tableIdName . '[]" class="cbhidden" id = "hcb' . $key . '" value = "' . $val . '" >';
        }

        return $selectedHidden;
    }

    protected function getHeader()
    {
        $headerTh = '';
        if (!empty($this->checkbox || !empty($this->select))) {
            $headerTh .= '
            <td  class="text-center width20 ">';
            if (!empty($this->checkbox)) $headerTh .=
                '<input name="select_all"  value="1" id="checkAll" type="checkbox">';
            $headerTh .=
                '</td>';
        }

        foreach ($this->headers as $key => $thField) {
            $headerTh .= '<th class="nowrap">';
            $headerTh .= (!($key == 1)) ? '' : '<a class="toggle-vis" data-column="' . $this->getIdColumn() . '" href="#">(ID)</a> ';
            $headerTh .= $this->view->translate($thField) . '</th>';
        }
        return $headerTh;
    }


    protected function getRowData()
    {
        $rowDatas = [];
        foreach ($this->datas as $key => $row) {

            if (!empty($this->checkbox)) {
                $rowDatas[$key][] = $row[0];
            }

            for ($i = 0; $i < count($this->headers); $i++) {

                if (array_key_exists($i, $this->extraInputs)) {
                    //ha a para,éterbe megadtuk, hogy az X-dik oszlop értékével töltse fel,
                    //az értékadó oszlop az inpput után legyen
                    $valueFromColumn = null;
                    if (!empty($this->extraInputs[$i]['valueColumn'])) {
                        $valueFromColumn = $row[$this->extraInputs[$i]['valueColumn']];
                    }
                    $rowDatas[$key][] = $this->getExtraInput($i, $row[0], $valueFromColumn);
                }

                //ha olyan oszlopnűl tartunk aminek az értékét beszúrtuk az inputba akkor continue
                if (array_key_exists($i, $this->extraInputs)
                    && !empty($this->extraInputs[$i]['valueColumn'])
                ) {
                    continue;
                }

                if (array_key_exists($i, $row)) {
                    $rowDatas[$key][] = $row[$i];
                }
            }

        }

        return $rowDatas;
    }


    protected function getRow()
    {
        $rowDatas = $this->getRowData();
        $trRend = '';

        foreach ($rowDatas as $rowKey => $rowValues) {
            //$trRend .= '<tr id="tr' . $rowValues[0] . '">';
            $trRend .= '<tr id="tr' . $rowValues[0] . '">';
            foreach ($rowValues as $columnKey => $columnValue) {
                $trRend .= '<td class="nowrap">';
                $trRend .= $rowDatas[$rowKey][$columnKey];
                $trRend .= '</td>';
            }

            $trRend .= '</tr>';
        }

        return $trRend;

    }


    protected function getSelect2Field()
    {
        $this->view->headScript()->prependFile($this->view->basePath('js/select2/select2.min.js'));
        $this->view->inlineScript()->appendFile($this->view->basePath('/js/select2/select2.init.js'));

        $columnsContent = $this->getRowData();

        $selectField = '<select id="selectrow" class="select2 '. $this->tableIdName .'-select-row">';
        foreach ($columnsContent as $rowKey => $val) {
            $selectField .= '<option class="add-opt-row" id="row-opt' . $val[0] . '"';
            for ($i = 1; $i < count($val); $i++) {
                $selectField .= 'data-' . $i . '="' . htmlspecialchars($columnsContent[$rowKey][$i]) . '" ';
            }
            $selectField .= ' >' . strip_tags($val[1]) . '</option>';
        }
        $selectField .= '</select>';

        return $selectField;

    }


    protected function getIdColumn()
    {
        return (!empty($this->checkbox) || !empty($this->select)) ? '1' : '0';
    }


    protected function getJavascriptBlock()
    {

        $datatableInit = '<script>
    ////////////////////////////////////////////////////////////////////////////////
    // ALAP DATATABLE KONFIGURÁLÁS
    ////////////////////////////////////////////////////////////////////////////////
        var form = $("#' . $this->tableIdName . '-form","#' . $this->tableIdName . '-datatableblock");
        var selectedId = [];
        var table' . $this->tableIdName . ' = $(" #' . $this->tableIdName . '","#' . $this->tableIdName . '-datatableblock").DataTable({
            "language": {
                "url": "/js/datatables-lang/' . $this->view->translate('hu_HU') . '.json"
            },
            ';
        $datatableInit .= !empty($this->naked) ? '
             "paging":   false,
             "ordering": false,
             "info":     false,
             "searching": false, ' : ' ';

        $datatableInit .= !empty($this->select) ? '
             "paging":   false,
             "ordering": false,
             "info":     false,
             "searching": false, ' : ' ';

        $datatableInit .= '
            "stateSave": false,
            "columnDefs": ';

        //ha a checkbox be van állítva akkor configoljuk be az oszlopát
        $datatableInit .= !empty($this->checkbox) ? '
                [{
                "width": "20px",
                "className": "text-center",
                "targets": 0,
                "searchable": false,
                "orderable": false,
                //"className": "dt-body-center",
                "render": function (data, type, full, meta) {
                    return "<div class=\\"coverCheckboxCont\\">" +
                        "<input type=\\"checkbox\" class=\\"rowCheckbox\\" nameforhidden=\\"' . $this->tableIdName . '[]\\" " +
                        "value=\"" + $("<div/>").text(data).html() + "\" " +
                        "id=\"cb" + $("<div/>").text(data).html().replace(/^\D+/g, "") + "\">" +
                        "<!--<div class=\\"coverCheckbox\\"></div>--></div>";
                }}],' : '[], ';

        //TODO: #134 valamiért nem jó a sorting
        $datatableInit .= '
            "order": [[' . $this->orderColumn . ',"' . $this->orderColumnDirection . '"]]
        });

        '; //ALAP DATATABLE KONFIGURÁLÁS VÉGE


        //** HA VAN EXTRAINPUT MEGADVA */
        if (!empty($this->extraInputs)) {
            $datatableInit .= '

             $( " #' . $this->tableIdName . ' tbody","#' . $this->tableIdName . '-datatableblock" ).on( "change", ".extrainput", function() {
                $("#h" +this.id,"#' . $this->tableIdName . '-datatableblock").attr("value", this.value);
                if( $(" #h"+ this.id,"#' . $this->tableIdName . '-datatableblock").attr("value") != this.value )
                {
                    $("#h"+ this.id,"#' . $this->tableIdName . '-datatableblock").attr("id")
                       alert("' . $this->view->translate("Hiba az oldal futása közben!") . '");
                       location.reload();
                }


            });

            ';
        }


        ////////////////////////////////////////////////////////////////////////////////
        //Selectes cuccok ez is csak úgy működik, ha az első oszlop az ID!!!!!!!!!!!!
        ////////////////////////////////////////////////////////////////////////////////
        //TODO: #135 megoldani, hogyha kiXszelnek egy sort ne az első oldalra ugorjon
        if (!empty($this->select)) {
            $datatableInit .= '



        // ** SELECTES Select2 nél, ha az X-re kattintasz, akkor eltűnik a sor és a hidden input value törlődik
        $("#' . $this->tableIdName . ' tbody","#' . $this->tableIdName . '-datatableblock").on( "click", ".icon-deleterow", function () {

       // $(this).parents("tr").find(".extrainput").attr("value","");

            table' . $this->tableIdName . '
            .row( $(this).parents("tr") )
            .remove()
            .draw();

        var id = $(this).attr("id").replace(/^\D+/g, "");

        $(".eih-"+id,"#' . $this->tableIdName . '-datatableblock").attr("value","");
        $("#hcb"+id,"#' . $this->tableIdName . '-datatableblock").remove();

        } );





        // ** SELECTES a megadott selected sorokat, (amiket hiddenbe kiteszünk) létrehozza a táblázatban
        $(".cbhidden","#' . $this->tableIdName . '-datatableblock").each(function () {
            id = this.id.replace(/^\D+/g, "");
            attrDatas = new Array();';

            for ($i = 1; $i < count($this->headers); $i++) {
                $datatableInit .= 'attrDatas[' . $i . '] = $("option#row-opt"+id, "#selectrow","#' . $this->tableIdName . '-datatableblock").attr("data-' . $i . '"); ';

            }

            $datatableInit .= '
            addRowFromSelect(id, attrDatas, true);

        });






        // ** SELECTES ha az optionre kattintok, akkor hozzádja a sorhoz
        $("#selectrow","#' . $this->tableIdName . '-datatableblock").on("change",function() {



             var id = $("option:selected", this).attr("id").replace(/^\D+/g, ""); ';
            $datatableInit .= '
             var attrDatas = new Array();

             ';
            for ($i = 1; $i < count($this->headers); $i++) {
                $datatableInit .= 'attrDatas[' . $i . '] = $("option:selected", this).attr("data-' . $i . '");
                '; //ide a paraméterben megadott tömb elemei lesznek
            }


            $datatableInit .= '
            addRowFromSelect(id, attrDatas);

             //TODO: szutyvákolás:  a sorban lévő összes input value-ját átmásolja a hiddenbe is
            $(".extrainput","#' . $this->tableIdName . '-datatableblock").each(function(){
                $("#h"+this.id,"#' . $this->tableIdName . '-datatableblock").attr("value",this.value);
            });


        });







        // ** SELECTES row dinamikus hozzáadása fg, erre a selectRow change, és az előre betöltött input hiddenek hivatkoznak
        function addRowFromSelect(id, attrDatas, ignoreHidden) {

            //TODO: valahogy megoldani, hogyha alapból az x-ik optionön van a select és újra rákattintunk, akkor is reagáljon
            //ha már be van szúrva akkor ne hozzon létre újat, ezt úgy tudjuk ellenőrini, hogy a checkboxnál is
            //hasnálatos hcb hidden input létezik-e vagy nem, a checkbox bepipálása helyett a sor létrehozásával érjk ezt el

            if ($("#hcb" + id,"#' . $this->tableIdName . '-datatableblock").length && ignoreHidden != true) {
                return;
            }

            ';

            for ($i = 1; $i < count($this->headers); $i++) {
                $datatableInit .= 'var data' . $i . ' = attrDatas[' . $i . ']; '; //ide a paraméterben megadott tömb elemei lesznek
            }

            //TODO: #136 beállítani, hogy az új elemet mindig az első sorba szúrja be
            $datatableInit .= '
            //a value-t tartalmazó hidden inputot behozzuk a formba, úgy hogy beállítjuk a name értékét
            $(".eih-"+id,"#' . $this->tableIdName . '-datatableblock").each(function () {
                $(this).attr("name", $(this).attr("inputname")) ;
             });


            //ezt php foreachel szépen kitöltjük a datas array alapján
            table' . $this->tableIdName . '.row.add([
                "<a class=\"btn btn-sm btn-default icon-deleterow\" id=\"row-" + id + " \">" +
                "<span class=\"glyphicon glyphicon-remove \"> </span></a>",
                id';

            for ($i = 1; $i < count($this->headers); $i++) {
                $datatableInit .= ' , data' . $i;
            }

            $datatableInit .= '
            ]).draw(false);


         //ha betöltéskor már megvolt a hidden input csinálva akkor nem kell újat
         if (ignoreHidden == true) {
            return;
         }

            $("#hiddeninputs","#' . $this->tableIdName . '-datatableblock").append(
                $("<input>")
                .attr("type", "hidden")
                .attr("name", "' . $this->tableIdName . '[]")
                .attr("class", "cbhidden")
                .attr("id", "hcb" + id)
                .val(id)
            );



        }';

        } //Selectes cuccok vége


////////////////////////////////////////////////////////////////////////////////
        //Chckboxos cuccok csak úgy működik, ha az első oszlop az ID!!!!!!!!!!!!
////////////////////////////////////////////////////////////////////////////////

        if (!empty($this->checkbox)) {

            $datatableInit .= '
        $(".bulkaction","#' . $this->tableIdName . '-datatableblock").attr("disabled", "disabled");

        // ** CHECKBOXOS select_allra pebipáltatjuk és beszinezzük az összes checkboxot
        $("#checkAll","#' . $this->tableIdName . '-datatableblock").on("click", function () {

            // Check/uncheck all checkboxes in the table
            var rows = table' . $this->tableIdName . '.rows({"search": "applied"}).nodes();
            $(".rowCheckbox", rows,"#' . $this->tableIdName . '-datatableblock").prop("checked", this.checked);
            if(this.checked == true) {
                $(rows).addClass("selectedRow");
            } else {
                $(rows).removeClass("selectedRow");
            }

        });



        // ** CHECKBOXOS  click on checkbox to set state of "Select all" control
        $("#' . $this->tableIdName . ' tbody","#' . $this->tableIdName . '-datatableblock").on("change", ".rowCheckbox", function () {
            // If checkbox is not checked

            if (!this.checked) {
                var el = $("#checkAll","#' . $this->tableIdName . '-datatableblock").get(0);
                // If "Select all" control is checked and has "indeterminate" property
                if (el && el.checked && ("indeterminate" in el)) {
                    // Set visual state of "Select all" control
                    // as "indeterminate"
                    el.indeterminate = true;
                }
            }

        });





        // ** CHECKBOXOS  Ha az egyikr kattintasz akkor hozza létre/törölje a hozzá tartozó hidden inputot szinezzen stb
        $(".rowCheckbox","#' . $this->tableIdName . '-datatableblock").on("click", function () {

             $(".bulkaction","#' . $this->tableIdName . '-datatableblock").attr("disabled", "disabled");
             var form = $("#' . $this->tableIdName . '-form","#' . $this->tableIdName . '-datatableblock");
            if((this.checked))
            {

             $("#' . $this->tableIdName . ' tbody","#' . $this->tableIdName . '-datatableblock").find("#tr" + $(this).attr("id").replace(/^\D+/g, "")).addClass("selectedRow");

                $(form).append(
                $("<input>")
                    .attr("type", "hidden")
                    .attr("name", $(this).attr("nameforhidden"))
                    .attr("class", "cbhidden")
                    .attr("id", "hcb" + this.value)
                    .val(this.value)
                );
            }
            else {
            $("#' . $this->tableIdName . ' tbody","#' . $this->tableIdName . '-datatableblock").find("#tr" + $(this).attr("id").replace(/^\D+/g, "")).removeClass("selectedRow");
                $("#h" + this.id,"#' . $this->tableIdName . '-datatableblock").remove();
            }
            if($(".cbhidden","#' . $this->tableIdName . '-datatableblock").length > 0) {
                $(".bulkaction","#' . $this->tableIdName . '-datatableblock").removeAttr("disabled");
            }
             //ha nincs semmi javascriptes gubanc, akkor jelenjen meg a bulk action

        });




        // ** CHECKBOXOS Összes kijelölésnél az összes checkbox hidden inutját megcsináljuk/töröljük
        $("#checkAll","#' . $this->tableIdName . '-datatableblock").on("click", function (e) {

            var form = $("#' . $this->tableIdName . '-form","#' . $this->tableIdName . '-datatableblock");
            selectedId = [];

            //először töröljön ki minden hiddent
             $(".cbhidden","#' . $this->tableIdName . '-datatableblock").each(function () { this.remove(); });

            // Iterate over all checkboxes in the table
            table' . $this->tableIdName . '.$(".rowCheckbox").each(function () {
                // If checkbox doesn"t exist in DOM
               // if (!$.contains(document, this)) {
                    //ha checkall pipás akkor meg csinálja meg (újra) az összes hidden elementet
                    // If checkbox is checked
                    if (this.checked) {
                        // Create a hidden element
                        $(form).append(
                            $("<input>")
                                .attr("type", "hidden")
                                .attr("name", $(this).attr("nameforhidden"))
                                .attr("class", "cbhidden")
                                .attr("id", "hcb" + this.value)
                                .val(this.value)
                        );
                    }
                //}

                //szinezés ilyenek
                var checkedStatus = this.checked;
                if (checkedStatus == true) {
                    $(".bulkaction","#' . $this->tableIdName . '-datatableblock").removeAttr("disabled");
                    $("#' . $this->tableIdName . ' tbody","#' . $this->tableIdName . '-datatableblock").find("tr").addClass("selectedRow");
                    selectedId.push($(this).val().replace(/^\D+/g, ""));
                } else {
                    $("#' . $this->tableIdName . ' tbody","#' . $this->tableIdName . '-datatableblock").find("tr").removeClass("selectedRow");
                    $(".bulkaction","#' . $this->tableIdName . '-datatableblock").attr("disabled", "disabled");
                    selectedId = [];
                }
            });

        });




        //** CHECKBOXOS Átcsekkoljuk, hogy a hidden input és a checkboxok ugyanazt az értéket képviselik:
        $(".bulkaction","#' . $this->tableIdName . '-datatableblock").on("click", function () {

           table' . $this->tableIdName . '.$(".rowCheckbox").each(function () {
            if(this.checked == true) {
                if(this.value != $("#h"+this.id).attr("value"))
                    alert("' . $this->view->translate('Hiba az oldal feldolgozása során, frissítse újra az oldalt!') . '");
            }
           });

            $(".cbhidden","#' . $this->tableIdName . '-datatableblock").each(function () {
                if( $("#cb"+ this.id.replace(/^\D+/g, ""),"#' . $this->tableIdName . '-datatableblock").checked != true)
                {
                    //console.log($("#cb"+ this.id.replace(/^\D+/g, "")).attr("id"));
                }
            });

        });

        ';
        } //Checkboxos cuccok vége


        $datatableInit .= '

        // ** ID OSZLOP kibe kapcsolása
        var column' . $this->tableIdName . ' = table' . $this->tableIdName . '.column(' . $this->getIdColumn() . ');
        column' . $this->tableIdName . '.visible( ! column' . $this->tableIdName . '.visible() );

        $("a.toggle-vis","#' . $this->tableIdName . '-datatableblock").on( "click", function (e) {
            e.preventDefault();

           column' . $this->tableIdName . '.visible( ! column' . $this->tableIdName . '.visible() );
         } );



        // ** DESIGN: AMIKOR MOBIL NÉZET VAN AKKOR AZ ABSOLUT UTÁN AKKORÁNAK KELL LENNIE A MARGIN-TOPNAK MINT AMILYEN MAGAS A TABLE

        if( $("#' . $this->tableIdName . '-filler").css("position") == "absolute") {
            $tableHeight = $("#' . $this->tableIdName . '").height() + 50;
            $("#' . $this->tableIdName . '-relative").css("height", $tableHeight);
        }

        $(".'. $this->tableIdName .'-select-row").on("change", function() {
           $tableHeight = $("#' . $this->tableIdName . '").height() + 50;
            $("#' . $this->tableIdName . '-relative").css("height", $tableHeight);
        });

        $("#' . $this->tableIdName . ' tbody","#' . $this->tableIdName . '-datatableblock").on( "click", ".icon-deleterow", function () {
           $tableHeight = $("#' . $this->tableIdName . '").height() + 50;
           $("#' . $this->tableIdName . '-relative").css("height", $tableHeight);
        });

    </script>';

        return $datatableInit;
    }


    protected function getModalPopup()
    {

        $modulPopup = '
<!-- delete window -->
<div class="modal fade" data-keyboard="true" tabindex="-1" id="Modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 id="modalTitle" class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <p id="dialog-cname" class="font-normal-regular"></p>
                <p class="text-center font-normal-regular" id="dialogText">
                    <span class="glyphicon glyphicon-warning-sign red"></span>&nbsp;</p>
                <div class="big-modal-text"></div>

</div>
<div class="modal-footer">
    <input type="submit" id="okButton" type="button" class="btn btn-danger"
            cid="" value="' . $this->view->translate("Igen") . '">
     <button type="button" class="btn btn-default"
         data-dismiss="modal">' . $this->view->translate("Vissza") . '</button>
</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div><!-- /.modal -->


<script>

    //------------------------------------------------------
    // Modal ablak
    //------------------------------------------------------
    $("#Modal","#' . $this->tableIdName . '-datatableblock").on("show.bs.modal", function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal

        var title = button.data("title");
        var warningText = button.data("warning-text");
        var urlaction = button.data("url");
        var cid = button.data("cid");
        var cname = button.data("cname");

        $("#' . $this->tableIdName . '-form","#' . $this->tableIdName . '-datatableblock").prop("action",urlaction);
        $("#modalTitle","#' . $this->tableIdName . '-datatableblock").text(title);
        $("#dialogText","#' . $this->tableIdName . '-datatableblock").text(warningText);

    });

    //Ha a action gombra kattint
    $("#okButton","#' . $this->tableIdName . '-datatableblock").click(function () {
        $("#Modal","#' . $this->tableIdName . '-datatableblock").modal("toggle");
        //window.location = $(this).attr("cid");
    });

    /*
    class
    id
    data-title
    data-url
    data-warning-text */

    //------------------------------------------------------
    // Modal ablak VÉGE
    //------------------------------------------------------
</script>
';

        return $modulPopup;
    }


}



