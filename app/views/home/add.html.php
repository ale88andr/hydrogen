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
    <?= Link::to('home', 'Home page', ['class' => 'h1_html'])?>
    <div class="field">
        <!-- <label for="login">Username</label> -->
        <?= Form::label('user.login', 'Username') ?>
        <?= Form::text('user.login', [
                                    'value' => Input::find('user.login'),
                                    'autocomplete' => 'off',
                                ]);?>
        <!-- <input type="text" name="login" class="login" id="login" value="<?= Input::find('login'); ?>" autocomplete="off"> -->
    </div>

    <div class="field">
        <?= Form::label('user.email', 'Email') ?>
        <?= Form::email('user.email', ['value' => Input::find('user.email')]);?>
        <!-- <input type="email" name="email" class="email" id="email" value="<?= Input::find('email'); ?>" autocomplete="off"> -->
    </div>

    <div class="field">
        <?= Form::label('user.password', 'Type your password') ?>
        <?= Form::password('user.password');?>
        <!-- <label for="password">Type your password</label>
        <input type="password" name="password" class="password" id="password"> -->
    </div>

    <div class="field">
        <?= Form::label('user.password_confirmation', 'Retype your password') ?>
        <?= Form::password('user.password_confirmation');?>
        <!-- <label for="password_confirmation">Retype your password</label>
        <input type="password" name="password_confirmation" class="password" id="password_confirmation"> -->
    </div>

    <div class="field">
        <?= Form::label('user.age', 'Type your age: ') ?>
        <?= Form::numeric('user.age', 5, 100, 1);?>
    </div>

    <div class="field">
        <?= Form::label('user.country', 'Choose your country: ') ?>
        <?= Form::select('user.country', ['Russia', 'Ukraine', 'China']);?>
    </div>

    <div class="field">
        <?= Form::label('user.sex', 'Choose your country: ') ?>
        <?= Form::check_box('user.sex', ['male' => 'Man', 'female' => 'Women'], 'male', [], true);?>
    </div>

    <div class="field">
        <?= Form::label('user.sex', 'Choose your sex: ') ?>
        <?= Form::radio('user.sex', ['male' => 'Man', 'female' => 'Women'], 'male');?>
    </div>

    <div class="field">
        <?= Form::label('user.born', 'Select your bithday: ') ?>
        <?= Form::date('user.born', '1940-01-01');?>
    </div>

    <div class="date">
        <?= Date::current(true, '-') ?>
        <?= Date::now() ?>
        <?= Date::year() ?>
        <?= Date::diff('01.01.2015', '01.01.2016') ?>
    </div>

    <?= Form::hidden('user.surname', Input::find('user.login')) ?>
    <?= String::capitalize(Input::find('user.login')) ?>

    <?= Form::submit('Register') ?>
    <!-- <input type="submit" value="Register"> -->
</form>