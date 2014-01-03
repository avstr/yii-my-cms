<?php
/* @var $this NewsController */
/* @var $model News */

$this->menu=array(
	array('label'=>'Журнал новостей', 'url'=>array('index')),
);
?>

<h1>Создание новости</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>