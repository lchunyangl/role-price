<?php


namespace Chunyang\RolePrice\Models;


use App\Models\AdminLog;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Base;
use Illuminate\Support\Str;

abstract class Model extends Base
{
    protected $log;

    protected $logType = 0;

    protected $logTypeArr = ['新增', '更新', '删除'];

    protected $logEnable = [];

    protected $logUnable = ['*'];

    protected static $unLogUnable = false;

    public static function boot()
    {
        parent::boot();
        self::created(function ($model) {
            $model->logType = 0;
            $model->log()->header()->toLog();
        });
        self::updated(function ($model) {
            $model->logType = 1;
            $model->log()->header()->toLog();
        });
        self::deleted(function ($model) {
            $model->logType = 2;
            $model->log()->header()->toLog();
        });
    }

    public function getLogEnable()
    {
        return $this->logEnable;
    }

    public function logEnable(array $logEnable)
    {
        $this->logEnable = $logEnable;
        return $this;
    }

    public function getLogUnable()
    {
        return $this->logUnable;
    }

    public function logUnable(array $logUnable)
    {
        $this->logUnable = $logUnable;
        return $this;
    }

    public function getLogType()
    {
        return isset($this->logTypeArr[$this->logType]) ? $this->logTypeArr[$this->logType] : $this->logType;
    }

    protected function logTypeArr(array $logTypeArr)
    {
        $this->logTypeArr = $logTypeArr;
        return $this;
    }

    public function isLogEnable($key)
    {
        if (static::$unLogUnable) {
            return true;
        }

        if (in_array($key, $this->getLogEnable())) {
            return true;
        }

        if ($this->islogUnable($key)) {
            return false;
        }

        return empty($this->getLogEnable()) && !Str::startsWith($key, '_');
    }

    public function isLogUnable($key)
    {
        return in_array($key, $this->getlogUnable()) || $this->getlogUnable() == ['*'];
    }

    protected function logEnableFromArray(array $attributes)
    {
        if (count($this->getLogEnable()) > 0 && !static::$unLogUnable) {
            return array_intersect_key($attributes, array_flip($this->getLogEnable()));
        }

        return $attributes;
    }

    public function log()
    {
        $this->log = collect();
        if ($this->logType == 2) {
            $dirty = $this->getAttributes();
        } else {
            $dirty = $this->getDirty();
        }
        unset($dirty[$this->primaryKey]);
        foreach ($this->logEnableFromArray($dirty) as $key => $value) {
            $key = $this->removeTableFromKey($key);
            if ($this->islogEnable($key)) {
                $this->description($key);
            }
        }
        return $this;
    }

    protected function header($before = '', $after = '')
    {
        $collection = collect();
        $collection->push($before);
        $collection->push($this->getKey());
        $collection->push($after);
        $collection = $collection->map(function ($item) {
            if (!empty($item)) {
                return '「' . $item . '」';
            }
        });
        $table = trans('models/' . $this->getTable());
        $name = isset($table['table']) ? $table['table'] : $this->getTable();
        if ($this->log->count() > 0)
            $this->log->prepend($this->getLogType() . $name . $collection->implode(''));
        return $this;
    }

    protected function description($key)
    {
        $column = trans('models/' . $this->getTable() . '.' . $key);
        $info = isset($column['info']) ? $column['info'] : $key;
        if (isset($column['fun'])) {
            $fun = $column['fun'];
            $from = $fun($this->getFrom($key));
            $to = $fun($this->getTo($key));
        } else {
            $from = $this->getFrom($key);
            $to = $this->getTo($key);
        }
        if (in_array($this->logType, [0, 2]))
            $this->log->push($info . '「' . $this->toString($to) . '」');
        elseif ($this->logType == 1)
            $this->log->push($info . '由「' . $this->toString($from) . '」变成「' . $this->toString($to) . '」');
        return $this;
    }

    protected function toLog()
    {
        if ($this->log->count() > 0) {
            $log = new AdminLog();
            $log->user_id = auth()->id();
            $log->ip_address = request()->ip();
            $log->log_time = time();
            $header = $this->log->shift();
            $log->log_info = $header . ':' . $this->log->implode(';');
            $log->save();
        }
    }

    protected function getFrom($key)
    {
        if ($this->hasGetMutator($key)) {
            $from = $this->{'get' . Str::studly($key) . 'Attribute'}($this->getOriginal($key));
        } else {
            $from = $this->getOriginal($key);
        }
        return $from;
    }

    protected function getTo($key)
    {
        $to = $this->$key;
        return $to;
    }

    protected function toString($value)
    {
        if ($value instanceof Carbon) {
            $value = $value->format('Y-m-d H:i:s');
        } elseif (is_array($value) || is_object($value)) {
            $value = response()->json($value)->getContent();
        }
        return $value;
    }
}