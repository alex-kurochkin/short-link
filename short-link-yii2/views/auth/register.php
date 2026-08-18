<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= \yii\helpers\Html::encode($this->title) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        h1 {
            margin-bottom: 1.5rem;
            color: #1f2937;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #374151;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-size: 1rem;
        }
        button {
            width: 100%;
            padding: 0.75rem;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
        }
        button:hover {
            background: #2563eb;
        }
        .error {
            color: #dc2626;
            margin-top: 0.5rem;
        }
        .help-block {
            color: #dc2626;
            margin-top: 0.5rem;
        }
        .links {
            margin-top: 1rem;
            text-align: center;
        }
        .links a {
            color: #3b82f6;
            text-decoration: none;
        }
        .required {
            color: #dc2626;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Регистрация</h1>
        <?php $form = \yii\widgets\ActiveForm::begin(['action' => ['auth/register'], 'method' => 'post']); ?>
            <div class="form-group">
                <label for="user-name">Имя</label>
                <?= $form->field($model, 'name')->textInput(['autofocus' => true])->label(false) ?>
            </div>
            <div class="form-group">
                <label for="user-email">Email</label>
                <?= $form->field($model, 'email')->textInput(['type' => 'email'])->label(false) ?>
            </div>
            <div class="form-group">
                <label for="user-password">Пароль</label>
                <?= $form->field($model, 'password')->passwordInput()->label(false) ?>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Подтверждение пароля</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
            </div>
            <button type="submit">Зарегистрироваться</button>
        <?php \yii\widgets\ActiveForm::end(); ?>
        <div class="links">
            <a href="<?= \yii\helpers\Url::to(['auth/login']) ?>">Уже есть аккаунт? Войти</a>
        </div>
    </div>
</body>
</html>
