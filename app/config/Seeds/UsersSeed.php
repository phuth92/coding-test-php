<?php
declare(strict_types=1);

use Migrations\AbstractSeed;
use Cake\Auth\DefaultPasswordHasher;

/**
 * Users seed.
 */
class UsersSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     *
     * @return void
     */
    public function run(): void
    {
        $data = [
            [
                'email'       => 'ivc.phuth@gmail.com',
                'password'    => 'password',
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'email'       => 'user@gmail.com',
                'password'    => 'password',
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ]
        ];

        $hasher = new DefaultPasswordHasher();
        foreach ($data as $key => $user) {
            $data[$key]['password'] = $hasher->hash($user['password']);
        }

        $table = $this->table('users');
        $table->insert($data)->save();
    }
}
