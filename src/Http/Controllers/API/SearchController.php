<?php namespace Riari\Forum\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Riari\Forum\Models\Category;
use Riari\Forum\Models\Post;
use Riari\Forum\Models\Thread;

class SearchController extends BaseController
{
    /**
     * Return the model to use for this controller.
     *
     * @return Category
     */
    protected function model()
    {
        return new Category;
    }

    /**
     * Return the translation file name to use for this controller.
     *
     * @return string
     */
    protected function translationFile()
    {
        return 'categories';
    }

    /**
     * GET: Return an index of categories.
     *
     * @param  Request  $request
     * @return JsonResponse|Response
     */
    public function index(Request $request)
    {
        $categories = $this->model()->withRequestScopes($request);

        if ($keyword = $request->input('search_keyword')) {
            $categories->search($keyword);

            $matchingThreadCategoryIds = (new Thread())->search($keyword)
                ->get()
                ->pluck(['category_id']);

            $threads = (new Post())->search($keyword)
                ->get()
                ->pluck(['thread_id']);

            $matchingPostCategoryIds = (new Thread())->whereIn('id', $threads)
                ->get()
                ->pluck(['category_id']);

            $ids = $matchingThreadCategoryIds->merge($matchingPostCategoryIds);

            if ($ids) {
                $categories->orWhere(function($query) use ($ids) {
                    $query->whereIn('id', $ids);
                });
            }
        }

        $categories = $categories->get()->filter(function ($category) {
            if ($category->private) {
                return Gate::allows('view', $category);
            }

            return true;
        });

        return $this->response($categories);
    }
}
