<?php
/* @var $this ResponseController */
/* @var $model Response */

$this->menu=array(
	array('label'=>'Журнал отзывов', 'url'=>array('index')),
);
?>

<h1>Создать отзыв</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>