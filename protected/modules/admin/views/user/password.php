<?php
/* @var $this UserController */
/* @var $model User */


$this->menu=array(
    array('label'=>'Журнал пользователей', 'url'=>array('index')),
    array('label'=>'Просмотреть пользователя', 'url'=>array('view', 'id'=>$model->id)),
    array('label'=>'Редактировать пользователя', 'url'=>array('update', 'id'=>$model->id)),
);
?>

<h1>Изменить пароль<?php echo $model->id; ?></h1>
Введите новый пароль <br>
<?php
echo CHtml::form();
echo CHtml::textField('password');
echo CHtml::submitButton("Изменить");
echo CHtml::endForm();
?>