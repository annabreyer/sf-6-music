<?php

namespace App\Controller\Admin;

use App\Entity\Album;
use App\Entity\Artist;
use App\Entity\Playlist;
use App\Entity\Song;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        parent::index();

        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Earlies Paylist Manager');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Playlists', 'fas fa-list', Playlist::class);
        yield MenuItem::linkToCrud('Songs','fas fa-music',  Song::class);
        yield MenuItem::linkToCrud('Artists','fas fa-user', Artist::class);
        yield MenuItem::linkToCrud('Albums', 'fas fa-compact-disc', Album::class);
    }

    public function configureCrud(): Crud
    {
        return parent::configureCrud()
                     ->showEntityActionsInlined();
    }

    public function configureActions(): Actions
    {
        return parent::configureActions()
                     ->add(Crud::PAGE_INDEX, 'detail');
    }
}
