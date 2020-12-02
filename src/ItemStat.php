<?php
/**
 * Creator htm
 * Created by 2020/11/17 13:17
 **/

namespace Szkj\Stat;


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Illuminate\Database\Eloquent\Builder;

class ItemStat
{

    /**
     * @param Integer|null $task_id
     * @return Builder
     */
    public static function query(Integer $task_id = null)
    {

        $drive = config('szkj.items.drive');

        if ($drive != 'db' && $drive != 'appoint') {
            throw new BadRequestHttpException('config bad,szkj.items.drive is db or appoint');
        }

        $task_id = $task_id ? $task_id : static::{$drive}();

        $item = class_exists(\App\Models\Item::class) ? new \App\Models\Item() : new \Szkj\Stat\Models\Item();

        return $item->setTable(static::tableName($task_id))->newQuery();

    }


    protected function db()
    {

        $connection = config('database.default');

        $where = config('szkj.items.drives.db.where');

        $created_at = class_exists(\App\Models\Task::class) ? \App\Models\Task::CREATED_AT : \Szkj\Collection\Models\Task::CREATED_AT;

        $query = DB::connection($connection)->table('task')->orderBy($created_at, 'desc');

        foreach ($where as $k => $v) {
            $query->where($k, $v);
        }

        return $query->first()->id;
    }

    protected function appoint()
    {
        return config('szkj.items.drives.appoint.task_id');
    }


    /**
     * @param $task_id
     * @return string
     */
    protected static function tableName($task_id)
    {
        return 'items_' . $task_id.' as items';
    }


    public static function migration($task_id)
    {
        if (!Schema::hasTable(static::tableName($task_id))) {
            Schema::create(static::tableName($task_id), function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('task_id')->default(0)->comment('任务id');
                $table->string('batch', 50)->nullable()->comment('批号');
                $table->string('keyword', 50)->nullable()->comment('关键词');
                $table->string('nid', 50)->comment('商品id');
                $table->string('category_id', 200)->nullable()->comment('原始分类id');
                $table->string('title', 500)->comment('标题');
                $table->string('location', 50)->nullable()->comment('发货地');
                $table->string('shop_id', 50)->comment('店铺id');
                $table->double('view_price')->nullable()->comment('列表价格');
                $table->integer('view_sales')->nullable()->comment('显示销量');
                $table->integer('comment_count')->nullable()->comment('评论数量');
                $table->integer('platform_id')->comment('平台');
                $table->json('property')->nullable()->comment('属性');
                $table->double('view_amount')->nullable()->comment('总销售额');
                $table->string('nick', 255)->nullable();
                $table->string('seller_id', 255)->nullable();
                $table->string('classify', 255)->nullable()->comment('公司分类');
                $table->string('item_url', 255)->nullable()->comment('商品链接');
                $table->index('task_id', 'task_id');
                $table->index(['nid', 'platform_id', 'seller_id']);
                $table->index('classify', 'classify');
                $table->index('item_url', 'item_url');
                $table->timestamps();
            });
        }
    }
}