<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
	
	\Bitrix\Main\UI\Extension::load("ui.alerts");

	if(isset($arResult["content"]))
	{
		echo '<div class="ui-alert ui-alert-primary ui-alert-icon-info">
			<span class="ui-alert-message"><strong>Раздел: '.$arResult['title'].', </strong> список.
		</div>';
		foreach ($arResult['content'] as $key => $value) {
			echo '<a href="?jid='.$value["ID"].'" class="ui-alert ui-alert-default">
				<span class="ui-alert-message">
					<strong>'.$value['NAME'].'</strong><br>
					<strong>Зарплата:</strong> '.$value['UF_SALARY'].'<br>
					<strong>Краткое описание:</strong> '.$value['PREVIEW_TEXT'].'<br>
					<strong>Полное описание:</strong> '.$value['DETAIL_TEXT'].'<br>
				</span>
			</href>';
		}
	} 
	else
	{
		echo '<a href="./" class="ui-alert ui-alert-default">
			<span class="ui-alert-message">
				<strong>'.$arResult['title'].',</strong> '.$arResult['text'].'
			</span>
		</href>';
	}

?>