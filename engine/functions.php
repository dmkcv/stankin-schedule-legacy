<?php
function passwordHash($info) {
    return password_hash($info, PASSWORD_DEFAULT);
}

function _redirect($url, $code = 303) {
    $base = '/';

    // Append base url to redirect url
    if ($base != '/' && strpos($url, '://') === false) {
        $url = $base . preg_replace('#/+#', '/', '/' . $url);
    }

    Registry::get('response')
        ->status($code)
        ->header('Location', $url)
        ->write($url)
        ->send();
}

function getSpecificValue ($array, $key) {
    return array_search ($key, $array);
}

/**
 * Возвращает из массива только требуемые элементы (по ключам) и переименовывает при надобности
 *
 * Примечание: если у ключа нет значения, то он исключается из итогового массива - даже если указан в attribs
 * ```
 * $arr = array ("user"=>"1","pass"=>"cat","code"=>"242434","test"=>"feb");
 * print_r(filter_json ($arr, array("user","code","test"), array("test"=>"month")));
 * Array ( [user] => 1 [code] => 242434 [month] => feb )
 * ```
 *
 * @param $array - массив ключ-значение
 * @param $attribs - ключи для сохранения
 * @param null $replace - ключи для замены (они должны быть и сохранены в результативном массиве!)
 * @return array
 */
function filter_output ($array, $attribs, $replace = null)
{
    if (!$array) { return array(); }
    $attribskeys = @array_keys ($replace);
    $result = [[]];
    foreach ($array as $k=>$v)
    {
        if (in_array($k, $attribs) && $k)
        {
            if (@in_array($k, $attribskeys)) {
                $result[] = array($replace[$k]=>$v);
            }
            else {
                $result[] = array($k=>$v);
            }
        }
    }
    return array_merge(...$result);
}

function filter_multi_output ($marray, $attribs, $replace = null)
{
    if (!$marray) { return array(); }
    $attribskeys = @array_keys ($replace);
    $mresult = [];
    foreach ($marray as $array) {
        $result = [[]];
        foreach ($array as $k => $v) {
            if (in_array($k, $attribs) && $k) {
                if (@in_array($k, $attribskeys)) {
                    $result[] = array($replace[$k] => $v);
                } else {
                    $result[] = array($k => $v);
                }
            }
        }
        $mresult[] = array_merge(...$result);
    }
    return $mresult;
}

/**
 * Группирует идентичные записи в массиве по нескольким полям
 * ```
 * group_records ($tmp, array("lid", "day", "hour", "week"), "class", "classes")
 * ```
 * @param $array - массив для разбора
 * @param $groupfields - поля группировки
 * @param $stackfield - поле для выборки
 * @param $resultfield - результативное поле
 * @return array
 */
function group_records ($array, $groupfields, $stackfield, $resultfield) {
    $idarr = array();
    $result = array();

    for ($i = 0, $iMax = count($array); $i < $iMax; $i++) {
        $tmp = '';
        foreach ($groupfields as $f) {
            $tmp .= $array[$i][$f];
        }
        $idarr[] = crc32($tmp);
    }
    for ($i = 0, $iMax = count($idarr); $i < $iMax; $i++) {
        $r = array_search($idarr[$i], $idarr);
        if ($r == $i) {
            $t = $array[$i];
            $t[$resultfield] = $t[$stackfield];
            $result[$i] = $t;
        } else {
            $result[$r][$resultfield] .= ',' .$array[$i][$stackfield];
        }
        $result[$r]['rid'] = $idarr[$i];
    }
    $result = array_values($result);
    for ($i = 0, $iMax = count($result); $i < $iMax; $i++) {
        unset($result[$i]['rid'], $result[$i][$stackfield]);
        $result[$i][$resultfield] = array_keys(array_count_values(explode(',', $result[$i][$resultfield]))); // Заменен array_unique
    }
    return $result;
}

function mkdirs($dir, $mode = 0777, $recursive = true) {
    if( null === $dir || $dir === ''){
        return FALSE;
    }
    if( is_dir($dir) || $dir === '/'){
        return TRUE;
    }
    if( mkdirs(dirname($dir), $mode, $recursive) ){
        return mkdir($dir, $mode);
    }
    return FALSE;
}

function file_ext_strip($filename) {
    return preg_replace('/.[^.]*$/', '', $filename);
}

function delete_dir($dirPath) {
    if (! is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            delete_dir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}

function eval_template($matches){
    eval($matches[1]);
}

function gen_logout_key () {
    return intval(substr(md5(date('H w y').CLI_KEY), 0, 8), 16);
}

function get_http_value ($name, $method, $filter = false, $default = null, $header = false)
{
    $name = (!$header) ? $name : (str_replace('-', '_', strtoupper('http-' .$name)));
    $value = array_key_exists($name,$method) ? $method[$name] : $default;
    switch ($filter) {
        case 'int':
            $value = preg_replace("/\D/", '', $value);
            break;

        case 'csd':
            $value = preg_replace("/[^\d+(,\d+)*$]/", '', $value);
            break;

        case 'float':
            $value = (float)$value;
            break;

        case 'bool':
            $value = (bool)$value;
            break;

        case 'alnum':
            $value = filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
            break;

        default:
            break;
    }
    return (!empty($value)) ? $value : $default;
}

function normalize_name ($name) {
    $name = preg_replace(array("/\(.*?\)/", '/[^\p{L}\p{N}\s]/u', '/\s/'), '', $name);
    return mb_strtolower($name);
}

/**
 * Группирует поля массива в подмассивы по префиксу
 * ```
 * $arr = array ("cat%id"=>"1","cat%pass"=>"123","dog_model"=>"cat","firefly*code"=>"242","app"=>"ppa");
 * print_r(group_by_prefix ($arr, array("cat","dog","firefly"), array("%","_","")));
 * Array ( [firefly*code] => 242 [app] => ppa [cat] => Array ( [id] => 1 [pass] => 123 ) [dog] => Array ( [model] => cat ) )
 * ```
 * * Если передан пустой разделитель, то поля с префиксом остаются неизмененными
 * * Если передано несколько префиксов, но один разделитель, то он применяется ко всем префиксам
 * * Если разделитель не передан вообщен, массив возвращается без изменений
 *
 * @param array $array - исходный одномерный массив
 * @param mixed $prefix - массив или единственный префикс для разделения
 * @param mixed $delimiter - массив или единственный разделитель между префиксом и имененем поля
 * @return array
 */
function group_by_prefix (array $array, $prefix, $delimiter) {
    if (is_array($prefix)) {
        foreach ($prefix as $key=>$p) {
            $loop_delimiter = '';
            if (is_array($delimiter)) {
                if (array_key_exists($key, $delimiter) && !empty($delimiter[$key])) {
                    $loop_delimiter =  $delimiter[$key];
                } else { continue; }
            } else {
                $loop_delimiter = $delimiter;
            }
            $array = group_by_prefix($array, $p, $loop_delimiter);
        }
    } else {
        if (empty($delimiter)) return $array;
        $full_prefix = $prefix.$delimiter;
        $full_prefix_count = strlen($full_prefix);
        if (array_key_exists($prefix, $array)) {
            $array[$prefix.'_'.$prefix] = $array[$prefix];
            unset($array[$prefix]);
        }
        $array[$prefix] = [];

        foreach ($array as $k=>$v) {
            if (mb_stripos($k, $full_prefix) !== false) {
                $array[$prefix][substr($k, $full_prefix_count)] = $v;
                unset($array[$k]);
            }
        }
        ksort($array[$prefix]);
    }
    return $array;
}
?>