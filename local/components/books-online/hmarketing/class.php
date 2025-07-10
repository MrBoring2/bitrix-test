<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

// проверяем установку модуля «Информационные блоки»
if (!CModule::IncludeModule('iblock')) {
    return;
}

// пространство имен для 404 ошибки
use Bitrix\Iblock\Component\Tools;
// пространство имен для загрузки необходимых файлов, классов, модулей
use Bitrix\Main\Loader;
// пространство имен для обращения к глобальным сущностям ядра
use \Bitrix\Main\Application;

class ComplexComponent extends CBitrixComponent
{
    // выполняет основной код компонента, аналог конструктора (метод подключается автоматически)
    public function executeComponent()
    {
        // подключаем модуль «Информационные блоки»
        Loader::includeModule('iblock');

        // если выбран режим поддержки ЧПУ, вызываем метод sefMode()
        if ($this->arParams["SEF_MODE"] === "Y") {
            $componentPage = $this->sefMode();
        }

        // если отключен режим поддержки ЧПУ, вызываем метод noSefMode()
        if ($this->arParams["SEF_MODE"] != "Y") {
            $componentPage = $this->noSefMode();
        }

        // отдаем 404 статус если не найден шаблон
        if (!$componentPage) {
            Tools::process404(
                $this->arParams["MESSAGE_404"],
                ($this->arParams["SET_STATUS_404"] === "Y"),
                ($this->arParams["SET_STATUS_404"] === "Y"),
                ($this->arParams["SHOW_404"] === "Y"),
                $this->arParams["FILE_404"]
            );
        }

        // подключается файл php из папки комплексного компонента по имени файла, если $componentPage=section, значит подключится section.php расположенный по пути templates/.default
        $this->IncludeComponentTemplate($componentPage);
    }

