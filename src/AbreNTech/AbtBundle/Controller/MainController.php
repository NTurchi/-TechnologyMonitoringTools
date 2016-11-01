<?php

namespace AbreNTech\AbtBundle\Controller;

use Proxies\__CG__\AbreNTech\AbtBundle\Entity\Category;
use Proxies\__CG__\AbreNTech\AbtBundle\Entity\Type;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AbreNTech\AbtBundle\Entity\Link;
use AbreNTech\AbtBundle\Form\Type\LinkType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MainController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        if (!$categories)
        {
            return $this->createNotFoundException('Categories non trouvées');
        }

        return $this->render('@Abt/Main/index.html.twig', array(
            'categories' => $categories,
        ));
    }

    /**
     * @Route("/link/manage/{type}/{category}", name="manage", defaults={"type" = 0, "category" = 0}, requirements={
     *    "type": "\d+",
     *    "category": "\d+"
     * })
     *
     */
    public function manageLinkAction(Request $request, $type, $category)
    {
        $repo = $this->getDoctrine()->getRepository(Link::class);
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $title = "Administration : Liens";
        if ($type > 3)
        {
            return $this->redirectToRoute('homepage');
        } else if ($type === 0) {
            $links = $repo->findAllWithRelation();
        } else if ($type <= 2) {
            $links = $repo->findAllWithRelationByType($type);
            $title = $this->getDoctrine()->getRepository(Type::class)->find($type)->getName();
        } else {
            if ($category > count($categories))
            {
                return $this->createNotFoundException('Categorie inexistante');
            }
            $links = $repo->findAllWithRelationByCategory($category);
            $title = $this->getDoctrine()->getRepository(Category::class)->find($category)->getName();
        }

        return $this->render('@Abt/Main/manage.html.twig', array(
            'links' => $links,
            'categories' => $categories,
            'title' => $title,
        ));
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     *
     * @Route("/category/manage/{id}", name="manageCategory", defaults={"id" = 0}, requirements={
     *    "id": "\d+",
     * })
     */
    public function manageCategoryAction(Request $request, $id)
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $title = "Administration : Catégories";

        return $this->render('@Abt/Main/manageCategory.html.twig', array(
            'title' => $title,
            'categories' => $categories,
        ));
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @Route("/delete/{type}/{id}", name="delete", requirements={
     *    "type": "\d+",
     *    "id": "\d+"
     * })
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $id, $type)
    {
        $em = $this->getDoctrine()->getManager();
        if ($type == 1)
        {
            $objecttodelete = $this->getDoctrine()->getRepository(Link::class)->find($id);
        } else {
            $objecttodelete = $this->getDoctrine()->getRepository(Category::class)->find($id);
        }
        if (!$objecttodelete) {
            $request->getSession()->getFlashBag()->add('warning', 'Suppression impossible');
            return $this->redirectToRoute('manage');
        }
        $em->remove($objecttodelete);
        $em->flush();
        $request->getSession()->getFlashBag()->add('warning', 'Suppression effectuée');
        if ($type == 1)
        {
            return $this->redirectToRoute('manage');
        } else {
            return $this->redirectToRoute('manageCategory');
        }
    }
}
