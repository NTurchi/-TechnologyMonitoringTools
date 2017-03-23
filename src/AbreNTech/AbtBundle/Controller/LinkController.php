<?php

namespace AbreNTech\AbtBundle\Controller;

use AbreNTech\AbtBundle\Form\Type\LinkType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AbreNTech\AbtBundle\Entity\Link;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LinkController extends Controller
{
    /**
     * @Route("/show/link/{id}/{page}", defaults={"page" = 1}, name="showlink", requirements={
     *    "id": "\d+"
     * })
     */
    public function showAction(Request $request, $id, $page)
    {
        $link = $this->getDoctrine()->getRepository(Link::class)->findOneWithRelation($id);

        if (!$link) {
            return $this->createNotFoundException("Erreur, le lien n'existe pas !");
        }

        if ($link->getType()->getName() == 'URL') {
            return $this->redirect($link->getLinkstr());
        }
        try {
            $rss = simplexml_load_file($link->getLinkstr());
        } catch(\Exception $e) {
            return new Response("<html><body>Le lien XML est invalide, veuillez le modifier !<p><a href='/'>Retour</a></p></body></html>");
        }
        $itemsRss = $rss->channel->item;
        $totalitems = count($itemsRss);
        $nbrpage = (int)($totalitems / 5);
        $items = array();

        if ($page > $nbrpage)
        {
            return $this->createNotFoundException('La page demandé est inexistante !');
        }

        $posts = array();
        foreach ($itemsRss as $post)
        {
            $posts[] = $post;
        }

        for ($i = ($page-1) * 5; $i < ((($page-1) * 5) + 5); $i++)
        {
            $items[] = $posts[$i];
        }



        return $this->render('@Abt/Link/showXml.html.twig', array(
            'link' => $link,
            'nbrpage' => $nbrpage,
            'items' => $items,
        ));
    }

    /**
     * @Route("/link/add", name="addLink")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $newlink = new Link();
        $newlink->setName(' ');
        $newlink->setDescription(' ');
        $form = $this->createForm(LinkType::class, $newlink);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted())
        {
            $em = $this->getDoctrine()->getManager();
            if ($newlink->getType()->getName() == "XML")
            {
                $rss = simplexml_load_file($newlink->getLinkstr());
                $newlink->setName($rss->channel->title);
                $newlink->setDescription($rss->channel->description);
            }
            $em->persist($newlink);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Ajout effectué !');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('@Abt/Link/add.html.twig', array(
            'form' => $form->createView(),
            'link' => $newlink,
        ));
    }

    /**
     * @Route("/link/modify/{id}", name="modifyLink")
     */
    public function modifyAction(Request $request, $id)
    {
        $link = $this->getDoctrine()->getRepository(Link::class)->findOneWithRelation($id);


        $form = $this->createForm(LinkType::class, $link);

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted())
        {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Modification effectuée !');

            return $this->redirectToRoute('manage');
        }

        return $this->render('@Abt/Link/add.html.twig', array(
            'form' => $form->createView(),
        ));

    }


}