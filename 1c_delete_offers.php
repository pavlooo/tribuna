<?php
	set_time_limit(0);
	$_SERVER['DOCUMENT_ROOT'] = '/d/www/t/tribuna.com.ru/docs';
	require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
	// удаляем OFFERS без сезона
	
	if(CModule::IncludeModule("iblock")){
		$last_id = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/bitrix/log/last__del_offer_id.txt');
		$arSelect = Array("ID", "IBLOCK_ID", "PROPERTY_SEZON");
		$arFilter = Array("IBLOCK_ID"=> 8, "PROPERTY_SEZON" => false, '>ID' => $last_id);
		$res = CIBlockElement::GetList(Array('ID' => 'ASC'), $arFilter, false, Array('nTopCount' => 25), $arSelect);
		$next = false;
		while($ob = $res->GetNextElement())
		{
			$arFields = $ob->GetFields();
			if(isset($arFields['PROPERTY_SEZON_VALUE']{0})) continue;
			CIBlockElement::Delete($arFields["ID"]);
			
			$last_id = $arFields['ID'];
			$next = true;
		}
		echo $last_id;
		file_put_contents($_SERVER['DOCUMENT_ROOT'].'/bitrix/log/last__del_offer_id.txt', $last_id);
		
		if($next){
			//echo '<meta http-equiv="refresh" content="0; url="http://www.tribuna.com.ru/bitrix/1c_delete_offers.php">'; 
			exec("php /d/www/t/tribuna.com.ru/docs/bitrix/1c_delete_offers.php");
			}else{
			file_put_contents($_SERVER['DOCUMENT_ROOT'].'/bitrix/log/last__del_offer_id.txt', '0');
			die("THE END");	
		}
	}
?>