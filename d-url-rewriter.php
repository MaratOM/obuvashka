<?php
// Раздел настройки ЧПУ (Все пути начинаются с ведущего слеша от корневой директории)
  $aURLRewriter = array (
    '/product-type/8'=>'/product-type/detskaya-obuv-zimnyaya/',
	'/content/detskaya-obuv-kapika'=>'/content/kapika-detskaya-obuv/',
	'/content/minimen'=>'/content/minimen-detskaya-obuv/',
	'/content/skandia'=>'/content/skandia-detskaya-obuv/',
	'/products/75'=>'/products/tufli-detskie/',
	'/products/63'=>'/products/kedy-detskie/',
	'/products/62'=>'/products/valenki-detskie/',
	'/products/61'=>'/products/botinki-detskie/',
	'/products/64'=>'/products/detskie-krossovki/',
	'/products/72'=>'/products/sapogi-detskie/',

	'/products/75/'=>'/products/tufli-detskie/',
	'/products/63/'=>'/products/kedy-detskie/',
	'/products/62/'=>'/products/valenki-detskie/',
	'/products/61/'=>'/products/botinki-detskie/',
	'/products/64/'=>'/products/detskie-krossovki/',
	'/products/72/'=>'/products/sapogi-detskie/',
	//New Pages
	'/content/kuoma-0'=>'/products/sapogi-detskie/kuoma',
	'/content/rezinovye'=>'/products/sapogi-detskie/rezinovyie',
	'/content/dlya-devochek'=>'/products/sapogi-detskie/dlya-devochek',
	'/content/zimnie'=>'/products/kedy-detskie/zimnyie',
	'/content/belye'=>'/products/detskie-krossovki/belyie',
	'/content/futbolnye'=>'/products/detskie-krossovki/futbolnyie',
	'/content/na-kablukakh'=>'/products/tufli-detskie/na-kablukah',


  );
//Сквозные редиректы
  $aR301SkipCheck = array (
    '/products/70' => '/products/70/',
    '/products/74' => '/products/74/',
    '/products/8/68' => '/products/8/73/56',
'/products/68'=>'/products/68/',
  );
//Удаленные страницы
  $a410Response = array (
    '/test3',
  );
// Только замена ссылок
  $aURLRewriterOnly = array (
'/products/68'=>'/products/68/',
    '/products/70' => '/products/70/',
    '/products/74' => '/products/74/',
    '/products/8/68' => '/products/8/73/56',
  );
  define('DUR_DEBUG', 0);                   //Включение режима отладки (вывод инфо в конце исходного текста на странице)
  define('DUR_PREPEND_APPEND', 0);          //Единая точка входа (.htaccess) Не рекомендуется
  define('DUR_BASE_ROOT', 0);               //Прописать принудительно <base href="http://domain.com/"> Бывает полезно при ссылках вида href="?page=2". При указании строки, пропишет ее
  define('DUR_LINK_PARAM', 0);              //Дописать путь перед ссылками вида href="?page=2"
  define('DUR_ANC_HREF', 0);                //Пофиксить ссылки вида href="#ancor"
  define('DUR_ROOT_HREF', 1);               //Пофиксить ссылки вида href="./"
  define('DUR_REGISTER_GLOBALS', 0);        //Регистрировать глобальные переменные
  define('DUR_SKIP_POST', 1);               //Не выполнять подмену при запросе POST
  define('DUR_CMS_TYPE', 'DRUPAL');           //Включение особенностей для CMS, возможные значения: NONE, NETCAT, JOOMLA, HTML, DRUPAL, WEBASYST, ICMS
  define('DUR_OUTPUT_COMPRESS', 'AUTO');    //Сжатие выходного потока, возможные значения: NONE, GZIP, DEFLATE, AUTO, SKIP
  define('DUR_SUBDOMAINS', 0);              //Обрабатывать поддомены, указываем здесь основной домен!
  define('DUR_SKIP_USERAGENT', '#^(|mirror)$#'); //Не выполнять редиректы при указанном HTTP_USER_AGENT (регулярка)
  define('DUR_SKIP_URLS', '#^/_?(admin|manag|bitrix|indy|cms|phpshop)#siU');  //Skip URLS
  define('DUR_FIX_CONTLEN', 0);             //Фиксить Content-Length
  define('DUR_PATHINFO', 0);                //Регистрировать переменные для передачи вида /index.php/uri

























