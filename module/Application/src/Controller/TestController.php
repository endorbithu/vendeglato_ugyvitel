<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Entity\EventLog;
use Application\Filter\ShortString;
use Application\Filter\SubString;
use Application\Form\TestForm;
use Application\Service\Test\EgyServiceAzSajatEventhez;
use Application\Service\Test\TweetService;
use Application\View\Helper\DatatableHelper;
use Basedata\Entity\BasedataLanguage;
use Basedata\Entity\Ingredient;
use Basedata\Entity\IngredientGroup;
use Basedata\Entity\ProductGroup;
use Basedata\Entity\Storage;
use Basedata\Repository\ProductGroupRecursive;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Interop\Container\ContainerInterface;
use User\Form\Register;
use Zend\Filter\FilterChain;
use Zend\Filter\StripTags;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\View\Model\ViewModel;


class TestController extends AbstractActionController
{
    private $sm;
    private $statusMsg;

    public function __construct(ContainerInterface $sm)
    {
        $this->sm = $sm;
    }


    public function indexAction()
    {
        $e = new EventLog();
        var_dump($this->sm->get('translator')->getLocale());

        return new ViewModel(array());
    }


    public function unittestAction()
    {
        return new ViewModel(array());
    }

    public function select2Action()
    {
        return new ViewModel(array());
    }

    public function dtAction()
    {
        return new ViewModel([]);

    }


    public function select2dataAction()
    {

        $search = strip_tags(trim($this->params()->fromQuery('q')));
        $resultArray = $this->sm->get(EntityManager::class)
            ->getRepository(Ingredient::class)->getIngredientForSelect2($search);

        $data = [];
        if (count($resultArray) > 0) {
            foreach ($resultArray as $key => $value) {
                $data[] = ['id' => $value['id'], 'text' => $value['name']];
            }
        } else {
            $data[] = array('id' => '0', 'text' => 'Nincs eredmény');
        }


        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $response->setContent(json_encode($data));
        return $response;


    }

    public function translateAction()
    {
        return array();
    }


