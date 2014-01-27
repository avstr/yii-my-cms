<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Журнал пользователей', 'url'=>array('index')),
	array('label'=>'Редактировать пользователя', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Изменить пароль', 'url'=>array('password', 'id'=>$model->id)),
);
?>

<h1>View User #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'secondname',
		'surname',
		'login',
		'created',
		'role',
		'status' => array(
            'name' => 'Статус',
            'value' => ($model->status == "no_verify") ? "новый" : (($model->status == "verify") ? "подтвержденный" : (($model->status == "banned") ? "забанненный" : "удаленный")),
        ),
		'email',
	),
)); ?>
