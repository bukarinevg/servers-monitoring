<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateWebServerTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $this->table('web_server')
            ->addColumn('name', 'string', ['limit' => 100])
            ->addColumn('path', 'string', ['limit' => 100])
            ->addColumn('port', 'integer')
            ->addColumn('status', 'integer')
            ->addColumn('created_at', 'integer')
            ->addColumn('updated_at', 'integer')
            ->create();
    }
}
