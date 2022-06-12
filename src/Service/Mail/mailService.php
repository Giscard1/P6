<?php


namespace App\Service\Mail;



use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class mailService extends AbstractController
{
    private $mailer;
    private $urlGenerator;
    /**
     * mailService constructor.
     * @param $mailer
     */
    public function __construct(MailerInterface $mailer, UrlGeneratorInterface $urlGenerator)
    {
        $this->mailer = $mailer;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param User $user
     */
    public function sendEmail(User $user)
    {
        $userEmail = $user->getEmail();
        $token = $user->getToken();

        $email = (new TemplatedEmail())
            ->from('giscard.projets@gmail.com')
            ->to(new Address($userEmail))
            ->subject('Mot de passe oublié')

            //path of the twig template
            ->htmlTemplate('Email/mail_mot_de_passe_oubliee.html.twig')
            ->context([
                'token' => $token,
                'url' => $this->urlGenerator->generate('newPasword', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL)
            ]);

        $this->mailer->send($email, null);
    }
}
