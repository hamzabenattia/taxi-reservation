<?php

namespace App\Controller\Admin;

use App\Entity\Facture;
use App\Entity\Reservation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_DRIVER')]
class DashboardController extends AbstractDashboardController
{




    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {

        return $this->render('/admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Taxi Reservation');
    }


    public function configureUserMenu(UserInterface $user): UserMenu
    {
        // Usually it's better to call the parent method because that gives you a
        // user menu with some menu items already created ("sign out", "exit impersonation", etc.)
        // if you prefer to create the user menu from scratch, use: return UserMenu::new()->...
        return parent::configureUserMenu($user)
            // use the given $user object to get the user name
            ->setName($user->getFirstName())
            // use this method if you don't want to display the name of the user

            // you can return an URL with the avatar image
            ->setAvatarUrl($user->getAvatar())
            // use this method if you don't want to display the user image
            // you can also pass an email address to use gravatar's service
            ->setGravatarEmail($user->getEmail())

            // you can use any type of menu item, except submenus
            ->addMenuItems([
                MenuItem::linkToRoute('Profile', 'fa fa-id-card','app_profile', ['...' => '...']),
            ]);
    }


    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Reservation', 'fas fa-taxi', Reservation::class);
        yield MenuItem::linkToCrud('Facture', 'fas fa-file-invoice', Facture::class);
        yield MenuItem::linkToLogout('Se déconnecter', 'fa fa-fw fa-sign-out');


    

    }
    
}
