<?php

namespace Wanup\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('WanupCmsBundle:Default:index.html.twig');
    }
}
