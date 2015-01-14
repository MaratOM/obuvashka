<?php
//Для отладки введите адрес каталога с параметром debug-links. Пример http://www.plta.ru/other/?debug-links или используйте в юзерагенте сочетание символов "dem:debug"
  $aPath = explode('/', $_SERVER['REQUEST_URI']);
/**** Setting part ****/
  $sAnchor = 'Полезное';
  $sSitePath = $aPath[1]; //'other';
  $sSiteHost = $_SERVER['HTTP_HOST']; //'www.plta.ru';
  $sEncoding = 'utf-8'; //utf-8
  $bDynamicTitle = true;
  $bSEF = true;
  $sParseTemplate = 'http://obyvashka.ru/content/o-nas'; // ''
  $sServer = 'http://links2.demis.ru/output/index/index/';
/**** /Setting part ****/
  if (!isset($_SERVER['HTTP_USER_AGENT'])) $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6';
  if (isset($_GET['p'])) $_SERVER['REQUEST_URI'] = str_replace('/?p=', '/', $_SERVER['REQUEST_URI']);
  define('LINKS_DEBUG', (isset($_GET['debug-links']) || (strpos($_SERVER['HTTP_USER_AGENT'], 'dem:debug') > 0)) ? 1 : 0);
  ini_set('error_reporting', LINKS_DEBUG ? E_ALL : 0);
  ini_set('display_errors', LINKS_DEBUG ? 1 : 0);
//Формируем запрос
  $aParams = array();
  foreach ($_GET as $sKey => $sVal) $aParams[$sKey] = urlencode($sVal);
  foreach ($_POST as $sKey => $sVal) $aParams[$sKey] = urlencode($sVal);
  $aQuery = explode('/', str_replace($sSitePath . '/', '', $_SERVER['REQUEST_URI']));
  if (isset($aQuery[1]) && ($aQuery[1] != 'form') && ($aQuery[1] != 'addlink')) {
    $sQuery = '/sect/' . $aQuery[1] . '/page/' . (isset($aQuery[2]) ? $aQuery[2] : '');
  }
  $sQuery .= '?';
  $aNeedParams = array('banner', 'text', 'sect', 'mail', 'our_url', 'key');
  foreach ($aParams as $sKey => $sVal) if (in_array($sKey, $aNeedParams)) $sQuery .= isset($aParams[$sKey]) ? '&' . $sKey . '=' . $aParams[$sKey] : '';
  $sQuery = $sServer . 'site/' . $sSiteHost . $sQuery;
//Получаем контент
  $sContent = getContentByURL($sQuery);
//Проверяем на 404 отклик
  $sTitle = '';
  $bNotFound = false;
  if ((strlen($sContent) < 20) || (strpos($sContent, '<!-- Error_404-->') !== false)) {
    $bNotFound = true;
    header('HTTP/1.0 404 Not Found');
    header('Status: 404 Not Found');
    $sContent = '<h1>Страница не найдена</h1><p>Указанная страница не найдена, попробуйте начать поиск с <a href="/' . $sSitePath . '/">главной  страницы</a> каталога</p>';
    $sTitle = 'Страница не найдена';
  }
//Подменяем ссылки если не SEF
  if (!$bSEF) {
    $sContent = str_replace('<a href="http://' . $sSiteHost . '/' . $sSitePath . '/', '<a href="http://' . $sSiteHost . '/' . $sSitePath . '/?p=', $sContent);
    $sContent = str_replace($sSitePath . '/?p="', $sSitePath . '/"', $sContent);
  }
//Параметры отладки
  if (LINKS_DEBUG) {
    $sDebug = '<div style="margin: 10px; padding: 10px; border: 1px solid #ff0000; background: #ffeeee; font-size: 8pt;">';
    $sDebug .= '<b>allow_url_fopen</b>: ' . (ini_get('allow_url_fopen') ? 'on' : 'off') . '<br />';
    $sDebug .= '<b>curl</b>: ' . (function_exists('curl_init') ? 'on' : 'off') . '<br />';
    $sDebug .= '<b>file_get_contents</b>: ' . (function_exists('file_get_contents') ? 'on' : 'off') . '<br />';
    $sDebug .= '<b>test ns</b>: ' . (strlen(@file_get_contents('http://ya.ru')) > 100 ? 'ok' : 'fail') . '<br />';
    $sDebug .= '<b>test ip</b>: ' . (strlen(@file_get_contents('http://87.250.251.3')) > 100 ? 'ok' : 'fail');
    $sDebug .= '</div>';
    $sContent = $sDebug . $sContent;
  }
