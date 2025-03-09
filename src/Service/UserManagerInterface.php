<?php

namespace Umbrella\AdminBundle\Service;

use Umbrella\AdminBundle\Entity\BaseAdminUser;

interface UserManagerInterface
{
    public function create(): BaseAdminUser;

    public function find($id): ?BaseAdminUser;

    public function findOneBy(array $criteria): ?BaseAdminUser;

    public function findOneByEmail(string $email): ?BaseAdminUser;

    public function findOneByConfirmationToken(string $confirmationToken): ?BaseAdminUser;

    public function updatePassword(BaseAdminUser $user): void;

    public function update(BaseAdminUser $user): void;

    public function delete(BaseAdminUser $user): void;

    public function getClass(): string;
}
