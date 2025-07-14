<?php
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
class BooksOnlineCatalogIndexComponent extends CBitrixComponent
{
    public function executeComponent()
    { 
        Loader::includeModule('iblock');
        if ($this->arParams["SEF_MODE"] === "Y") {
            $componentPage = $this->sefMode();
        }
        // если отключен режим поддержки ЧПУ, вызываем метод noSefMode()
        else if ($this->arParams["SEF_MODE"] != "Y") {
            $componentPage = $this->noSefMode();
        }
        $this->includeComponentTemplate($componentPage);
    }

    protected function sefMode() {
        $arComponentVariables = [
            'sort'
        ];

        $arDefaultVariableAliases404 = array(
            'section' => array(
                'ELEMENT_COUNT' => 'count',
            ),
        );
        // метод предназначен для объединения дефолтных GET параметров которые приходят в $arParams["VARIABLE_ALIASES"], в режиме ЧПУ $arParams["VARIABLE_ALIASES"] будет пустой и дополнительных GET параметров из массива $arDefaultVariableAliases404. Параметры из настроек $arrParams заменяют дополнительные из $arDefaultVariableAliases404
        $arVariableAliases = CComponentEngine::makeComponentVariableAliases(
            // массив псевдонимов переменных из GET параметра
            $arDefaultVariableAliases404,
            // массив псевдонимов из $arParams, в режиме ЧПУ $arParams["VARIABLE_ALIASES"] будет пустой
            $this->arParams["VARIABLES"]
        );

        if (empty($this->arParams["SEF_FOLDER"])) {
            // получаем данные из настроек инфоблока
            $dbResult = CIBlock::GetByID($this->arParams["IBLOCK_ID"])->GetNext();
            if (!empty($dbResult)) {
                // перетираем данные в $arParams["SEF_URL_TEMPLATES"]
                $this->arParams["SEF_URL_TEMPLATES"]["element"] = $dbResult["DETAIL_PAGE_URL"];
                $this->arParams["SEF_FOLDER"] = $dbResult["LIST_PAGE_URL"];
            }
        }

        $arDefaultUrlTemplates404 = [
            "index" => "",
            "element" => "#ELEMENT_CODE#/",
        ];

        $arUrlTemplates = CComponentEngine::makeComponentUrlTemplates(
            // массив переменных с масками по умолчанию
            $arDefaultUrlTemplates404,
            // массив переменных с масками из входных параметров $arParams["SEF_URL_TEMPLATES"]
            $this->arParams["SEF_URL_TEMPLATES"]
        );

        $engine = new CComponentEngine($this);

        $arVariables = [];

        $componentPage = $engine->guessComponentPath(
            // путь до корня секции
            $this->arParams["SEF_FOLDER"],
            // массив масок
            $arUrlTemplates,
            // путь до секции SECTION_CODE и элемента ELEMENT_CODE
            $arVariables
        );

        if ($componentPage == FALSE) {
            $componentPage = 'index';
        }

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

        $this->arResult = [
            // данные полученые из GET и URL параметров
            "VARIABLES" => $arVariables,
            // массив с параметрами псевдонимов для возможности востановления дальше в обычном компоненте
            "ALIASES" => $arVariableAliases
        ];
        return $componentPage;
    }

    protected function noSefMode() {
        if (empty($this->arParams["VARIABLES"]["CATALOG_URL"])) {
            // получаем данные из настроек инфоблока
            $dbResult = CIBlock::GetByID($this->arParams["IBLOCK_ID"])->GetNext();
            if (!empty($dbResult)) {
                // перетираем данные в $arParams["VARIABLE_ALIASES"]
                $this->arParams["VARIABLES"]["ELEMENT_ID"] = preg_replace('/\#/', '', $dbResult["DETAIL_PAGE_URL"]);
                $this->arParams["VARIABLES"]["CATALOG_URL"] = preg_replace('/\#/', '', $dbResult["LIST_PAGE_URL"]);
            }
        }

        $arDefaultVariableAliases = [
            'ELEMENT_COUNT' => 'count',
        ];

        $arVariableAliases = CComponentEngine::makeComponentVariableAliases(
            // массив псевдонимов переменных из GET параметра
            $arDefaultVariableAliases,
            // массив псевдонимов из $arParams
            $this->arParams["VARIABLES"]
        );

        $arVariables = [];
        // дополнительные GET параметры которые будем отлавливать в запросе, в массив $arVariables будет добавлена переменная sort, значение которой будет получено из $_REQUEST['sort'], применяется когда не нужно указывать точный псевдоним для ключа
        $arComponentVariables = [
            'sort'
        ];

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

        $context = Application::getInstance()->getContext();
        // получаем объект Request
        $request = $context->getRequest();
        // получаем директорию запрошенной страницы
        $rDir = $request->getRequestedPageDirectory();

        $componentPage = "";
        // если запрошенная директория равна переданой в arParams["CATALOG_URL"], определяем тип страницы стартовая
        if ($arVariableAliases["CATALOG_URL"] == $rDir) {
            $componentPage = "index";
        }
        // по найденным параметрам $arVariables определяем тип страницы элемент
        if ((isset($arVariables["ELEMENT_ID"]) && intval($arVariables["ELEMENT_ID"]) > 0) || (isset($arVariables["ELEMENT_CODE"]) && $arVariables["ELEMENT_CODE"] <> '')) {
            $componentPage = "element";
        }

        $this->arResult = [
            // данные полученые из GET параметров
            "VARIABLES" => $arVariables,
            // массив с параметрами псевдонимов для возможности востановления дальше в обычном компоненте
            "ALIASES" => $arVariableAliases
        ];
        return $componentPage;
    }
}