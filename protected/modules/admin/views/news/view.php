<?php
/* @var $this NewsController */
/* @var $model News */


$this->menu=array(
	array('label'=>'Журнал новостей', 'url'=>array('index')),
	array('label'=>'Создать новость', 'url'=>array('create')),
	array('label'=>'Редактировать новость', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Удалить новость', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>Просмотр новости #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
        array(
            'name' => 'Дата',
            'value' => date("d.m.Y", strtotime($model->date)),
        ),
		'title',
		'shorh_desc',
		'description',
        array(
            'name' => 'Скрыто',
            'value' => ($model->hidden == 'yes') ? 'да' : 'нет',
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
