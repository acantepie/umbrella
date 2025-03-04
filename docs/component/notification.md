# How enable web notifications on Admin

### Generate entity and provider
```bash
php bin/console make:admin:notification
```

### Customize generated code as you want, then enable notification on config :
```yaml
# config/packages/umbrella_admin.yaml
umbrella_admin:
  notification:
    provider: App\Notification\AdminNotificationProvider
    poll_interval: 10
```

### Enable route :
```yaml
# config/routes.yaml
umbrella_admin_notification_:
  resource: "@UmbrellaAdminBundle/config/routes/notification.php"
  prefix: /admin
```

### Finally, create your notification :
```php 
$notification = new AdminNotification();
$notification->title = 'Hello';

$em->persist($notification);
$em->flush();
```