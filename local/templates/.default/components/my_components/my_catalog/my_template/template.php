<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}

//Bitrix\Main\Diag\Debug::dump($arResult);
?>

<div class="wrapper container" style="display: flex; justify-content: space-between">
    <div class="subwrapper">
        gjgf
    </div>
    <div class="subwrapper">
        gbcz
    </div>
</div>

<div class="wrapper container" style="display: flex; flex-wrap: wrap">
<?php
    foreach ($arResult['ELEMENTS'] as $itemId => $item):?>
        <div class="subwrapper" style="padding: 10px">
            <h4><?= $item['NAME'] ?></h4>
            <div style="height: 230px">
                <img src="<?= $item['PREVIEW_PICTURE_SRC'] ?>">
            </div>
            <div style="width: 230px">
                <p><?= $item['PREVIEW_TEXT_SRC'] ?></p>
                <p><?= $item['DETAIL_PAGE_URL'] ?></p>
            </div>
        </div>
<?php endforeach; ?>
</div>




