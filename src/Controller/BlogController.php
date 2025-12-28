<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

final class BlogController extends AbstractController
{
    #[Route('/blog', name: 'app_blog', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $format = $request->request->get('format');
            $nom = $request->request->get('nom');

            if (!empty($nom)) {
                $cvPath = $this->getParameter('kernel.project_dir') . '/public/cv/';
                $fileName = ($format === 'docx') ? 'CV_ABIDERRAHMANE_ADAM.docx' : 'CV_ABIDERRAHMANE_ADAM.pdf';
                $filePath = $cvPath . $fileName;

                // Vérifie si le fichier existe au sein du dossier CV
                if (!file_exists($filePath)) {
                    return new Response("Erreur : Le fichier est introuvable dans " . $cvPath, 404);
                }

                // Réponse avec fichier
                $response = new BinaryFileResponse($filePath);

                // Force le type de fichier donc docx et pdf
                $mimeType = ($format === 'docx') 
                    ? 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' 
                    : 'application/pdf';
                
                $response->headers->set('Content-Type', $mimeType);

                // Force le téléchargement (parce que j'arrivais pas à faire en sorte que je puisse télécharger mon document donc j'ai forcé volontairement le téléchargement)
                $response->setContentDisposition(
                    ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                    $fileName
                );

                return $response;
            }
        }

        return $this->render('blog/index.html.twig');
    }
}