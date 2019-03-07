<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class UserController extends AbstractController
{
    /**
     * @Route("/api/hello", name="hello")
     * @IsGranted("ROLE_AUTH0_AUTHENTICATED")
     */
    public function hello()
    {
        return $this->json([ 'message' => 'Hello World!' ]);
    }

}