    // метод обработки режима ЧПУ
    protected function sefMode()
    {
        //******************************************************//
        // Обработка GET параметров                             //
        //******************************************************//

        // дополнительные GET параметры которые будем отлавливать в запросе, в массив $arVariables будет добавлена переменная sort, значение которой будет получено из $_REQUEST['sort'], применяется когда не нужно указывать точный псевдоним для ключа 
        $arComponentVariables = [
            'sort'
        ];

        // дополнительные GET параметры которые будем отлавливать в запросе, полезно например для постраничной навигации. В массив $arVariableAliases будет добавлена переменная ELEMENT_COUNT, значение которой будет получено из $_REQUEST['count'], отлавливаться параметр будет только в разделе section, в итоге данные попадут в $arVariables, применяется когда нужно указать точный псевдоним для ключа 
        $arDefaultVariableAliases404 = array(
            'section' => array(
                'ELEMENT_COUNT' => 'count',
            )
        );

        // метод предназначен для объединения дефолтных GET параметров которые приходят в $arParams["VARIABLE_ALIASES"], в режиме ЧПУ $arParams["VARIABLE_ALIASES"] будет пустой и дополнительных GET параметров из массива $arDefaultVariableAliases404. Параметры из настроек $arrParams заменяют дополнительные из $arDefaultVariableAliases404
        $arVariableAliases = CComponentEngine::makeComponentVariableAliases(
            // массив псевдонимов переменных из GET параметра
            $arDefaultVariableAliases404,
            // массив псевдонимов из $arParams, в режиме ЧПУ $arParams["VARIABLE_ALIASES"] будет пустой
            $this->arParams["VARIABLE_ALIASES"]
        );

        //*****************************************************//
        // Обработка данных по маске из URL запроса           //
        //*****************************************************//

        // если в комплексном компоненте не задан базовый URL
        if (empty($this->arParams["SEF_FOLDER"])) {
            // получаем данные из настроек инфоблока
            $dbResult = CIBlock::GetByID($this->arParams["IBLOCK_ID"])->GetNext();
            if (!empty($dbResult)) {
                // перетираем данные в $arParams["SEF_URL_TEMPLATES"]
                $this->arParams["SEF_URL_TEMPLATES"]["element"] = $dbResult["DETAIL_PAGE_URL"];
                $this->arParams["SEF_URL_TEMPLATES"]["section"] = $dbResult["SECTION_PAGE_URL"];
                $this->arParams["SEF_FOLDER"] = $dbResult["LIST_PAGE_URL"];
            }
        }

        // значение маски URL по умолчанию
        $arDefaultUrlTemplates404 = [
            "section" => "#SECTION_CODE#/",
            "element" => "#SECTION_CODE#/#ELEMENT_CODE#/",
        ];

        // метод предназначен для объединения дефолтных параметров масок URL которые приходят в arParams["SEF_URL_TEMPLATES"] и из массива $arDefaultUrlTemplates404. Параметры из настроек $arrParams заменяют дефолтные из $arDefaultUrlTemplates404
        $arUrlTemplates = CComponentEngine::makeComponentUrlTemplates(
            // массив переменных с масками по умолчанию
            $arDefaultUrlTemplates404,
            // массив переменных с масками из входных параметров $arParams["SEF_URL_TEMPLATES"]
            $this->arParams["SEF_URL_TEMPLATES"]
        );

        //*****************************************************//
        // Получение шаблона для подключения                   //
        //*****************************************************//

        // объект для поиска шаблонов
        $engine = new CComponentEngine($this);

        // главная переменная комплексного компонента, именно она будут записана в массив $arResult, как результат работы комплексного компонента. Она будет доступна в файлах section.php, element.php, index.php, которые будут подключены, после того как отработает class.php
        $arVariables = [];

        // определение шаблона, какой файл подключать section.php, element.php, index.php и заполнение $arVariables получеными URL в соответствие с масками
        $componentPage = $engine->guessComponentPath(
            // путь до корня секции
            $this->arParams["SEF_FOLDER"],
            // массив масок
            $arUrlTemplates,
            // путь до секции SECTION_CODE и элемента ELEMENT_CODE
            $arVariables
        );

        // проверяем, если не удалось сопоставить шаблон, значит выводим index.php
        if ($componentPage == FALSE) {
            $componentPage = 'index';
        }

        //*****************************************************//
        // Формируем $arResult                                 //
        //*****************************************************//

        // метод предназначен для объединения GET и URL параметров, результат записываем в $arVariables
        CComponentEngine::initComponentVariables(
            // нужен для режима ЧПУ, содержит файл который будет подключен section.php, element.php, index.php
            $componentPage,
            // массив дополнительных GET параметров без псевдонимов
            $arComponentVariables,
            // массив основных GET параметров с псевдонимами
            $arVariableAliases,
            // обьединяем все найденные URL и GET параметры и записываем в переменну
            $arVariables
        );

        // формируем arResult
        $this->arResult = [
            // данные полученые из GET и URL параметров 
            "VARIABLES" => $arVariables,
            // массив с параметрами псевдонимов для возможности востановления дальше в обычном компоненте
            "ALIASES" => $arVariableAliases
        ];

        return $componentPage;
    }

