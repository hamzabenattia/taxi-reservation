<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Entity\Driver;
use App\Entity\Facture;
use App\Entity\Reservation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_DRIVER')]
class DashboardController extends AbstractDashboardController
{



    #[Route('/dashboard', name: 'app_dashboard')]
    #[IsGranted('ROLE_DRIVER')]

    public function index(): Response
    {


        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect($adminUrlGenerator->setController(ReservationCrudController::class)->generateUrl());

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Taxi Reservation');
            
    }


    public function configureUserMenu(UserInterface $user): UserMenu
    {

        // dd($user);
        return parent::configureUserMenu($user)
            // use the given $user object to get the user name
            ->setName($user->getFirstName())

            // use this method if you don't want to display the name of the user

            // you can return an URL with the avatar image
            ->setAvatarUrl($user->getAvatar())
            // use this method if you don't want to display the user image
            // you can also pass an email address to use gravatar's service

            // you can use any type of menu item, except submenus
            ->addMenuItems([
                MenuItem::linkToCrud('Profile', 'fa fa-id-card',Driver::class)->setEntityId(
                    $user->getId()
                )->setAction(Action::EDIT)
            ]);
    }


    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Reservation', 'fas fa-taxi', Reservation::class);
        yield MenuItem::linkToCrud('Facture', 'fas fa-file-invoice', Facture::class);
        yield MenuItem::linkToCrud('Messages', 'fas fa-sms', Contact::class);
        yield MenuItem::linkToCrud('Profile', 'fa fa-id-card',Driver::class)->setEntityId(
            $this->getUser()->getId()
        )->setAction(Action::EDIT);
        yield MenuItem::linkToLogout('Se déconnecter', 'fa fa-fw fa-sign-out');


    

    }
    
}
