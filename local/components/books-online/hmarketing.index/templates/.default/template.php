<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? foreach ($arResult as $arSection) : ?>
    <article>
        <? if (!empty($arSection['NAME'])) : ?>
            <div>
                <?= $arSection['NAME']; ?>
            </div>
        <? endif; ?>
        <? if ($arParams["SEF_MODE"] == "Y") : ?>
            <p><a href="<?= $arSection['CODE'] . '/'; ?>">Вперед в раздел</a></p>
        <? else : ?>
            <p><a href="<?= '?SECTION_ID=' . $arSection['ID']; ?>">Вперед в раздел</a></p>
        <? endif; ?>
    </article>
<? endforeach ?>