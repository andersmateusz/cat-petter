<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\CatPicture;
use App\Entity\Kittie;
use App\Form\KittieType;
use Doctrine\ORM\EntityManagerInterface;
use Stof\DoctrineExtensionsBundle\Uploadable\UploadableManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Turbo\TurboBundle;

#[Route('/kittie')]
class KittieController extends AbstractController
{
    #[Route('/new', name: 'app_kittie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, UploadableManager $uploadableManager, HubInterface $hub, #[MapQueryParameter] bool $asStream = false): Response
    {
        $form = $this->createForm(KittieType::class, $kittie = new Kittie());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $kittie->setCatPicture($catPicture = new CatPicture());
            $em->persist($kittie);
            $uploadableManager->markEntityToUpload($catPicture, $form->get('catPicture')->getData());
            $em->flush();
            $hub->publish(new Update('https://example.com/notifications', $this->renderView('kittie/created_notification.stream.html.twig', ['cat' => $kittie])));
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return $this->render('kittie/create_success.stream.html.twig', ['cat' => $kittie]);
        }

        $asStream && $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

        return $this->render('kittie/new.html.twig', [
            'kittie' => $kittie,
            'form' => $form,
            'asStream' => $asStream,
        ]);
    }

    #[Route('/picture/{id}', 'app_kittie_picture', methods: ['GET'])]
    public function downloadCatPicture(CatPicture $catPicture): Response
    {
        if ($fp = \fopen($catPicture->getPath(), 'r')) {
            return new StreamedResponse(static function () use ($fp): void {
                while (!\feof($fp)) {
                    echo \fgets($fp);
                    \flush();
                }
                \fclose($fp);
            }, headers: ['Content-Type' => $catPicture->getMimeType()]);
        }
        return new Response();
    }
}
