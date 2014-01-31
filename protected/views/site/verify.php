<div class="flash-error">
    <?php if($result == 'verify'): ?>
            Ваша учетная запись подтверждена. Можете залогинеться на сайте.
    <?php elseif($result == 'earlier_verify'): ?>
            Ваша учетная запись уже подтверждена.
    <?php elseif($result == 'not_user'): ?>
            Нет пользователя с таким id.
    <?php elseif($result == 'error_id_secure_code'): ?>
            Secure_code не соответствует заданному id.
    <?php elseif($result == 'error_save'): ?>
            Не удалось сохранить данные по пользователю.
    <?php endif ?>
</div>