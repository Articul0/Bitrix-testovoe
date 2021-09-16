<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}
//Bitrix\Main\Diag\Debug::dump($arResult);

?>

<div class="nav container">
    <?php

    $newUrlPathParams = strstr($arResult['sUrlPathParams'], 'PAGE=', true);
    if ($newUrlPathParams === false) {
        $newUrlPathParams = $arResult['sUrlPathParams'];
    }

    // Если страница только одна, то панель пагинации не выводится
    if ($arResult['NavPageCount'] > 1):

        // Проверяем активная ли первая страница
        if ($arResult['NavPageNomer'] != 1):?>
            <li class="page-item">
                <a class="page-link" href="<?=$newUrlPathParams . 'PAGE=' . ($arResult['NavPageNomer'] - 1) ?>"> &laquo; </a>
            </li>
            <li class="page-item">
                <a class="page-link" href="<?=$newUrlPathParams . 'PAGE=1' ?>"> 1 </a>
            </li>
        <?php else: ?>
            <li class="page-item active">
                <a class="page-link" href="<?=$newUrlPathParams . 'PAGE=1' ?>"> 1 </a>
            </li>
        <?php endif;

        // Проверяем нужно ли многоточие после ссылки на первую страницу
        if ($arResult['NavPageCount'] > 6):
            if ($arResult['NavPageNomer'] > 3): ?>
                <li class="page-item">
                    <a class="page-link" href="<?=$newUrlPathParams . 'PAGE=' . ($arResult['NavPageNomer'] - 5) ?>"> ... </a>
                </li>
            <?php endif;
        endif;

        // Расставляем номера страниц на панель пагинации
        for ($page = $arResult['nStartPage'] + 1; $page < $arResult['nEndPage']; $page++):
            if ($arResult['NavPageNomer'] != $page):?>
                <li class="page-item">
                    <a class="page-link" href="<?=$newUrlPathParams . 'PAGE=' . $page ?>"> <?= $page ?> </a>
                </li>
            <?php else: ?>
                <li class="page-item active">
                    <a class="page-link" href="<?=$newUrlPathParams . 'PAGE=' . $page ?>"> <?= $page ?> </a>
                </li>
            <?php endif; ?>
        <?php endfor;

        // Проверяем нужно ли многоточие перед сылкой на последнюю страницу
        if ($arResult['NavPageCount'] > 6):
            if ($arResult['NavPageNomer'] < $arResult['NavPageCount'] - 2): ?>
                <li class="page-item">
                    <a class="page-link" href="<?=$newUrlPathParams . 'PAGE=' . ($arResult['NavPageNomer'] + 5) ?>"> ... </a>
                </li>
            <?php endif;
        endif;

        // Проверяем активная ли последняя страница
        if ($arResult['NavPageNomer'] != $arResult['NavPageCount']):?>
            <li class="page-item">
                <a class="page-link" href="<?=$newUrlPathParams . 'PAGE=' . $arResult['NavPageCount'] ?>"> <?php echo $arResult['NavPageCount'] ?> </a>
            </li>
            <li class="page-item">
                <a class="page-link" href="<?=$newUrlPathParams . 'PAGE=' .  ($arResult['NavPageNomer'] + 1) ?>"> &raquo; </a>
            </li>
        <?php else: ?>
            <li class="page-item active">
                <a class="page-link" href="<?=$newUrlPathParams . 'PAGE=' . $arResult['NavPageCount'] ?>"> <?php echo $arResult['NavPageCount'] ?> </a>
            </li>
        <?php endif;

    endif; ?>
</div>