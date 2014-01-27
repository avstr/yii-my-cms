<div>
    Комментарии
</div>

<?php
    $this->renderPartial('../comment/_item',array(
        'comments' => $comments,
    ));
?>