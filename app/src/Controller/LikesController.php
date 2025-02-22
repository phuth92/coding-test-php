<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;
/**
 * Likes Controller
 *
 * @property \App\Model\Table\LikesTable $Likes
 * @method \App\Model\Entity\Like[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LikesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users', 'Articles'],
        ];
        $likes = $this->paginate($this->Likes);

        $this->set(compact('likes'));
    }

    /**
     * View method
     *
     * @param string|null $id Like id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $like = $this->Likes->get($id, [
            'contain' => ['Users', 'Articles'],
        ]);

        $this->set(compact('like'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $like = $this->Likes->newEmptyEntity();
        if ($this->request->is('post')) {
            $like = $this->Likes->patchEntity($like, $this->request->getData());
            if ($this->Likes->save($like)) {
                $this->Flash->success(__('The like has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The like could not be saved. Please, try again.'));
        }
        $users = $this->Likes->Users->find('list', ['limit' => 200])->all();
        $articles = $this->Likes->Articles->find('list', ['limit' => 200])->all();
        $this->set(compact('like', 'users', 'articles'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Like id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $like = $this->Likes->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $like = $this->Likes->patchEntity($like, $this->request->getData());
            if ($this->Likes->save($like)) {
                $this->Flash->success(__('The like has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The like could not be saved. Please, try again.'));
        }
        $users = $this->Likes->Users->find('list', ['limit' => 200])->all();
        $articles = $this->Likes->Articles->find('list', ['limit' => 200])->all();
        $this->set(compact('like', 'users', 'articles'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Like id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $like = $this->Likes->get($id);
        if ($this->Likes->delete($like)) {
            $this->Flash->success(__('The like has been deleted.'));
        } else {
            $this->Flash->error(__('The like could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function like($articleId)
    {
        $this->request->allowMethod(['post']);
        $likesTable = TableRegistry::getTableLocator()->get('Likes');
        $this->Authorization->authorize($likesTable);
        $userId = $this->Authentication->getIdentity()->get('id');

        $like = $this->Likes->newEmptyEntity();
        $like = $this->Likes->patchEntity($like,[
            'user_id' => $userId,
            'article_id' => $articleId
        ]);

        if ($this->Likes->exists(['user_id' => $userId, 'article_id' => $articleId])) {
            $message = 'You have already liked this article.';
            $status = false;
        } else if ($this->Likes->save($like)) {
            $message = 'Article liked successfully.';
            $status = true;
        } else {
            $message = 'Unable to like the article. Please try again.';
            $status = false;
        }

        $this->set([
            'status' => $status,
            'message' => $message,
            '_serialize' => ['status', 'message'],
        ]);
    }
}
