<?
#
# Display all errors
#

header('Content-Type: text/html; charset=utf-8');
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

#
# Функция для логирования скрипта
#

function script_end($error = false, $die = true){

	global $log, $file_log, $debug;
	
	//Подключаем библиотеку для логов
	require '../include/KLogger.php'; //https://github.com/katzgrau/KLogger
	$loging = new KLogger ( $file_log , KLogger::INFO );

	if($debug){
		echo '<pre>';
		echo $log;
		echo '</pre>';
	}

	if(!$error){
		$loging->logInfo($log); //действия при успешном выполнении
	}
	else{
		$loging->logError($log); //действия при ошибке 
	}
	
	if($die)
		die();
}

#
# Ограничиваем текст до $maxlen символов (по словам)
#

function cutString($string, $maxlen) {
	$len = (mb_strlen($string) > $maxlen)
		? mb_strripos(mb_substr($string, 0, $maxlen), ' ')
		: $maxlen
	;
	$cutStr = mb_substr($string, 0, $len);
	return (mb_strlen($string) > $maxlen)
		? '' . $cutStr . '...'
		: '' . $cutStr . ''
	;
}
