<?php

return [
    'notification' => [
        'empty' => 'Vous n\'avez aucune notification.',
    ],

    'user' => [
        'anonymous' => 'Anonyme',
        'unauthenticated' => 'Non authentifié',
        'add' => 'Ajouter un utilisateur',
        'edit' => 'Modifier un utilisateur'
    ],

    'login' => [
        'title' => 'Se connecter à votre compte',
        'forget_password' => 'Mot de passe oublié ?',
        'sign_in' => 'Se connecter',
    ],

    'password_resetting' => [
        'request' => [
            'title' => 'Réinitialiser votre mot de passe',
            'text' => 'Saisissez l\'adresse email associée à votre compte. Nous enverrons la procédure de récupération de votre mot de passe sur votre messagerie.',
            'submit' => 'Réinitialiser',
        ],
        'error' => [
            'title' => 'Ce lien a éxpiré.',
            'cause1' => 'Cela fait fait plus de 24 heures que vous avez demandé à ce que votre mot de passe soit réinitialisé. Veuillez faire une nouvelle demande de <a href="%url%" class="text-primary">réinitialisation de mot de passe</a>.',
            'cause2' =>  'Si vous avez effectué plusieurs demande, seul l\'email de réinitialisation envoyé en dernier sera valide.',
        ],
        'success' => [
            'title' => 'Vérifier vos emails',
            'text' => 'Un email a été envoyé à <b>%email%</b>. Vérifier votre boite mail et suivez les instructions',
        ],
        'reset' => 'Réinitialiser votre mot de passe',
        'back_login_link' => 'Forget it, <a href="%url%">send me back</a> to the sign in screen.',
        'back_login' => 'Revenir à l\'écran de connexion',
        'email' => [
            'subject' => 'Changer votre mot de passe',
            'body' => <<<EMAIL
<p>Bonjour,</p>
<p>Une demande de réinitialisation du mot de passe de <b>%name%</b> a été effectuée sur le back-office %app_name%.</p>
<p>Cliquez <a href="%reset_url%">ici</a> pour définir votre nouveau mot de passe.</p>
<p>Si vous n'êtes pas à l'origine de la demande vous pouvez ignorer cet e-mail.</p>
<p>Cordialement</p>
EMAIL
        ]
    ],

    'label' => [
        'notifications' => 'Notifications',
        'active' => 'Actif',
        'firstname' => 'Prénom',
        'lastname' => 'Nom',
        'welcome' => 'Bienvenue',
        'password' => 'Mot de passe',
        'enter_your_password' => 'Saisissez votre mot de passe',
        'enter_your_new_password' => 'Saisissez votre nouveau mot de passe',
        'password_not_set_if_empty' => 'Laissez vide pour conserver le mot de passe actuel',
        'email' => 'Email',
        'enter_your_email' => 'Saissiez votre email',
        'newpassword' => 'Nouveau mot de passe',
        'password_confirm' => 'Confirmer',
        'confirm_your_new_password' => 'Confirmer votre nouveau mot de passe',
        'my_account' => 'Mon compte',
        'name' => 'Nom',
        'created_at' => 'Crée le',
        'sign_out' => 'Se déconnecter',
    ],

    'alert' => [
        'account_updated' => 'Votre compte a bien été mis à jour.',
        'password_resetted' => 'Votre mot de passe a bien été réinitialisé.',
    ]
];