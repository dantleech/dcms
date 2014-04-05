<?php

namespace Dcms\Bundle\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MentalController extends Controller
{
    public function pageAction(Request $request)
    {
        $mental = $request->get('_mental');

        return $this->render('DcmsPageBundle:Mental:page.html.twig', array(
            'mental' => $mental,
        ));
    }
}
