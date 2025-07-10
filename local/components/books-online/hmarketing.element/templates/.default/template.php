<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<article>
    <? if (!empty($arResult[0]['NAME'])) : ?>
        <div>
            <?= $arResult[0]['NAME']; ?>
        </div>
    <? endif; ?>
    <? if (!empty($arResult[0]['DETAIL_TEXT'])) : ?>
        <div>
            <?= $arResult[0]['DETAIL_TEXT']; ?>
        </div>
    <? endif; ?>
    <? if ($arParams["SEF_MODE"] == "Y") : ?>
        <p><a href="<?= $arParams['SEF_FOLDER'] . $arParams['SECTION_CODE'] . '/'; ?>">Назад в раздел</a></p>
    <? else : ?>
        <p><a href="<?= '?SECTION_ID=' . $arParams['SECTION_ID']; ?>">Назад в раздел</a></p>
    <? endif; ?>
</article>