<?php

namespace App\Repositories;

use Yajra\DataTables\DataTables;

/**
 * Class BaseRepository.
 *
 * Modified from: https://github.com/kylenoland/laravel-base-repository
 */
abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * The repository model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * The query builder.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * Alias for the query limit.
     *
     * @var int
     */
    protected $take;

    /**
     * Array of related models to eager load.
     *
     * @var array
     */
    protected $with = [];

    /**
     * Array of related models to eager load.
     *
     * @var array
     */
    protected $whereHas = [];

    /**
     * Array of one or more where clause parameters.
     *
     * @var array
     */
    protected $wheres = [];

    /**
     * Array of one or more where in clause parameters.
     *
     * @var array
     */
    protected $whereIns = [];

    /**
     * Array of one or more ORDER BY column/value pairs.
     *
     * @var array
     */
    protected $orderBys = [];

    /**
     * Array of scope methods to call on the model.
     *
     * @var array
     */
    protected $scopes = [];

    protected $hasEdit = true;

    protected $hasDelete = true;


    /**
     * Get all the model records in the database.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        $this->newQuery()->eagerLoad();

        $models = $this->query->get();

        $this->unsetClauses();

        return $models;
    }

    /**
     * Count the number of specified model records in the database.
     *
     * @return int
     */
    public function count()
    {
        return $this->get()->count();
    }

    /**
     * Sum the number of specified model records in the database.
     *
     * @param $column
     * @return double
     */
    public function sum($column)
    {
        return $this->get()->sum($column);
    }

    /**
     * Get the first specified model record from the database.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function first()
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();

        $model = $this->query->first();

        $this->unsetClauses();

        return $model;
    }

    /**
     * Get all the specified model records in the database.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get()
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();

        $models = $this->query->get();

        $this->unsetClauses();

        return $models;
    }

    /**
     * Get the specified model record from the database.
     *
     * @param $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getById($id)
    {
        $this->unsetClauses();

        $this->newQuery()->eagerLoad();

        return $this->query->findOrFail($id);
    }

    /**
     * @param $item
     * @param $column
     * @param  array $columns
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getByColumn($item, $column, array $columns = ['*'])
    {
        $this->unsetClauses();

        $this->newQuery()->eagerLoad();

        return $this->query->where($column, $item)->first($columns);
    }


    public function deleteByColumn($column,$item)
    {
        $this->unsetClauses();

        $this->newQuery()->eagerLoad();

        return $this->query->where($column, $item)->delete();
    }

    /**
     * Delete the specified model record from the database.
     *
     * @param $id
     *
     * @return bool|null
     * @throws \Exception
     */
    public function deleteById($id)
    {
        $this->unsetClauses();

        return $this->getById($id)->delete();
    }

    /**
     * Set the query limit.
     *
     * @param int $limit
     *
     * @return $this
     */
    public function limit($limit)
    {
        $this->take = $limit;

        return $this;
    }

    /**
     * Set an ORDER BY clause.
     *
     * @param string $column
     * @param string $direction
     * @return $this
     */
    public function orderBy($column, $direction = 'asc')
    {
        $this->orderBys[] = compact('column', 'direction');

        return $this;
    }

    /**
     * @param int $limit
     * @param array $columns
     * @param string $pageName
     * @param null $page
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($limit = 25, array $columns = ['*'], $pageName = 'page', $page = null)
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();

        $models = $this->query->paginate($limit, $columns, $pageName, $page);

        $this->unsetClauses();

        return $models;
    }

    public function simplePaginate($limit = 25, array $columns = ['*'], $pageName = 'page', $page = null)
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();

        $models = $this->query->simplePaginate($limit, $columns, $pageName, $page);

        $this->unsetClauses();

        return $models;
    }

    /**
     * Add a simple where clause to the query.
     *
     * @param string $column
     * @param string $value
     * @param string $operator
     *
     * @return $this
     */
    public function where($column, $value, $operator = '=')
    {
        $this->wheres[] = compact('column', 'value', 'operator');

        return $this;
    }

    /**
     * Add a simple where in clause to the query.
     *
     * @param string $column
     * @param mixed $values
     *
     * @return $this
     */
    public function whereIn($column, $values)
    {
        $values = is_array($values) ? $values : [$values];

        $this->whereIns[] = compact('column', 'values');

        return $this;
    }

    /**
     * Set Eloquent relationships to eager load.
     *
     * @param $relations
     *
     * @return $this
     */
    public function with($relations)
    {
        if (is_string($relations)) {
            $relations = func_get_args();
        }

        $this->with = $relations;

        return $this;
    }

    public function whereHas($relations)
    {
        if (is_string($relations)) {
            $relations = func_get_args();
        }

        $this->whereHas = $relations;

        return $this;
    }

    public function whereHasRelation($relations,$function)
    {
        $this->model = $this->model->whereHas($relations,$function);
        return $this;
    }

    /**
     * Create a new instance of the model's query builder.
     *
     * @return $this
     */
    protected function newQuery()
    {
        $this->query = $this->model->newQuery();

        return $this;
    }

    /**
     * Add relationships to the query builder to eager load.
     *
     * @return $this
     */
    protected function eagerLoad()
    {
        foreach ($this->with as $relation) {
            $this->query->with($relation);
        }

        foreach ($this->whereHas as $relation) {
            $this->query->whereHas($relation);
        }

        return $this;
    }

    /**
     * Set clauses on the query builder.
     *
     * @return $this
     */
    protected function setClauses()
    {
        foreach ($this->wheres as $where) {
            $this->query->where($where['column'], $where['operator'], $where['value']);
        }

        foreach ($this->whereIns as $whereIn) {
            $this->query->whereIn($whereIn['column'], $whereIn['values']);
        }

        foreach ($this->orderBys as $orders) {
            $this->query->orderBy($orders['column'], $orders['direction']);
        }

        if (isset($this->take) and !is_null($this->take)) {
            $this->query->take($this->take);
        }

        return $this;
    }

    /**
     * Set query scopes.
     *
     * @return $this
     */
    protected function setScopes()
    {
        foreach ($this->scopes as $method => $args) {
            $this->query->$method(implode(', ', $args));
        }

        return $this;
    }

    /**
     * Reset the query clause parameter arrays.
     *
     * @return $this
     */
    protected function unsetClauses()
    {
        $this->wheres = [];
        $this->whereIns = [];
        $this->scopes = [];
        $this->take = null;

        return $this;
    }

    /**
     * Create a new model record in the database.
     *
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        $this->unsetClauses();

        return $this->model->create($data);
    }

    /**
     * Update the specified model record in the database.
     *
     * @param       $id
     * @param array $data
     * @param array $options
     *
     * @return Collection|Model
     */
    public function updateById($id, array $data, array $options = [])
    {
        $this->unsetClauses();

        $model = $this->getById($id);

        $model->update($data, $options);

        return $model;
    }

    public function getWithDatatable()
    {
        return DataTables::of($this->model->with($this->with)->select())
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return view('backend.includes.partials.datatables.action', ['action' => $this->setDatatableAction($data)])->render();
            })->make(true);
    }

    public function setDatatableAction($data)
    {
        $action_links = '';

        if ($this->hasEdit) {
            $action_links .= "<a href='{$data->url->edit}' class='btn btn-success'>
                                    <i class='fas fa-edit' data-toggle='tooltip' data-placement='top' title='' data-original-title='Edit'></i>
                                </a>";
        }

        if ($this->hasDelete) {
            $action_links .= "<a href='#'
                                     data-method='delete'
                                     data-trans-button-cancel='" . __('buttons.general.cancel') . "'
                                     data-trans-button-confirm='" . __('buttons.general.crud.delete') . "'
                                     data-trans-title='" . __('strings.backend.general.are_you_sure') . "'
                                     class='btn btn-danger' onclick='deleteEle({$data->id})'>

                                     <i class='fas fa-trash' data-toggle='tooltip'
                                     data-placement='top' title='" . __('buttons.general.crud.delete') . "'></i>

                                     <form action='{$data->url->delete}' id='ele{$data->id}' method='POST' name='delete_item' style='display:none'>
                                     <input type='hidden' name='_method' value='delete'>

                                     <input type='hidden' name='_token' value='".  csrf_token()  ."'>
                                    </form>
                                </a>";

        }


//        for ($i = 0; $i < count($this->actions); $i++) {
//            $action_links .= ($action_links != '' ? ' ' : '') . $this->actions[$i];
//        }

        return $action_links;
    }

    public function createData()
    {
        return [];
    }

    public function editData($id)
    {
        return [
            'data' => $this->getById($id)
        ];
    }

    public  function whereDate($column, $operator, $value = null, $boolean = 'and')
    {
        $this->unsetClauses();

        $this->newQuery()->eagerLoad();

        return $this->query->whereDate($column, $operator, $value, $boolean);
    }

    public  function orWhere($column, $operator = '=', $value = null)
    {
        //$this->unsetClauses();

        //$this->newQuery()->eagerLoad();

         $this->model->orWhere($column, $operator, $value);
        return $this;
    }

    public function orderByTranslation($column,$sort = "asc")
    {
        return $this->model->orderByTranslation($column,$sort);
    }

    public function whereTranslationLike($column,$value)
    {
        return $this->model->whereTranslationLike($column,$value);
    }
}
