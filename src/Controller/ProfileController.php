<?php

namespace Umbrella\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use function Symfony\Component\Translation\t;

use Umbrella\AdminBundle\Entity\BaseAdminUser;
use Umbrella\AdminBundle\Lib\Controller\AdminController;
use Umbrella\AdminBundle\Service\UserManagerInterface;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;

class ProfileController extends AdminController
{
    public const PROFILE_ROUTE = 'umbrella_admin_profile_index';

    public function __construct(protected readonly UserManagerInterface $userManager, protected readonly UmbrellaAdminConfiguration $config)
    {
    }

    public function index(Request $request): Response
    {
        $user = $this->getUser();

        if (!$user instanceof BaseAdminUser) {
            throw new NotFoundHttpException(\sprintf('Profile view are only available for fully authenticate %s user.', BaseAdminUser::class));
        }

        $settingsForm = $this->createForm($this->config->userProfileForm(), $user);
        $settingsForm->handleRequest($request);

        if ($settingsForm->isSubmitted() && $settingsForm->isValid()) {
            $this->userManager->update($user);

            $this->toastSuccess(t('alert.account_updated', [], 'UmbrellaAdmin'));

            return $this->redirectToRoute(self::PROFILE_ROUTE);
        }

        return $this->render('@UmbrellaAdmin/profile/index.html.twig', [
            'user' => $user,
            'settings_form' => $settingsForm->createView(),
        ]);
    }
}
