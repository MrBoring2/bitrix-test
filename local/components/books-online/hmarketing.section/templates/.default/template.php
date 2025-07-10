<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? foreach ($arResult as $arElement) : ?>
    <article>
        <?php if (!empty($arElement['NAME'])) : ?>
            <div>
                <?= $arElement['NAME']; ?>
            </div>
        <?php endif; ?>
        <? if ($arParams["SEF_MODE"] == "Y") : ?>
            <p><a href="<?= $arParams["SEF_FOLDER"] . $arParams['SECTION_CODE'] . "/" . $arElement["CODE"] . "/"; ?>">Вперед в элемент</a></p>
        <? else : ?>
            <p><a href="<?= '?ELEMENT_ID=' . $arElement['ID'] . '&SECTION_ID=' . $arParams["SECTION_ID"]; ?>">Вперед в элемент</a></p>
        <? endif; ?>
    </article>
<? endforeach ?>
<article>
    <? if ($arParams["SEF_MODE"] == "Y") : ?>
        <p><a href="<?= $arParams["SEF_FOLDER"]; ?>">Назад в раздел</a></p>
    <? else : ?>
        <p><a href="<?= $arParams["CATALOG_URL"]; ?>">Назад в раздел</a></p>
    <? endif; ?>
</article>