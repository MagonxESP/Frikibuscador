<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\File;

class ContentController extends AbstractController {

    public function content(string $filename) {
        $file = $this->getDoctrine()
            ->getRepository(File::class)
            ->findOneBy(['name' => urldecode($filename)]);

        if ($file) {
            $html = file_get_contents($file->getPath());
            return new Response($html);
        } else {
            return new Response(null, 404);
        }
    }

}