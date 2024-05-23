<?php

namespace App\Command;

use App\Repository\ChiffreAffairesRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SendReminderEmailsCommand extends Command
{
    private $mailer;
    private $caRepository;

    public function __construct(MailerInterface $mailer, ChiffreAffairesRepository $caRepository)
    {
        parent::__construct();

        // Injection des dépendances (service de messagerie et repository)
        $this->mailer = $mailer;
        $this->caRepository = $caRepository;
    }

    protected function configure()
    {
        // Configuration de la commande
        $this
            ->setName('app:send-reminder-emails')
            ->setDescription('Send reminder emails to users who have not submitted their turnover for the previous month.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Récupération de la date correspondant au premier jour du mois précédent
        $date = new \DateTime();
        $date->modify('first day of last month');

        // Recherche des utilisateurs n'ayant pas saisi de chiffre d'affaires pour le mois précédent
        $usersWithoutCA = $this->caRepository->findUsersWithoutCAForMonth($date);

        // Envoi d'un email de rappel à chaque utilisateur concerné
        foreach ($usersWithoutCA as $user) {
            $email = (new Email())
                ->from('elisdewall@gmail.com')
                ->to($user->getEmail())
                ->subject('Rappel : Saisie du chiffre d\'affaires du mois passé')
                ->text('Cher '.$user->getUsername().', vous n\'avez pas encore saisi votre chiffre d\'affaires pour le mois précédent. Merci de le faire dès que possible.');

            $this->mailer->send($email);
        }

        // Affichage d'un message de succès dans la console
        $output->writeln('Reminder emails sent successfully.');

        // Retour de la constante indiquant que la commande a été exécutée avec succès
        return Command::SUCCESS;
    }
}
