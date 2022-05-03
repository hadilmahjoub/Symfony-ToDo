<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{
//    #[Route('/todos', name: 'todos')]

    /**
     * @Route("/",name="todo")
     */
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        // Afficher notre tableau de todo
        // si pas de tableau de todo dans ma session => initialiser puis affichage
        if (!$session->has('todos')){
            $todos = [
                'achat'=>'acheter clé usb',
                'cours'=>'Finaliser mon cours',
                'correction'=>'corriger mes examens'
            ];
            $session->set('todos', $todos);
            $this->addFlash('info', "La liste des todos viens d'etres initialisée");
        }
        // si non => afficher
        return $this->render('todo/index.html.twig');
    }

    #[Route('/add/{name?todo}/{content?study}',
        // {var?defaultValue}
        // {var<requirement>}
        name: 'todo.add',
    )]
    public function addTodo(Request $request, $name, $content): RedirectResponse{
        $session = $request->getSession();
        // verifier si j ai mon tableau de todos
        if ($session->has('todos')){
            // si oui
            // verifier si on a deja un todo avec le meme name
            $todos = $session->get('todos');
            if (isset($todos[$name])){
                // si oui => afficher erreur
                $this->addFlash('error', "Le todo d'id $name exite deja dans la liste");
            } else {
                // si non => ajouter et afficher un message de succes
                $todos[$name] = $content;
                $session->set('todos', $todos);
                $this->addFlash('success', "Le todo d'id $name a ete ajouté avec succes");
            }
        } else {
            // si non => afficher une erreur et rediriger vers le controlleur index
            $this->addFlash('error', "La liste des todos n'est pas encore initialisée");
        }
        return $this->redirectToRoute('todo');
    }

    #[Route('/update/{name}/{content}', name: 'todo.update')]
    public function updateTodo(Request $request, $name, $content): RedirectResponse{
        $session = $request->getSession();
        // verifier si j ai mon tableau de todos
        if ($session->has('todos')){
            // si oui
            // verifier si on a deja un todo avec le meme name
            $todos = $session->get('todos');
            if (!isset($todos[$name])){
                // si non => afficher erreur
                $this->addFlash('error', "Le todo d'id $name n'exite pas dans la liste");
            } else {
                // si oui => ajouter et afficher un message de succes
                $todos[$name] = $content;
                $session->set('todos', $todos);
                $this->addFlash('success', "Le todo d'id $name a ete modifié avec succes");
            }
        } else {
            // si non => afficher une erreur et rediriger vers le controlleur index
            $this->addFlash('error', "La liste des todos n'est pas encore initialisée");
        }
        return $this->redirectToRoute('todo');
    }

    #[Route('/delete/{name}', name: 'todo.delete')]
    public function deleteTodo(Request $request, $name): RedirectResponse{
        $session = $request->getSession();
        // verifier si j ai mon tableau de todos
        if ($session->has('todos')){
            // si oui
            // verifier si on a deja un todo avec le meme name
            $todos = $session->get('todos');
            if (!isset($todos[$name])){
                // si oui => afficher erreur
                $this->addFlash('error', "Le todo d'id $name n'exite pas dans la liste");
            } else {
                // si non => supprimer et afficher un message de succes
                unset($todos[$name]);
                $session->set('todos', $todos);
                $this->addFlash('success', "Le todo d'id $name a ete supprimé avec succes");
            }
        } else {
            // si non => afficher une erreur et rediriger vers le controlleur index
            $this->addFlash('error', "La liste des todos n'est pas encore initialisée");
        }
        return $this->redirectToRoute('todo');
    }

    #[Route('/reset', name: 'todo.reset')]
    public function resetTodo(Request $request): RedirectResponse{
        $session = $request->getSession();
        $session->remove('todos');
        return $this->redirectToRoute('todo');
    }
}