// Раздел обработки
  define('DUR_TIME_START', microtime(true));
  define('DUR_REQUEST_URI', $_SERVER['REQUEST_URI']);
  define('DUR_HTTP_HOST', $_SERVER['HTTP_HOST']);
  define('DUR_FULL_URI', $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
  define('BX_COMPRESSION_DISABLED', true); //Hack for bitrix
  define('DUR_SKIP_THIS', preg_match(DUR_SKIP_URLS, DUR_REQUEST_URI, $aM));
  define('DUR_SKIP_R301', !isset($_SERVER['HTTP_USER_AGENT']) || preg_match(DUR_SKIP_USERAGENT, $_SERVER['HTTP_USER_AGENT']));
  if (defined('DUR_DEBUG') && DUR_DEBUG) {
    ini_set('display_errors', 1);
    ini_set('error_reportings', E_ALL);
  }
  if (isset($_GET['_openstat'])) {
    unset($_GET['_openstat']);
    unset($_REQUEST['_openstat']);
    unset($HTTP_GET_VARS['_openstat']);
    $_SERVER['REQUEST_URI'] = preg_replace('%[&?]_openstat=[^&]+(&|$)%siU', '', $_SERVER['REQUEST_URI']);
  }
  if (isset($a410Response[$_SERVER['REQUEST_URI']]) && !DUR_SKIP_THIS) {
    header('HTTP/1.0 410 Gone');
    echo '<h1 style="font-size: 18pt;">Ошибка 410</h1><p>Страница удалена</p><p style="text-align: right; margin: 10px;"><a href="/">На главную</a></p>';
    exit;
  }
  if (isset($aR301SkipCheck[$_SERVER['REQUEST_URI']]) && !DUR_SKIP_THIS && !DUR_SKIP_R301) {
    if (!defined('DUR_SKIP_POST') || !DUR_SKIP_POST || (strtoupper($_SERVER['REQUEST_METHOD']) != 'POST')) {
      header('Location: ' . $aR301SkipCheck[$_SERVER['REQUEST_URI']], true, 301);
      exit;
    }
  }
  foreach ($aURLRewriter as $sKey => $sVal) {
    $aURLRewriter[$sKey] = str_replace(
      array('р', 'у', 'к', 'е', 'н', 'х', 'в', 'а', 'о', 'ч', 'с', 'м', 'и', 'т', ' '),
      array('p', 'y', 'k', 'e', 'h', 'x', 'b', 'a', 'o', '4', 'c', 'm', 'n', 't', '_'),
      $sVal
    );
    if (!defined('DUR_SEO_REQUEST_URI') && ($sVal == $_SERVER['REQUEST_URI'])) {
      define('DUR_SEO_REQUEST_URI', $sKey);
    }
  }
  $aURFlip = array_flip($aURLRewriter);
  //Многократная вложенность замен (до 10)
  for ($i = 0; $i < 10; $i++) {
    foreach ($aURLRewriter as $sFrom => $sTo) {
      if (isset($aURLRewriter[$sTo])) {
        $aURLRewriter[$sFrom] = $aURLRewriter[$sTo];
        $aURFlip[$aURLRewriter[$sTo]] = $sFrom;
      }
    }
  }
  //Joomla hack! (Против защиты от register globals)
  if (defined('DUR_CMS_TYPE') && (DUR_CMS_TYPE == 'JOOMLA')) {
    $_SERVER['dur'] = array($aURLRewriter, $aURFlip, $aURLRewriterOnly);
  }
  //Единая точка входа
  if (defined('DUR_PREPEND_APPEND') && DUR_PREPEND_APPEND && !DUR_SKIP_THIS) {
    durRun ();
  }


// Функции
  function durRun () {
    if (defined('DUR_RUNNED')) return;
//    if (isset())
    define('DUR_RUNNED', 1);
    durR301();
    ob_start('durLinkChanger');
    durIFRewrite();
  }

  function dur404 () {
    $aPages404 = array('404.php', '404.html', '404.htm', 'index.php', 'index.html', 'index.htm');
    header('HTTP/1.1 404 Not found');
    foreach ($aPages404 as $sPage404) {
      if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $sPage404)) {
        include($_SERVER['DOCUMENT_ROOT'] . '/' . $sPage404);
        exit;
      }
    }
    echo '<h1>Ошибка 404</h1><p>Страница не найдена</p><p style="text-align: right; margin: 10px;"><a href="/">На главную</a></p>';
    exit;
  }

  function durRewrite ($sURL) {
    global $QUERY_STRING, $REQUEST_URI, $REDIRECT_URL, $HTTP_GET_VARS;
    define('DUR_DEBUG_BEFORE', "SERVER:\n" . durDebugVar($_SERVER) . "\n\nGET:\n" . durDebugVar($_GET) . "\n\nREQUEST:\n" . durDebugVar($_REQUEST) . "\n");
    if (defined('DUR_CMS_TYPE') && (DUR_CMS_TYPE == 'WEBASYST')) {
      $sURL = '/?__furl_path=' . substr($sURL, 1) . '&frontend=1';
    }
    if (defined('DUR_CMS_TYPE') && (DUR_CMS_TYPE == 'ICMS')) {
      $sURL = '/index.php?path=' . substr($sURL, 1, -5) . '&frontend=1';
    }
    $QUERY_STRING = strpos($sURL, '?') ? substr($sURL, strpos($sURL, '?') + 1) : '';
    $REQUEST_URI = $sURL;
    $REDIRECT_URL = $sURL;
    $_SERVER['QUERY_STRING'] = $QUERY_STRING;
    $_SERVER['REDIRECT_URL'] = $sURL;
    $_SERVER['REQUEST_URI'] = $sURL;
    if (defined('DUR_CMS_TYPE') && (DUR_CMS_TYPE == 'NETCAT')) {
      putenv('REQUEST_URI=' . $sURL);
    }
    if (defined('DUR_CMS_TYPE') && (DUR_CMS_TYPE == 'DRUPAL')) {
      $_GET['q'] = substr($sURL, 1);
      $_REQUEST['q'] = substr($sURL, 1);
    }
    if (preg_match_all('%[\?&]([^\=]+)\=([^&]*)%', $sURL, $aM)) {
      $aParams = array();
      foreach ($aM[1] as $iKey => $sName) {
        $sVal = urldecode($aM[2][$iKey]);
        if (preg_match('#^(.+)\[\]$#siU', $sName, $aMatch)) {
          $aParams[$aMatch[1]][] = $sVal;
        }
        elseif (preg_match('#^(.+)\[([\w-]+)\]$#siU', $sName, $aMatch)) {
          $aParams[$aMatch[1]][$aMatch[2]] = $sVal;
        }
        else {
          $aParams[$sName] = $sVal;
        }
      }
      foreach ($aParams as $sKey => $mVal) {
        $_GET[$sKey] = $mVal;
        $HTTP_GET_VARS[$sKey] = $mVal;
        $_REQUEST[$sKey] = $mVal;
        if (defined('DUR_REGISTER_GLOBALS') && DUR_REGISTER_GLOBALS) {
          global $$sKey;
          $$sKey = $mVal;
        }
      }
    }
    if (defined('DUR_PATHINFO') && DUR_PATHINFO) {
      $_SERVER['PATH_INFO'] = substr($sURL, 1);
      $_SERVER['PHP_SELF'] = $sURL;
    }
    if (DUR_CMS_TYPE == 'HTML') {
      $sFName = $sURL;
      if ($iPos = strpos($sFName, '?')) {
        $sFName = substr($sFName, 0, $iPos);
      }
      if (file_exists($_SERVER['DOCUMENT_ROOT'] . $sFName)) {
        include($_SERVER['DOCUMENT_ROOT'] . $sFName);
        exit;
      }
      else {
        dur404();
      }
    }
  }

  function durIFRewrite () {
    global $aURFlip, $aURLRewriter;
    if (DUR_SKIP_THIS) return;
    $sKey = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    if (defined('DUR_SUBDOMAINS') && DUR_SUBDOMAINS && isset($aURFlip[$sKey])) {
      if (!defined('DUR_ORIG_RURI')) {
        define('DUR_ORIG_RURI', $aURFlip[$sKey]);
      }
      durRewrite ($aURFlip[$sKey]);
    }
    elseif (isset($aURFlip[$_SERVER['REQUEST_URI']])) {
      if (!defined('DUR_ORIG_RURI')) {
        define('DUR_ORIG_RURI', $aURFlip[$_SERVER['REQUEST_URI']]);
      }
      durRewrite ($aURFlip[$_SERVER['REQUEST_URI']]);
    }
    elseif (defined('DUR_CMS_TYPE') && (DUR_CMS_TYPE == 'HTML')) {
      if (file_exists($_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'])) {
        durRewrite ($_SERVER['REQUEST_URI']);
      }
      else {
        dur404();
      }
    }
  }

  function durR301 () {
    global $aURFlip, $aURLRewriter;
    if (DUR_SKIP_THIS || DUR_SKIP_R301) return;
    if (defined('DUR_SKIP_POST') && DUR_SKIP_POST && (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')) {
      return;
    }
    if (isset($aURLRewriter[$_SERVER['REQUEST_URI']])) {
      if ('http://' . DUR_HTTP_HOST == trim($aURLRewriter[$_SERVER['REQUEST_URI']], '/')) {
        return;
      }
      header('Location: ' . $aURLRewriter[$_SERVER['REQUEST_URI']], true, 301);
      exit;
    }
  }

  function durRExpEscape ($sStr) {
    return str_replace(array('?', '.', '-', ':', '%', '[', ']', '(', ')'), array('\\?', '\\.', '\\-', '\\:', '\\%', '\\[', '\\]', '\\(', '\\)'), $sStr);
  }

  function durReplaceOnceLink ($sLink, $sNewLink, $sContent) {
    $sContent = preg_replace('%(href\s*=\s*[\'"]?)\s*' . durRExpEscape ($sLink) . '([#\'"\s>])%siU', '$1' . $sNewLink . '$2', $sContent);
    $sContent = preg_replace('%(href\s*=\s*[\'"]?)\s*' . durRExpEscape (str_replace('&', '&amp;', $sLink)) . '([#\'"\s>])%siU', '$1' . $sNewLink . '$2', $sContent);
    return $sContent;
  }

  function durReplaceLink ($sHost, $sBase, $sFrom, $sTo, $sContent) {
    $sNewLink = $sTo;
  // Link type: "http://domain/link"
    $sContent = durReplaceOnceLink ('http://' . $sHost . $sFrom, $sNewLink, $sContent);
  // Link type: "https://domain.com/link"
    //$sContent = durReplaceOnceLink ('https://' . $sHost . $sFrom, $sNewLink, $sContent);
  // Link type: "//domain.com/link"
    //$sContent = durReplaceOnceLink ('//' . $sHost . $sFrom, $sNewLink, $sContent);
  // Link type: "/link"
    $sContent = durReplaceOnceLink ($sFrom, $sNewLink, $sContent);
  // Link type: "./link"
    $sContent = durReplaceOnceLink ('.' . $sFrom, $sNewLink, $sContent);
  // Link type: "link" (Calc fromlink)
    $aLink = explode('/', $sFrom);
    $aBase = empty($sBase) ? array('') : explode('/', str_replace('//', '/', '/' . $sBase));
    $sReplLnk = '';
    for ($i = 0; $i < max(count($aLink), count($aBase)); $i++) {
      if (isset($aBase[$i]) && isset($aLink[$i])) {
        if ($aLink[$i] == $aBase[$i]) {
          continue;
        }
        else {
          for ($j = $i; $j < count($aBase); $j++) {
            $sReplLnk .= '../';
          }
          for ($j = $i; $j < count($aLink); $j++) {
            $sReplLnk .= $aLink[$j] . '/';
          }
          break;
        }
      }
      elseif (isset($aLink[$i])) {
        $sReplLnk .= $aLink[$i] . '/';
      }
      elseif (isset($aBase[$i])) {
        $sReplLnk .= '../';
      }
    }
    $sReplLnk = preg_replace('%/+%', '/', $sReplLnk);
    $sReplLnk2 = trim($sReplLnk, '/');
    $sReplLnk3 = rtrim($sReplLnk2, '.');
    if (strlen($sReplLnk) > 1) {
      $sContent = durReplaceOnceLink ($sReplLnk, $sNewLink, $sContent);
      $sContent = durReplaceOnceLink ('./' . $sReplLnk, $sNewLink, $sContent);
    }
    if (($sReplLnk2 != $sReplLnk) && (strlen($sReplLnk2) > 1)) {
      $sContent = durReplaceOnceLink ($sReplLnk2, $sNewLink, $sContent);
      $sContent = durReplaceOnceLink ('./' . $sReplLnk2, $sNewLink, $sContent);
    }
    if (($sReplLnk3 != $sReplLnk2) && (strlen($sReplLnk3) > 1)) {
      $sContent = durReplaceOnceLink ($sReplLnk3, $sNewLink, $sContent);
      $sContent = durReplaceOnceLink ('./' . $sReplLnk3, $sNewLink, $sContent);
    }
    return $sContent;
  }

  function durGZDecode($sS) {
    $sM = ord(substr($sS,2,1)); $iF = ord(substr($sS,3,1));
    if ($iF & 31 != $iF) return null;
    $iLH = 10; $iLE = 0;
    if ($iF & 4) {
      if ($iL - $iLH - 2 < 8) return false;
      $iLE = unpack('v',substr($sS,8,2));
      $iLE = $iLE[1];
      if ($iL - $iLH - 2 - $iLE < 8) return false;
      $iLH += 2 + $iLE;
    }
    $iFCN = $iFNL = 0;
    if ($iF & 8) {
      if ($iL - $iLH - 1 < 8) return false;
      $iFNL = strpos(substr($sS,8+$iLE),chr(0));
      if ($iFNL === false || $iL - $iLH - $iFNL - 1 < 8) return false;
      $iLH += $iFNL + 1;
    }
    if ($iF & 16) {
      if ($iL - $iLH - 1 < 8) return false;
      $iFCN = strpos(substr($sS,8+$iLE+$iFNL),chr(0));
      if ($iFCN === false || $iL - $iLH - $iFCN - 1 < 8) return false;
      $iLH += $iFCN + 1;
    }
    $sHCRC = '';
    if ($iF & 2) {
      if ($iL - $iLH - 2 < 8) return false;
      $calccrc = crc32(substr($sS,0,$iLH)) & 0xffff;
      $sHCRC = unpack('v', substr($sS,$iLH,2));
      $sHCRC = $sHCRC[1];
      if ($sHCRC != $calccrc) return false;
      $iLH += 2;
    }
    $sScrc = unpack('V',substr($sS,-8,4));
    $sScrc = $sScrc[1];
    $iSZ = unpack('V',substr($sS,-4));
    $iSZ = $iSZ[1];
    $iLBD = $iL-$iLH-8;
    if ($iLBD < 1) return null;
    $sB = substr($sS,$iLH,$iLBD);
    $sS = '';
    if ($iLBD > 0) {
      if ($sM == 8) $sS = gzinflate($sB);
      else return false;
    }
    if ($iSZ != strlen($sS) || crc32($sS) != $sScrc) return false;
    return $sS;
  }

  function durGZDecode2($sS) {
    $iLen = strlen($sS);
    $sDigits = substr($sS, 0, 2);
    $iMethod = ord(substr($sS, 2, 1));
    $iFlags  = ord(substr($sS, 3, 1));
    if ($iFlags & 31 != $iFlags) return false;
    $aMtime = unpack('V', substr($sS, 4, 4));
    $iMtime = $aMtime[1];
    $sXFL   = substr($sS, 8, 1);
    $sOS    = substr($sS, 8, 1);
    $iHeaderLen = 10;
    $iExtraLen  = 0;
    $sExtra     = '';
    if ($iFlags & 4) {
      if ($iLen - $iHeaderLen - 2 < 8) return false;
      $iExtraLen = unpack('v', substr($sS, 8, 2));
      $iExtraLen = $iExtraLen[1];
      if ($iLen - $iHeaderLen - 2 - $iExtraLen < 8) return false;
      $sExtra = substr($sS, 10, $iExtraLen);
      $iHeaderLen += 2 + $iExtraLen;
    }
    $iFilenameLen = 0;
    $sFilename = '';
    if ($iFlags & 8) {
      if ($iLen - $iHeaderLen - 1 < 8) return false;
      $iFilenameLen = strpos(substr($sS, $iHeaderLen), chr(0));
      if ($iFilenameLen === false || $iLen - $iHeaderLen - $iFilenameLen - 1 < 8) return false;
      $sFilename = substr($sS, $iHeaderLen, $iFilenameLen);
      $iHeaderLen += $iFilenameLen + 1;
    }
    $iCommentLen = 0;
    $sComment = '';
    if ($iFlags & 16) {
      if ($iLen - $iHeaderLen - 1 < 8) return false;
      $iCommentLen = strpos(substr($sS, $iHeaderLen), chr(0));
      if ($iCommentLen === false || $iLen - $iHeaderLen - $iCommentLen - 1 < 8) return false;
      $sComment = substr($sS, $iHeaderLen, $iCommentLen);
      $iHeaderLen += $iCommentLen + 1;
    }
    $sCRC = '';
    if ($iFlags & 2) {
      if ($iLen - $iHeaderLen - 2 < 8) return false;
      $sCalcCRC = crc32(substr($sS, 0, $iHeaderLen)) & 0xffff;
      $sCRC = unpack('v', substr($sS, $iHeaderLen, 2));
      $sCRC = $sCRC[1];
      if ($sCRC != $sCalcCRC) return false;
      $iHeaderLen += 2;
    }
    $sDataCRC = unpack('V', substr($sS, -8, 4));
    $sDataCRC = sprintf('%u', $sDataCRC[1] & 0xFFFFFFFF);
    $iSize = unpack('V', substr($sS, -4));
    $iSize = $iSize[1];
    $iBodyLen = $iLen - $iHeaderLen - 8;
    if ($iBodyLen < 1) return false;
    $sBody = substr($sS, $iHeaderLen, $iBodyLen);
    $sS = '';
    if ($iBodyLen > 0) {
      switch ($iMethod) {
        case 8: $sS = gzinflate($sBody); break;
        default: return false;
      }
    }
    $sCRC  = sprintf('%u', crc32($sS));
    $bCRCOK = ($sCRC == $sDataCRC);
    $bLenOK = ($iSize == strlen($sS));
    if (!$bLenOK || !$bCRCOK) return false;
    return $sS;
  }

  function durGZCheck ($sContent) {
    $iLen = strlen($sContent);
    if ($iLen < 18 || strcmp(substr($sContent, 0, 2), "\x1f\x8b")) {
      return $sContent;
    }
    $sData = durGZDecode2($sContent);
    if (!$sData) {
      $sData = durGZDecode($sContent);
    }
    return $sData ? $sData : $sContent;
  }

  function durOutputCompress ($sContent) {
    if (!defined('DUR_OUTPUT_COMPRESS')) {
      define('DUR_OUTPUT_COMPRESS', 'SKIP');
    }
    if (DUR_OUTPUT_COMPRESS == 'SKIP') {
      return $sContent;
    }
    $aAccept = array();
    if (isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {
      $aAccept = array_map('trim', explode(',', strtolower($_SERVER['HTTP_ACCEPT_ENCODING'])));
    }
    $bGZIP = in_array('gzip', $aAccept) && function_exists('gzencode');
    $bDEFL = in_array('deflate', $aAccept) && function_exists('gzdeflate');
    $sCompress = DUR_OUTPUT_COMPRESS;
    if ((!$bGZIP && !$bDEFL) || (!$bGZIP && ($sCompress == 'GZIP')) || (!$bDEFL && ($sCompress == 'DEFLATE'))) {
      $sCompress = 'NONE';
    }
    if ($sCompress == 'AUTO') {
      $sCompress = $bGZIP ? 'GZIP' : ($bDEFL ? 'DEFLATE' : 'NONE');
    }
    switch ($sCompress) {
      case 'GZIP':
        header('Content-Encoding: gzip');
        $sContent = gzencode($sContent);
        break;
      case 'DEFLATE':
        header('Content-Encoding: deflate');
        $sContent = gzdeflate($sContent, 9);
        break;
      default:
        //header('Content-Encoding: none');
    }
    return $sContent;
  }

  function durDebugEscape ($sText) {
    return str_replace(array('--', '-->'), array('==', '==}'), $sText);
  }

  function durDebugVar ($mVar, $sPref = '  ') {
    $Ret = '';
    foreach ($mVar as $sKey => $sVal) {
      $Ret .= "{$sPref}{$sKey} => ";
      if (is_array($sVal)) {
        $Ret .= "ARRAY (\n" . durDebugVar($sVal, $sPref.'  ') . "{$sPref})\n";
      }
      else {
        $Ret .= "{$sVal}\n";
      }
    }
    return durDebugEscape($Ret);
  }

  function durLinkChanger ($sContent) {
    global $aURFlip, $aURLRewriter, $aURLRewriterOnly;
    if (DUR_SKIP_THIS) return $sContent;
    $iTimeStart = microtime(true);
    $sContent = durGZCheck($sContent);
    if (defined('DUR_CMS_TYPE') && (DUR_CMS_TYPE == 'JOOMLA') && isset($_SERVER['dur'])) {
      $aURLRewriter = $_SERVER['dur'][0];
      $aURFlip = $_SERVER['dur'][1];
      $aURLRewriterOnly = $_SERVER['dur'][2];
      unset($_SERVER['dur']);
    }
    $aURLRewriter = array_merge($aURLRewriter, $aURLRewriterOnly);
    //Base path
    if (preg_match('%<[^<>]*base[^<>]*href=[\'"]?([\w_\-\.\:/]+)[\'"\s>][^<>]*>%siU', $sContent, $aM)) {
      $sBase = $aM[1];
      $sBaseHref = $aM[1]; 
    }
    else {
      $sBase = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '/'));
      $sBaseHref = ''; 
    }
    $sBase = trim(str_replace(array('http://', 'https://'), '', $sBase), '/');
    $aHosts = array($_SERVER['HTTP_HOST']);
    if (substr($_SERVER['HTTP_HOST'], 0, 4) == 'www.') {
      $aHosts[] = substr($_SERVER['HTTP_HOST'], 4);
    }
    $sExtHost = str_replace('www.www.', 'www.', 'www.' . DUR_SUBDOMAINS);
    $aHosts[] = $sExtHost;
    $aHosts[] = str_replace('www.', '', $sExtHost);
    $aHosts = array_unique($aHosts);
    $sBase = str_replace($aHosts, '', $sBase);
    //href="?..."
    if (defined('DUR_LINK_PARAM') && defined('DUR_ORIG_RURI') && DUR_LINK_PARAM) {
      $sContent = preg_replace('%(href\s*=\s*[\'"]?)\s*([?#].*[#\'"\s>])%siU', '$1' . DUR_ORIG_RURI . '$2', $sContent);
    }
    //Main cicle
    foreach ($aURLRewriter as $sFrom => $sTo) {
      foreach ($aHosts as $sHost) {
        $sContent = durReplaceLink ($sHost, $sBase, $sFrom, $sTo, $sContent);
      }
    }
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/d-seo.php')) {
      include_once($_SERVER['DOCUMENT_ROOT'] . '/d-seo.php');
    }
    if ((defined('DUR_BASE_ROOT') && DUR_BASE_ROOT) || !empty($sBaseHref)) {
      if (strlen(DUR_BASE_ROOT) > 7) {
        $sBaseHref = DUR_BASE_ROOT;
      }
      else {
        $sBaseHref = (empty($sBaseHref) ? 'http://' . $aHosts[0] : $sBaseHref) . '/';
      }
      $sBaseHref = '<base href="' . $sBaseHref . '">';
      $sContent = preg_replace('%<base[^>]+href[^>]+>%siU', '', $sContent);
      $sContent = preg_replace('%(<head[^>]*>)%siU', "$1" . $sBaseHref, $sContent);
    }
    if (defined('DUR_ANC_HREF') && DUR_ANC_HREF) {
      $sContent = preg_replace('%(href\s*=\s*["\']+)(#\w)%siU', '$1' . DUR_REQUEST_URI . '$2', $sContent);
    }
    if (defined('DUR_ROOT_HREF') && DUR_ROOT_HREF) {
      $sContent = preg_replace('%(href\s*=\s*["\']*)\./(\w)%siU', '$1http://' . $_SERVER['HTTP_HOST'] . $sBase . '/$2', $sContent);
    }
    if (function_exists('durOtherReplacer')) {
      $sContent = durOtherReplacer ($sContent);
    }
    if (defined('DUR_DEBUG') && DUR_DEBUG) {
      $sContent .= "\n<!--\n";
      if (defined('DUR_DEBUG_BEFORE') && DUR_DEBUG_BEFORE) {
        $sContent .= " ===== VARS BEFORE REWRITE =====\n\n" . DUR_DEBUG_BEFORE;
      }
      $sContent .= "===== VARS AFTER REWRITE =====\n\nSERVER:\n" . durDebugVar($_SERVER) . "\n\nGET:\n" . durDebugVar($_GET) . "\n\nREQUEST:\n" . durDebugVar($_REQUEST) . "\n";
      $sContent .= "\nCONSTANTS:\n" .
                   '  DUR_REQUEST_URI     => ' . durDebugEscape(DUR_REQUEST_URI) . "\n" .
                   '  DUR_HTTP_HOST       => ' . durDebugEscape(DUR_HTTP_HOST) . "\n" .
                   '  DUR_FULL_URI        => ' . durDebugEscape(DUR_FULL_URI) . "\n" .
                   '  DUR_ORIG_RURI       => ' . (defined('DUR_ORIG_RURI') ? durDebugEscape(DUR_ORIG_RURI) : 'NOT-SET') . "\n" .
                   '  DUR_SEO_REQUEST_URI => ' . (defined('DUR_SEO_REQUEST_URI') ? durDebugEscape(DUR_SEO_REQUEST_URI) : 'NOT-SET') . "\n";
      $iTimeNow = microtime(true);
      $iTimeAll = ($iTimeNow - DUR_TIME_START) / 1000;
      $iTimeContent = ($iTimeStart - DUR_TIME_START) / 1000;
      $iTimeLinks = ($iTimeNow - $iTimeStart) / 1000;
      $sContent .= "\nTIME:\n" . 
                   '  ALL: ' . number_format($iTimeAll, 8) . " sec. (100%)\n" .
                   '  CMS: ' . number_format($iTimeContent, 8) . ' sec. (' . number_format($iTimeContent / $iTimeAll * 100, 2)  . "%)\n" . 
                   '  DUR: ' . number_format($iTimeLinks, 8) . ' sec. (' . number_format($iTimeLinks / $iTimeAll * 100, 2)  . "%)\n";
      $sContent .= '-->';
    }
    $sContent = durOutputCompress($sContent);
    if (defined('DUR_FIX_CONTLEN') && DUR_FIX_CONTLEN) {
      header('Content-Length: ' . strlen($sContent));
    }
    return $sContent;
  }

