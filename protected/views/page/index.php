<?php
/* @var $this SiteController */

$this->pageTitle=$model->meta_t;
$this->breadcrumbs=PageUrlRule::$breadCrumbs;
?>

<?php
    echo $model->content;
?>