<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use App\Entity\Category;
use App\Entity\Post;
use App\Repository\UserRepository;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

class DashboardController extends AbstractDashboardController
{
    protected $categoryRepository;
    protected $userRepository;
    protected $postRepository;

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        //Préparations des valeurs: labels et data
        $postsParCategory = $this->categoryRepository->findPostsByCategories();
        $labels_chart1 = [];
        $data_chart1 = [];
        foreach ($postsParCategory as $post) {
            $labels_chart1[] = $post[0]->getName();
            $data_chart1[] = $post["total"];
        }

        $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => $labels_chart1,
            'datasets' => [
                [
                    'label' => 'Mon premier graphique',
                    'backgroundColor' => 'rgb(25, 79, 13, 0.6)',
                    'borderColor' => 'rgb(255, 199, 112, 1)',
                    'borderWidth' => 2,
                    'hoverBackgroundColor' => 'rgb(255, 199, 112,1)',
                    'data' => $data_chart1,
                ]
            ],
        ]);
       
   

        $chart->setOptions([
            'scales' => [
                'yAxes' => [
                    ['ticks' => ['min' => 0, 'max' => 10]],
                ],
            ],
        ]);

        //Préparations des valeurs: labels et data
        $postsParUser = $this->userRepository->findPostsByUsers();
        $labels_chart2 = [];
        $data_chart2 = [];
        foreach ($postsParUser as $post) {
            $labels_chart2[] = $post[0]->getUsername();
            $data_chart2[] = $post["total"];
        }


        $chart2 = $this->chartBuilder->createChart(Chart::TYPE_PIE);
        $chart2->setData([
            'labels' => $labels_chart2,
            'datasets' => [
                [
                    'backgroundColor' => ['#3B6007', '#000','#154EAF', '#10A625','#15000F', '#12E625','#AEF0B1'],
                    'data' => $data_chart2,
                ],
            ],
        ]);

        $chart->setOptions([ /* ... */]);
        
        
        
        return $this->render('bundles/EasyAdminBundle/welcome.html.twig',[
            'test'=>'string de test',
            'chart' => $chart,
            'chart2' => $chart2,
            'nombreUsers' => $this->userRepository->findAllUsers(),
            'posts' =>$this->postRepository->findAll(),
            'postsByUser' =>$this->userRepository->findPostsByUsers()
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('MonApplication')
            ->renderContentMaximized(true);
            ;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Tableau de bord', 'fa fa-home');
        ;
        yield MenuItem::section('Important');
        yield MenuItem::subMenu('Mise à jour', 'fa fa-article')->setSubItems([
            MenuItem::linkToCrud('Categories', 'fa fa-tags', Category::class),
            MenuItem::linkToCrud('Posts', 'fa fa-file-text', Post::class),
            MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class),
        ]);
    }

    // Configuration des biblithèques et autres fichiers externes
    public function configureAssets(): Assets
    {
        return Assets::new ()
            ->addCssFile('/dist/css/adminlte_perso.css')
            ->addJsFile('build/runtime.0b137493.js')
            ->addJsFile('build/985.871ece4d.js')            
            ->addJsFile('build/app.b4905a31.js')
            ->addJsFile('dist/js/adminlte.min.js')
            ->addJsFile('vendor/jquery/jquery.min.js')
            ->addJsFile('vendor/bootstrap/js/bootstrap.bundle.min.js')
            

        ;
    }

    

    public function __construct(
        ChartBuilderInterface $chartBuilder,
        UserRepository $userRepository,
        PostRepository $postRepository,
        CategoryRepository $categoryRepository
    )

    {
        $this->chartBuilder = $chartBuilder;
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
        $this->categoryRepository = $categoryRepository;
    }
}