//////закрываем исходящие ссылки в ноиндекс и нофоллоу
function replace($matches){

    if(preg_match('%counter%siU',$matches[0]))
    {
      return $matches[0];
    }

    $sEq = false; //есть ли совпадения
    $res = $matches[0];
    //проверяем внешняя ли ссылка 
    $sEq = !preg_match('%http://%siU',$matches[0]);

    $arMassNotNoindex = array(
    'demis.ru',
	'demis-promo.ru',
    ); 
	$arMassNotNoindex[]=$_SERVER['HTTP_HOST'];

    foreach($arMassNotNoindex as $item){
    if (strpos($matches[0],$item)) {
    $sEq = true; break;
    }
    }

    if (!$sEq && (!preg_match('%/useful%siU',$_SERVER['REQUEST_URI']))){ // если совпадений не нашлось
    $res = '<noindex>'.$matches[0].'</noindex>';

    //если отсутствует rel, то добавляем его 
    if (!preg_match('%rel=%siU',$res)){
    $res = preg_replace('%(<a[^>].*)(>.*</a>)%siU','$1'.' rel="nofollow"'.'$2',$res);
    }
    }

    return $res;
}

function file_get_contents_curl($url) {
	$ch = curl_init();
 
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
 
	$data = curl_exec($ch);
	curl_close($ch);
 
	return $data;
}


  function durOtherReplacer ($sContent) {		
//закрываем исходящие ссылки в ноиндекс и нофоллоу
$sContent = preg_replace_callback('%<a.*</a>%siU','replace',$sContent); 

$sContent = str_replace('<a href="/card">','<a href="/">', $sContent);

if(DUR_REQUEST_URI=='/products/sapogi-detskie/'){
/*$sContent = str_replace('<h2>Сапоги</h2><div class="item-list"><ul>','<h2>Сапоги</h2><div class="item-list"><ul><li><a href="/products/sapogi-detskie/kuoma">Kuoma</a></li>
<li><a href="/products/sapogi-detskie/rezinovyie">Резиновые</a></li>
<li><a href="/products/sapogi-detskie/dlya-devochek">Для девочек</a></li>', $sContent);
*/
  $sContent = str_replace('<p><a href="/products/72/" class="season">Сапоги</a></p>','<p><a href="/products/72/" class="season">Сапоги</a></p><p><a href="/products/sapogi-detskie/kuoma">Kuoma</a></p>
  <p><a href="/products/sapogi-detskie/rezinovyie">Резиновые</a></p>
  <p><a href="/products/sapogi-detskie/dlya-devochek">Для девочек</a></p>', $sContent);

}elseif(DUR_REQUEST_URI=='/products/kedy-detskie/'){

  $sContent = str_replace('<p><a href="/products/63/" class="season">Кеды</a></p>','<p><a href="/products/63/" class="season">Кеды</a></p><p><a href="/products/kedy-detskie/zimnyie">Зимние</a></p>', $sContent);
  
}elseif(DUR_REQUEST_URI=='/products/detskie-krossovki/'){

$sContent = str_replace('<p><a href="/products/64/" class="season">Кроссовки</a></p> ','<p><a href="/products/64/" class="season">Кроссовки</a></p><p><a href="/products/detskie-krossovki/belyie">Белые</a></p>
<p><a href="/products/detskie-krossovki/futbolnyie">Футбольные</a></p>', $sContent);

}elseif(DUR_REQUEST_URI=='/products/tufli-detskie/'){

$sContent = str_replace('<p><a href="/products/75/" class="season">Туфли</a></p>','<p><a href="/products/75/" class="season">Туфли</a></p><p><a href="/products/tufli-detskie/na-kablukah">На каблуках</a></p>', $sContent);

}
    


$sContent = str_replace('<div id="footer_im"><div id="footer">Copyright © 2009-2010 ООО "Обувашка"<br>','<div id="footer_im"><div id="footer">Copyright © 2009-2012 ООО "Обувашка"<br>',$sContent);


$sContent = str_replace('<!--<h2 class="title"></h2>-->','',$sContent);
$sContent = str_replace('<h1>магазины детской обуви</h1>','<p class="kakh1">магазины детской обуви</p>',$sContent);
$sContent = str_replace('<strong>тел.: 8(499)409-0779</strong><strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style="line-height: 0; display: none;">﻿</span>&nbsp;&nbsp; </strong>e-mail: <strong>obyvashka@bk.ru</strong>','<span style="font-weight:bold;">тел.: 8(499)409-0779</span><span style="font-weight:bold;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style="line-height: 0; display: none;">﻿</span>&nbsp;&nbsp; </span>e-mail: <span style="font-weight:bold;">obyvashka@bk.ru</span>',$sContent);

$arr1 = array(
'<h2>Сапоги</h2>',
'<h2>Ботинки</h2>',
'<h2>Валенки</h2>',
'<h2>Туфли</h2>',
);
$arr2 = array(
'<p class="kakh2">Сапоги</p>',
'<p class="kakh2">Ботинки</p>',
'<p class="kakh2">Валенки</p>',
'<p class="kakh2">Туфли</p>',
);

$sContent = str_replace($arr1,$arr2,$sContent);
/*
<h2>Сапоги</h2>

*/
		
//if(DUR_REQUEST_URI=='')

//$sContent = preg_replace('##Usi','', $sContent);
    if ($_SERVER['REQUEST_URI']=='/') {
        $sContent = str_replace('<a href="/content/karta-saita">','<a href="http://www.demis.ru/articles/prodvizhenie_saitov_v_yandex/">продвижение сайтов в яндексе</a> от лидера рынка - Demis GroUp. <a href="/content/karta-saita"> <a href="/obyvashka/">Партнеры</a> ',$sContent);
    }
    
    $sContent=str_replace('includetext','',$sContent);
    $sContent=str_replace('obyvashka@bk.ru','<a href="mailto:obyvashka@bk.ru">obyvashka@bk.ru</a>',$sContent);
    
    
    
    $insearch = <<<HERE
<br>
<div class="ya-site-form ya-site-form_inited_no" onclick="return {'bg': '#F39345', 'target': '_self', 'language': 'ru', 'suggest': true, 'tld': 'ru', 'site_suggest': true, 'action': 'http://yandex.ru/sitesearch', 'webopt': false, 'fontsize': 12, 'arrow': false, 'fg': '#000000', 'searchid': '1898430', 'logo': 'ww', 'websearch': false, 'type': 2}">
<form action="http://yandex.ru/sitesearch" method="get" target="_self"><input type="hidden" name="searchid" value="1898430" />
<input type="hidden" name="l10n" value="ru" /><input type="hidden" name="reqenc" value="" /><input type="text" name="text" value="" />
<input type="submit" value="Найти" />
</form>
</div>
<style type="text/css">
.ya-page_js_yes .ya-site-form_inited_no { display: none; }
</style>
<script type="text/javascript">
(function(w,d,c){var s=d.createElement('script'),h=d.getElementsByTagName('script')[0],e=d.documentElement;(' '+e.className+' ').indexOf(' ya-page_js_yes ')===-1&&(e.className+=' ya-page_js_yes');s.type='text/javascript';s.async=true;s.charset='utf-8';s.src=(d.location.protocol==='https:'?'https:':'http:')+'//site.yandex.net/v2.0/js/all.js';h.parentNode.insertBefore(s,h);(w[c]||(w[c]=[])).push(function(){Ya.Site.Form.init()})})(window,document,'yandex_site_callbacks');
</script>
  
HERE;


    $sContent=preg_replace('%</div>[\s|\n]*</div>[\s|\n]*</div>[\s|\n]*</div><!--END con_side-->%siU','</div>'.$insearch.'</div></div></div><!--END con_side-->',$sContent);
    return $sContent;//.$_SERVER['REQUEST_URI'];
  }






/* Подключение в начале файла

// ЧПУ ---
  if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/d-url-rewriter.php')) {
    include_once($_SERVER['DOCUMENT_ROOT'] . '/d-url-rewriter.php');
    durRun ();
  }
// --- ЧПУ

/* Для поддоменов неплохо было прописывать

RewriteCond %{HTTP_HOST} ^www.(.{4,}.nickon.ru)$
RewriteRule ^(.*)$ http://%1/$1 [R=301,L]

RewriteCond %{HTTP_HOST} ^(.{4,}).nickon.ru$
RewriteRule ^robots\.txt$ robots-%1.txt [L]

*/


/* Подключение с единой точкой входа
RemoveHandler .html .htm
AddType application/x-httpd-php .php .htm .html .phtml
php_value auto_prepend_file "/d-url-rewriter.php"
*/

