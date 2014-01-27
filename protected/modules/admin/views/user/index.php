<?php
/* @var $this UserController */
/* @var $model User */

$this->menu=array(
    array('label'=>'Создать пользователя', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#user-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Журнал пользователей</h1>

<?php echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
    <?php $this->renderPartial('_search',array(
    'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'user-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'columns'=>array(
        'id' => array(
            'name' => "id",
            'htmlOptions' => array("width" => "20px"),
        ),
        'name',
        'secondname',
        'surname',
        'login',
        'role' => array(
            'name' => 'role',
            'filter' => array("user" => "user", "admin" => "admin"),
        ),
        'status' =>array(
            'name' => 'status',
            "value" =>'($data->status == "no_verify") ? "новый" : (($data->status == "verify") ? "подтвержденный" : (($data->status == "banned") ? "забанненный" : "удаленный"))',
            'filter' => array('no_verify'=>'новый','verify'=>'подтвержденный', 'banned' => 'забанненный', 'deleted' => 'удаленный'),
        ),
        'email',
        array(
            'class'=>'CButtonColumn',
        ),
    ),
)); ?>
