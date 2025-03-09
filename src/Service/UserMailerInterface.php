<?php

namespace Umbrella\AdminBundle\Service;

use Umbrella\AdminBundle\Entity\BaseAdminUser;

interface UserMailerInterface
{
    public function sendPasswordRequest(BaseAdminUser $user): void;
}
