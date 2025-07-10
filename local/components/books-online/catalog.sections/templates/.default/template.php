<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?php if ($arResult['PARENT_SECTION']): ?>
    <div class="section-back">
         <a href="?section=<?= htmlspecialchars($arResult['BACK_SECTION_CODE']) ?>"
           class="ajax-section-link"
           data-section="<?= htmlspecialchars($arResult['BACK_SECTION_CODE']) ?>"
           data-iblock-id="<?= htmlspecialchars($arResult['IBLOCK_ID']) ?>"
           data-count="<?= htmlspecialchars($arResult["ELEMENT_COUNT"]) ?>">← Назад</a>
    </div>
    <h3><?= htmlspecialchars($arResult['PARENT_SECTION']['NAME']) ?></h3>
<?php else: ?>
    <h3>Разделы каталога</h3>
<?php endif; ?>

<?php foreach ($arResult['SECTIONS'] as $section): ?>
    <div class="section-item">
        <a href="?section=<?= htmlspecialchars($section['CODE']) ?>"
           class="ajax-section-link"
           data-section="<?= htmlspecialchars($section['CODE']) ?>"
           data-iblock-id="<?= htmlspecialchars($arResult['IBLOCK_ID']) ?>"
           data-count="<?= htmlspecialchars($arResult["ELEMENT_COUNT"]) ?>">
           <?= htmlspecialchars($section['NAME']) ?>
        </a>
    </div>
<?php endforeach; ?>
