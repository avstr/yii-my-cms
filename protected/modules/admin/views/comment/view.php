<?php
/* @var $this CommentController */
/* @var $model Comment */

$this->breadcrumbs=array(
	'Comments'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'Журнал комментариев', 'url'=>array('index')),
	array('label'=>'Редактирование комментария', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Удаление комметария', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>Просмотр комментария #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
        array(
            'name' => 'Дата',
            'type' => 'raw',
            'value' => date("d.m.Y H:i", strtotime($model->date)),
        ),
		'module_name',
		'object_id',
        array(
            'name' => 'Пользователь',
            'type' => 'raw',
            'value' => "{$model->user->surname} {$model->user->name}",
        ),
		'title',
		'comment',
		array(
            'name' => 'Скрыто',
            'type' => 'raw',
            'value' => ($model->hidden == "yes") ? "да" : "нет",
        ),
	),
)); ?>
