<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.07.
 * Time: 8:16
 */

namespace Application\Service\Crud;


use Zend\Form\Form;

/**
 * A db-ben tárolt adatok egyyesével történő manipulálása (create, update, delete),  a delete tudjon tömbböt fogadni és
 * tömeges idkat törölni a db-ből, így ő kivétel
 * Interface IManipulateEntityByForm
 * @package Application\Service\Crud
 */
interface IManipulateEntityByForm
{
    public function setForm(Form $form);

    public function getForm();

    public function setPostData(array $postData);

    public function setContentId($contentId);

    public function setEntity($enity);

    public function setRepository($repository);

    public function isValid();

    public function manipulateDb();



}