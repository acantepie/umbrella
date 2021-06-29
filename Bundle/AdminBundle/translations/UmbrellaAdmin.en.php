<?php

return [
    'security_page' => [
        'sign_in' => 'Login to access the service.',
        'forget_password' => 'Forgot your password ?',
        'reset_password_error' => 'This link has expired.',
        'reset_password_error_cause1' => 'It has been more than 24 hours since you requested a password reset. You can do a new <a href="%url%" class="text-primary">password request</a>.',
        'reset_password_error_cause2' => 'If you have made more than one request, only the last email will be valid.',
        'forget_password_explanation' => 'Enter the email address associated to your account. We will send your password recovery procedure to your mailbox.',
        'back_login_markup' => 'back to <b>Log In</b>',
        'back_login' => 'back to Log In',
        'request_password_success' => 'An email was sent to <b>%email%</b>. Check your mailbox and follow instructions given.',
    ],

    'notification' => [
        'empty' => 'You have no notifications.',
    ],

    'user' => [
        'anonymous' => 'Anonymous',
        'unauthenticated' => 'Unauthenticated',
        'add' => 'Add an user',
        'edit' => 'Edit an user'
    ],

    'label' => [
        'notifications' => 'Notifications',
        'active' => 'Active',
        'firstname' => 'Firstname',
        'lastname' => 'Lastname',
        'welcome' => 'Welcome',
        'username' => 'Username',
        'password' => 'Password',
        'password_not_set_if_empty' => 'Let empty to keep your current password.',
        'enter_your_username' => 'Enter your username',
        'enter_your_password' => 'Enter your password',
        'email' => 'Email',
        'your_email' => 'Your email',
        'newpassword' => 'New password',
        'password_confirm' => 'Confirm password',
        'my_account' => 'My account',
        'name' => 'Name',
        'created_at' => 'Created at',
    ],

    'action' => [
        'sign_in' => 'Login',
        'sign_out' => 'Logout',
        'reset_your_password' => 'Reset your password',
        'check_your_email' => 'Check your emails',
        'save' => 'Save'
    ],

    'alert' => [
        'account_updated' => 'Your account has been updated.',
        'password_resetted' => 'Your password has been resetted.',
    ]
];