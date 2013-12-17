<?php
class PageUrlRule extends CBaseUrlRule
{
    public $connectionID = 'db';

    public function createUrl($manager,$route,$params,$ampersand)
    {
        if ($route==='car/index')
        {
            if (isset($params['manufacturer'], $params['model']))
                return $params['manufacturer'] . '/' . $params['model'];
            else if (isset($params['manufacturer']))
                return $params['manufacturer'];
        }
        return false;  // не применяем данное правило
    }

    public function parseUrl($manager,$request,$pathInfo,$rawPathInfo)
    {
        $aliases = explode('/', $pathInfo);
        $aliasPage = $aliases[sizeof($aliases) - 1];
        $pages = Yii::app()->db->createCommand()->select('id, alias, parents, type, URL')->from("{{page}}")->where('alias IN ("' . implode('", "', $aliases) . '") AND hidden="no"')->queryAll();
        if(sizeof($aliases) != sizeof($pages)){
            return false;
        }
        $urlPathIds = "/" . $pathInfo . "/";

        $page_aliases = array();
        foreach($pages as $page){
            //создаем путь из id, пришедших в адресной строке
            $urlPathIds = str_replace("/{$page['alias']}/", "/{$page['id']}/", $urlPathIds);
            if($page["alias"] == $aliasPage){
                $realPathIds = "{$page["parents"]}{$page['id']}/";
                $currentPage = $page;
            }
        }
        //URL путь к странице не совпадает с реальным путем
        if($urlPathIds != $realPathIds){
            return false;
        }

        if($currentPage["type"] == "static"){
            $_GET["id"] = $currentPage["id"];
            return "page/index";
        }else{
            return $currentPage["URL"];
        }
    }
}