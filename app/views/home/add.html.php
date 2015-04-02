<?php
    if(Input::isSubmit()){
        // echo Input::find('username');
        $check = new Validate;
        $validates_result = $check->validates($_POST, [
            'username' => [ 'presence' => true,
                            // 'length' => ['minimum' => 3, 'maximum' => 50], // ['in' => '3..50', 'is' => 6]
                            'length' => ['in' => '6..50'],
                            'inclusion' =>  ['www', 'us', 'ca', 'jp'],
                            'format' => '/REGEXP/',
                            'uniqueness' => true
                            ],
            'password' => [ 'presence' => true, 
                            'length' => ['minimum' => 3], 
                            'confirmation' => true,
                            'numericality' => true
                            ],
            ]);
        if($validates_result->isValid()){
            echo "valid";
        } else {
            print_r($validates_result->errors());
        }
    }
?>

<form action="?" method="post">
    <div class="field">
        <label for="username">Username</label>
        <input type="text" name="username" class="username" id="username" value="<?= Input::find('username'); ?>" autocomplete="off">
    </div>

    <div class="field">
        <label for="email">Email</label>
        <input type="email" name="email" class="email" id="email" value="<?= Input::find('email'); ?>" autocomplete="off">
    </div>

    <div class="field">
        <label for="password">Type your password</label>
        <input type="password" name="password" class="password" id="password">
    </div>

    <div class="field">
        <label for="password_repeat">Retype your password</label>
        <input type="password_repeat" name="password_repeat" class="password_repeat" id="password_repeat">
    </div>

    <input type="submit" value="Register">
</form>