    public function datatableAction()
    {

        $prg = $this->prg();
        if ($prg instanceof \Zend\Http\PhpEnvironment\Response) return $prg;

        if (!empty($prg)) var_dump($prg);


        //$categorySelect = [0 => 'Összes kategória'] + $this->sm->get(EntityManager::class)->getRepository(ProductGroup::class)->getRecursiveIdKeyAndName();


        $igidSearch = [];
        if (!empty($igId = (int)$this->params()->fromQuery("ujprobasub")) || !empty($igId = (int)$this->params()->fromQuery("ujproba2sub"))) $igidSearch = ['ig.id' => (int)$igId];

        if (empty($datas = $this->sm->get(EntityManager::class)->getRepository(Ingredient::class)->getDataTable($this->sm->get('translator')->getLocale(),$igidSearch))) {
            $datas = '/test/ingredient' . (empty($igId) ? '' : '?ujprobasub=' . $igId);
        } else {
            $workedDatas = [];
            foreach ($datas as $key => $content) {
                $workedDatas[$key][] = $content->getId();
                $workedDatas[$key][] = $content->getId();
                $workedDatas[$key][] = '<div style="white-space: nowrap;"><a href="' . $this->url()->fromRoute('basedata', ['action' => 'ingredient', 'id' => $content->getId()]) . '">' . $content->getName() . '</a></div>';
                $workedDatas[$key][] = '<div style="white-space: nowrap;"><a href="' . $this->url()->fromRoute('basedata', ['action' => 'ingredientgroup', 'id' => $content->getIngredientGroup()->getId()]) . '">' . $content->getIngredientGroup()->getName() . '</a></div>';
                $workedDatas[$key][] = $content->getIngredientUnit()->getName();
                //$workedDatas[$key][] = '<input class="datatable-extrainputs form-control" name="mertek[' . $content->getId() . ']" value="aa" type="text">'; //FOntos a datatable-extrainputs!!!
                $workedDatas[$key][] = $this->sm->get('nf')->nf($content->getMinimumAmount(), true) . ' ' . $content->getIngredientUnit()->getShortName();
                $workedDatas[$key][] = empty($content->getMoreInfo()) ? '' : substr($content->getMoreInfo(), 1, 16) . '...';

                $workedDatas[$key]['image'] = "/img/basedata/ingredient/ingredient" . $content->getId() . ".jpg"; //selecthez, kép a selecten belül
                $workedDatas[$key]['rightside'] = "4.333Ft"; //selecthez, pl árat ki tudjuk írni a jobboldalra
                $workedDatas[$key]['subid'] = ',' . $content->getIngredientGroup()->getId() . ','; //selecthez, azért van az elején és végén vessző,hogy gyorsabban lehessen keresni benne

                $workedDatas[$key]['2s'] = ',' . $content->getIngredientGroup()->getId() . ','; //checkboxhoz, ua, mint a subid, azért van az elején és végén vessző,hogy gyorsabban lehessen keresni benne


            }
            $datas = $workedDatas;
        }

        /*
        $categorySelect = [0 => 'Összes kategória']; //itt töltjük fel a subid-s cuccot kategóriákkal, meg lehet oldalni hierarchikusan is kötöjeles behúzásokkal
        $ingrGroup = $this->sm->get(EntityManager::class)->getRepository(Ingredient::class)->getDataTableIngredientGroup();
        foreach ($ingrGroup as $ig) {
            $categorySelect[$ig['igid']] = $ig['igname'];
        }
        */

        $sessionManager = $this->sm->get(SessionManager::class);
        $sessionContainer = new Container('datatable', $sessionManager);
        if (empty($sessionContainer->ujproba)) {
            $sessionElem = $sessionContainer->ujproba = [];
        } else {
            $sessionElem = array_map('intval', $sessionContainer->ujproba);
        }


        //$categorySelect = [0 => 'Összes kategória'] + ($this->sm->get(EntityManager::class)->getRepository(ProductGroup::class)->getRecursiveIdKeyAndName());


        $tableproba = $this->sm->get('ViewHelperManager')->get('datatable');
        $tableproba2 = clone $this->sm->get('ViewHelperManager')->get('datatable');
        $tableproba3 = clone $this->sm->get('ViewHelperManager')->get('datatable');

        $tableproba->initTable(
            [
                'tableIdName' => 'ujproba',
                'headers' => ['ID', 'Név', 'Csoport', 'Mértéke.', 'Minimum', 'Egyéb'],
                'datas' => $datas,
                //'order' => [],
                'type' => 'select',
                'select' =>
                    [
                        'paging' => false,
                        'selectall' => false,
                        'form' => false,
                        'isSelect2Char' => false, //legyen-e ~ az optionok előtt, ajaxnál a ajax controllerben kell manuálisan odatenni
                        'toolbarAtBottom' => true,
                        'selectedToSession' => false,

                        'ajaxUrl' => '/test/ingredient', //ha van selected element akkor innen hívja le ami nincs meg
                        //'search' => [1, '2s' => $categorySelect],
                        'arith' => [4],
                        'selectedRow' => $sessionElem,
                        'action' => [
                            ['name' => 'Törlés', 'actionUrl' => '/test/datatable', 'warningText' => 'Biztos ezt akarod?', 'icon' => 'remove'],
                            ['name' => 'Módosítás', 'actionUrl' => '/test/datatable', 'warningText' => 'Biztos ezt akarod?', 'icon' => 'edit'],
                            ['name' => 'Beküldés', 'actionUrl' => '/test/datatable', 'warningText' => 'Biztos ezt akarod?', 'icon' => 'edit'],
                        ],
                    ],
            ]);

        $tableproba2->initTable(
            [
                'tableIdName' => 'ujproba2',
                'headers' => ['ID', 'Név', 'Csoport', 'Mértéke.', 'Minimum', 'Egyéb'],
                'datas' => $datas,
                //'order' => [1 => 'asc'],
                'type' => 'checkbox',
                'checkbox' =>
                    [
                        //'paging' => false,
                        //'selectall' => false,
                        // 'form' => false,
                        'toolbarAtBottom' => true,
                        'selectedToSession' => true,
                        'ajaxUrl' => '/test/ingredient',
                        //'search' => [1, '2s' => $categorySelect],
                        'arith' => [4],
                        // 'selectedRows' => $sessionElem,
                        'action' => [
                            ['name' => 'Törlés', 'actionUrl' => '/test/datatable', 'warningText' => 'Biztos ezt akarod?', 'icon' => 'remove'],
                            ['name' => 'Módosítás', 'actionUrl' => '/test/datatable', 'warningText' => 'Biztos ezt akarod?', 'icon' => 'edit'],
                            ['name' => 'Beküldés', 'actionUrl' => '/test/datatable', 'warningText' => 'Biztos ezt akarod?', 'icon' => 'edit'],
                        ],
                    ]
            ]);


        //A nekdnél nem kell bele a duplikált ID ezért kitörlöm a 0. oszlopát
        if (is_array($datas)) {
            if (array_key_exists(0, $datas)) $length = count($datas[0]);
            foreach ($datas as &$element) {
                $element = array_slice($element, 1, $length);
            }
        }

        $tableproba3->initTable(
            [
                'tableIdName' => 'ujproba3',
                'headers' => ['ID', 'Név', 'Csoport', 'Mértéke.', 'Minimum', 'Egyéb'],
                'datas' => $datas,
                'order' => [1 => 'asc'],
                'type' => 'naked',
                'naked' => [
                    'ajaxUrl' => '/test/ingredient',
                    'paging' => true,
                    'action' => [
                        //['name' => 'Törlés', 'actionUrl' => 'datatable', 'warningText' => 'Biztos ezt akarod?', 'icon' => 'remove'],
                        ['name' => 'Módosítás', 'actionUrl' => '/test/datatable', 'warningText' => 'Biztos ezt akarod?', 'icon' => 'edit'],
                        // ['name' => 'Beküldés', 'actionUrl' => 'datatable', 'warningText' => 'Biztos ezt akarod?', 'icon' => 'edit'],
                    ],
                ]
            ]);

        return array('select' => $tableproba, 'checkbox' => $tableproba2, 'naked' => $tableproba3);
    }


