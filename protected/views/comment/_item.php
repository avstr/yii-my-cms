<ul>
    <?php foreach((array)$comments as $comment): ?>
    <li comment_id="<?php echo $comment['id']; ?>">
        <a id="comment_<?php $comment['id']; ?>"></a>
        <b><?php echo $comment['user_name'], ' ', $comment['user_surname']; ?></b>
        <br>
        <?php  echo $comment['date']; ?>
        <br>
        <?php echo CHtml::encode($comment['title']); ?>
        <br>
        <?php echo CHtml::encode($comment['comment']); ?>
        <br>
        <?php if(!Yii::app()->user->isGuest):?>
            <?php
                echo CHtml::button('Ответить', array(
                    'comment_id' => $comment['id'],
                    'class' => 'add_comment',
                ));
            ?>
        <?php endif ?>
        <?php
        if(!empty($comment['items'])){
            $this->renderPartial("../comment/_item",array(
                'comments' => $comment['items'],
            ));
        }
        ?>
    </li>
    <?php endforeach ?>
</ul>

