<?php


if ($arResult['CANONICAL_LINK']) {
    $APPLICATION->SetPageProperty('canonical', $arResult['CANONICAL_LINK']);
}


/*
if($_GET['REPORT'] == 'Y') {
    if ($arParams['REPORT_AJAX'] == 'Y' && isset($_GET['ID'])) {

        $APPLICATION->RestartBuffer();

        $jsonObject = [
            'success' => false
        ];

        if (CModule::IncludeModule('iblock')) {
            $date = new \Bitrix\Main\Type\Date();

            $arUser = '';
            if ($USER->IsAuthorized())
                $arUser = $USER->GetID() . " (" . $USER->GetLogin() . ") " . $USER->GetFullName();
            else
                $arUser = "Не авторизован";

            $arFields = [
                'IBLOCK_ID' => 8,
                'NAME' => 'Новость ' . $_GET['ID'],
                'ACTIVE_FROM' => \Bitrix\Main\Type\DateTime::createFromTimestamp(time()),
                'PROPERTY_VALUES' => array(
                    'USER' => $arUser,
                    'NEWS' => $_GET['ID']
                )
            ];

            $element = new CIBlockElement();

            if ($elId = $element->Add($arFields)) {
                $jsonObject['success'] = true;
                $jsonObject['ID'] = $elId;
            }
        }

        echo json_encode($jsonObject);

        die();
    } else if ($arParams['REPORT_AJAX'] == 'N' && isset($_GET['ID'])) {
        if (CModule::IncludeModule('iblock')) {
            $date = new \Bitrix\Main\Type\Date();

            $arUser = '';
            if ($USER->IsAuthorized())
                $arUser = $USER->GetID() . " (" . $USER->GetLogin() . ") " . $USER->GetFullName();
            else
                $arUser = "Не авторизован";
            $arFields = [
                'IBLOCK_ID' => 8,
                'NAME' => 'Новость ' . $_GET['ID'],
                'ACTIVE_FROM' => \Bitrix\Main\Type\DateTime::createFromTimestamp(time()),
                'PROPERTY_VALUES' => array(
                    'USER' => $arUser,
                    'NEWS' => $_GET['ID']
                )
            ];

            $element = new CIBlockElement();

            if ($elId = $element->Add($arFields)) {
                $APPLICATION->SetPageProperty('ajax_report_text', "Ваше мнение учтено, №" . $elId);
            } else {
                $APPLICATION->SetPageProperty('ajax_report_text', "Ошибка");
            }
        }
    }



}*/


if ($_GET['TYPE'] == 'REPORT_RESULT') {
    if ($_GET['ID']) {
        echo '<script>
            var textElem = document.getElementById("ajax-report-text");
            textElem.innerText = "Ваше мнение учтено, №' . $_GET['ID'] . '";
            window.history.pushState(null, null, "' . $APPLICATION->GetCurPage() . '");
            </script>';
    } else {
        echo '<script>
                var textElem = document.getElementById("ajax-report-text");
                textElem.innerText = "Ошибка";
            window.history.pushState(null, null, "' . $APPLICATION->GetCurPage() . '");
        </script>';
    }
} else if (isset($_GET['ID'])) {
    $jsonObject = array();

    if (CModule::IncludeModule('iblock')) {
        $arUser = '';
        if ($USER->IsAuthorized())
            $arUser = $USER->GetID() . " (" . $USER->GetLogin() . ") " . $USER->GetFullName();
        else
            $arUser = "Не авторизован";

        $arFields = [
            'IBLOCK_ID' => 8,
            'NAME' => 'Новость ' . $_GET['ID'],
            'ACTIVE_FROM' => \Bitrix\Main\Type\DateTime::createFromTimestamp(time()),
            'PROPERTY_VALUES' => array(
                'USER' => $arUser,
                'NEWS' => $_GET['ID']
            )
        ];

        $element = new CIBlockElement();

        if ($elId = $element->Add($arFields)) {
            $jsonObject['ID'] = $elId;

            if ($_GET['TYPE'] == 'REPORT_AJAX') {
                $APPLICATION->RestartBuffer();
                echo json_encode($jsonObject);
                die();
            } else if ($_GET['TYPE'] == 'REPORT_GET') {
                LocalRedirect($APPLICATION->GetCurPage() . "?TYPE=REPORT_RESULT&ID=" . $jsonObject['ID']);
            }
        } else {
            LocalRedirect($APPLICATION->GetCurPage() . "?TYPE=REPORT_RESULT");
        }
    }
}















