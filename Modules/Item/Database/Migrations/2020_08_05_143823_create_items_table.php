<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateItemsTable
 *
 * The Migrations is Defined for Item.
 *
 * PHP version 7.1.3
 *
 * @category  Sales
 * @package   Modules\Item
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.items_table'));
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.items_table'), function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->string('name');
                $table->double('price', 8, 2);
                $table->mediumText('description')->nullable();
                $table->string('unit')->nullable();
                $table->timestamps();
                $table->softDeletes();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.items_table'));
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
