<?php

return [
    'notification' => [
        'empty' => 'You have no notifications.',
    ],

    'user' => [
        'anonymous' => 'Anonymous',
        'unauthenticated' => 'Unauthenticated',
        'add' => 'Add an user',
        'edit' => 'Edit an user'
    ],

    'login' => [
        'title' => 'Login',
        'text' => 'Login to access the service.',
        'forget_password' => 'Forgot your password ?',
        'sign_in' => 'Sign in',
    ],

    'password_resetting' => [
        'request' => [
            'title' => 'Reset your password',
            'text' => 'Enter the email address associated to your account. We will send your password recovery procedure to your mailbox.',
            'submit' => 'Reset your password',
        ],
        'error' => [
            'title' => 'This link has expired.',
            'cause1' => 'It has been more than 24 hours since you requested a password reset. You can do a new <a href="%url%" class="text-primary">password request</a>.',
            'cause2' => 'If you have made more than one request, only the last email will be valid.'
        ],
        'success' => [
            'title' => 'Check your emails',
            'text' => 'An email was sent to <b>%email%</b>. Check your mailbox and follow instructions given.'
        ],
        'reset' => 'Reset your password',
        'back_login_markup' => 'back to <b>Log In</b>',
        'back_login' => 'back to Log In',
        'email' => [
            'subject' => 'Update your password',
            'body' => <<<EMAIL
<p>Hello,</p>
<p>A request to reset password of <b>%name%</b> has been made on back-office %app_name%.</p>
<p>Click <a href="%reset_url%">here</a> to define a new password.</p>
<p>Ignore this e-mail if you are not the initiator.</p>
<p>Sincerely</p>
EMAIL
        ]
    ],

    'label' => [
        'notifications' => 'Notifications',
        'active' => 'Active',
        'firstname' => 'Firstname',
        'lastname' => 'Lastname',
        'welcome' => 'Welcome',
        'password' => 'Password',
        'enter_your_password' => 'Enter your password',
        'enter_your_new_password' => 'Enter your new password',
        'password_not_set_if_empty' => 'Let empty to keep your current password.',
        'email' => 'Email',
        'enter_your_email' => 'Enter your email',
        'newpassword' => 'New password',
        'password_confirm' => 'Confirm password',
        'confirm_your_new_password' => 'Confirm your new password',
        'my_account' => 'My account',
        'name' => 'Name',
        'created_at' => 'Created at',
        'sign_out' => 'Logout',
    ],

    'alert' => [
        'account_updated' => 'Your account has been updated.',
        'password_resetted' => 'Your password has been resetted.',
    ]
];