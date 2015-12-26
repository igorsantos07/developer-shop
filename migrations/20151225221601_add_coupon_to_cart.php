<?php

use Phinx\Migration\AbstractMigration;

class AddCouponToCart extends AbstractMigration {

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
        $this->table('coupons')
            ->addColumn('code', 'string')
            ->addColumn('discount', 'decimal', ['scale' => 0, 'precision' => 2]) //allows for two digits, so we will never have a 100% discount
            ->addIndex('code', ['unique' => true])
            ->insert([
                ['code' => 'SHIPIT', 'discount' => 20],
                ['code' => 'NOTTHIS', 'discount' => 1]
            ])
            ->create();

        $this->table('orders')
            ->addColumn('coupon_id', 'integer', ['null' => true])
            ->addForeignKey('coupon_id', 'coupons', 'id')
            ->update();
    }
}
