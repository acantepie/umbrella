# Create an Admin Controller

Create a controller on your project :
```php
// src/Controller/Admin/DefaultController.php
<?php

namespace App\Controller\Admin;

use Symfony\Component\Routing\Annotation\Route;
use Umbrella\CoreBundle\Controller\BaseController;

class DefaultController extends BaseController
{
    /**
     * @Route("/admin", name="admin_home")
     */
    public function index()
    {
        return $this->render('@UmbrellaAdmin/layout.html.twig');
    }

}
```

Note, all your admin view must extend `@UmbrellaAdmin/layout.html.twig`.

When create an Admin controller, extending `BaseController` is optional. This Class provides some helper to use Umbrella Components.

### Next step 
[>> Add entry on menu](menu.md)

[<< Back to documentation](/docs)