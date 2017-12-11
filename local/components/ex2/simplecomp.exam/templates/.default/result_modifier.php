<?php

$allPrices = array();

foreach ($arResult['ITEMS'] as $item) {
    $allPrices[] = $item['PRICE'];
}

$arResult['MIN_PRICE'] = min($allPrices);
$arResult['MAX_PRICE'] = max($allPrices);
$this->__component->SetResultCacheKeys(array('MIN_PRICE', 'MAX_PRICE'));