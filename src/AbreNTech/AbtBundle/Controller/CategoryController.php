<?php

namespace AbreNTech\AbtBundle\Controller;

use AbreNTech\AbtBundle\Form\Type\CategoryType;
use Proxies\__CG__\AbreNTech\AbtBundle\Entity\Category;
use AbreNTech\AbtBundle\Entity\Link;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    /**
     * @param Request $request
     * @param $id integer
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/show/category/{id}", name="showcategory", defaults={"id" = 0}, requirements={
     *    "id": "\d+",
     * })
     */
    public function showAction(Request $request, $id)
    {

        // récupération de la categorie

        $repoC = $this->getDoctrine()->getRepository(Category::class);
        $repoL = $this->getDoctrine()->getRepository(Link::class);

        $category = $repoC->find($id);

        if (!$category)
        {
            return $this->redirectToRoute('homepage');
        }

        // tri des différents liens de la catégorie

        $links = $repoL->findAllWithRelationByCategory($category->getId());

        $urlLink = array();
        $xmlLink = array();

        foreach ($links as $link)
        {
            if ($link->getType()->getName() == 'XML')
            {
                $xmlLink[] = $link;
            } else {
                $urlLink[] = $link;
            }
        }

        // check the last posts of rss flux

        $allitems = array();
        $recentItems = array();

        for ($i = 0; $i < count($xmlLink); $i++)
        {
            try {
                $rss = simplexml_load_file($xmlLink[$i]->getLinkstr());
                foreach ($rss->channel->item as $item)
                {
                    $allitems[strtotime($item->pubDate)] = $item;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        krsort($allitems);
        $recentItems = array_slice($allitems, 0, 5);


        return $this->render('@Abt/Category/show.html.twig', array(
            'category' => $category,
            'urlLink' => $urlLink,
            'xmlLink' => $xmlLink,
            'recentPosts' => $recentItems,
        ));
    }

    /**
     * @Route("/category/modify/{id}", name="modifyCategory")
     */
    public function modifyAction(Request $request, $id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);


        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted())
        {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Modification effectuée !');

            return $this->redirectToRoute('manageCategory');
        }

        return $this->render('@Abt/Link/add.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * @Route("/category/add", name="addCategory")
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(CategoryType::class);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted())
        {
            $em = $this->getDoctrine()->getManager();
            $newcategory = $form->getData();

            $em->persist($newcategory);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Ajout effectué !');

            return $this->redirectToRoute('manageCategory');
        }

        return $this->render('@Abt/Category/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}