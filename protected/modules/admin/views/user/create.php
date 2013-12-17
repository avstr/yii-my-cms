<?php
/* @var $this UserController */
/* @var $model User */

$this->menu=array(
	array('label'=>'Журнал пользователей', 'url'=>array('index')),
);
?>

<h1>Создание пользователя</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>