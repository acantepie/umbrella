<?php

namespace Umbrella\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use function Symfony\Component\Translation\t;
use Umbrella\AdminBundle\Model\AdminUserInterface;
use Umbrella\AdminBundle\Services\UserManager;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;

/**
 * Class AccountController
 *
 * @Route("/profile")
 */
class ProfileController extends AdminController
{
    public const PROFILE_ROUTE = 'umbrella_admin_profile_index';

    protected UserManager $userManager;
    protected UmbrellaAdminConfiguration $config;

    /**
     * ProfileController constructor.
     */
    public function __construct(UserManager $userManager, UmbrellaAdminConfiguration $config)
    {
        $this->userManager = $userManager;
        $this->config = $config;
    }

    /**
     * @Route("")
     */
    public function index(Request $request)
    {
        $user = $this->getUser();

        if (!$user || !is_a($user, AdminUserInterface::class)) {
            throw new AccessDeniedException();
        }

        $settingsForm = $this->createForm($this->config->profileForm(), $user);
        $settingsForm->handleRequest($request);

        if ($settingsForm->isSubmitted() && $settingsForm->isValid()) {
            $this->userManager->update($user);

            $this->toastSuccess(t('message.account_updated'));

            return $this->redirectToRoute(self::PROFILE_ROUTE);
        }

        return $this->render('@UmbrellaAdmin/Profile/index.html.twig', [
            'user' => $user,
            'settings_form' => $settingsForm->createView(),
        ]);
    }
}
