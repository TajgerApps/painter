<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CatalogController extends AbstractController
{
    const dataDirectory = 'data';

    public function index(Request $request): Response
    {
        $path = $request->query->get('path', null);
        $finder = new Finder();
        $finder->in(self::dataDirectory . DIRECTORY_SEPARATOR . $path);
        return $this->render('catalog/index.html.twig', [
            'finder' => $finder,
        ]);
    }

    public function addDirectory(Request $request): JsonResponse
    {
        $path = $request->query->get('path', null);
        if (is_null($path)) {
            return new JsonResponse('Wrong data');
        }
        $filesystem = new Filesystem();
        if (!$filesystem->exists(self::dataDirectory . DIRECTORY_SEPARATOR . $path)) {
            $filesystem->mkdir(self::dataDirectory . DIRECTORY_SEPARATOR . $path);
            return new JsonResponse('ok');
        }
        return new Response('Directory exists');
    }
}
