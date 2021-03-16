<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arComponentParameters = array(
	"PARAMETERS" => array(
		"LOCATED_BASKET" => array(
			"PARENT" => "BASE",
			"NAME" => Loc::getMessage("LOCATED_BASKET_NAME"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"ELEMENT_ID" => array(
			"PARENT" => "BASE",
			"NAME" => Loc::getMessage("ELEMENT_ID_NAME"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
	)
);
?>