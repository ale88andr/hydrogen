<?php
    Input::find('user.login');
    if(Input::isSubmit()){
        // echo Input::find('username');
        $check = new Validate;
        $validates_result = $check->validates($_POST['user'], [
            'login' => [ 'presence' => true,
                            'length' => ['minimum' => 3, 'maximum' => 50], // ['in' => '3..50', 'is' => 6]
                            // 'length' => ['in' => '6..50'],
                            // 'length' => ['minimum' => 6, 'maximum' => 10],
                            // 'length' => ['is' => 7],
                            // 'inclusion' =>  ['www', 'us', 'ca', 'jp'],
                            'uniqueness' => 'users'
                            ],
            'password' => [ 'presence' => true,
                            'length' => ['minimum' => 3],
                            'confirmation' => true,
                            // 'numericality' => true
                            ],
            // 'email' => ['format' => '/\A([^@\s]+)@((?:[-a-z0-9]+\.)`[a-z]{2,})\z/i'],
            ]);
        if($validates_result->isValid()){
            $user = new Home;

            try {
                $user->create([
                        'login' => Input::find('login'),
                        'email' => Input::find('email'),
                        'password' => Input::find('password'),
                        'group_id' => 1,
                    ]);

                Redirect::to([  'controller' => 'home',
                                'action' => 'index',
                                'params' => []
                            ]);
            } catch(Exception $e) {
                die($e->getMessage);
            }
        } else {
            echo '<div class="errors">';
            foreach ($validates_result->errors() as $error) {
                echo $error . '</br>';
            }
            echo '</div>';
        }
    }
?>

<form action="?" method="post">
    <?= Html::tag('h1', 'H1 content', ['class' => 'h1_html'])?>
    <?= Link::to("home", 'Home', ['class' => 'h1_html'])?>
    <div class="field">
        <!-- <label for="login">Username</label> -->
        <?= Form::label('login', 'Username') ?>
        <?= Form::text('user.login', [
                                    'value' => Input::find('user.login'),
                                    'autocomplete' => 'off'
                                ]);?>
        <!-- <input type="text" name="login" class="login" id="login" value="<?= Input::find('login'); ?>" autocomplete="off"> -->
    </div>

    <div class="field">
        <?= Form::label('email', 'Email') ?>
        <?= Form::email('user.email', ['value' => Input::find('user.email')]);?>
        <!-- <input type="email" name="email" class="email" id="email" value="<?= Input::find('email'); ?>" autocomplete="off"> -->
    </div>

    <div class="field">
        <?= Form::label('password', 'Type your password') ?>
        <?= Form::password('user.password');?>
        <!-- <label for="password">Type your password</label>
        <input type="password" name="password" class="password" id="password"> -->
    </div>

    <div class="field">
        <?= Form::label('password_confirmation', 'Retype your password') ?>
        <?= Form::password('user.password_confirmation');?>
        <!-- <label for="password_confirmation">Retype your password</label>
        <input type="password" name="password_confirmation" class="password" id="password_confirmation"> -->
    </div>

    <input type="submit" value="Register">
</form>