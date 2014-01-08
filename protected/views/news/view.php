<?php
/* @var $this NewsController */
/* @var $model News */
$this->breadcrumbs=array_merge(PageUrlRule::$fullBreadCrumbs, array(
    $model->title,
));

?>

<h1>Просмотр новости #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'date',
		'title',
		'shorh_desc',
        array(
            'name' => 'Описание',
            'type' => 'raw',
            'value' => 'dascription',
        ),
        array(
            'name' => 'Изображение',
            //'type'=>'image',
            //'value' => '(!empty($data->picture) && file_exists($_SERVER["DOCUMENT_ROOT"].Yii::app()->urlManager->baseUrl."/images/news/".substr($data->date, 0, 4)."/small/".$data->picture)) ?  "/images/news/".substr($data->date, 0, 4)."/small/".$data->picture : "-"',
            'filter'=>'',
            //'headerHtmlOptions'=>array('width'=>'30', 'height'=>'30'),
            'type' => 'raw',
            'value' =>$model->material_image($model->id, substr($model->date, 0, 4), $model->picture, $model->title, '150','small_img left'),

        ),
	),
)); ?>