    public function ingredientAction()
    {


        $column = [
            'i.id',
            'i.name',
            'ig.name',
            'iu.name',
            'i.minimumAmount',
            'i.moreInfo',
            'ig.id'
        ];


        //TODO: ide még lehetne filtert
        $filtersForString = new FilterChain();
        $filtersForString->attach(new StripTags());
        $filtersForString->attach(new SubString(['max' => 16]));

        $searches = [];
        $allPostValues = $this->params()->fromPost();


        //////// DATATBLE //////////////////////////////
        if (!empty($allPostValues && !(array_key_exists('s2', $this->params()->fromQuery())))) {

            $orderColumn = array_key_exists('order', $allPostValues) ? $column[((int)$allPostValues['order'][0]['column'] - 1)] : $column[1];
            $desk = array_key_exists('order', $allPostValues) ? $filtersForString->filter($allPostValues['order'][0]['dir']) : 'asc';
            $from = (int)$allPostValues['start'];
            $max = (int)$allPostValues['length'];
            $draw = (int)$allPostValues['draw'];

            foreach ($allPostValues['columns'] as $key => $val) {
                (($val['search']['value']) == "") ?: $searches[$column[(int)$key]] = $filtersForString->filter($val['search']['value']);
            }


        }
        //////// DATATBLE VÉGE //////////////////////////////

        //////////////////// SELECT2 /////////////////////////////////////
        if (array_key_exists('s2', $this->params()->fromQuery())) {
            $s2Params = $this->params()->fromQuery();
            $page = (array_key_exists('page', $s2Params)) ? (int)$s2Params['page'] : 1;
            $term = (array_key_exists('q', $s2Params)) ? $filtersForString->filter($s2Params['q']) : false;
            $max = (array_key_exists('nopaging', $s2Params)) ? (int)DatatableHelper::MAX_NONAJAX_ELEMENT : (int)DatatableHelper::MAX_ELEMENT_PER_AJAX_LOADING;

            if (!empty($term)) $searches[$column[1]] = $filtersForString->filter($s2Params['q']);

            $from = (int)(($page - 1) * $max);

            $orderColumn = $column[1];
            $desk = 'asc';
        }

        /////////////////////// SELECT2 VÉGE ////////////////////////

        /////////////////////// NEUTRAL ////////////////////////


        if (!empty($igId = $this->params()->fromQuery("ujprobasub")) || !empty($igId = $this->params()->fromQuery("ujproba2sub"))) $searches['ig.id'] = (int)$igId;

        if (!empty($iId = $this->params()->fromPost("iid"))) {
            $iIdInt = array_map('intval', $iId);
            $searches['i.id'] = $iIdInt;
        }

        $sqlResult = $this->sm->get(EntityManager::class)->getRepository(Ingredient::class)
            ->getDataTable($this->sm->get('translator')->getLocale(),$searches, (int)$from, (int)$max, $orderColumn, $desk, true);
        $paginator = new Paginator($sqlResult, $fetchJoinCollection = false);
        $result = [];
        /////////////////////// NEUTRAL VÉGE ////////////////////////


        //////// DATATBLE //////////////////////////////////////////
        if (!empty($allPostValues) && !(array_key_exists('s2', $this->params()->fromQuery()))) {

            $dataColumnKey = 'data';

            $result[$dataColumnKey] = [];
            foreach ($paginator as $key => $content) {
                $result[$dataColumnKey][] = [
                    $content->getId(),
                    $content->getId(),
                    '<div style="white-space: nowrap;"><a href="' . $this->url()->fromRoute('basedata', ['action' => 'ingredient', 'id' => $content->getId()]) . '">' . $content->getName() . '</a></div>',
                    '<div style="white-space: nowrap;"><a href="' . $this->url()->fromRoute('basedata', ['action' => 'ingredientgroup', 'id' => $content->getIngredientGroup()->getId()]) . '">' . $content->getIngredientGroup()->getName() . '</a></div>',
                    $content->getIngredientUnit()->getName(),
                    $this->sm->get('nf')->nf($content->getMinimumAmount(), true) . ' ' . $content->getIngredientUnit()->getShortName(),
                    empty($content->getMoreInfo()) ? '' : substr($content->getMoreInfo(), 1, 16) . '...',

                ];
            }


            $result['draw'] = $draw;
            $result['recordsTotal'] = count($paginator);
            $result['recordsFiltered'] = count($paginator);
        }
        //////// DATATBLE VÉGE //////////////////////////////


        /////////////////////// SELECT2  ////////////////////////
        if (array_key_exists('s2', $this->params()->fromQuery())) {

            $dataColumnKey = 'items';
            $result[$dataColumnKey] = [];
            foreach ($paginator as $key => $content) {
                $result[$dataColumnKey][] = [
                    'id' => $content->getId(),
                    'text' => '~ ' . $content->getName(),
                    'data' => [
                        $content->getId(),
                        '<div style="white-space: nowrap;"><a href="' . $this->url()->fromRoute('basedata', ['action' => 'ingredient', 'id' => $content->getId()]) . '">' . $content->getName() . '</a></div>',
                        '<div style="white-space: nowrap;"><a href="' . $this->url()->fromRoute('basedata', ['action' => 'ingredientgroup', 'id' => $content->getIngredientGroup()->getId()]) . '">' . $content->getIngredientGroup()->getName() . '</a></div>',
                        '<input class="datatable-extrainputs form-control" name="mertek[' . $content->getId() . ']" type="text">', //FOntos a datatable-extrainputs!!!
                        $this->sm->get('nf')->nf($content->getMinimumAmount(), true) . ' ' . $content->getIngredientUnit()->getShortName(),
                        empty($content->getMoreInfo()) ? '' : substr($content->getMoreInfo(), 1, 16) . '...',
                        'image' => "/img/basedata/ingredient/ingredient" . $content->getId() . '.jpg',
                        'rightside' => "4.333Ft",
                    ]
                ];
            }

            $result['total_count'] = count($paginator);
            $result['incomplete_results'] = ($result['total_count'] > (($page * $max)));

        }
        /////////////////////// SELECT2 VÉGE ////////////////////////


        /////////////////////// NEUTRAL ////////////////////////
        $this->response->setContent(json_encode($result));
        return $this->response;
        /////////////////////// NEUTRAL VÉGE ////////////////////////

    }

