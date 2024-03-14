<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.04.
 * Time: 9:57
 */

namespace Catalog\Service\BaseDataEdit;

use Application\Service\Crud\ManipulateEntityByForm;

/**
 * ManipulateEntityByForm az agy ez felelős a Foros CRUD-os datatablés műveletekért és ide az elég is
 * Class ProductDataEditService
 * @package Catalog\Service\BaseDataEdit
 */
class BaseDataEditService extends ManipulateEntityByForm
{

    public function __construct($em)
    {
        parent::__construct($em);
    }




}