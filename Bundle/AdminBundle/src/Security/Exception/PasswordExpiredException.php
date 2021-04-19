<?php

namespace Umbrella\AdminBundle\Security\Exception;

use Symfony\Component\Security\Core\Exception\AccountStatusException;

/**
 * Class PasswordExpiredException
 */
class PasswordExpiredException extends AccountStatusException
{
    protected $requestPasswordUrl;

    public function setRequestPasswordUrl(string $requestPasswordUrl): void
    {
        $this->requestPasswordUrl = $requestPasswordUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function __serialize(): array
    {
        return [$this->requestPasswordUrl, parent::__serialize()];
    }

    /**
     * {@inheritdoc}
     */
    public function __unserialize(array $data): void
    {
        [$this->requestPasswordUrl, $parentData] = $data;
        $parentData = \is_array($parentData) ? $parentData : unserialize($parentData);
        parent::__unserialize($parentData);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageKey()
    {
        return 'password_expired';
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageData()
    {
        return [
            '%url%' => $this->requestPasswordUrl
        ];
    }
}
