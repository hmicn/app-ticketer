<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class HomeController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getMysqlVersion(): string
    {
        $connection = $this->em->getConnection();
        $version = $connection->fetchOne('SELECT VERSION()');
    
        return $version;
    }

    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        $version = $this->getMysqlVersion();
        if (!str_starts_with($version, '9.1')) {
            throw new \Exception('MySQL version is not 9.1');
        }

        $version = phpversion();
        if (!str_starts_with($version, '8.3')) {
            throw new \Exception('PHP version is not 8.3');
        }
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
