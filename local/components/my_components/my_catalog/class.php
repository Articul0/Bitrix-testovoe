<?php

// посм про namespace
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}

class MyCatalogComponent extends CBitrixComponent
{
    const IMAGE_SIZE = ['width' => 256, 'height' => 256];


    /**
     * @return mixed|void|null
     * @throws LoaderException
     */
    public function executeComponent()
    {
        $this->includeModules();

        $this->arResult = ['ELEMENTS' => $this->getItems()];

        $this->includeComponentTemplate();
    }


    /**
     * @return array
     */
    private function getItems(): array
    {
        $items = [];
        $filter = [
            'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
            'ACTIVE' => 'Y',
        ];

        $select = [
            'ID',
            'NAME',
            'PREVIEW_PICTURE',
            'PREVIEW_TEXT',
            'DETAIL_PAGE_URL'
        ];

        $elements = CIBlockElement::GetList (
            [],
            $filter,
            false,
            false,
            // Перечисляесм все свойства элементов, которые планируем выводить
            $select
        );

        while($element = $elements->GetNext()){
            $element['PREVIEW_PICTURE_SRC'] = CFile::ResizeImageGet($element["PREVIEW_PICTURE"], $this::IMAGE_SIZE)['src'];
            $items[] = $element;
        }

        return $items;
    }

    /**
     * @throws LoaderException
     */
    private function includeModules(): void
    {
        Loader::includeModule('iblock');
    }
}