<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.14.
 * Time: 10:49
 * @copyright Copyright (c) 2011, Marco Neumann
 * @license   http://binware.org/license/index/type:new-bsd New BSD License
 *
 * User Controller Class
 *
 * User Controller
 *
 * @category  User
 * @package   User_Controller
 * @copyright Copyright (c) 2011, Marco Neumann
 * @license   http://binware.org/license/index/type:new-bsd New BSD License
 */


namespace User\Controller;

use Application\Model\RetrieveByDatatableModel;
use Application\Service\Crud\ManipulateEntityByForm;
use Application\Service\Crud\DeleteEntityItems;
use User\Form\Fieldset\UserFieldset;
use Doctrine\ORM\EntityManager;
use User\Entity\User;
use User\Form\UserRegisterForm;
use User\Service\RegisteredService\UserAuthentication;
use Zend\Mvc\Controller\AbstractActionController;


class UserController extends AbstractActionController
{

    private $sm;
    private $is;

    public function __construct($serviceManager, array $internalService)
    {
        $this->sm = $serviceManager;
        $this->is = $internalService;
    }

    /**
     * Index Action
     */
    public function indexAction()
    {
        $datatableModel = new RetrieveByDatatableModel();


        $header = ['Id', 'Név', 'Felhasználónév', 'Szerepkör', 'Telefon', 'Email'];
        $datas = [];
        foreach ($this->sm->get(EntityManager::class)->getRepository(User::class)->findAll() as $user) {
            $datas[] = [
                $user->getId(),
                '<a href=' . $this->url()->fromRoute('user', ['action' => 'show', 'id' => $user->getId()]) . '>' . $user->getName() . '</a>',
                $user->getUserName(),
                $user->getRole()->getDisplayName(),
                $user->getTelephone(),
                $user->getEmail(),
            ];
        }

        $datatableModel->setAction([['name' => 'Törlés', 'actionUrl' => $this->url()->fromRoute('user', ['action' => 'delete']), 'warningText' => 'Biztos törli a tétel(eke)t?', 'icon' => 'remove']]);
        $datatableModel->setSelectable('checkbox');
        $datatableModel->setData($datas);
        $datatableModel->setHeader($header);
        $datatableModel->setName('User');
        $datatableModel->setOrderColumn(1);


        return ([
            'datatableModel' => $datatableModel,
        ]);

    }

    /**
     * Login Action
     *
     * @return array
     */
    public function loginAction()
    {
        if (!empty($this->identity())) {
            $this->flashmessenger()->addMessage("Már be vagy jelentkezve!", 'info');
            return $this->redirect()->toRoute('index');
        }

        $request = $this->getRequest();
        $form = $this->is['LoginForm'];

        $data = $this->getRequest()->getPost();

        $form->setData($data);


        if ($request->isPost() && $form->isValid()) {

            $uAuth = $this->sm->get(UserAuthentication::class);
            $authAdapter = $uAuth->getAuthAdapter();

            $authAdapter->setIdentity($request->getPost()['username']);
            $authAdapter->setCredential(md5($request->getPost()['password']));

            $result = $uAuth->getAuthService()->authenticate();

            if ($result->isValid()) {
                $this->flashmessenger()->addMessage("Sikeres bejelentkezés!", "success");
                return $this->redirect()->toRoute('index');
            } else {
                $this->flashmessenger()->addMessage("Hibás bejelentkezési adatok!", 'error');
                return $this->redirect()->toRoute('login');
            }

        }

        return array('form' => $form);
    }

    /**
     * Logout Action
     */
    public function logoutAction()
    {
        $this->sm->get(UserAuthentication::class)->getAuthService()->clearIdentity();
        $this->flashmessenger()->addMessage("Sikeres kijelentkezés!", "success");
        return $this->redirect()->toRoute('login');
    }

