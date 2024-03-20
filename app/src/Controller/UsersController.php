<?php
declare(strict_types=1);

namespace App\Controller;

use Firebase\JWT\JWT;
use Cake\Utility\Security;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Flash');

        // Add this line to check authentication result and lock your site
        $this->loadComponent('Authentication.Authentication');
    }

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->loadComponent('Flash');
        // Configure the login action to not require authentication, preventing
        // the infinite redirect loop issue
        $this->Authentication->addUnauthenticatedActions(['login','logout']);
    }

    public function login()
    {
        $this->request->allowMethod([ 'post']);
        $this->Authorization->skipAuthorization();
        $result = $this->Authentication->getResult();
        // regardless of POST or GET, redirect if user is logged in
        if ($result && $result->isValid()) {
            $this->request->getSession()->renew();
            $user = $result->getData();
            $key = Security::getSalt();
            $payload = [
                'sub' => $user->id,
                'user_id' => $user->id,
                'exp' => time(),
            ];

            $token = JWT::encode($payload, $key, 'HS256');
            
            $data = [
                'status' => 'successed',
                'data' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'token' => $token,
                ],
            ];
        } else {
            $data = ['status' => 'failed', 'message' => 'Invalid credentials'];
        }
        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($data))
            ->withDisabledCache();;
    }

    public function logout()
    {
        $this->request->allowMethod([ 'post']);
        $this->Authorization->skipAuthorization();
        // var_dump($this->Authentication->logout());die();
        $user = $this->Authentication->getIdentity();
        if ($user) {
            $this->Authentication->logout();
            $data = ['status' => 'success', 'message' => 'Logout successfully'];
        } else {
            $data = ['status' => 'error', 'message' => 'Unable to logout'];
        }

        $this->response = $this->response->withType('application/json');
        $this->set(compact('data'));
        $this->viewBuilder()->setOption('serialize', 'data');
        $this->viewBuilder()->disableAutoLayout();
    }
}
