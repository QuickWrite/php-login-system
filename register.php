<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register on this Website</title>
</head>
<body>
<?php
require_once 'core/init.php';

if(Input::exists()) {
    if(Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array(
                'required' => true,
                'min' => 2,
                'max' => 20,
                'unique' => 'users'
            ),
            'pwd' => array(
                'required' => true,
                'min' => 6
            ),
            'rep_pwd' => array(
                'required' => true,
                'matches' => 'pwd'
            ),
            'name' => array(
                'required' => true,
                'min' => 2,
                'max' => 50
            )
        ));

        if($validation->passed()) {
            $user = new User();

            $salt = Hash::salt('32');

            try {
                $user->create(array(
                    'username' => Input::get('username'),
                    'password' => Hash::make(Input::get('pwd'), $salt),
                    'salt' => $salt,
                    'name' => Input::get('name'),
                    'joined' => date('Y-m-d H:i:s'),
                    'group' => 1
                ));

                Session::flash('home', 'You have been registered and can now log in!');
                Redirect::to('index.php');

            } catch(Exception $e) {
                die($e->getMessage());
            }
        } else {
            foreach($validation->errors() as $error) {
                echo $error.'<br>';
            }
        }
    }
}
?>
    <form action="" method="post">
        <div class="field">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" value="<?php echo escape(Input::get('username'));?>" autocomplete="off">
        </div>
        <div class="field">
            <label for="pwd">Choose a password</label>
            <input type="password" name="pwd" id="pwd" value="">
        </div>
        <div class="field">
            <label for="rep_pwd">Enter your password again</label>
            <input type="password" name="rep_pwd" id="rep_pwd" value="">
        </div>
        <div class="field">
            <label for="name">Your name</label>
            <input type="text" name="name" id="name" value="<?php echo escape(Input::get('name'));?>" autocomplete="off">
        </div>

        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
        <input type="submit" value="Register">
    </form>
</body>
</html>