    public function excelAction()
    {


        /** Include PHPExcel */
        //require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';


        define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
        // Create new PHPExcel object
        echo date('H:i:s'), " Create new PHPExcel object", EOL;
        $objPHPExcel = new \PHPExcel();

        // Set document properties
        echo date('H:i:s'), " Set document properties", EOL;
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

        // Generate an image
        echo date('H:i:s'), " Generate an image", EOL;
        $gdImage = @imagecreatetruecolor(120, 20) or die('Cannot Initialize new GD image stream');
        $textColor = imagecolorallocate($gdImage, 255, 255, 255);
        imagestring($gdImage, 1, 5, 5, 'Created with PHPExcel', $textColor);
        $gdImage = imagecreatefromjpeg('http://vendeglato.local/img/basedata/ingredient/ingredient1.jpg');


        // Add a drawing to the worksheet
        echo date('H:i:s'), " Add a drawing to the worksheet", EOL;
        $objDrawing = new \PHPExcel_Worksheet_MemoryDrawing();
        $objDrawing->setName('Sample image');
        $objDrawing->setDescription('Sample image');
        $objDrawing->setImageResource($gdImage);
        $objDrawing->setRenderingFunction(\PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
        $objDrawing->setMimeType(\PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
        $objDrawing->setHeight(36);
        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

        echo date('H:i:s'), " Write to Excel2007 format", EOL;
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
        echo date('H:i:s'), " File written to ", str_replace('.php', '.xlsx', pathinfo(__FILE__, PATHINFO_BASENAME)), EOL;


        // Echo memory peak usage
        echo date('H:i:s'), " Peak memory usage: ", (memory_get_peak_usage(true) / 1024 / 1024), " MB", EOL;

        // Echo done
        echo date('H:i:s'), " Done writing file", EOL;
        echo 'File has been created in ', getcwd(), EOL;


        //////////////////////////////////////////////////////////////////////////////
        //
        //


        /** Include PHPExcel */


// Create new \PHPExcel object
        echo date('H:i:s'), " Create new PHPExcel object", EOL;
        $objPHPExcel = new \PHPExcel();

// Set document properties
        echo date('H:i:s'), " Set document properties", EOL;
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

// Set default font
        echo date('H:i:s'), " Set default font", EOL;
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')
            ->setSize(10);

// Add some data, resembling some different data types
        echo date('H:i:s'), " Add some data", EOL;
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'String')
            ->setCellValue('B1', 'Simple')
            ->setCellValue('C1', 'PHPExcel');

        $objPHPExcel->getActiveSheet()->setCellValue('A2', 'String')
            ->setCellValue('B2', 'Symbols')
            ->setCellValue('C2', '!+&=()~§±æþ');

        $objPHPExcel->getActiveSheet()->setCellValue('A3', 'String')
            ->setCellValue('B3', 'UTF-8')
            ->setCellValue('C3', 'Создать MS Excel Книги из PHP скриптов');

        $objPHPExcel->getActiveSheet()->setCellValue('A4', 'Number')
            ->setCellValue('B4', 'Integer')
            ->setCellValue('C4', 12);

        $objPHPExcel->getActiveSheet()->setCellValue('A5', 'Number')
            ->setCellValue('B5', 'Float')
            ->setCellValue('C5', 34.56);

        $objPHPExcel->getActiveSheet()->setCellValue('A6', 'Number')
            ->setCellValue('B6', 'Negative')
            ->setCellValue('C6', -7.89);

        $objPHPExcel->getActiveSheet()->setCellValue('A7', 'Boolean')
            ->setCellValue('B7', 'True')
            ->setCellValue('C7', true);

        $objPHPExcel->getActiveSheet()->setCellValue('A8', 'Boolean')
            ->setCellValue('B8', 'False')
            ->setCellValue('C8', false);

        $dateTimeNow = new \DateTime();
        $objPHPExcel->getActiveSheet()->setCellValue('A9', 'Date/Time')
            ->setCellValue('B9', 'Date')
            ->setCellValue('C9', \PHPExcel_Shared_Date::PHPToExcel($dateTimeNow));

        $objPHPExcel->getActiveSheet()->getStyle('C9')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);

        $objPHPExcel->getActiveSheet()->setCellValue('A10', 'Date/Time')
            ->setCellValue('B10', 'Time')
            ->setCellValue('C10', \PHPExcel_Shared_Date::PHPToExcel($dateTimeNow));
        $objPHPExcel->getActiveSheet()->getStyle('C10')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME4);

