<?php
/* @var $this ResponseController */
/* @var $model Response */

$this->breadcrumbs=array(
	'Отзывы'=>array('index'),
	'Добавить отзыв',
);

?>

<h1>Добавить отзыв</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>