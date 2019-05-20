<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\ContactService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ContactController
 */
class ContactController extends AbstractController
{
    /**
     * @var ContactService
     */
    private $contactService;

    public function __construct(
        ContactService $contactService
    ) {
        $this->contactService = $contactService;
    }

    /**
     * @Route("/", name="contact")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function index(Request $request): Response
    {
        $result = $this->contactService->getContacts($request);

        return $this->render(
            'contact/index.html.twig',
            [
                'pagination' => $result,
            ]
        );
    }
}
