<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}

$this->addExternalCss("/local/styles.css");

//Bitrix\Main\Diag\Debug::dump($arResult['ELEMENTS']);
//Bitrix\Main\Diag\Debug::dump($arResult['PROPERTY_PARAMS']);

?>

<div class="menu wrapper container">
    <div class="subwrapper">
        <p class="panel"> Сортировать по: </p>
        <a class="sort-filter-links" href="/?SORT_NAME=<?= ($arResult['SORT_NAME'] === 'ASC' ? 'DESC' : 'ASC') . $arResult['FILTER'] ?>"> Названию </a>
        <a class="sort-filter-links" href="/?SORT_ID=<?= ($arResult['SORT_ID'] === 'ASC' ? 'DESC' : 'ASC') . $arResult['FILTER'] ?>"> Индексу </a>
        <a class="sort-filter-links" href="/?<?= $arResult['FILTER'] ?>"> Без сортировки </a>
    </div>
    <div class="subwrapper">
        <p class="panel"> Фильтр: </p>
        <a class="sort-filter-links" href="/"> Все </a>
        <?php
        foreach ($arResult['PROPERTY_PARAMS'] as $propertyXmlId => $property):?>
            <a class="sort-filter-links" href="/?FILTER=<?php echo $propertyXmlId ?>"> <?php echo $property ?> </a>
        <?php endforeach; ?>
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

<?php
    $APPLICATION->IncludeComponent(
        'bitrix:system.pagenavigation',
        $arParams['PAGINATION_TEMPLATE'],
        array(
            'NAV_TITLE'   => 'Элементы',
            'NAV_RESULT'  => $arResult['NAV'],
            'SHOW_ALWAYS' => false
        )
    );
?>







