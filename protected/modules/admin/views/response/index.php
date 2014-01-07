<?php
class Test{
    private $var;
    function setMe($value){
        $this->var = $value;
    }
}

class More extends Test{
    public $var;
}
$oMore = new More;

$oMore->setMe('foo');

echo $oMore->var;

/* @var $this ResponseController */
/* @var $model Response */


$this->menu=array(
	array('label'=>'Создать отзыв', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#response-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Журнал отзывов</h1>

<?php echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'response-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
        array(
            'name' => 'date',
            'header' => 'Дата',
            'value' => 'date("d.m.Y", strtotime($data->date))',
        ),
		'name',
		'email',
		'title',
		'text',
        array(
            'name' => 'hidden',
            'header' => 'Скрыто',
            'value' => '($data->hidden == "yes") ? "да" : "нет"',
            'filter' => array('yes' => "да", 'no' => "нет"),
        ),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
