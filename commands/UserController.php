<?php

declare(strict_types=1);

namespace app\commands;

use app\models\User;
use yii\console\Controller;
use yii\console\ExitCode;

class UserController extends Controller
{
    public function actionCreateAdmin(): int
    {
        $username = 'admin';
        $email = 'admin@example.com';
        $password = 'admin';

        if (User::find()->where(['username' => $username])->exists()) {
            $this->stdout("Admin user already exists.\n");

            return ExitCode::OK;
        }

        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->status = User::STATUS_ACTIVE;
        $user->setPassword($password);
        $user->generateAuthKey();

        if (!$user->save()) {
            print_r($user->getErrors());

            return ExitCode::UNSPECIFIED_ERROR;
        }

        $this->stdout("Admin user created.\n");
        $this->stdout("Username: {$username}\n");
        $this->stdout("Password: {$password}\n");

        return ExitCode::OK;
    }
}
