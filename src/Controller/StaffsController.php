<?php

// 1. было бы лучше создать сервисы, которые бы обращались к ресурсам. Их использовать в контроллерах. В Симфони чуть как-то по другому видимо делается (сущность, репозитарий).
// 2. в своих других проектамх я выделяю отдельно роутинг и сервисы, так же модели (сущности). И между ними идет коммуникация.

namespace App\Controller;

use App\Entity\Staff;
use App\Repository\StaffRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class StaffsController extends AbstractController
{
    public function index()
    {
        return new Response(
            '<html><body>index</body></html>'
        );
    }

    public function getAll(Request $request, StaffRepository $staffRepository)
    {
        $filter = $request->query->get('filter');

        if ($filter) {
            $filter = trim($filter);
        }

        if ($filter) {
            $staffs = $staffRepository->createQueryBuilder('s')
                ->where('s.name LIKE :name')
                ->setParameter('name', '%' . $filter . '%')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult();
        } else {
            $staffs = $staffRepository->findAll();
        }

        return $this->json($staffs);
    }

    public function getOne($id, StaffRepository $staffRepository)
    {
        $staff = $staffRepository->find($id);
        return $this->json($staff);
    }

    public function create(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $name = $request->request->get('name');
        $name = trim($name);

        if (!$name) {
            throw new BadRequestHttpException('empty name');
        }

        $staff = new Staff();
        $staff->setName($name);

        $entityManager->persist($staff);
        $entityManager->flush();

        return $this->json($staff);
    }

    public function update($id, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $name = $request->request->get('name');
        $name = trim($name);

        if (!$name) {
            throw new BadRequestHttpException('empty name');
        }

        $stuff = $entityManager->getRepository(Staff::class)->find($id);
        if (!$stuff) {
            throw $this->createNotFoundException('No stuff found for id ' . $id);
        }

        $stuff->setName($name);
        $entityManager->flush();

        return $this->json($stuff);
    }

    public function delete($id, StaffRepository $staffRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $stuff = $entityManager->getRepository(Staff::class)->find($id);

        if (!$stuff) {
            throw $this->createNotFoundException('No stuff found for id ' . $id);
        }

        $entityManager->remove($stuff);
        $entityManager->flush();

        return $this->json("", 204);
    }
}