<?php
/**
 * Creator htm
 * Created by 2020/12/1 10:46
 **/

namespace Szkj\Stat\Models;


use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    /**
     * @var string
     */
    protected $table = 'violations';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('database.default');

        $this->setConnection($connection);

        parent::__construct($attributes);
    }

    /**
     * @param \DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}