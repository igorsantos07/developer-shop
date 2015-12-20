<?php

use Phinx\Migration\AbstractMigration;

class Cart extends AbstractMigration {

    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change() {
        $this->table('orders')
            ->addColumn('session', 'string')
            ->addColumn('total', 'decimal', ['scale' => 2, 'precision' => 9]) // 1,000,000.00
            ->addColumn('status', 'boolean')
            ->addTimestamps()
            ->create();

        $this->table('items')
            ->addColumn('order_id', 'integer')
            ->addColumn('item', 'string')
            ->addColumn('price', 'decimal', ['scale' => 2, 'precision' => 9])
            ->addTimestamps()
            ->create();
    }
}