        $objPHPExcel->getActiveSheet()->setCellValue('A11', 'Date/Time')
            ->setCellValue('B11', 'Date and Time')
            ->setCellValue('C11', \PHPExcel_Shared_Date::PHPToExcel($dateTimeNow));
        $objPHPExcel->getActiveSheet()->getStyle('C11')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_DATETIME);

        $objPHPExcel->getActiveSheet()->setCellValue('A12', 'NULL')
            ->setCellValue('C12', NULL);

        $objRichText = new \PHPExcel_RichText();
        $objRichText->createText('你好 ');

        $objPayable = $objRichText->createTextRun('你 好 吗？');
        $objPayable->getFont()->setBold(true);
        $objPayable->getFont()->setItalic(true);
        $objPayable->getFont()->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_DARKGREEN));

        $objRichText->createText(', unless specified otherwise on the invoice.');

        $objPHPExcel->getActiveSheet()->setCellValue('A13', 'Rich Text')
            ->setCellValue('C13', $objRichText);


        $objRichText2 = new \PHPExcel_RichText();
        $objRichText2->createText("black text\n");

        $objRed = $objRichText2->createTextRun("red text");
        $objRed->getFont()->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_RED));

        $objPHPExcel->getActiveSheet()->getCell("C14")->setValue($objRichText2);
        $objPHPExcel->getActiveSheet()->getStyle("C14")->getAlignment()->setWrapText(true);


        $objPHPExcel->getActiveSheet()->setCellValue('C15', 33.44);
        $objPHPExcel->getActiveSheet()->getStyle('C15')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);


        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

