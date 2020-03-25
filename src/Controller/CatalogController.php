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
        $path = $request->attributes->get('_route_params')['path']??'';
        $finder = new Finder();
        $finder->in(self::dataDirectory . DIRECTORY_SEPARATOR . $path);
        $dirData = [];
        foreach ($finder as $file) {
            $dirData[] = [
                'name' => $file->getFilename(),
                'encodedPath' => rawurlencode($file->getRelativePath()),
            ];
        }
        return $this->render('catalog/index.html.twig', [
            'directoryData' => $dirData,
            'path' => $path,
        ]);

    }

    public function addDirectory(Request $request): JsonResponse
    {
        $dirname = $request->request->get('dirname', null);
        $currentPath = $request->request->get('currentPath', '');
        if (is_null($dirname)) {
            return new JsonResponse('Wrong data');
        }
        $filesystem = new Filesystem();
        if (!$filesystem->exists(self::dataDirectory . DIRECTORY_SEPARATOR . $currentPath . DIRECTORY_SEPARATOR . $dirname)) {
            $filesystem->mkdir(self::dataDirectory . DIRECTORY_SEPARATOR . $currentPath . DIRECTORY_SEPARATOR . $dirname);
            return new JsonResponse('Ok');
        }
        return new JsonResponse('Directory exists');
    }
}
