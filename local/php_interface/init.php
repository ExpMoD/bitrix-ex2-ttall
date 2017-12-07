<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 07.12.17
 * Time: 10:56
 */

if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/local/php_interface/include/EventHandlers.php"))
    require_once($_SERVER['DOCUMENT_ROOT'] . "/local/php_interface/include/EventHandlers.php");

if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/local/php_interface/include/Seo.php"))
    require_once($_SERVER['DOCUMENT_ROOT'] . "/local/php_interface/include/Seo.php");