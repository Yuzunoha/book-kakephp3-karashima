<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function isAuthorized($user)
    {
        if ($this->request->getParam('action') === 'index') {
            /* indexアクションである */
            if (isset($user['role']) && $user['role'] === 'admin') {
                /* roleがadminである */
                return true;
            }
            /* roleがadminでない */
            return false;
        }
        /* indexアクションでない */
        return true;
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        // loginは許可に追加しないこと
        $this->Auth->allow(['add', 'logout']);
    }

    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);

                $redirectUrl = $this->Auth->redirectUrl();
                return $this->redirect($redirectUrl);
            }
            $this->Flash->error('ユーザ名かパスワードが違います');
        }
    }

    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Messages'],
        ]);
        $cate_assoc = ConnectionManager::get('default')
            ->execute('select * from categories')
            ->fetchAll('assoc');
        $cate_list = array_column($cate_assoc, 'name', 'id');
        $this->set(compact('user', 'cate_list'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        $user = $this->Users->patchEntity($user, $this->request->getData());
        $user->status = 1;
        $user->role = 'user';
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('登録しました。ユーザ名とパスワードでログインしてください'));
                return $this->redirect(['action' => 'login']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit()
    {
        $id = $this->Auth->user('id');
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                // redirect: http://localhost:8765/users/view/5
                return $this->redirect(['action' => 'view', $id]);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete()
    {
        $id = $this->Auth->user('id');
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->newEntity();
        $user->id = $id;
        $user->status = 2;
        if ($this->Users->save($user)) {
            $this->Flash->success(__('The user has been deleted.'));
            return $this->redirect(['action' => 'logout']);
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }
    }
}
