<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}

$this->addExternalCss("/local/styles.css");

//Bitrix\Main\Diag\Debug::dump($arResult['PAGINATION_PARAMS']['PAGES']);

?>

<div class="menu wrapper container">
    <div class="subwrapper">
        Сортировать по:
        <a href="/?SORT_NAME=<?= ($arResult['SORT_NAME'] == 'ASC' ? 'DESC' : 'ASC') . $arResult['PAGINATION_PARAMS']['FILTER'] ?>"> Названию </a>
        <a href="/?SORT_ID=<?= ($arResult['SORT_ID'] == 'ASC' ? 'DESC' : 'ASC') . $arResult['PAGINATION_PARAMS']['FILTER'] ?>"> Индексу </a>
        <a href="/?<?= $arResult['PAGINATION_PARAMS']['FILTER'] ?>"> Без сортировки </a>
    </div>
    <div class="subwrapper">
        Фильтр:
        <a href="/"> Все </a>
        <a href="/?FILTER=discounts"> Акции </a>
        <a href="/?FILTER=news"> Новинки </a>
    </div>
</div>

<div class="wrapper container">
<?php
    foreach ($arResult['ELEMENTS'] as $itemId => $item):?>
        <div class="subwrapper">
            <h4><?= $item['NAME'] ?></h4>
            <div class="url-link item">
                <a><?= $item['DETAIL_PAGE_URL'] ?></a>
            </div>
            <div class="item">
                <img src="<?= $item['PREVIEW_PICTURE_SRC'] ?>">
            </div>
            <div class="item">
                <p><?= $item['PREVIEW_TEXT'] ?></p>
            </div>
        </div>
<?php endforeach; ?>
</div>

<div class="container">
    <?php
    if ($arResult['PAGINATION_PARAMS']['PAGES_COUNT'] > 1):
        if ($arResult['PAGINATION_PARAMS']['PAGES_COUNT'] > 2):
            if ($arResult['PAGINATION_PARAMS']['PAGES_COUNT'] > 6):
                if ($arResult['PAGINATION_PARAMS']['CURRENT_PAGE'] != 1):?>
                    <a href="/?<?=$arResult['PAGINATION_PARAMS']['SORT'] . $arResult['PAGINATION_PARAMS']['FILTER'] . '&PAGE=' .  ($arResult['PAGINATION_PARAMS']['CURRENT_PAGE'] - 1) ?>"> Назад </a>
                <?php endif; ?>
                <a href="/?<?=$arResult['PAGINATION_PARAMS']['SORT'] . $arResult['PAGINATION_PARAMS']['FILTER'] . '&PAGE=1' ?>"> 1 </a>
                <?php
                if ($arResult['PAGINATION_PARAMS']['CURRENT_PAGE'] > 4): ?>
                    ...
                <?php endif;
                foreach ($arResult['PAGINATION_PARAMS']['PAGES'] as $page): ?>
                    <a href="/?<?=$arResult['PAGINATION_PARAMS']['SORT'] . $arResult['PAGINATION_PARAMS']['FILTER'] . '&PAGE=' . $page ?>"> <?= $page ?> </a>
                <?php endforeach;
                if ($arResult['PAGINATION_PARAMS']['CURRENT_PAGE'] < $arResult['PAGINATION_PARAMS']['PAGES_COUNT'] - 3): ?>
                    ...
                <?php endif; ?>
                <a href="/?<?=$arResult['PAGINATION_PARAMS']['SORT'] . $arResult['PAGINATION_PARAMS']['FILTER'] . '&PAGE=' . $arResult['PAGINATION_PARAMS']['PAGES_COUNT'] ?>"> <?php echo $arResult['PAGINATION_PARAMS']['PAGES_COUNT'] ?> </a>
                <?php
                if ($arResult['PAGINATION_PARAMS']['CURRENT_PAGE'] != $arResult['PAGINATION_PARAMS']['PAGES_COUNT']):?>
                    <a href="/?<?=$arResult['PAGINATION_PARAMS']['SORT'] . $arResult['PAGINATION_PARAMS']['FILTER'] . '&PAGE=' .  ($arResult['PAGINATION_PARAMS']['CURRENT_PAGE'] + 1) ?>"> Вперёд </a>
                <?php endif; ?>
            <?php else: ?>
                <a href="/?<?=$arResult['PAGINATION_PARAMS']['SORT'] . $arResult['PAGINATION_PARAMS']['FILTER'] . '&PAGE=1' ?>"> 1 </a>
                <?php
                foreach ($arResult['PAGINATION_PARAMS']['PAGES'] as $page): ?>
                    <a href="/?<?=$arResult['PAGINATION_PARAMS']['SORT'] . $arResult['PAGINATION_PARAMS']['FILTER'] . '&PAGE=' . $page ?>"> <?= $page ?> </a>
                <?php endforeach; ?>
                <a href="/?<?=$arResult['PAGINATION_PARAMS']['SORT'] . $arResult['PAGINATION_PARAMS']['FILTER'] . '&PAGE=' . $arResult['PAGINATION_PARAMS']['PAGES_COUNT'] ?>"> <?php echo $arResult['PAGINATION_PARAMS']['PAGES_COUNT'] ?> </a>
            <?php endif;
        else: ?>
            <a href="/?<?=$arResult['PAGINATION_PARAMS']['SORT'] . $arResult['PAGINATION_PARAMS']['FILTER'] . '&PAGE=1' ?>"> 1 </a>
            <a href="/?<?=$arResult['PAGINATION_PARAMS']['SORT'] . $arResult['PAGINATION_PARAMS']['FILTER'] . '&PAGE=' . $arResult['PAGINATION_PARAMS']['PAGES_COUNT'] ?>"> 2 </a>
        <?php endif;
    endif; ?>
</div>




