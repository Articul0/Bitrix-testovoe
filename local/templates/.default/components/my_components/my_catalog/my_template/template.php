<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}

$this->addExternalCss("/local/styles.css");

//Bitrix\Main\Diag\Debug::dump($arResult['ELEMENTS']);
//Bitrix\Main\Diag\Debug::dump($arResult['PAGINATION_PARAMS']);

?>

<div class="menu wrapper container">
    <div class="subwrapper">
        <p class="panel"> Сортировать по: </p>
        <a class="sort-filter-links" href="/?SORT_NAME=<?= ($arResult['SORT_NAME'] === 'ASC' ? 'DESC' : 'ASC') . $arResult['PAGINATION_PARAMS']['FILTER'] ?>"> Названию </a>
        <a class="sort-filter-links" href="/?SORT_ID=<?= ($arResult['SORT_ID'] === 'ASC' ? 'DESC' : 'ASC') . $arResult['PAGINATION_PARAMS']['FILTER'] ?>"> Индексу </a>
        <a class="sort-filter-links" href="/?<?= $arResult['PAGINATION_PARAMS']['FILTER'] ?>"> Без сортировки </a>
    </div>
    <div class="subwrapper">
        <p class="panel"> Фильтр: </p>
        <a class="sort-filter-links" href="/"> Все </a>
        <a class="sort-filter-links" href="/?FILTER=discounts"> Акции </a>
        <a class="sort-filter-links" href="/?FILTER=news"> Новинки </a>
    </div>
</div>

<div class="wrapper container">
<?php
    foreach ($arResult['ELEMENTS'] as $itemId => $item):?>
        <div class="subwrapper">
            <h4><?= $item['NAME'] ?></h4>
            <div class="item">
                <img src="<?= $item['PREVIEW_PICTURE_SRC'] ?>">
            </div>
            <div class="url-link item">
                <a href="#"><?= $item['DETAIL_PAGE_URL'] ?></a>
            </div>
            <div class="item">
                <p><?= $item['PREVIEW_TEXT'] ?></p>
            </div>
        </div>
<?php endforeach; ?>
</div>

<div class="nav container">
    <?php
    // Если страница только одна, то панель пагинации не выводится
    if ($arResult['PAGINATION_PARAMS']['PAGES_COUNT'] > 1):

        // Проверяем активная ли первая страница
        if ($arResult['PAGINATION_PARAMS']['CURRENT_PAGE'] !== 1):?>
            <li class="page-item">
                <a class="page-link" href="/?<?=$arResult['PAGINATION_PARAMS']['SORT'] . $arResult['PAGINATION_PARAMS']['FILTER'] . '&PAGE=' .  ($arResult['PAGINATION_PARAMS']['CURRENT_PAGE'] - 1) ?>"> &laquo; </a>
            </li>
            <li class="page-item">
                <a class="page-link" href="/?<?=$arResult['PAGINATION_PARAMS']['SORT'] . $arResult['PAGINATION_PARAMS']['FILTER'] . '&PAGE=1' ?>"> 1 </a>
            </li>
        <?php else: ?>
            <li class="page-item active">
                <a class="page-link" href="/?<?=$arResult['PAGINATION_PARAMS']['SORT'] . $arResult['PAGINATION_PARAMS']['FILTER'] . '&PAGE=1' ?>"> 1 </a>
            </li>
        <?php endif;

        // Проверяем нужно ли многоточие после ссылки на первую страницу
        if ($arResult['PAGINATION_PARAMS']['PAGES_COUNT'] > 6):
            if ($arResult['PAGINATION_PARAMS']['CURRENT_PAGE'] > 4): ?>
                <li class="page-item">
                    <a class="page-link" href="/?<?=$arResult['PAGINATION_PARAMS']['SORT'] . $arResult['PAGINATION_PARAMS']['FILTER'] . '&PAGE=' . ($arResult['PAGINATION_PARAMS']['CURRENT_PAGE'] - 5) ?>"> ... </a>
                </li>
            <?php endif;
        endif;

        // Расставляем номера страниц на панель пагинации
        foreach ($arResult['PAGINATION_PARAMS']['PAGES'] as $page):
            if ($arResult['PAGINATION_PARAMS']['CURRENT_PAGE'] !== $page):?>
                <li class="page-item">
                    <a class="page-link" href="/?<?=$arResult['PAGINATION_PARAMS']['SORT'] . $arResult['PAGINATION_PARAMS']['FILTER'] . '&PAGE=' . $page ?>"> <?= $page ?> </a>
                </li>
            <?php else: ?>
                <li class="page-item active">
                    <a class="page-link" href="/?<?=$arResult['PAGINATION_PARAMS']['SORT'] . $arResult['PAGINATION_PARAMS']['FILTER'] . '&PAGE=' . $page ?>"> <?= $page ?> </a>
                </li>
            <?php endif; ?>
        <?php endforeach;

        // Проверяем нужно ли многоточие перед сылкой на последнюю страницу
        if ($arResult['PAGINATION_PARAMS']['PAGES_COUNT'] > 6):
            if ($arResult['PAGINATION_PARAMS']['CURRENT_PAGE'] < $arResult['PAGINATION_PARAMS']['PAGES_COUNT'] - 3): ?>
                <li class="page-item">
                    <a class="page-link" href="/?<?=$arResult['PAGINATION_PARAMS']['SORT'] . $arResult['PAGINATION_PARAMS']['FILTER'] . '&PAGE=' . ($arResult['PAGINATION_PARAMS']['CURRENT_PAGE'] + 5) ?>"> ... </a>
                </li>
            <?php endif;
        endif;

        // Проверяем активная ли последняя страница
        if ($arResult['PAGINATION_PARAMS']['CURRENT_PAGE'] !== $arResult['PAGINATION_PARAMS']['PAGES_COUNT']):?>
            <li class="page-item">
                <a class="page-link" href="/?<?=$arResult['PAGINATION_PARAMS']['SORT'] . $arResult['PAGINATION_PARAMS']['FILTER'] . '&PAGE=' . $arResult['PAGINATION_PARAMS']['PAGES_COUNT'] ?>"> <?php echo $arResult['PAGINATION_PARAMS']['PAGES_COUNT'] ?> </a>
            </li>
            <li class="page-item">
                <a class="page-link" href="/?<?=$arResult['PAGINATION_PARAMS']['SORT'] . $arResult['PAGINATION_PARAMS']['FILTER'] . '&PAGE=' .  ($arResult['PAGINATION_PARAMS']['CURRENT_PAGE'] + 1) ?>"> &raquo; </a>
            </li>
        <?php else: ?>
            <li class="page-item active">
                <a class="page-link" href="/?<?=$arResult['PAGINATION_PARAMS']['SORT'] . $arResult['PAGINATION_PARAMS']['FILTER'] . '&PAGE=' . $arResult['PAGINATION_PARAMS']['PAGES_COUNT'] ?>"> <?php echo $arResult['PAGINATION_PARAMS']['PAGES_COUNT'] ?> </a>
            </li>
        <?php endif;

    endif; ?>
</div>




