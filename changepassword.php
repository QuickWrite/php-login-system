<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Change your password</title>
</head>
<body>
    <?php
    require_once 'core/init.php';

    $user = new User();

    if(!$user->isLoggedIn()) {
        Redirect::to('index.php');
    }

    if(Input::exists()) {
        if(Token::check(Input::get('token'))) {
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'current_pwd' => array(
                    'required' => true,
                    'min' => 6
                ),
                'new_pwd' => array(
                    'required' => true,
                    'min' => 6
                ),
                'new_pwd_again' => array(
                    'required' => true,
                    'min' => 6,
                    'matches' => 'new_pwd'
                )
            ));

            if($validation->passed()) {
                if(Hash::make(Input::get('current_pwd'), $user->data()->salt) !== $user->data()->password) {
                    echo 'Your current password is wrong.';
                } else {
                    $salt = Hash::salt(32);
                    $user->update(array(
                        'password' => Hash::make(Input::get('new_pwd'), $salt),
                        'salt' => $salt
                    ));

                    Session::flash('home', 'Your password has been changed!');
                    Redirect::to('index.php');
                }
            } else {
                foreach($validation->errors() as $error) {
                    echo $error . '<br>';
                }
            }
        }
    }
    ?>
    <form action="" method="post">
        <div class="field">
            <label for="currrent_pwd">Current password:</label>
            <input type="password" name="current_pwd" id="current_pwd">
        </div>
        <div class="field">
            <label for="new_pwd">New password:</label>
            <input type="password" name="new_pwd" id="new_pwd">
        </div>
        <div class="field">
            <label for="new_pwd_again">Current password:</label>
            <input type="password" name="new_pwd_again" id="new_pwd_again">
        </div>

        <input type="submit" value="Change password">
        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    </form>
</body>
</html>