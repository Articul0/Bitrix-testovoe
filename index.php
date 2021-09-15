<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Bitrix // тестовое // Якимчук");

?>

<div>
    <?$APPLICATION->IncludeComponent(
        'my_components:my_catalog', // имя компонента
        'my_template',  // шаблон компонента, пустая строка если шаблон по умолчанию
        $arParams=array('IBLOCK_ID' => 5, 'ELEMENTS_PER_PAGE' => 1, 'CACHE_TIME' => 3600),   // параметры
        $parentComponent=null,  // null или объект родительского компонента
        $arFunctionParams=array()
    );?>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>