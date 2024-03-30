<?php

namespace Modules\Permission\Dto;

use Illuminate\Support\Str;

class ModelPermissionDto
{
    public string $viewAny;
    public string $view;
    public string $create;
    public string $update;
    public string $delete;
    public string $restore;
    public string $forceDelete;

    public function __construct(string $viewAny, string $view, string $create, string $update, string $delete, string $restore, string $forceDelete)
    {
        $this->viewAny = $viewAny;
        $this->view = $view;
        $this->create = $create;
        $this->update = $update;
        $this->delete = $delete;
        $this->restore = $restore;
        $this->forceDelete = $forceDelete;
    }

    /**
     * Generate policy permissions from any suffix, such as a table name, model slug, etc.
     * @param string $suffix
     * @return static
     */
    public static function fromSuffix(string $suffix): static
    {
        return new static(
            "viewAny {$suffix}",
            "view {$suffix}",
            "create {$suffix}",
            "update {$suffix}",
            "delete {$suffix}",
            "restore {$suffix}",
            "forceDelete {$suffix}",
        );
    }

    /**
     * Generate policy permissions from a model's class.
     * @param string $class The model's class.
     * @return static
     */
    public static function fromClass(string $class): static
    {
        if (class_exists($class)) {
            $instance = new $class();
            $table = $instance->getTable();
            return static::fromSuffix($table);
        } else {
            return static::fromSuffix(Str::slug($class, '_', 'en', ['\\' => '_']));
        }
    }

    /**
     * Transforms the DTO into an associative array, more specifically action => permission pairs.
     * @return array
     */
    public function toArray(): array
    {
        return [
            'viewAny' => $this->viewAny,
            'view' => $this->view,
            'create' => $this->create,
            'update' => $this->update,
            'delete' => $this->delete,
            'restore' => $this->restore,
            'forceDelete' => $this->forceDelete,
        ];
    }

}
