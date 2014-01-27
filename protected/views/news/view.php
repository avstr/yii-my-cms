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
        array(
            'name' => 'Дата',
            'value' => date("d.m.Y", strtotime($model->date)),
        ),
		'date',
		'title',
		'shorh_desc',
        array(
            'name' => 'Описание',
            'type' => 'raw',
            'value' => $model->description,
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

<?php if(!Yii::app()->user->isGuest):?>
<?php
//echo "<pre>"; print_r(Yii::app()->session->get("comment_model")); echo "</pre>"; exit;
    $commentModel = unserialize(Yii::app()->session->get("comment_model"));
    if(empty($commentModel)){
        $commentModel = new Comment;
    }else{
        Yii::app()->session->add("comment_model", '');
    }
    //echo "<pre>"; print_r($commentModel);echo "</pre>";
    $this->renderPartial('../comment/form',array(
        'moduleName' => 'news',
        'objectId' => $model->id,
        'model' => $commentModel,
    ));
?>
<?php endif ?>
<?php
    Comment::listForObject('news', $model->id);
    $this->renderPartial('../comment/list',array(
        'comments' => Comment::$commentsForObject,
    ));

?>

