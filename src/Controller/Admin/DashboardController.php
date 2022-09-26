<?php

namespace App\Controller\Admin;

use App\Entity\Front\Logo;
use App\Entity\Front\Shop;
use App\Entity\Front\Pages;
use App\Entity\Front\Theme;
use App\Entity\Front\Banner;
use App\Entity\Front\Social;
use App\Entity\Product\Taxe;
use App\Entity\Product\Offer;
use App\Entity\Product\Option;
use App\Entity\Product\Product;
use App\Entity\Product\Category;
use App\Entity\Product\OptionParent;
use App\Entity\Product\ParentCategory;
use App\Entity\Communication\NewsLetter;
use App\Entity\Product\FeaturedProducts;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('gabyShop - Administration')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('');
        yield MenuItem::section('Gestion Produits');
        yield MenuItem::linkToCrud('Catégorie', 'fas fa-folder', ParentCategory::class);
        yield MenuItem::linkToCrud('Sous Catégorie', 'fas fa-folder-tree', Category::class);
        yield MenuItem::linkToCrud('Produit', 'fas fa-box', Product::class);
        yield MenuItem::linkToCrud('Mise en avant', 'fas fa-star', FeaturedProducts::class);
        yield MenuItem::section('');
        yield MenuItem::linkToCrud('Catégories des options', 'fas fa-folder-plus', OptionParent::class);
        yield MenuItem::linkToCrud('Options', 'fas fa-circle-plus', Option::class);
        yield MenuItem::section('');
        yield MenuItem::section('Taxes');
        yield MenuItem::linkToCrud('TVA', 'fas fa-percent', Taxe::class);
        yield MenuItem::section('');
        yield MenuItem::section('');
        yield MenuItem::section('Promotions');
        yield MenuItem::linkToCrud('Offres', 'fas fa-ad', Offer::class);
        yield MenuItem::section('');
        yield MenuItem::section('Newsletter');
        yield MenuItem::linkToCrud('Gestion des newsletters', 'fas fa-envelope', NewsLetter::class);
        yield MenuItem::section('');
        yield MenuItem::section('Gestion des Pages');
        yield MenuItem::linkToCrud('Gestion des Pages', 'fas fa-pen', Pages::class);
        yield MenuItem::section('');
        yield MenuItem::section('Personnalisation');
        yield MenuItem::linkToCrud('Boutique', 'fas fa-store', Shop::class);
        yield MenuItem::linkToCrud('Logo', 'fas fa-font-awesome', Logo::class);
        yield MenuItem::linkToCrud('Réseaux sociaux', 'fas fa-share-alt', Social::class);
        yield MenuItem::linkToCrud('Banniére', 'fas fa-image', Banner::class);
        yield MenuItem::linkToCrud('Thémes', 'fas fa-droplet', Theme::class);
        yield MenuItem::section('');
        yield MenuItem::linkToRoute('Retour sur le site', 'fas fa-home', 'home');
    }
}
