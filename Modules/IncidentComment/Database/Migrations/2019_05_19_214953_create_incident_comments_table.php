<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateIncidentCommentsTable
 *
 * The Migrations is Defined for Incident Comment.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\IncidentComment
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class CreateIncidentCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.incident_comments_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.incident_comments_table'),
            function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('user_id')->unsigned();
                $table->foreign('user_id')
                    ->references('id')
                    ->on(config('core.acl.users_table'))
                    ->onDelete('cascade');
                $table->integer('incident_id')->unsigned()->index();
                $table->foreign('incident_id')
                    ->references('id')
                    ->on(config('core.acl.incidents_table'))
                    ->onDelete('cascade');
                $table->integer('parent_id')->default(0);
                $table->text('comment');
                $table->text('attachments')->nullable();
                $table->softDeletes();
                $table->timestamps();
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
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.incident_comments_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
