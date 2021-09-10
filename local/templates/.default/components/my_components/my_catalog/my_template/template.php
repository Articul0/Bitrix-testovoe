<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}

//Bitrix\Main\Diag\Debug::dump($arResult);

foreach ($arResult['ELEMENTS'] as $itemId => $item):?>

    <div class="wrapper">
        <div class="subwrapper">
            <h4><?= $item['NAME'] ?></h4>
            <img src="<?= $item['PREVIEW_PICTURE_SRC'] ?>">
            <p><?= $item['PREVIEW_TEXT'] ?></p>
            <p><?= $item['DETAIL_PAGE_URL'] ?></p>
        </div>
    </div>

<?php endforeach; ?>



