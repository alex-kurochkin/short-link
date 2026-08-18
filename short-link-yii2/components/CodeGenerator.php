<?php

namespace app\components;

use yii\base\Component;
use Yii;

/**
 * CodeGenerator component for generating unique short link codes
 */
class CodeGenerator extends Component
{
    /**
     * @var int длина кода
     */
    public $codeLength = 6;

    /**
     * @var int максимальное количество попыток генерации
     */
    public $maxAttempts = 10;

    /**
     * Generate a unique code
     * @return string
     * @throws \RuntimeException if unable to generate unique code
     */
    public function generate(): string
    {
        $attempts = 0;
        
        do {
            $code = $this->generateRandomCode();
            $attempts++;
        } while ($this->codeExists($code) && $attempts < $this->maxAttempts);

        if ($attempts >= $this->maxAttempts) {
            throw new \RuntimeException('Не удалось сгенерировать уникальный код');
        }

        return $code;
    }

    /**
     * Generate a random code
     * @return string
     */
    private function generateRandomCode(): string
    {
        return Yii::$app->security->generateRandomString($this->codeLength);
    }

    /**
     * Check if code exists in database
     * @param string $code
     * @return bool
     */
    private function codeExists(string $code): bool
    {
        return (bool) \app\models\Link::find()->where(['code' => $code])->exists();
    }
}
