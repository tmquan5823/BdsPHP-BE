<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = app($this->getModel());
    }

    /**
     * Get the model for the repository
     *
     * @return string
     */
    abstract public function getModel();

    /**
     * Get all records
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * Find record by ID
     *
     * @param int $id
     * @return Model|null
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Create new record
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update record
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, array $data)
    {
        $record = $this->find($id);
        if ($record) {
            return $record->update($data);
        }
        return false;
    }

    /**
     * Delete record
     *
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $record = $this->find($id);
        if ($record) {
            return $record->delete();
        }
        return false;
    }
}
