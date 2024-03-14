<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.11.
 * Time: 10:55
 */

namespace Application\View\Helper;


use Zend\Form\View\Helper\AbstractHelper;

class ModalPopupHelper extends AbstractHelper
{


    public function __invoke()
    {
        return '


<form id="modal-form" method="post">
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
                        <span class="glyphicon glyphicon-warning-sign red"></span>&nbsp;
                    </p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="content-id" name="id" value="">
                    <input type="submit" name="submit-delete" id="ok-button" type="button" class="btn btn-danger"
                           value="">
                    <button type="button" class="btn btn-default" id="cancel-button"
                            data-dismiss="modal">' . $this->view->translate("Megszakítás") . '
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</form>
        <script>

    //------------------------------------------------------
    // Modal ablak
    //------------------------------------------------------
    $("#Modal").on("show.bs.modal", function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal

        var title = button.data("title");
        var warningText = button.data("warning-text");
        var urlaction = button.data("url");
        var id = button.data("id");
        var cname = button.data("cname");
        var inputName = button.data("input-name");
        var okbutton = button.data("okbutton");

        $("#modalTitle").text(title);
        $("#dialogText").text(warningText);
        $("#modal-form").attr("action",urlaction);
        $("#content-id").attr("value",id);
        $("#content-id").attr("name",inputName);
        $("#ok-button").attr("value",okbutton);

    });

    //Ha a action gombra kattint
    $("#okButton").click(function () {
        $("#Modal").modal("toggle");
    });


    //------------------------------------------------------
    // Modal ablak VÉGE
    //------------------------------------------------------
</script>';
    }
}
