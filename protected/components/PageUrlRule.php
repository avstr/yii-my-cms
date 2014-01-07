<?php
class PageUrlRule extends CBaseUrlRule
{
    public $connectionID = 'db';
    private $prefix_cache = "page_";
    static $breadCrumbs = array();


    public function createUrl($manager,$route,$params,$ampersand)
    {
        //кеширование md5($route . serialize($params)), $params отсортировны
        //вместо false пишем 0
        //сформируем ключ для cache
        $urlPath = '';
        ksort($params);
        //echo "<pre>"; print_r($params); echo "</pre>";
        //echo "<br>{$route}<br>";
        $key_cache = $this->prefix_cache . md5($route . serialize($params));
        $value_cache = Yii::app()->cache->get($key_cache);
        if($value_cache !== false){
            return ($value_cache === '0') ? false : $value_cache;
        }
        $getStr = '';
        $getArrayStr = array();
        if(!empty($params)){
            foreach($params as $key => $val){
                $getArrayStr[] = "{$key}={$val}";
            }
            $getStr = '?' . implode('&', $getArrayStr);
        }
        if ($route==='page/index' && (!empty($params["id"]) || !empty($params["alias"])))
        {
            //получаем массивы страниц
            Page::getAllVisiblePage($pageByAlias, $pageById);
            //в параметрах установлен или alias или id у статической страницы
            if(!empty($params["id"]) && isset($pageById[$params["id"]])){
                $page = $pageById[$params["id"]];
            }elseif(!empty($params["alias"]) && isset($pageById[$params["alias"]])){
                $page = $pageByAlias[$params["alias"]];
            }
            //echo "<pre>"; print_r($page); echo "</pre>";
            if(empty($page)){
                $urlPath = '0';
            }
            if($urlPath == ''){
                //находим всех родителей текущей страницы
                if($page->parents == "/"){
                    Yii::app()->cache->set($key_cache, $page->alias);
                    $urlPath = $page->alias;
                }else{
                    $urlPath = $this->getPathAlias($page->parents);
                    if(empty($urlPath)){
                        $urlPath = '0';
                    }
                    $urlPath = "{$urlPath}/{$page['alias']}";
                }
            }
        }
        if($urlPath == ''){
            //ищем нужный модуль с action
            $criteria = new CDbCriteria;
            $criteria->select = "URL, parents";
            $criteria->condition = "hidden='no' AND URL='{$route}'";
            $page=Page::model()->find($criteria);
            if(!empty($page)){
                $urlPath = $this->getPathAlias($page->parents);
                if(is_bool($urlPath) && $urlPath == false){
                    $urlPath = '0';
                }
                if($urlPath == ''){
                    //echo "{$route}<pre>"; print_r($params); echo "</pre>";
                    $urlPath = ($urlPath == '') ? $route . $getStr : "{$urlPath}/{$route}" . $getStr;
                }
            }
        }

        if($urlPath == ''){
            //ищем путь к модулю, если в базе нет module/action
            $routeParts = explode("/", $route);

            $moduleName = $routeParts[0];
            $criteria->condition = "hidden='no' AND URL LIKE '{$moduleName}%'";

            $page=Page::model()->find($criteria);

            //       echo "<pre>"; print_r($page); echo "</pre>";
            if(!empty($page)){
                $urlPath = $this->getPathAlias($page->parents);
                if(is_bool($urlPath) && $urlPath == false){
                    $urlPath = '0';
                }
                if($urlPath == ''){
                    $urlPath = ($urlPath == '') ? $route.$getStr : "{$urlPath}/{$route}".$getStr;
                }
            }
        }
        $urlPath = ($urlPath == '') ? '0' : $urlPath;
        Yii::app()->cache->set($key_cache, $urlPath);
        return ($urlPath === '0') ? false : $urlPath;
    }

    public function parseUrl($manager,$request,$pathInfo,$rawPathInfo)
    {
       // echo "<pre>"; print_r($_GET); echo "</pre>";
        $aliases = explode('/', $pathInfo);
        $pages = Yii::app()->db->createCommand()->select('id, alias, title, parents, type, URL')->from("{{page}}")->where('alias IN ("' . implode('", "', $aliases) . '") AND hidden="no"')->queryAll();
        if(sizeof($pages) < 1){
            return false;
        }
        $urlPathIds = "/" . $pathInfo . "/";

        $pageByAlias = array();
        foreach($pages as $page){
            $pageByAlias[$page["alias"]] = $page;
        }

        $pageIds = array();
        foreach($aliases as $alias){
            if(!empty($pageByAlias[$alias])){
                $currentAlias = $alias;
                $pageIds[] = $pageByAlias[$alias]["id"];
            }else{
                break;
            }
        }

        $currentPage = $pageByAlias[$currentAlias];
        //echo "<pre>"; print_r($pages); echo "</pre>";
        $realPathIds = "{$currentPage["parents"]}{$currentPage['id']}/";
        $urlPathIds = "/" . implode("/", $pageIds) . "/";
        //URL путь к странице не совпадает с реальным путем
        if($urlPathIds != $realPathIds){
            return false;
        }

        //формируем остаток пути
        $restPath = array_slice($aliases, sizeof($pageIds));
        switch($currentPage["type"]){
            case "static":
                if(empty($restPath)){
                    $_GET["id"] = $currentPage["id"];
                    return "page/index";
                }
                return $moduleName . "/" . implode("/", $restPath);
                break;
            case "URL":
                $urlStr = explode('?', $currentPage["URL"]);
                if(!empty($urlStr[1])){
                    $get = explode("&", $urlStr[1]);
                    foreach($get as $getOne){
                        $getParam = explode("=", $getOne);
                        $_GET[$getParam[0]] = $getParam[1];
                    }
                }
                $url = $urlStr[0];
                $URL_array = explode("/", $url);
                $module_name = $URL_array[0];
                return (!empty($restPath)) ? $module_name . "/" . implode("/", $restPath) : $url;
                break;
        }
       return false;
    }

    /** родительский путь идентификаторов преобразуем  в родительский путь из алиасов
     *
     * @params string
     * @return mixed (string, bool)
     */
    public function getPathAlias($parents){
        if($parents == '/'){
            return '';
        }

        $parent_ids = explode("/", substr($parents, 1, -1));
        Page::getAllVisiblePage($pageByAlias, $pageById);
        $urlPath = $parents;
        foreach($parent_ids as $parent_id){
            //создаем путь из alias
            if(!isset($pageById[$parent_id])){
                return false;
            }
            $urlPath = str_replace("/{$parent_id}/", "/{$pageById[$parent_id]['alias']}/", $urlPath);
        }
        //убираем '/' вначале и в конце
        return substr($urlPath, 1, -1);
    }
}