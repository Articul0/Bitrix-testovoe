<?php

// посм про namespace
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Context;

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

        $request = Context::getCurrent()->getRequest();

        $this->arResult = [];

        $elements = $this->getItems($request);

        // Элементы каталога
        $this->arResult['ELEMENTS'] = $elements['ITEMS'];

        // Параметры, необходимые для корректной работы пагинации
        $this->arResult['PAGINATION_PARAMS'] = $elements['PAGINATION_PARAMS'];

        // Передаём параметры сортировки и фильтра
        $this->arResult['SORT_NAME'] = $request['SORT_NAME'];
        $this->arResult['SORT_ID'] = $request['SORT_ID'];
        $this->arResult['FILTER'] = $request['FILTER'];

        $this->includeComponentTemplate();
    }


    /**
     * @param $request
     * @return array
     */
    private function getItems($request): array
    {
        $items = [];
        $filter = [
            'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
            'ACTIVE' => 'Y',
        ];
        if ($request['FILTER'] ==='discounts') {
            $filter['PROPERTY_SPECIALOFFER_VALUE'] = 'Акция';

        } elseif ($request['FILTER'] ==='news') {
            $filter['PROPERTY_SPECIALOFFER_VALUE'] = 'Новинка';
        }
        $select = [
            'ID',
            'NAME',
            'PREVIEW_PICTURE',
            'PREVIEW_TEXT',
            'DETAIL_PAGE_URL'
        ];

        // Сортировка
        $sort = [];
        if ($request['SORT_NAME'] === 'ASC') {
            $sort['NAME'] = 'ASC';
        } elseif ($request['SORT_NAME'] === 'DESC') {
            $sort['NAME'] = 'DESC';
        }
        if ($request['SORT_ID'] === 'ASC') {
            $sort['ID'] = 'ASC';
        } elseif ($request['SORT_ID'] === 'DESC') {
            $sort['ID'] = 'DESC';
        }

        $elements = CIBlockElement::GetList (
            $sort,
            $filter,
            false,
            Array(
                'nPageSize' => $this->arParams['ELEMENTS_PER_PAGE'],
                'iNumPage' => $this->getCurrentPage((int) $request['PAGE'], $this->getPagesCount())
            ),
            $select
        );

        //Bitrix\Main\Diag\Debug::dump($elements->NavPageCount);
        //Bitrix\Main\Diag\Debug::dump($elements->NavPageNomer);

        while($element = $elements->GetNext()) {
            // Обрезаем текст анонса
            if (strlen($element['PREVIEW_TEXT']) > self::PREVIEW_TEXT_LENGTH) {
                if ($this->ifLastSymbolIsSpace($element['PREVIEW_TEXT'])) {
                    $element['PREVIEW_TEXT'] = $this->cutSymbolsFromTheEnd($element['PREVIEW_TEXT'], 2) . '...';
                } else {
                    $element['PREVIEW_TEXT'] = $this->cutSymbolsFromTheEnd($element['PREVIEW_TEXT'], 1) . '...';
                }
            }
            // Масштабируем картинку анонса
            $element['PREVIEW_PICTURE_SRC'] = $this->resizeImage($element['PREVIEW_PICTURE']);
            $items[] = $element;
        }

        return Array(
            'ITEMS' => $items,
            'PAGINATION_PARAMS' => $this->getPaginationParams($request, $elements->NavPageCount)
        );
    }


    /**
     * @param $request
     * @return array
     */
    private function getPaginationParams($request, $pagesCount): array
    {
        if ($request['FILTER'] !== null) {
            $filter = '&FILTER=' . $request['FILTER'];
        } else {
            $filter = '';
        }
        if ($request['SORT_NAME'] !== null) {
            $sort = 'SORT_NAME=' . $request['SORT_NAME'];
        } else {
            $sort = '';
        }

        $currentPage = $this->getCurrentPage((int) $request['PAGE'], $pagesCount);

        $pagesFrom = $currentPage < 5 ? 2 : $currentPage - 2;
        $pagesTo = ($currentPage > $pagesCount - 3 ? $pagesCount - 1 : $currentPage + 2);

        $pages = [];
        for ($i = $pagesFrom; $i <= $pagesTo; $i++) {
            $pages[] = $i;
        }

        return array('SORT' => $sort, 'FILTER' => $filter, 'PAGES' => $pages, 'CURRENT_PAGE' => $currentPage, 'PAGES_COUNT' => $pagesCount);
    }


    /**
     * @return int
     */
    private function getPagesCount (): int
    {
        return (int) ceil(CIBlock::GetElementCount($this->arParams['IBLOCK_ID']) / $this->arParams['ELEMENTS_PER_PAGE']);
    }


    /**
     * @param int $requestedPage
     * @param int $pagesCount
     * @return int
     */
    private function getCurrentPage (int $requestedPage, int $pagesCount): int
    {
        if ($requestedPage != null) {
            if ($requestedPage > 0 && $requestedPage < $pagesCount + 1) {
                $currentPage = $requestedPage;
            } elseif ($requestedPage > $pagesCount) {
                $currentPage = $pagesCount;
            } else {
                $currentPage = 1;
            }
        } else {
            $currentPage = 1;
        }
        return $currentPage;
    }


    /**
     * @param string $previewText
     * @return bool
     */
    private function ifLastSymbolIsSpace (string $previewText): bool
    {
        return strcasecmp(substr($previewText, self::PREVIEW_TEXT_LENGTH, 1), ' ') === 0;
    }


    /**
     * @param string $text
     * @param int $symbolsCount
     * @return string
     */
    private function cutSymbolsFromTheEnd (string $text, int $symbolsCount): string
    {
        return substr($text, 0, self::PREVIEW_TEXT_LENGTH - $symbolsCount);
    }


    /**
     * @param string $image
     * @return string
     */
    private function resizeImage (string $image): string
    {
        return CFile::ResizeImageGet($image, self::IMAGE_SIZE, BX_RESIZE_IMAGE_EXACT)['src'];
    }


    /**
     * @throws LoaderException
     */
    private function includeModules(): void
    {
        Loader::includeModule('iblock');
    }
}