// Rename worksheet
        echo date('H:i:s'), " Rename worksheet", EOL;
        $objPHPExcel->getActiveSheet()->setTitle('Datatypes');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);


// Save Excel 2007 file
        echo date('H:i:s'), " Write to Excel2007 format", EOL;
        $callStartTime = microtime(true);

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
        $callEndTime = microtime(true);
        $callTime = $callEndTime - $callStartTime;

        echo date('H:i:s'), " File written to ", str_replace('.php', '.xlsx', pathinfo(__FILE__, PATHINFO_BASENAME)), EOL;
        echo 'Call time to write Workbook was ', sprintf('%.4f', $callTime), " seconds", EOL;
// Echo memory usage
        echo date('H:i:s'), ' Current memory usage: ', (memory_get_usage(true) / 1024 / 1024), " MB", EOL;


        echo date('H:i:s'), " Reload workbook from saved file", EOL;
        $callStartTime = microtime(true);

        $objPHPExcel = \PHPExcel_IOFactory::load(str_replace('.php', '.xlsx', __FILE__));

        $callEndTime = microtime(true);
        $callTime = $callEndTime - $callStartTime;
        echo 'Call time to reload Workbook was ', sprintf('%.4f', $callTime), " seconds", EOL;
// Echo memory usage
        echo date('H:i:s'), ' Current memory usage: ', (memory_get_usage(true) / 1024 / 1024), " MB", EOL;


        var_dump($objPHPExcel->getActiveSheet()->toArray());


// Echo memory peak usage
        echo date('H:i:s'), " Peak memory usage: ", (memory_get_peak_usage(true) / 1024 / 1024), " MB", EOL;

