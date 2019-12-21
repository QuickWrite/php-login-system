<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Update your profile</title>
</head>
<body>
    <?php
    require_once 'core/init.php';

    $user = new User;

    if(!$user->isLoggedIn()) {
        Redirect::to('index.php');
    }

    if(Input::exists()) {
        if(Token::check(Input::get('token'))) {
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                    'min' => 2,
                    'max' => 50s
                )
            ));

            if($validation->passed()) {
                try {
                    $user->update(array(
                        'name' => Input::get('name')
                    ));
                    Session::flash('home', 'Your details have been updated.');
                    Redirect::to('index.php');
                } catch(Exeption $e) {
                    die($e->getMessage());
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
            <label for="name">Name</label>s
            <input type="text" name="name" id="name" value="<?php echo escape($user->data()->name); ?>">
        </div>

        <input type="submit" value="Update">
        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>"> 
    </form>
</body>
</html>