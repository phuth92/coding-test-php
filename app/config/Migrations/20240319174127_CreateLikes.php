<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateLikes extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('likes');
        $table->addColumn('user_id', 'integer')
              ->addColumn('article_id', 'integer')
              ->addColumn('created_at', 'datetime')
              ->addColumn('updated_at', 'datetime')
              ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
              ->addForeignKey('article_id', 'articles', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
              ->create();
    }
}