// Echo done
        echo date('H:i:s'), " Done testing file", EOL;
        echo 'File has been created in ', getcwd(), EOL;


    }


    public function prgAction()
    {

        $prg = $this->prg();
        if ($prg instanceof \Zend\Http\PhpEnvironment\Response) {
            return $prg;
        } //PRG TRÜKK újratölti az oldalt, ha van $_POST
        ///////////// trükk vége ///////////

        $this->statusMsg = $this->getEvent()->getViewModel()->getVariable('statusMessages');
        $form = new Register();

        //HA VAN POST ( prg-vel feldolgozva)
        if (!empty($prg)) {
            $form->setData($prg);
        }


        //HA NINCS POST
        if ($prg === false) {
            //Ha Response típus, akkor jött POST data ezért újratölti az oldalt
        }


        return array('prg' => $prg, 'form' => $form);

    }


    public function currencyAction()
    {
        $this->statusMsg = $this->getEvent()->getViewModel()->getVariable('statusMessages');


//CURRENCY TESZTELÉS
        $this->statusMsg->addMessage($this->sm->get('currencyConverter')->getSelectedCurrency() . ' teszteld így: ?currency=...');

//a Product entity megörökli a ServiceManagerAwareInterfacet így beinjáktálódik az sm és lehet az összegnél váltani a pénzt

//!!!!Az Entityben lévő converter tesztelése, mielőőt váltanál currencyt kommenteld be hogy ne írja be újra!!!
        $product = $this->sm->get('Doctrine\ORM\EntityManager')->getRepository('Basedata\Entity\Product')->find(212);

//$egyProduct->setPrice(10);
//$this->sm->get('Doctrine\ORM\EntityManager')->persist($egyProduct);
//$this->sm->get('Doctrine\ORM\EntityManager')->flush();


        $this->statusMsg->addMessage('<span class="fromDb">' . round($this->sm->get('Doctrine\ORM\EntityManager')
                ->getRepository('Basedata\Entity\Product')->find(6)->getPrice(), 2) . '</span>');


        return new ViewModel(array(

            'osszeg' => $this->sm->get('Doctrine\ORM\EntityManager')
                ->getRepository('Basedata\Entity\Product')->find(6)->getPrice(),
            'szorzo' => $this->sm->get('config')['currency'],
            'code' => $this->params()->fromQuery('currency')
        ));

//------------------------------------------------
    }


    public function sajateventAction()
    {
        $this->statusMsg = $this->getEvent()->getViewModel()->getVariable('statusMessages');
        //SAJÁT EVENT ÉS LISTENER ÉS TRIGGER
        $rendeles = new EgyServiceAzSajatEventhez();
        $rendeles->setValtozo('ezt a controlleről trigger előtt');

        //Trigger előtt: a listener előtti állapot itt még ne módosul
        $this->statusMsg->addMessage($rendeles->getValtozo());

        // $this->getEventManager()->trigger('sendTweet', null, ['content' => $rendeles]);


        $tweetService = new TweetService();
        $tweetService->setEventManager($this->getEventManager());
        $tweetService->sendTweet($rendeles);


        //Trigger után miután a listenerek megdolgozták a paraméternek átadott $redeles EgyServiceAzSajatEventhez példányt
        $this->statusMsg->addMessage($rendeles->getValtozo());
        //SAJÁT EVENT ÉS LISTENER ÉS TRIGGER VÉGE

        return new ViewModel(array());

    }


    public function formAction()
    {

        $this->statusMsg = $this->getEvent()->getViewModel()->getVariable('statusMessages');

        // $filterChain = new FilterChain();
        // $filterChain
        //     ->attach(new StringTrim())
        //     ->attach(new StringToLower());

        //var_dump($filterChain->filter('    This is (my) content:    '));


        //$filter = new AccentToAscii();
        //var_dump($filter->filter("Árvíztűrő tükörfúrógép, ÁRVÍZTŰRŐ TÜKÖRFÚRÓGÉP BĐŁßÄ€Í"));


        $form = new TestForm();


        if ($this->getRequest()->isPost()) {

            $data = $this->getRequest()->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                var_dump($form->getData());
            } else {
                $this->statusMsg->addMessage("Hiba a bevitt adatokba", "error");
            }
        }


        return new ViewModel(array(
            'form' => $form,
        ));
    }


}
