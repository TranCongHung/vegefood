<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Services\Category\Actions\CreateCategoryAction;
use App\Services\Category\Actions\DeleteCategoryAction;
use App\Services\Category\Actions\ShowCategoryAction;
use App\Services\Category\Actions\UpdateCategoryAction;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categoryList = resolve(ShowCategoryAction::class)->run();

        return view('admin.categories.index', array(
            'categoryList' => $categoryList,
        ));
    }

    public function create()
    {
        $categoryList = resolve(ShowCategoryAction::class)->run();
        return view('admin.categories.create', array('categoryList' => $categoryList));
    }

    public function store(CategoryRequest $request)
    {
        $category = resolve(CreateCategoryAction::class)->create($request->all());
        $category->addMediaFromRequest('image')->usingName($category->name)->toMediaCollection('categories_images');

        $request->session()->flash('status', 'them thanh cong');
        return redirect()->route('admin.categories.index');
    }

    public function edit($id)
    {

        $category = resolve(ShowCategoryAction::class)->find($id);

       return view('admin.categories.edit', array('category' => $category));
    }

    public function update(Request $request, $id)
    {
        $category = resolve(UpdateCategoryAction::class)->update($id, $request->all());
        resolve(UpdateCategoryAction::class)->updateCategoryMedia($category, $request);

        return redirect()->route('admin.categories.index');
    }

    public function destroy($id, Request $request)
    {
        $bool = resolve(DeleteCategoryAction::class)->delete($id);
        if ($bool)
            $request->session()->flash('status', 'xóa thanh cong');
        else
            $request->session()->flash('status', 'xóa that bai');

        return redirect()->route('admin.categories.index');
    }
}
