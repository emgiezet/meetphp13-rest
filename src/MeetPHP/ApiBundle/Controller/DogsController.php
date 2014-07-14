<?php
/**
 * Created by PhpStorm.
 * User: mgz
 * Date: 7/12/14
 * Time: 3:06 PM
 */

namespace MeetPHP\ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\SecurityExtraBundle\Annotation\Secure;



class DogsController  extends FOSRestController {
    /**
     * Get all Events in calendar
     *
     * @param string $applicaionSlug slug identifier of application
     * @param string $calendarSlug   slug identifier of calendar
     *
     * @return View
     * @ApiDoc(
     * description="Get all Events in calendar",
     * statusCodes={
     *     200="OK",
     *     404="Not Found",
     *     403="Forbidden"
     * },
     *  output="MeetPHP\CoreBundle\Entity\Dog"
     * )
     */
    public function getDogsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $data = $em->getRepository('MeetPHPCoreBundle:Dog')
            ->findAll();

        $view = $this->view($data, 200)
            ->setTemplate("MeetPHPApiBundle:Api:many.html.twig")
            ->setTemplateVar('entities');

        return $this->handleView($view);
    }

} 