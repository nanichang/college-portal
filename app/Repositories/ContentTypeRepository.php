<?php

namespace App\Repositories;

use CollegePortal\Models\User;
use CollegePortal\Models\ContentType;
use App\Filters\ContentTypeFilters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ContentTypeRepository
{
    public function model()
    {
        return app(ContentType::class);
    }

    public function list(User $user, ContentTypeFilters $filters) {
        return $this->model()->filter($filters)->paginate();
    }

    public function single($id, ContentTypeFilters $filters = null) {
        $q = $this->model();
        if ($filters) {
            $q = $q->filter($filters);
        }
        return $q->findOrFail($id);
    }

    public function delete($id) {
        return DB::transaction(function () use ($id) {
            return $this->model()->findOrFail($id)->delete();
        });
    }

    public function create($opts) {
        return DB::transaction(function () use ($opts) {
            return $this->model()->create($opts);
        });
    }

    public function update($id, $opts = []) {
        return DB::transaction(function () use ($id, $opts) {
            $item = $this->model()->findOrFail($id);
            $item->fill($opts);
            $item->save();
            return $item;
        });
    }

    public function count(ContentTypeFilters $filters)
    {
        return $this->model()->filter($filters)->select('id', DB::raw('count(*) as total'))->count();
    }
}