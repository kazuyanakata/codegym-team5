<?php

use Migrations\AbstractMigration;

class CreatePoints extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('points', ['id' => false, 'primary_key' => ['member_id', 'schedule_id', 'column_number', 'record_number','is_minus']]);
        $table->addColumn('member_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('schedule_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('column_number', 'string', [
            'default' => null,
            'limit' => 2,
            'null' => false,
        ]);
        $table->addColumn('record_number', 'string', [
            'default' => null,
            'limit' => 2,
            'null' => false,
        ]);
        $table->addColumn('point', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('is_minus', 'boolean', [
            'default' => 0,
            'null' => false,
        ]);
        $table->addColumn('is_cancelled', 'boolean', [
            'default' => 0,
            'null' => false,
        ]);
        $table->addColumn('created_at', 'datetime', [
            'default' => 'CURRENT_TIMESTAMP',
            'null' => false,
        ]);
        $table->addColumn('updated_at', 'datetime', [
            'default' => 'CURRENT_TIMESTAMP',
            'update' => 'CURRENT_TIMESTAMP',
            'null' => false,
        ]);
        $table->create();
    }
}
