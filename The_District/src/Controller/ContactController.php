<?php

namespace App\Controller;

use App\Form\ContactFormType;
use App\Entity\Contact;
use App\Service\MailService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactFormType::class);

        // / Traitement des données soumises au formulaire
        $form->handleRequest($request);

        // je recupere les données transmises si le form est valide et envoyé
        if ($form->isSubmitted() && $form->isValid()) {

            // Récupération de l'entité Contact associée au formulaire
            $contact = $form->getData();

            // Récupération des propriétés de l'entité Contact
            $address = $contact->getEmail();
            $subject = $contact->getObjet();
            $content = $contact->getMessage();
            // $content = 'objet : ' . $contact->getObjet() . "\n";
            // $content .= 'message : ' . $contact->getMessage();
            // dd($contact);

            $email = (new Email())
                ->from($address)
                ->to('admin@admin.com')
                ->subject('demande de contact')
                ->text($content);

            // dd($email);
            $mailer->send($email);

            return $this->redirectToRoute('app_accueil');
        }
        return $this->render('contact/index.html.twig', [
            'form' => $form
        ]);
    }
}
