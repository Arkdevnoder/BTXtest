<?php

/**
*
*	@author PHP Developer
*	@version build#0.0.1
*	@brief Job's Bitrix component
*
*	@var array $arParams
*	[In-environmental variable]
*
*	@var array $arResult
*	[Out-environmental variable]
*
*/

/// Default settings set

if(!isset($arParams["MANAGED_CACHE"])) 
	$arParams["MANAGED_CACHE"] = true;

if(!isset($arParams["IS_CACHE"])) 
	$arParams["IS_CACHE"] = true;

if(!isset($arParams["TIME_CACHE"])) 
	$arParams["TIME_CACHE"] = 10;

if(!isset($arParams["HASH_CACHE"])) 
	$arParams["HASH_CACHE"] = __FILE__;

if(!isset($arParams["TEMPLATE"])) 
	$arParams["TEMPLATE"] = "";

/// Default settings complete

/// Preinit checking of single infoblock

if (!defined("B_PROLOG_INCLUDED") 
	|| B_PROLOG_INCLUDED!==true) die();

CModule::IncludeModule('iblock');

use Bitrix\Main\Application;

$request = Application::getInstance()

->getContext()->getRequest();

$page = $request->get("jid");

/// Preinit checking completed

/// Initialization var's spread of component 

$arOrder = ["SORT" => "ASC"];

$arFilter = [
	'IBLOCK_ID' => (int) $arParams["IB_ID"],
	'ACTIVE' => 'Y',
	'GLOBAL_ACTIVE' => 'Y'
];

$arGroupBy = false;

$arNavStartParams = false;

$arSelectFields = [
	"ID",
	"NAME",
	"PREVIEW_TEXT",
	"DETAIL_TEXT",
	"IBLOCK_ID",
	"IBLOCK_NAME",
	"PROPERTY_".$arParams["FIELD_SALARY"]
];

$spread = [
	$arOrder,
	$arFilter,
	$arGroupBy,
	$arNavStartParams,
	$arSelectFields
];

$isCache = $arParams["IS_CACHE"];

$managedCache = $arParams["MANAGED_CACHE"];

$timeCache = $arParams["TIME_CACHE"];

/// Initialization completed

/// Cache part of the component

if($isCache)
{
	if(!$managedCache)
	{
		$cache = Bitrix\Main\Data\Cache::createInstance();
		if ($cache->initCache($arParams["TIME_CACHE"],
		$arParams["HASH_CACHE"].$page, "/"))
		{
			$result = $cache->getVars();
		}
		elseif ($cache->startDataCache())
		{
			$result = ($page) ? 
			getElement($page) : 
			getJobList(...$spread);
			if ($isInvalid)
			{
			$cache->abortDataCache();
			}
			$cache->endDataCache($result);
		}
	} 
	else
	{
		$cache = \Bitrix\Main\Application
		::getInstance()->getManagedCache();

		if ($cache->read($arParams["TIME_CACHE"],
		$arParams["HASH_CACHE"].$page)) 
		{
			$result = $cache->get($arParams["HASH_CACHE"].$page);
		}
		else
		{
			$result = ($page) ? 
			getElement($page) : 
			getJobList(...$spread);
			$cache->set(
				$arParams["HASH_CACHE"].$page,
				$result
			);
		}
	}
} else {
	$result = ($page) ?
	getElement($page) :
	getJobList(...$spread);
}

$arResult = getJobList(...$spread);

$arResult = (!$page) ? [
	"title" => $result[2],
	"content" =>$result[1]
] : [
	"title" => $result[0],
	"text" => $result[1]
];

/// Cache part completed

/**
*
*	Getting list of vacancies, receive
*	information from infoblocks.
*
*	@param[in] $arOrder - order param
*	@param[in] $arFilter - filter param
*	@param[in] $arGroupBy - group by db param
*	@param[in] $arNavStartParams - offset param
*	@param[in] $arSelectFields - selected
*
*	@param[out] $result[0] - execution time
*	@param[out] $result[1] - result array
*
*/

function getJobList($arOrder, $arFilter, $arGroupBy,
	$arNavStartParams,$arSelectFields){

	$time = microtime(true);
	$res = CIBlockElement::GetList($arOrder, $arFilter, 
		$arGroupBy,$arNavStartParams,$arSelectFields);
	$result = [];
	$infoblock = "";
	while ($element = $res->GetNext())
	{
		$infoblock = $element["IBLOCK_NAME"];

		$result[] = [
			"ID" => $element["ID"],
			"NAME" => $element["NAME"],
			"PREVIEW_TEXT" => $element["PREVIEW_TEXT"],
			"DETAIL_TEXT" => $element["DETAIL_TEXT"],
			"UF_SALARY" => $element["PROPERTY_UF_SALARY_VALUE"]
		];
	}

	return [microtime(true) - $time, $result, $infoblock];
}

/**
*
*	Getting infoblock of job
*
*	@param[in] $id - of infoblock's element
*
*	@param[out] $result[0] - title of ib
*	@param[out] $result[1] - content of ib
*
*/

function getElement($id)
{
	$res = CIBlockElement::GetByID($id);
	if($ar_res = $res->GetNext())
	{
		$pageTitle = $ar_res['NAME'];
		$pageContent = $ar_res['DETAIL_TEXT'];
		return [$pageTitle, $pageContent];
	}
	else
	{
		return null;
	}
}

/// Component out start

$str = "В каталоге вакансий представлено: ".
		count($arResult)." вакансий";

$APPLICATION->SetTitle($str);

$this->IncludeComponentTemplate($arParams["TEMPLATE"]);

/// This component completed

?>