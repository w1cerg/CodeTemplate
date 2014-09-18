/*
 * Получить значения для списка пользовательского поля
 */

function GetUserField($ID)
{
	$UserField = CUserFieldEnum::GetList(array(), array("ID" => $ID));
	if($UserFieldAr = $UserField->GetNext())
	{
		return $UserFieldAr["VALUE"];
	}
	else return false;
}

/*
 * получить список пользовательского поля
 */

$arUserFields = $GLOBALS["USER_FIELD_MANAGER"]->GetUserFields("IBLOCK_2_SECTION", LANGUAGE_ID);
foreach ($arUserFields as $FIELD_NAME => $arUserField){
 	test_dump($arUserField);
}
