<?php
/* @var $this ResponseController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=PageUrlRule::$breadCrumbs;

?>


<h1>Отзывы</h1>

<?php echo CHtml::link('Добавить отзыв', array('create')); ?>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
