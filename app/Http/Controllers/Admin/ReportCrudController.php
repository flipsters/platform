<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ReportRequest as StoreRequest;
use App\Http\Requests\ReportRequest as UpdateRequest;

class ReportCrudController extends CrudController
{

    public function setUp()
    {

        /*
    		|--------------------------------------------------------------------------
    		| BASIC CRUD INFORMATION
    		|--------------------------------------------------------------------------
    		*/
        $this->crud->setModel("App\Models\Report");
        $this->crud->setRoute("admin/report");
        $this->crud->setEntityNameStrings('report', 'reports');

        /*
    		|--------------------------------------------------------------------------
    		| BASIC CRUD INFORMATION
    		|--------------------------------------------------------------------------
    		*/


        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'open',
            'label'=> 'Open'
        ], false, function ($values) { // if the filter is active
            $this->crud->query = $this->crud->query->where('status','0');
        });

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'closed',
            'label'=> 'Closed'
        ], false, function ($values) { // if the filter is active
            $this->crud->query = $this->crud->query->where('status','1');
        });

        // ------ CRUD FIELDS
        // $this->crud->addField($options, 'update/create/both');
        // $this->crud->addFields($array_of_arrays, 'update/create/both');
        // $this->crud->removeField('name', 'update/create/both');
        // $this->crud->removeFields($array_of_names, 'update/create/both');

        // ------ CRUD COLUMNS
        $this->crud->addColumn(['name' => 'status', 'label' => 'Status', 'type' => 'model_function','function_name' => 'getStatusAdmin']);
        $this->crud->addColumn(['name' => 'user_id', 'label' => 'User', 'type' => 'model_function','function_name' => 'getUserAdmin']);
        $this->crud->addColumn(['name' => 'reason', 'label' => 'Reason', 'type' => 'text']);
        $this->crud->addColumn(['name' => 'created_at', 'label' => 'Created', 'type' => 'model_function','function_name' => 'getDateAdmin']);

        // ------ CRUD BUTTONS
        $this->crud->addButtonFromView('line', 'show', 'show_report', 'beginning');
        $this->crud->removeButton('create');
        $this->crud->removeButton('delete');
        $this->crud->removeButton('update');

        // ------ CRUD ACCESS
        // $this->crud->allowAccess(['list', 'create', 'update', 'reorder', 'delete']);
        // $this->crud->denyAccess(['list', 'create', 'update', 'reorder', 'delete']);

        // ------ CRUD REORDER
        // $this->crud->enableReorder('label_name', MAX_TREE_LEVEL);
        // NOTE: you also need to do allow access to the right users: $this->crud->allowAccess('reorder');

        // ------ CRUD DETAILS ROW
        // $this->crud->enableDetailsRow();
        // NOTE: you also need to do allow access to the right users: $this->crud->allowAccess('details_row');
        // NOTE: you also need to do overwrite the showDetailsRow($id) method in your EntityCrudController to show whatever you'd like in the details row OR overwrite the views/backpack/crud/details_row.blade.php

        // ------ REVISIONS
        // You also need to use \Venturecraft\Revisionable\RevisionableTrait;
        // Please check out: https://laravel-backpack.readme.io/docs/crud#revisions
        // $this->crud->allowAccess('revisions');

        // ------ AJAX TABLE VIEW
        $this->crud->enableAjaxTable();

        // ------ DATATABLE EXPORT BUTTONS
        // Show export to PDF, CSV, XLS and Print buttons on the table view.
        // Does not work well with AJAX datatables.
        // $this->crud->enableExportButtons();

        // ------ ADVANCED QUERIES
        // $this->crud->addClause('active');
        // $this->crud->addClause('type', 'car');
        // $this->crud->addClause('where', 'name', '==', 'car');
        // $this->crud->addClause('whereName', 'car');
        // $this->crud->addClause('whereHas', 'posts', function($query) {
        //     $query->activePosts();
        // });
        // $this->crud->with(); // eager load relationships
        // $this->crud->orderBy();
        // $this->crud->groupBy();
        // $this->crud->limit();
        $this->crud->with('user');
    }

	public function store(StoreRequest $request)
	{
		// your additional operations before save here
        $redirect_location = parent::storeCrud();
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
	}

	public function update(UpdateRequest $request)
	{
		// your additional operations before save here
        $redirect_location = parent::updateCrud();
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
	}

  /**
   * Respond with the JSON of one or more rows, depending on the POST parameters.
   * @return JSON Array of cells in HTML form.
   */
  public function search()
  {
      $this->crud->hasAccessOrFail('list');

      // crate an array with the names of the searchable columns
      $columns = collect($this->crud->columns)
                              ->reject(function ($column, $key) {
                                  // the select_multiple columns are not searchable
                                      return isset($column['type']) && $column['type'] == 'select_multiple';
                              })
                              ->pluck('name')
                              // add the primary key, otherwise the buttons won't work
                              ->merge($this->crud->model->getKeyName())
                              ->toArray();

      // details row fix
      if ($this->crud->details_row) {
          array_unshift($columns, 'id');
      }

      // structure the response in a DataTable-friendly way
      $dataTable = new \LiveControl\EloquentDataTable\DataTable($this->crud->query, $columns);

      // make the datatable use the column types instead of just echoing the text
      $dataTable->setFormatRowFunction(function ($entry) {
          // get the actual HTML for each row's cell
              $row_items = $this->crud->getRowViews($entry, $this->crud);

              // add the buttons as the last column
              if ($this->crud->buttons->where('stack', 'line')->count()) {
                  $row_items[] = \View::make('crud::inc.button_stack', ['stack' => 'line'])
                                                      ->with('crud', $this->crud)
                                                      ->with('entry', $entry)
                                                      ->render();
              }

              // add the details_row buttons as the first column
              if ($this->crud->details_row) {
                  array_unshift($row_items, \View::make('crud::columns.details_row_button')
                                                      ->with('crud', $this->crud)
                                                      ->with('entry', $entry)
                                                      ->render());
              }

          return $row_items;
      });

      return $dataTable->make();
  }
}
