<?php

use Phinx\Migration\AbstractMigration;

class DefaultOrderFields extends AbstractMigration {

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
            ->changeColumn('total', 'decimal', ['scale' => 2, 'precision' => 9, 'default' => 0]) // 1,000,000.00
            ->renameColumn('status', 'closed')
            ->changeColumn('closed', 'boolean', ['default' => false])
            ->save();
    }
}
