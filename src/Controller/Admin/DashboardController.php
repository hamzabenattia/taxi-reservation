<?php

namespace App\Controller\Admin;

use App\Entity\Facture;
use App\Entity\Reservation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Reservation', 'fas fa-taxi', Reservation::class);
        yield MenuItem::linkToCrud('Facture', 'fas fa-file-invoice', Facture::class);

    }
    
}
