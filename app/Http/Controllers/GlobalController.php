<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GlobalController extends Controller
{
    public function basicSelect2(Request $request, $table)
    {
        $search = $request->input('data');
        $table = Helper::decryptFromUrl($table);
        $valueField = Helper::decryptFromUrl($request->input('valueField'));
        $textField = explode(',', Helper::decryptFromUrl($request->input('textField')));

        $selectFields = array_unique(array_merge([$valueField], $textField));

        $query = DB::table($table);

        $byPassOrder = false;
        if ($table == 'roles') {
            $byPassOrder = false;
            $query = $query->join('applications', 'roles.apps_code', 'applications.apps_code');
            $query = $query->orderBy('apps_name', 'desc');
        }

        $query = $query->select($selectFields)
            ->when($search, function ($q) use ($textField, $search) {
                foreach ($textField as $field) {
                    $q->orWhere($field, 'like', '%'.$search.'%');
                }
            });
        $exveptoonVal = $request->input('exceptionValues') ?? [];
        $exceptArray = array_filter($exveptoonVal);
        if ($exceptArray != '' && $exceptArray != null && ! empty($exceptArray)) {
            $exceptionValues = collect($exceptArray)
                ->map(function ($item) {
                    return Helper::decryptFromUrl($item);
                })
                ->toArray();
            $query = $query->whereNotIn($valueField, $exceptionValues);
        }
        if (! $byPassOrder) {
            $query = $query->orderBy($valueField, 'desc');
        }

        $onlyValues = $request->input('parameter')['onoptions'] ?? null;
        if ($onlyValues != '' && $onlyValues != null && ! empty($onlyValues)) {
            $onlyValues = Helper::decryptFromUrl($onlyValues);
            $query = $query->whereIn($valueField, $onlyValues);
        }

        $customOptions = $request->input('parameter')['customoption'] ?? null;
        if ($customOptions != '' && $customOptions != null && ! empty($customOptions)) {
            $customOptions = Helper::decryptFromUrl($customOptions);
            if ($customOptions == 'roleBaseOnAppsCode') {
                $appsCode = Helper::decryptFromUrl($request->input('parameter')['appscode']);
                $query = $query->where('roles.apps_code', $appsCode);
            }
        }

        $query = $query->get()
            ->map(function ($item) use ($valueField, $textField) {
                $text = '';
                foreach ($textField as $key => $field) {
                    if (isset($item->{$field})) {
                        if (count($textField) > 1 && $key == 0) {
                            $text .= '<div class="select2-option-code">'.$item->{$field}.'</div>';
                        } else {
                            $text .= $item->{$field};
                        }

                        if ($key < count($textField) - 1 && $key > 1) {
                            $text .= ' - ';
                        }
                    }
                }

                return [
                    'id' => Helper::encryptForUrl($item->{$valueField}),
                    'text' => '<span class="select2-option">'.$text.'</span>',
                ];
            });

        return response()->json($query);
    }

    public function show($path)
    {
        abort_unless(Storage::disk('public')->exists($path), 404);

        return Storage::disk('public')->response($path);
    }
}
