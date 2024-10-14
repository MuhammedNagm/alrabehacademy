<?php

namespace Modules\Settings\Http\Controllers;

use Modules\Foundation\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class UtilitiesController extends BaseController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * {!! ModulesForm::select('users','Users', [], false, null,
     * ['class'=>'select2-ajax','data'=>[
     * 'model'=>\Modules\User\Models\User::class,
     * 'columns'=> json_encode(['name','email']),
     * 'selected'=>json_encode([1=>'zzzzzz',3=>'xxxxxxxxx']),
     * 'orWhere'=>json_encode([]),
     * 'where'=>json_encode([
     * ['field'=>'tableX.col_x','operation'=>'=','value'=>'xx'],
     * ['field'=>'tableX.col_y','operation'=>'=','value'=>'yy']
     * ]),
     * 'join' =>[
     * 'table'=>'tableX',
     * 'type'=>'leftJoin',
     * 'on' =>['tableX.user_id','users.id']
     * ]
     * ]],'select2')
     * !!}
     */
    public function select2(Request $request)
    {
        $this->validate($request, [
            'columns' => 'required',
            'model' => 'required'
        ]);

        $query = $request->get('query');
        $columns = $request->get('columns');
        $model = $request->get('model');
        $selected = $request->get('selected', []);
        $where = $request->get('where', []);
        $orWhere = $request->get('orWhere', []);
        $join = $request->get('join', []);

        $result = null;

        if (empty($query) && empty($selected)) {
            return response()->json([]);
        }

        $result = $model::where(function ($q) use ($columns, $query) {
            foreach ($columns as $index => $column) {
                $q = $q->orWhere($column, 'like', '%' . $query . '%');
            }
        });

        if (!empty($selected)) {
            $result = $result->whereIn('id', $selected);
        }

        if (!empty($where)) {
            foreach ($where as $w) {
                $result = $result->where($w['field'], $w['operation'], $w['value']);
            }
        }

        if (!empty($orWhere)) {
            $result = $result->where(function ($query) use ($orWhere) {
                foreach ($orWhere as $w) {
                    $query = $query->orWhere($w['field'], $w['operation'], $w['value']);
                }
            });
        }

        if (!empty($join)) {
            $result = $result->{$join['type']}($join['table'], $join['on'][0], $join['on'][1]);
        }

        $text = join(',", ",', $columns);

        $id = !empty($join) ? with(new $model)->getTable() . '.id' : 'id';

        $result->select(\DB::raw("concat($text) as text"), $id);

        $result = $result->distinct()->get();

        $results = [];

        foreach ($result as $item) {
            array_push($results, ['id' => $item->id, 'text' => $item->text]);
        }

        return response()->json($results);
    }
}