//Обрабатываем кодировку  
  $sPageLang = 'страница';
  if ($sEncoding != 'windows-1251') {
    $sTitle = convertEncoding($sEncoding, $sTitle);
    $sContent = convertEncoding($sEncoding, $sContent);
    $sPageLang = convertEncoding($sEncoding, $sPageLang);
    $sAnchor = convertEncoding($sEncoding, $sAnchor);
  }
//Обрабатываем шаблон
  if (!empty($sParseTemplate)) {
    $sTemplate = '';
    if (is_readable('.tpl.cache') && (filemtime('.tpl.cache') + 86400 < time())) {
      $sTemplate = join('', @file('.tpl.cache'));
    }
    else {
      $sTemplate = getContentByURL($sParseTemplate);
      if (is_writable('.tpl.cache')) {
        $hFile = fopen('.tpl.cache', 'w');
        fwrite($hFile, $sTemplate);
        fclose($hFile);
      }
    }
    $sFullContent = preg_replace('#<!--{l2d2}-->.*<!--{/l2d2}-->#siU', $sContent, $sTemplate);
  }
  else {
    $sHeaderFile = file_exists($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php') ? $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php' : '_header.php';
    $sFooterFile = file_exists($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php') ? $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php' : '_footer.php';
    ob_start();
    if (file_exists($sHeaderFile)) include($sHeaderFile);
    echo $sContent;
    if (file_exists($sFooterFile)) include($sFooterFile);
    $sFullContent = ob_get_contents();
    ob_end_clean();
  }
//Обрабатываем title
  if (!$bNotFound && $bDynamicTitle) {
    $sTitle = $sAnchor;
    if (preg_match('#<h1>\s*<a[^>]*>.*\s-\s(.*)</a>#siU', $sContent, $aM)) $sTitle .= ': ' . $aM[1];
    if (is_numeric($aQuery[count($aQuery)-1])) $sTitle .= ' - ' . $sPageLang . ' ' . $aQuery[count($aQuery)-1];
  }
  if (!empty($sTitle)) {
    $sTitle = '<title>' . htmlspecialchars($sTitle) . '</title>';
    $sFullContent = preg_match('#<title>.*</title>#siU', $sFullContent) ? preg_replace('#<title>.*</title>#siU', $sTitle, $sFullContent) : preg_replace('#<head>#i', '<head>' . $sTitle, $sFullContent);
  }
//Выводим
$sFullContent = str_replace('"/content/o-nas"','"/content/o-kompanii"',$sFullContent);
  echo $sFullContent;
//Функции обработки
  function getContentByURL ($sURL) {
    $sContent = '';
    if (function_exists('curl_init')) {
      $hCURL = @curl_init();
      @curl_setopt($hCURL, CURLOPT_URL, $sURL);
      @curl_setopt($hCURL, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
      @curl_setopt($hCURL, CURLOPT_TIMEOUT, 10);
      @curl_setopt($hCURL, CURLOPT_RETURNTRANSFER, true);
      $sContent = @curl_exec($hCURL);
      @curl_close($hCURL);
    }
    if (empty($sContent) && function_exists('file_get_contents')) $sContent = @file_get_contents($sURL);
    if (empty($sContent)) $sContent = join('', @file($sURL));
    return $sContent;
  }
  function convertEncoding ($sEncoding, $sData) {
    if (function_exists('iconv')) $sData = iconv('windows-1251', $sEncoding, $sData);
    elseif (function_exists('mb_convert_encoding')) $sData = mb_convert_encoding($sData, $sEncoding, 'windows-1251');
    return $sData;
  }
