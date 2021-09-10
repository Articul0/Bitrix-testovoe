<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Хто я");

?>

<h1>
    <?php echo $APPLICATION->GetTitle() ?>
</h1>

<div>
    <?$APPLICATION->IncludeComponent(
        'my_components:my_catalog',         // имя компонента
        'my_template',      // шаблон компонента, пустая строка если шаблон по умолчанию
        $arParams=array(),     // параметры
        $parentComponent=null,  // null или объект родительского компонента
        $arFunctionParams=array()
    );?>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>