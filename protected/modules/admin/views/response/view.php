<?php
/* @var $this ResponseController */
/* @var $model Response */

$this->breadcrumbs=array(
	'Responses'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Журнал отзывов', 'url'=>array('index')),
	array('label'=>'Создать отзыв', 'url'=>array('create')),
	array('label'=>'Редактировать отзыв', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Удалить отзыв', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>Просмотр отзыва#<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'date',
		'name',
		'email',
		'title',
		'text',
		'answer',
		'hidden',
	),
)); ?>
