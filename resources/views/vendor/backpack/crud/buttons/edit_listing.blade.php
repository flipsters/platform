@if ($crud->hasAccess('update'))
	<a href="{{ url('listings/show-listing-'.$entry->getKey().'/edit') }}" class="btn btn-xs btn-default" target="_blank"><i class="fa fa-edit"></i> {{ trans('backpack::crud.edit') }}</a>
@endif
