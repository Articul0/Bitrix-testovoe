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

        $cacheParams = ['ELEMENTS_COUNT' => $this->arParams['ELEMENTS_PER_PAGE'], 'REQUEST' => $request];

        if ($this->StartResultCache($this->arParams['CACHE_TIME'], $cacheParams)) {

            $this->arResult = [];

            // Параметры для отображения полей фильтрации в шаблоне
            $propertyParams = $this->getPropertyParams($this->arParams['FILTER_PROPERTY']);
            $this->arResult['PROPERTY_PARAMS'] = $propertyParams;

            $elements = $this->getItems($request, $propertyParams);

            // Элементы каталога
            $this->arResult['ELEMENTS'] = $elements['ITEMS'];

            // Параметр, необходимый для работы пагинации
            $this->arResult['NAV'] = $elements['NAV'];

            // Параметры сортировки и фильтрации
            $this->arResult['SORT_NAME'] = $request['SORT_NAME'];
            $this->arResult['SORT_ID'] = $request['SORT_ID'];
            $this->arResult['FILTER'] = $elements['FILTER'];

            $this->includeComponentTemplate();
        }
    }

    /**
     * @param $propertyName
     * @return array
     */
    private function getPropertyParams ($propertyName): array
    {
        $propertyParams = CIBlockPropertyEnum::GetList(
            [],
            ['CODE' => $propertyName]
        );
        $property = [];
        while ($param = $propertyParams->GetNext()) {
            $property[$param['XML_ID']] = $param['VALUE'];
        }
        return $property;
    }

    /**
     * @param $request
     * @param array $propertyParams
     * @return array
     */
    private function getItems($request, array $propertyParams): array
    {
        $items = [];
        $filter = [
            'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
            'ACTIVE' => 'Y',
        ];
        foreach ($propertyParams as $propertyXmlId => $property) {
            if ($request['FILTER'] === $propertyXmlId) {
                $filter['PROPERTY_SPECIAL_OFFER_VALUE'] = $property;
                break;
            }
        }

        $select = [
            'ID',
            'NAME',
            'PREVIEW_PICTURE',
            'PREVIEW_TEXT',
            'DETAIL_PAGE_URL'
        ];

        // Сортировка задаётся либо по 'NAME', либо по 'ID'
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
            'FILTER' => $this->getFilterParams($request),
            'NAV' => $elements
        );
    }


    /**
     * @param $request
     * @return string
     */
    private function getFilterParams($request): string
    {
        if ($request['FILTER'] !== null) {
            $filter = '&FILTER=' . $request['FILTER'];
        } else {
            $filter = '';
        }

        return $filter;
    }


    /**
     * @return int
     */
    private function getPagesCount (): int
    {
        return (int) ceil(CIBlock::GetElementCount($this->arParams['IBLOCK_ID']) / $this->arParams['ELEMENTS_PER_PAGE']);
    }


    /**
     * @param int|null $requestedPage
     * @param int $pagesCount
     * @return int
     */
    private function getCurrentPage (?int $requestedPage, int $pagesCount): int
    {
        if ($requestedPage !== null) {
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