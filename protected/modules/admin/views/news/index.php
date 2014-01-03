<?php
/* @var $this NewsController */
/* @var $model News */

$this->menu=array(
	array('label'=>'Создать новость', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#news-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Журнал новостей</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'news-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		array(
            'name' => 'date',
            'header' => 'Дата',
            'value' => 'date("d.m.Y", strtotime($data->date))',
        ),
		'title',
		'shorh_desc',
		'description',
		array(
            'name' => 'picture',
            'header' => 'Изображение',
            //'type'=>'image',
            //'value' => '(!empty($data->picture) && file_exists($_SERVER["DOCUMENT_ROOT"].Yii::app()->urlManager->baseUrl."/images/news/".substr($data->date, 0, 4)."/small/".$data->picture)) ?  "/images/news/".substr($data->date, 0, 4)."/small/".$data->picture : "-"',
            'filter'=>'',
            //'headerHtmlOptions'=>array('width'=>'30', 'height'=>'30'),
            'type' => 'raw',
            'value' => '(!empty($data->picture) && file_exists($_SERVER["DOCUMENT_ROOT"].Yii::app()->urlManager->baseUrl."/images/news/".substr($data->date, 0, 4)."/small/".$data->picture)) ?  CHtml::image("/images/news/".substr($data->date, 0, 4)."/small/".$data->picture, "", array("width" => "30", "height" => "30")) : "-"',

        ),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
