<?php


if ($arResult['CANONICAL_LINK']) {
    $APPLICATION->SetPageProperty('canonical', $arResult['CANONICAL_LINK']);
}

if ($_GET['TYPE'] == "AJAX_RESULT") {
    if ($_GET['ID']) {
        echo '
            <script>
                var textElem = document.getElementById("ajax-report-text");
                textElem.innerText = "Ваше мнение учтено, №' . $_GET['ID'] . '";
                window.history.pushState(null, null, "' . $APPLICATION->GetCurPage() . '");
            </script>
        ';
    } else {
        echo '
            <script>
                var textElem = document.getElementById("ajax-report-text");
                textElem.innerText = "Ошибка!";
                window.history.pushState(null, null, "' . $APPLICATION->GetCurPage() . '");
            </script>
        ';
    }
} else if (isset($_GET['ID']) && CModule::IncludeModule('iblock')) {
    $userProp = "Не авторизован";

    if ($USER->IsAuthorized()) {
        $userProp = $USER->GetId() . " (" . $USER->GetLogin() . ") " . $USER->GetFullName();
    }

    $arFields = [
        'IBLOCK_ID' => 8,
        'NAME' => "Жалоба на новость",
        'ACTIVE_FROM' => \Bitrix\Main\Type\DateTime::createFromTimestamp(time()),
        'PROPERTY_VALUES' => array(
            'NEWS' => $_GET['ID'],
            'USER' => $userProp
        ),

    ];

    $element = new CIBlockElement();

    if ($elId = $element->Add($arFields)) {
        if ($_GET['TYPE'] == "AJAX_REPORT") {
            $APPLICATION->RestartBuffer();
            $jsonObject['ID'] = $elId;
            echo json_encode($jsonObject);
            die();
        } else if ($_GET['TYPE'] == "GET_REPORT") {
            LocalRedirect($APPLICATION->GetCurPage() . "?TYPE=AJAX_RESULT&ID=" . $elId);
        }
    } else {
        LocalRedirect($APPLICATION->GetCurPage() . "?TYPE=AJAX_RESULT");
    }
}













