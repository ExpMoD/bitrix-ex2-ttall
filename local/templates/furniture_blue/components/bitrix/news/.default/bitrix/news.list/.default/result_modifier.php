<?php

if ($arParams['SET_SPECIALDATE'] == 'Y') {
    $arResult['SPECIALDATE_TEXT'] = $arResult['ITEMS'][0]['ACTIVE_FROM'];

    $this->__component->SetResultCacheKeys(array('SPECIALDATE_TEXT'));
}