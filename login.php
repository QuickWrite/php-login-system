<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Log in</title>
</head>
<body>
<?php
require_once 'core/init.php';

if(Input::exists()) {
    if(Token::check(Input::get('token'))) {

        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array('required' => true),
            'pwd' => array('required' => true)
        ));

        if($validation->passed()) {
            $user = new User();
            $login = $user->login(Input::get('username'), Input::get('pwd'));

            if($login) {
                Redirect::to('index.php');
            } else {
                echo '<p>Sorry, login failed</p>';
            }

        } else {
            foreach($validation->errors as $error) {
                echo $error.'<br>';
            }
        }

    }
}
?>
    <form action="" method="post">
        <div class="field">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" autocomplete="off">
        </div>
        <div class="field">
            <label for="pwd">Password</label>
            <input type="password" name="pwd" id="pwd">
        </div>

        <input type="hidden" name="token" value="<?php echo Token::generate();?>">
        <input type="submit" value="Log in">
    </form>
</body>
</html>