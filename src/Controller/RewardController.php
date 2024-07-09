<?php

// src/Controller/RewardController.php
namespace App\Controller;

use App\Entity\Reward;
use App\Form\RewardType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class RewardController extends AbstractController
{

    #[Route('/rewards/new', name: 'reward_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reward = new Reward();
        $form = $this->createForm(RewardType::class, $reward);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reward);
            $entityManager->flush();

            return $this->redirectToRoute('reward_index');
        }

        return $this->render('reward/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/rewards', name: 'reward_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $rewards = $entityManager->getRepository(Reward::class)->findAll();

        return $this->render('reward/index.html.twig', [
            'rewards' => $rewards,
        ]);
    }

    #[Route('/rewards/{id}/edit', name: 'reward_edit')]
    public function edit(Request $request, Reward $reward, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RewardType::class, $reward);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('reward_index');
        }

        return $this->render('reward/edit.html.twig', [
            'form' => $form->createView(),
            'reward' => $reward,
        ]);
    }

    #[Route('/rewards/{id}/delete', name: 'reward_delete', methods: ['POST'])]
    public function delete(Request $request, Reward $reward, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reward->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reward);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reward_index');
    }
}
