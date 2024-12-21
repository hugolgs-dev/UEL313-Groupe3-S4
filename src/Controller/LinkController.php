<?php

namespace App\Controller;

use App\Entity\Link;
use App\Form\LinkType;
use App\Repository\LinkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\Tools\Pagination\Paginator;

#[Route('/link')]
final class LinkController extends AbstractController{
    #[Route(name: 'app_link_index', methods: ['GET'])]
    public function index(Request $request, LinkRepository $linkRepository): Response 
    {
        $page = $request->query->getInt('page', 1);
        $limit = 10;
        
        $query = $linkRepository->createQueryBuilder('l')
            ->orderBy('l.id', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();
            
        $paginator = new Paginator($query);
        $totalItems = count($paginator);
        $totalPages = max(ceil($totalItems / $limit), 1);

        if ($page > $totalPages) {
            return $this->redirectToRoute('app_link_index', ['page' => 1]);
        }

        return $this->render('link/index.html.twig', [
            'links' => $paginator,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'limit' => $limit,
        ]);
    }

    #[Route('/new', name: 'app_link_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $link = new Link();
    $form = $this->createForm(LinkType::class, $link);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($link);
        $entityManager->flush();

        return $this->redirectToRoute('app_link_index');
    }

    return $this->render('link/new.html.twig', [
        'link' => $link,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_link_show', methods: ['GET'])]
    public function show(Link $link): Response
    {
        return $this->render('link/show.html.twig', [
            'link' => $link,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_link_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Link $link, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LinkType::class, $link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_link_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('link/edit.html.twig', [
            'link' => $link,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_link_delete', methods: ['POST'])]
    public function delete(Request $request, Link $link, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$link->getId(), $request->request->get('_token'))) {
            try {
                $title = $link->getTitle();
                $entityManager->remove($link);
                $entityManager->flush();
                $this->addFlash('success', sprintf('Le lien "%s" a été supprimé avec succès.', $title));
            } catch (\Exception $e) {
                $this->addFlash('error', sprintf('Une erreur est survenue lors de la suppression du lien "%s".', $link->getTitle()));
            }
        } else {
            $this->addFlash('error', 'Action non autorisée.');
        }
        
        return $this->redirectToRoute('app_link_index', [], Response::HTTP_SEE_OTHER);
    }
}
