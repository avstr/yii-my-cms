<?php
/* @var $this ResponseController */
/* @var $model Response */

$this->breadcrumbs=array(
	'Отзывы'=>array('index'),
	$model->title,
);

?>

<h1>Просмотр отзыва #<?php echo $model->id; ?></h1>

<?php
    $attributes = array(
        array(
            'name' => 'Дата',
            'value' => date("d.m.Y", strtotime($model->date)),
        ),
        'name',
        'title',
        'text',
    );
    if($model->answer != ''){
        $attributes[] = array(
            'name'=>'Ответ',
            'type' => 'raw',
            'value'=>$model->answer,
        );
    }
    $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>$attributes,
)); ?>
