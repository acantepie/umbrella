<?php

namespace Umbrella\AdminBundle\Services;

use Umbrella\AdminBundle\Entity\BaseAdminUser;

interface UserMailerInterface
{
    public function sendPasswordRequest(BaseAdminUser $user): void;
}
