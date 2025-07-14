<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div id="section-meta" data-title="<?= htmlspecialchars($arResult['PARENT_SECTION']['NAME'] ?? 'Книги') ?>"></div>
<? if($arResult['PARENT_SECTION']): ?>
    <p class="section-title"><?= htmlspecialchars($arResult['PARENT_SECTION']['NAME']) ?></p>
<?php else: ?>
    <p class="section-title">Книги</p>
<?php endif; ?>

<?php if ($arResult['PARENT_SECTION']): ?>
    <div class="section-navigation">
        <div class="section-back">
         <a href="?section=<?= htmlspecialchars($arResult['BACK_SECTION_CODE']) ?>"
           class="ajax-section-link"
           data-section="<?= htmlspecialchars($arResult['BACK_SECTION_CODE']) ?>"
           data-iblock-id="<?= htmlspecialchars($arResult['IBLOCK_ID']) ?>"
           data-count="<?= htmlspecialchars($arResult["ELEMENT_COUNT"]) ?>"
           >← Назад</a>
        </div>
        <?php if (!empty($arResult['SECTION_CHAIN'])): ?>
        <div class="breadcrumbs">
        <?php foreach ($arResult['SECTION_CHAIN'] as $i => $section): ?>
               <a href="?section=<?= htmlspecialchars($section['CODE']) ?>"
               class="ajax-section-link"
               data-section="<?= htmlspecialchars($section['CODE']) ?>"
               data-iblock-id="<?= htmlspecialchars($arResult['IBLOCK_ID']) ?>"
               data-count="<?= htmlspecialchars($arResult['ELEMENT_COUNT']) ?>">
                <i class="fa fa-chevron-left"></i>
                    <?= htmlspecialchars($section['NAME']) ?>
                </a>
        <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
<?php endif; ?>

<?php foreach ($arResult['SECTIONS'] as $section): ?>
    <div class="section-item  <?if ($arResult['PARENT_SECTION']): ?>child <?endif?>">
        <a href="?section=<?= htmlspecialchars($section['CODE']) ?>"
           class="ajax-section-link"
           data-section="<?= htmlspecialchars($section['CODE']) ?>"
           data-iblock-id="<?= htmlspecialchars($arResult['IBLOCK_ID']) ?>"
           data-count="<?= htmlspecialchars($arResult["ELEMENT_COUNT"]) ?>">
           <?= htmlspecialchars($section['NAME']) ?>
        </a>
    </div>
<?php endforeach; ?>
