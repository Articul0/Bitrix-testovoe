<?php

// посм про namespace
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}

class MyCatalogComponent extends CBitrixComponent
{
    const IMAGE_SIZE = ['width' => 230, 'height' => 230];
    const PREVIEW_TEXT_LENGTH = 100;


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
            // Обрезаем текст анонса
            if (strlen($element['PREVIEW_TEXT']) > $this::PREVIEW_TEXT_LENGTH):
                if (strcasecmp(substr($element['PREVIEW_TEXT'], $this::PREVIEW_TEXT_LENGTH, 1), ' ') == 0):
                    $element['PREVIEW_TEXT_SRC'] = substr($element['PREVIEW_TEXT'], 0, $this::PREVIEW_TEXT_LENGTH - 2) . '...';
                else:
                    $element['PREVIEW_TEXT_SRC'] = substr($element['PREVIEW_TEXT'], 0, $this::PREVIEW_TEXT_LENGTH - 1) . '...';
                endif;
            else:
                $element['PREVIEW_TEXT_SRC'] = $element['PREVIEW_TEXT'];
            endif;
            // Масштабируем картинку анонса
            $element['PREVIEW_PICTURE_SRC'] = CFile::ResizeImageGet($element["PREVIEW_PICTURE"], $this::IMAGE_SIZE, BX_RESIZE_IMAGE_EXACT)['src'];
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