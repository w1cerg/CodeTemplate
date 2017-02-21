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

/*
 * Логин выставляем равный email
 */ 
AddEventHandler("main", "OnBeforeUserRegister", "OnBeforeUserUpdateHandler");
AddEventHandler("main", "OnBeforeUserUpdate", "OnBeforeUserUpdateHandler");
function OnBeforeUserUpdateHandler(&$arFields)
{
	$arFields["LOGIN"] = $arFields["EMAIL"];
	return $arFields;
}

/*
 * Шаблон регистрации, убираем предупреждение про логин
 */

foreach ($arResult["ERRORS"] as $key => $error){
	$error = preg_replace("/Пользователь с логином.*?уже существует.<br>/", "", $error);
	$arResult["ERRORS"][$key] = $error;
	// ..
}

/*
 * Редирект с сохранением параметров
 */
use Bitrix\Main\Web\Uri;
use \Bitrix\Main\Application;

$request = Application::getInstance()
    ->getContext()
    ->getRequest();

$uri = new Uri("/new_link/");
$uri->addParams($request->getQueryList()->toArray());
$uri->deleteParams(array("id_product", "ID_PRODUCT"));
LocalRedirect($uri->getUri(), false, 301);