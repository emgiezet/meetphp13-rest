<?php
namespace MeetPHP\ApiBundle\Controller;

use MeetPHP\ApiBundle\Form\ResetPasswordType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use MeetPHP\CoreBundle\Entity\User;

/** User password
*/
class UserController extends FOSRestController
{
    /**
     * Request of password reset. It sends an email with new generated password
     *
     *
     * @return View
     *
     * @ApiDoc(
     * description="Request of password reset",
     * statusCodes={
     *     200="OK",
     *     400="Validation Error",
     *     404="Not Found"
     * },
     * input="MeetPHP\ApiBundle\Form\ResetPasswordType"
     *
     * )
     *
     */
    public function putUserAction()
    {
        $request = $this->getRequest();

        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');

        $form = $this->createForm(new ResetPasswordType(), null, array('method' => 'PUT'));
        $data = null;

        if ('PUT' === $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                $data = $form->getData();

                $user = $userManager->findUserByEmail($data['email']);
                if (empty($user)) {
                    $statusCode = 404;
                    $view = $this->view(null, $statusCode);

                    return $this->handleView($view);
                }
                $newPassword = md5(uniqid());

                $user->setPlainPassword($newPassword);

                try {
                    //$this->sendEmailWithPassword($user, $newPassword);
                    $userManager->updateUser($user);
                    $data = array('status'=>'ok');
                    $statusCode = 200;
                } catch (Swift_TransportException $e) {
                    $data = array('error'=>'email not sent.');
                    $statusCode = 500;
                }
            } else {
                $statusCode = 400;
            }
        } else {
            $statusCode = 400;
        }
        $view = $this->view($data, $statusCode);

        return $this->handleView($view);
    }

}
