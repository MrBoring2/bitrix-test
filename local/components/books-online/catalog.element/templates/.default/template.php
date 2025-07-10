<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<article>
    <h1><?= htmlspecialchars($arResult['NAME']) ?></h1>

    <?php if (!empty($arResult['DETAIL_PICTURE_SRC'])): ?>
        <img src="<?= htmlspecialchars($arResult['DETAIL_PICTURE_SRC']) ?>" alt="<?= htmlspecialchars($arResult['NAME']) ?>">
    <?php endif; ?>

    <div><?= $arResult['DETAIL_TEXT'] ?></div>
</article>