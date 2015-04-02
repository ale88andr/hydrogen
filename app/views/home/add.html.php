<?php
    if(Input::isSubmit()){
        // echo Input::find('username');
        $check = new Validate;
        $validates_result = $check->validates($_POST, [
            'login' => [ 'presence' => true,
                            // 'length' => ['minimum' => 3, 'maximum' => 50], // ['in' => '3..50', 'is' => 6]
                            // 'length' => ['in' => '6..50'],
                            // 'length' => ['minimum' => 6, 'maximum' => 10],
                            // 'length' => ['is' => 7],
                            'inclusion' =>  ['www', 'us', 'ca', 'jp'],
                            'uniqueness' => 'users'
                            ],
            'password' => [ 'presence' => true, 
                            'length' => ['minimum' => 3], 
                            'confirmation' => true,
                            'numericality' => true
                            ],
            'email' => ['format' => '/\A([^@\s]+)@((?:[-a-z0-9]+\.)`[a-z]{2,})\z/i'],
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
        <input type="text" name="login" class="login" id="login" value="<?= Input::find('login'); ?>" autocomplete="off">
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
        <label for="password_confirmation">Retype your password</label>
        <input type="password" name="password_confirmation" class="password" id="password_confirmation">
    </div>

    <input type="submit" value="Register">
</form>