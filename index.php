<?php
require_once 'core/init.php';

if(Session::exists('home')) {
    echo '<p>'.Session::flash('home').'</p>';
}

$user = new User();
if($user->isLoggedIn()) {
?>
    <p>Hello <a href="profile.php?user=<?php echo escape($user->data()->username); ?>"><?php echo escape($user->data()->username);?></a></p>
    <ul>
        <li><a href="logout.php">Logout</a></li>
        <li><a href="update.php">Update your details</a></li>
        <li><a href="changepassword.php">Change your password</a></li>
    </ul>
<?php
    if($user->hasPermission('admin'))  {
        echo '<p>You are an administrator!</p>';
    }

} else {
    echo 'You need to <a href="login.php">log in</a> or <a href="register.php">register</a>';
}