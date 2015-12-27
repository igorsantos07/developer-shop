<?php

use Phinx\Migration\AbstractMigration;

class AddItemQty extends AbstractMigration {

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
        $this->table('items')
            ->addColumn('qty', 'decimal', ['scale' => 2, 'precision' => 5, 'null' => true]) // 100.00
            ->addColumn('final_price', 'decimal', ['scale' => 2, 'precision' => 9, 'null' => true])
            ->update();

        $this->execute('UPDATE items SET qty = 1, final_price = price');

        $this->table('items')
             ->changeColumn('qty', 'decimal', ['scale' => 2, 'precision' => 5, 'null' => false]) // 100.00
             ->changeColumn('final_price', 'decimal', ['scale' => 2, 'precision' => 9, 'null' => false])
             ->update();
    }
}
