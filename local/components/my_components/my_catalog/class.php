<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}

class MyCatalogComponent extends CBitrixComponent
{
    public function executeComponent()
    {
        $elements = [
            'ID' => null,
            'NAME' => null,
            'PREVIEW_PICTURE' => null,
            'PREVIEW_TEXT' => null,
            'PROPERTY_LIN_PR' => null
        ];

        if (CModule::IncludeModule("iblock")):
            // ID инфоблока из которого выводим элементы
            $iblock_id = 5;
            $elements = CIBlockElement::GetList (
                // Сортировка элементов
                Array("ID" => "ASC"),
                Array("IBLOCK_ID" => $iblock_id),
                false,
                false,
                // Перечисляесм все свойства элементов, которые планируем выводить
                Array(
                    'ID',
                    'NAME',
                    'PREVIEW_PICTURE',
                    'PREVIEW_TEXT',
                    'PROPERTY_LIN_PR'
                )
            );
        endif;

        $this->arResult = ['какжесложнотоблять' => 2, 'ELEMENTS' => $elements];
        $this->includeComponentTemplate();
    }
}