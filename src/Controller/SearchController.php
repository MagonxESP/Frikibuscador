<?php


namespace App\Controller;


use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\File;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends AbstractController {

    public function searchForm(Request $request) {
        $search_file = new File();

        $form = $this->createForm(SearchType::class, $search_file, [
            'action' => '/buscar'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search_file = $form->getData();

            return $this->redirectToRoute('search', [
                'keywords' => $search_file->getTitle()
            ]);
        }

        return $this->render('search/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function search(string $keywords) {
        $files = $this->getDoctrine()
            ->getRepository(File::class)
            ->findLikeTitle($keywords);

        return $this->render('search/results.html.twig', [
            'results' => $files
        ]);
    }

}