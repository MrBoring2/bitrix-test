<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// пространства имен для работы с языковыми файлами
use Bitrix\Main\Localization\Loc;
// пространства имен для всех исключений в системе
use Bitrix\Main\SystemException;
// пространства имен для загрузки необходимых файлов, классов, модулей
use Bitrix\Main\Loader;

class CIblocList extends CBitrixComponent
{
    // выполняет основной код компонента, аналог конструктора (метод подключается автоматически)
    public function executeComponent()
    {
        try {
            // подключаем метод проверки подключения модуля «Информационные блоки»
            $this->checkModules();
            // подключаем метод подготовки массива $arResult
            $this->getResult();
        } catch (SystemException $e) {
            ShowError($e->getMessage());
        }
    }

    // подключение языковых файлов (метод подключается автоматически)
    public function onIncludeComponentLang()
    {
        Loc::loadMessages(__FILE__);
    }

    // проверяем установку модуля «Информационные блоки» (метод подключается внутри класса try...catch)
    protected function checkModules()
    {
        // если модуль не подключен
        if (!Loader::includeModule('iblock'))
            // выводим сообщение в catch
            throw new SystemException(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
    }

    // обработка массива $arParams (метод подключается автоматически)
    public function onPrepareComponentParams($arParams)
    {
        // время кеширования
        if (!isset($arParams['CACHE_TIME'])) {
            $arParams['CACHE_TIME'] = 3600;
        } else {
            $arParams['CACHE_TIME'] = intval($arParams['CACHE_TIME']);
        }
        // возвращаем в метод новый массив $arParams     
        return $arParams;
    }

    // подготовка массива $arResult (метод подключается внутри класса try...catch)
    protected function getResult()
    {
        // если нет валидного кеша, получаем данные из БД
        if ($this->startResultCache()) {
            $sectionId = $this->arParams['SECTION_ID'];
            $iblockId = $this->arParams['IBLOCK_ID'];
            // для режима ЧПУ получаем ID секции по символьному коду
            if ($this->arParams['SEF_MODE'] == 'Y') {
                $section_code = $this->arParams['SECTION_CODE'];
                $sectionId = CIBlockFindTools::GetSectionID(false, $section_code, []);
            }
            $section = \Bitrix\Iblock\SectionTable::getByPrimary($sectionId, [
                'filter' => ['IBLOCK_ID' => $iblockId],
                'select' => ['LEFT_MARGIN', 'RIGHT_MARGIN'],
            ])->fetch();
            $dbItems = \Bitrix\Iblock\ElementTable::getList([
                'select' => ['ID', 'NAME', 'CODE'],
                'filter' => [
                    'IBLOCK_ID' => $iblockId,
                    '>=IBLOCK_SECTION.LEFT_MARGIN' => $section['LEFT_MARGIN'],
                    '<=IBLOCK_SECTION.RIGHT_MARGIN' => $section['RIGHT_MARGIN'],
                ],
            ]);

            // формируем массив arResult
            while ($arItem = $dbItems->fetch()) {
                $this->arResult[] = $arItem;
            }

            // кэш не затронет весь код ниже, он будут выполняться на каждом хите, здесь работаем с другим $arResult, будут доступны только те ключи массива, которые перечислены в вызове SetResultCacheKeys()
            if (!empty($this->arResult)) {
                // ключи $arResult перечисленные при вызове этого метода, будут доступны в component_epilog.php и ниже по коду, обратите внимание там будет другой $arResult
                $this->SetResultCacheKeys(
                    array()
                );
                // подключаем шаблон и сохраняем кеш
                $this->IncludeComponentTemplate();
            } else { // если выяснилось что кешировать данные не требуется, прерываем кеширование и выдаем сообщение «Страница не найдена»
                $this->AbortResultCache();
                \Bitrix\Iblock\Component\Tools::process404(
                    "Секция не найдена",
                    true,
                    true
                );
            }
        }
    }
}
