<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}

// Bitrix\Main\Diag\Debug::dump($arResult);
echo $arResult['какжесложнотоблять'];


while($ar_fields = $arResult['ELEMENTS']->GetNext())
{
    //Выводим элемент со всеми свойствами + верстка
    $img_path = CFile::GetPath($ar_fields["PREVIEW_PICTURE"]);
    //echo '<li><a href="'.$ar_fields['PROPERTY_LIN_PR_VALUE'].'">';

    echo '<h4>'.$ar_fields['NAME']."</h4>";
    echo "<img src='".$img_path."'/>";
    echo "<p>".$ar_fields['PREVIEW_TEXT']."</p>";
    echo '</a>';
}



