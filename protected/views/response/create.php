<?php
/* @var $this ResponseController */
/* @var $model Response */
$this->breadcrumbs=array_merge(PageUrlRule::$fullBreadCrumbs, array(
	'Добавить отзыв',
));

?>

<h1>Добавить отзыв</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>