<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => "Legacy тестовое задание",
	"DESCRIPTION" => "Вакансии",
	"ICON" => "/images/catalog.gif",
	"COMPLEX" => "Y",
	"SORT" => 10,
	"PATH" => array(
		"ID" => "content",
		"CHILD" => array(
			"ID" => "legacy.test",
			"NAME" => "legacy.test",
			"SORT" => 30,
		)
	)
);
?>