    // метод обработки режима без ЧПУ
    protected function noSefMode()
    {
        //******************************************************//
        // Переименование GET параметров                        //
        //******************************************************//

        // если в комплексном компоненте не задан базовый URL
        if (empty($this->arParams["VARIABLE_ALIASES"]["CATALOG_URL"])) {
            // получаем данные из настроек инфоблока
            $dbResult = CIBlock::GetByID($this->arParams["IBLOCK_ID"])->GetNext();
            if (!empty($dbResult)) {
                // перетираем данные в $arParams["VARIABLE_ALIASES"]
                $this->arParams["VARIABLE_ALIASES"]["ELEMENT_ID"] = preg_replace('/\#/', '', $dbResult["DETAIL_PAGE_URL"]);
                $this->arParams["VARIABLE_ALIASES"]["SECTION_ID"] = preg_replace('/\#/', '', $dbResult["SECTION_PAGE_URL"]);
                $this->arParams["VARIABLE_ALIASES"]["CATALOG_URL"] = preg_replace('/\#/', '', $dbResult["LIST_PAGE_URL"]);
            }
        }

        // дополнительные GET параметры которые будем отлавливать в запросе, полезно например для постраничной навигации. В массив $arVariableAliases будет добавлена переменная ELEMENT_COUNT, значение которой будет получено из $_REQUEST['count'], в итоге данные попадут в $arVariables, применяется когда нужно указать точный псевдоним для ключа 
        $arDefaultVariableAliases = [
            'ELEMENT_COUNT' => 'count',
        ];

        // метод предназначен для объединения дефолтных GET параметров которые приходят в $arParams["VARIABLE_ALIASES"] и дополнительных GET параметров из массива $arDefaultVariableAliases. Параметры из настроек $arrParams заменяют дополнительные из $arDefaultVariableAliases
        $arVariableAliases = CComponentEngine::makeComponentVariableAliases(
            // массив псевдонимов переменных из GET параметра
            $arDefaultVariableAliases,
            // массив псевдонимов из $arParams
            $this->arParams["VARIABLE_ALIASES"]
        );

        //******************************************************//
        // Получение и обьединение GET параметров               //
        //******************************************************//

        // главная переменная комплексного компонента, именно она будут записана в массив $arResult, как результат работы комплексного компонента. Она будет доступна в файлах section.php, element.php, index.php, которые будут подключены, после того как отработает class.php
        $arVariables = [];

        // дополнительные GET параметры которые будем отлавливать в запросе, в массив $arVariables будет добавлена переменная sort, значение которой будет получено из $_REQUEST['sort'], применяется когда не нужно указывать точный псевдоним для ключа 
        $arComponentVariables = [
            'sort'
        ];

        // метод предназначен для получения и объединения GET параметров результат записываем в $arVariables
        CComponentEngine::initComponentVariables(
            // нужен для режима ЧПУ, содержит файл который будет подключен section.php, element.php, index.php
            false,
            // массив дополнительных GET параметров без псевдонимов
            $arComponentVariables,
            // массив основных GET параметров с псевдонимами
            $arVariableAliases,
            // обьединяем все найденные GET параметры и записываем в переменну
            $arVariables
        );

        //*****************************************************//
        // Получение реального URL                             //
        //*****************************************************//

        // получаем контекст текущего хита
        $context = Application::getInstance()->getContext();
        // получаем объект Request
        $request = $context->getRequest();
        // получаем директорию запрошенной страницы
        $rDir = $request->getRequestedPageDirectory();

        //*****************************************************//
        // Получение нужного шаблона                           //
        //*****************************************************//

        // переменная предназначен для хранения подключаемого шаблона section.php, element.php, index.php
        $componentPage = "";

        // если запрошенная директория равна переданой в arParams["CATALOG_URL"], определяем тип страницы стартовая 
        if ($arVariableAliases["CATALOG_URL"] == $rDir) {
            $componentPage = "index";
        }

        // по найденным параметрам $arVariables определяем тип страницы секция
        if ((isset($arVariables["SECTION_ID"]) && intval($arVariables["SECTION_ID"]) > 0) || (isset($arVariables["SECTION_CODE"]) && $arVariables["SECTION_CODE"] <> '')) {
            $componentPage = "section";
        }

        // по найденным параметрам $arVariables определяем тип страницы элемент
        if ((isset($arVariables["ELEMENT_ID"]) && intval($arVariables["ELEMENT_ID"]) > 0) || (isset($arVariables["ELEMENT_CODE"]) && $arVariables["ELEMENT_CODE"] <> '')) {
            $componentPage = "element";
        }

        //*****************************************************//
        // Формируем $arResult                                 //
        //*****************************************************//

        // формируем $arResult
        $this->arResult = [
            // данные полученые из GET параметров 
            "VARIABLES" => $arVariables,
            // массив с параметрами псевдонимов для возможности востановления дальше в обычном компоненте
            "ALIASES" => $arVariableAliases
        ];

        return $componentPage;
    }
}
