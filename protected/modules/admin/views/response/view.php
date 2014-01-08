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
	array('label'=>'Удалить отзыв', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Вы уверены, что хотите удалить этот отзыв?')),
);
?>

<h1>Просмотр отзыва #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
        array(
            'name' => 'Дата',
            'value' => date("d.m.Y", strtotime($model->date)),
        ),
		'name',
		'email',
		'title',
		'text',
		'answer',
		'hidden',
	),
));
?>
