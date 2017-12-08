<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */


/*************************************************************************
	Processing of received parameters
*************************************************************************/
if (! isset($arParams["CACHE_TIME"]))
    $arParams["CACHE_TIME"] = 180;



if (! CModule::IncludeModule("iblock")) {
    ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
    return;
}

if ( !$arParams['IBLOCK_NEWS'] && ! $arParams['PROPERTY_CODE_NEWS_AUTHOR'] && ! $arParams['USER_PROPERTY_CODE_AUTHOR'])
    return;



if ($this->StartResultCache()) {
    global $USER;

    $currentUserId = $USER->GetID();

    $rsSelect = array(
        'SELECT' => array(
            "ID",
            "LOGIN",
            $arParams['USER_PROPERTY_CODE_AUTHOR']
        )
    );
    $rsFilter = array(
        "ACTIVE" => 'Y',
        "ID" => $currentUserId
    );
    $currentUserGroup = CUser::GetList($by = 'ID', $order = 'desc', $rsFilter, $rsSelect)->Fetch()[$arParams['USER_PROPERTY_CODE_AUTHOR']];
    $currentUserGroup = intval($currentUserGroup);


    $rsSelect = array(
        'SELECT' => array(
            "ID",
            "NAME",
            $arParams['USER_PROPERTY_CODE_AUTHOR']
        )
    );
    $rsFilter = array(
        "ACTIVE" => 'Y',
        "!ID" => $currentUserId,
        $arParams['USER_PROPERTY_CODE_AUTHOR'] => $currentUserGroup,
        "!" . $arParams['USER_PROPERTY_CODE_AUTHOR'] => false,
    );

    $arUsers = CUser::GetList($by = 'ID', $order = 'asc', $rsFilter, $rsSelect);

    $userList = array();
    $userIds = array();


    while ($user = $arUsers->Fetch()) {
        $userList[$user['ID']] = ['LOGIN' => $user['LOGIN']];
        $userIds[] = intval($user['ID']);
    }


    $rsSelect = array(
        "ID",
        "NAME",
        "ACTIVE_FROM",
        $arParams['PROPERTY_CODE_NEWS_AUTHOR']
    );

    $rsFilter = array(
        "IBLOCK_ID" => $arParams['IBLOCK_NEWS'],
        "!" . $arParams['PROPERTY_CODE_NEWS_AUTHOR'] => $currentUserId,
        $arParams['PROPERTY_CODE_NEWS_AUTHOR'] => $userIds
    );

    $arNews = CIBlockElement::GetList(array(), $rsFilter, false, false, $rsSelect);


    while ($news = $arNews->Fetch()) {
        $userList[$news[$arParams['PROPERTY_CODE_NEWS_AUTHOR'] . "_VALUE"]]['NEWS'][$news['ID']] = array(
            "NAME" => $news['NAME'],
            "ACTIVE_FROM" => $news['ACTIVE_FROM']
        );
    }

    $allNews = array();

    foreach ($userList as $ulK => $ulV) {
        foreach ($ulV['NEWS'] as $nK => $nV) {
            $allNews[$nK] = $nV;
        }
    }

    $APPLICATION->SetTitle('Новостей ' . count($allNews));

    $arResult = $userList;

    $this->IncludeComponentTemplate();
} else {
    $this->abortResultCache();
}