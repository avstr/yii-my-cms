<?php
/* @var $this UserController */
/* @var $model User */


$this->menu=array(
	array('label'=>'Журнал пользоателей', 'url'=>array('index')),
    array('label'=>'Изменение пароля', 'url'=>array('password', 'id'=>$model->id)),
);
?>

<h1>Update User <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>