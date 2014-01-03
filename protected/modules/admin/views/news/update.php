<?php
/* @var $this NewsController */
/* @var $model News */

$this->menu=array(
	array('label'=>'Журнал новостей', 'url'=>array('index')),
	array('label'=>'Создать новость', 'url'=>array('create')),
	array('label'=>'Просмотреть новость', 'url'=>array('view', 'id'=>$model->id)),
);
?>

<h1>Редактирование новости <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>