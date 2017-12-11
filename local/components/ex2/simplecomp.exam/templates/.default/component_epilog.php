<?php


if (isset($arResult['MIN_PRICE']) && isset($arResult['MAX_PRICE'])) {
    $infoTemplate = '<div style="color:red; margin: 34px 15px 35px 15px">#text#</div>';

    $text = "Мин - {$arResult['MIN_PRICE']}, Макс - {$arResult['MAX_PRICE']}";

    $info = str_replace('#text#', $text, $infoTemplate);

    $APPLICATION->SetPageProperty('ComponentInfo', $info);
}