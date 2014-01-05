<?php
/* @var $this ResponseController */
/* @var $model Response */

$this->menu=array(
	array('label'=>'Журнал отзывов', 'url'=>array('index')),
	array('label'=>'Создать отзыв', 'url'=>array('create')),
	array('label'=>'Просмотреть отзыв', 'url'=>array('view', 'id'=>$model->id)),
);
?>

<h1>Редактирование отзыва <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>