    /**
     * Reister Action
     */
    public function editAction()
    {
        $postData = $this->prg();
        if ($postData instanceof \Zend\Http\PhpEnvironment\Response) return $postData; //PRG TRÜKK

        $msg = $this->getEvent()->getViewModel()->getVariable('statusMessages');
        $userEntity = new User();
        $userEntity->setServiceManager($this->sm);
        $userRepository = $this->sm->get(EntityManager::class)->getRepository(User::class);
        $form = new UserRegisterForm(new UserFieldset($this->sm->get(EntityManager::class), $userEntity));

        $id = (int)$this->params()->fromRoute('id');


        //Ha VAN POST
        if (!empty($postData)) { //TODO: #166 ezt is megoldani Throw Catchcsel

            $modifyDb = new ManipulateEntityByForm($this->sm->get(EntityManager::class));
            $modifyDb->setContentId($id);
            $modifyDb->setForm($form);
            $modifyDb->setPostData($postData);
            $modifyDb->setEntity(clone $userEntity);
            $modifyDb->setRepository($userRepository);

            if ($modifyDb->isValid()) {

                if ($modifiedId = $modifyDb->manipulateDb()) {
                    $this->flashmessenger()->addMessage('Sikeres művelet, az adatok bekerültek az adatbázisba!', 'success');
                    return $this->redirect()->toRoute('user', ['action' => 'show', 'id' => $modifiedId]);
                } else {
                    $msg->addMessage('Hiba a feldolgozás során!', 'error');
                }
            } else {
                $msg->addMessage('Hiba a bevitt adatokban!', 'error');
            }

            $title = 'Felhasználó módosítása';
            $form = $modifyDb->getForm();
        }


        //HA NINCS POST
        if ($postData === false) {
            $userRepository = $userRepository->find($id);
            if (!empty($userRepository)) {
                $title = '"' . $userRepository->getName() . '" módosítása';
                $form->get('submit')->setValue('Módosíás');
                $form->bind($userRepository);
            } else {
                $form->get('submit')->setValue('Feltöltés');
                $title = 'Új felhasználó';
            }
        }

        $form->prepare();

        return [
            'form' => $form,
            'title' => $title,
        ];


    }

    public function showAction()
    {
        $id = (int)$this->params()->fromRoute('id');
        $userRepository = $this->sm->get(EntityManager::class)->getRepository(User::class);

        $content = $userRepository->find($id);
        $headerData = [
            'id' => 'Id',
            'name' => 'Teljes név',
            'username' => 'Felhasználónév',
            'telephone' => 'Telefon',
            'email' => 'Email',
            'role' => 'Szerepkör',
            'moreInfo' => 'Egyéb',
        ];


        $datas = [
            'id' => $content->getId(),
            'name' => $content->getName(),
            'username' => $content->getUserName(),
            'telephone' => $content->getTelephone(),
            'email' => $content->getEmail(),
            'role' => $content->getRole()->getDisplayName(),
            'moreInfo' => $content->getMoreInfo(),
        ];


        $title = 'Felhasználók';

        return [
            'headerData' => $headerData,
            'datas' => $datas,
            'title' => $title,
            'id' => $id,
        ];

    }

    public function deleteAction()
    {
        $postData = $this->prg();
        if ($postData instanceof \Zend\Http\PhpEnvironment\Response) return $postData; //PRG TRÜKK

        $actualUserId = $this->sm->get(UserAuthentication::class)->getIdentity()->getId();
        $userRepository = $this->sm->get(EntityManager::class)->getRepository(User::class);


        /** @var \Application\Service\Crud\DeleteEntityItems $deleteService */
        $deleteService = new DeleteEntityItems();
        $deleteService->setPostData($postData);
        $deleteService->setRepository($userRepository);
        $deleteService->setEntityName('User');
        $deleteService->setEm($this->sm->get(EntityManager::class));
        $deleteService->setActualUserId($actualUserId);

        if ($deleteService->allElementsExist() !== true) {
            $this->flashmessenger()->addMessage('Hiba lépett fel a feldolgozás során!');
            return $this->redirect()->toRoute('user', ['action' => 'index']);
        }


        $deleteService->deleteFromDb();

        if (!empty($deleteService->getCantDeleteElements())) {
            $errorElements = implode(" ", $deleteService->getCantDeleteElements());
            $this->flashmessenger()->addMessage("Ezeket az elemeket nem sikerült törölni, mert még függnek tőlük más adatok:<br>" . $errorElements, 'warning');
        }
        if (!empty($deleteService->getSuccessDeleteElements())) {
            $successElements = implode(" ", $deleteService->getSuccessDeleteElements());
            $this->flashmessenger()->addMessage("Ezek az elemek sikeresen törlődtek az adatbázisból:<br>" . $successElements, 'success');
        }

        return $this->redirect()->toRoute('user', ['action' => 'index']);
    }

}