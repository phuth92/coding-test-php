<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Http\Exception\UnauthorizedException;
use \Cake\Datasource\Exception\RecordNotFoundException;
use \Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;

/**
 * Articles Controller
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 * @property \App\Model\Table\LikesTable $Likes
 * @method \App\Model\Entity\Article[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ArticlesController extends AppController
{
    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $articles = $this->paginate($this->Articles);
        $this->set([
            'articles' => $articles,
            '_serialize' => ['articles'],
        ]);
        $this->Authorization->skipAuthorization();
    }

    /**
     * View method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id)
    {
        $article = $this->Articles->get($id);
        $this->Authorization->authorize($article);
        $this->set([
            'article' => $article,
            '_serialize' => ['article'],
        ]);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->request->allowMethod(['post']);
        $user = $this->Authentication->getIdentity();
        if ($user) {
            $userId = $user->get('id');
        } else {
            throw new UnauthorizedException('You are not authenticated.');
        }
        $article = $this->Articles->newEmptyEntity();
        $article = $this->Articles->patchEntity($article, $this->request->getData());
        $this->Authorization->authorize($article, 'add');
        $article->user_id = $userId;
        
        if ($this->Articles->save($article)) {
            $message = 'Saved';
        } else {
            $message = 'Error';
        }
        $this->set([
            'message' => $message,
            'article' => $article,
            '_serialize' => ['message', 'article'],
        ]);
    }

    /**
     * Edit method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id)
    {
        $this->request->allowMethod(['put']);
        $article = $this->Articles->get($id);
        $this->Authorization->authorize($article);
        $article = $this->Articles->patchEntity($article, $this->request->getData());
        if ($this->Articles->save($article)) {
            $message = 'Updated';
        } else {
            $message = 'Error';
        }
        $this->set([
            'message' => $message,
            '_serialize' => ['message'],
        ]);
    }

    /**
     * Delete method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id)
    {
        $this->request->allowMethod(['delete']);

        try {
            $article = $this->Articles->get($id);
            $this->Authorization->authorize($article);
            if ($this->Articles->delete($article)) {
                $message = 'Deleted';
            } else {
                $message = 'Error';
            }
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException('Article not found.');
        }

        $this->set([
            'message' => $message,
            '_serialize' => ['message'],
        ]);
        
        $this->viewBuilder()->setOption('serialize', ['message']);
        $this->response = $this->response->withType('application/json');
    }

    public function likeCount($articleId)
    {
        $this->request->allowMethod(['get']);
        $likesTable = TableRegistry::getTableLocator()->get('Likes');
        $this->Authorization->authorize($likesTable);
        $likeCount = $likesTable->find()
                            ->where(['article_id' => $articleId])
                            ->count();

        $this->set([
            'like_count' => $likeCount,
            '_serialize' => ['like_count']
        ]);

        

        $this->viewBuilder()->setOption('serialize', ['like_count']);
        $this->viewBuilder()->disableAutoLayout();
        $this->response = $this->response->withType('application/json');
    }
}
 