<?php
/**
 * @var $this \yii\web\View
 * @var $links \app\models\Link[]
 */

$this->title = 'Мои ссылки';
?>
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
            margin: 0;
            padding: 2rem;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #1f2937;
            margin-bottom: 1.5rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #374151;
        }
        input[type="url"] {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-size: 1rem;
            box-sizing: border-box;
        }
        button {
            padding: 0.75rem 1.5rem;
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
        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        .alert-success {
            background: #d1fae5;
            color: #065f46;
        }
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
        }
        th, td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            background: #f9fafb;
            color: #374151;
        }
        .short-link {
            color: #3b82f6;
            text-decoration: none;
        }
        .short-link:hover {
            text-decoration: underline;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .logout-form {
            display: inline;
        }
        .logout-btn {
            background: #ef4444;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        .logout-btn:hover {
            background: #dc2626;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Мои ссылки</h1>
            <?php \yii\widgets\ActiveForm::begin(['action' => ['auth/logout'], 'method' => 'post', 'options' => ['class' => 'logout-form']]); ?>
                <button type="submit" class="logout-btn">Выйти</button>
            <?php \yii\widgets\ActiveForm::end(); ?>
        </div>

        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="alert alert-success">
                <?= Yii::$app->session->getFlash('success') ?>
            </div>
        <?php endif; ?>

        <?php if (Yii::$app->session->hasFlash('error')): ?>
            <div class="alert alert-error">
                <?= Yii::$app->session->getFlash('error') ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= \yii\helpers\Url::to(['link/create']) ?>">
            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <div class="form-group">
                <label for="original_url">Добавить новую ссылку</label>
                <input type="url" id="original_url" name="original_url" required placeholder="https://example.com/very-long-url">
            </div>
            <button type="submit">Создать короткую ссылку</button>
        </form>

        <?php if (empty($links)): ?>
            <p style="margin-top: 2rem; color: #6b7280;">У вас пока нет ссылок. Создайте первую!</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Исходная ссылка</th>
                        <th>Короткая ссылка</th>
                        <th>Кликов</th>
                        <th>Дата создания</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($links as $link): ?>
                        <tr>
                            <td><?= \yii\helpers\Html::encode(\yii\helpers\StringHelper::truncate($link->original_url, 50)) ?></td>
                            <td><a href="<?= $link->shortUrl ?>" class="short-link" target="_blank"><?= $link->code ?></a></td>
                            <td><?= $link->clicksCount ?></td>
                            <td><?= date('Y-m-d H:i', $link->created_at) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
