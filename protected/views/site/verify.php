<?php if($result == 'verify'): ?>
    <div>
        Ваша учетная запись подтверждена. Можете залогинеться на сайте.
    </div>
<?php elseif($result == 'earlier_verify'): ?>
    <div>
        Ваша учетная запись уже подтверждена.
    </div>
<?php elseif($result == 'not_user'): ?>
    <div>
        Нет пользователя с таким id.
    </div>

<?php elseif($result == 'error_id_secure_code'): ?>
    <div>
        Secure_code не соответствует заданному id.
    </div>
<?php endif ?>