<?php

namespace WA\Http\Controllers;

use View;
use WA\Http\Controllers\Auth\AuthorizedController;
use Dingo\Api\Routing\Helpers;
use WA\Repositories\Company\CompanyInterface;
use Input;
use Redirect;
use Alert;
use Cartalyst\DataGrid\Laravel\Facades\DataGrid;

/**
 * Class PagesController.
 */
class PagesStaticController extends AuthorizedController
{
    use Helpers;

    protected $company;

    /**
     * PagesController constructor.
     *
     * @param CompanyInterface $company
     */
    public function __construct(CompanyInterface $company)
    {
        $this->company = $company;
    }

    public function showIndex()
    {
        if (empty($this->currentCompany)) {
            return redirect()->to('logout');
        }

        return view('dashboard.index');
    }

    public function showPages()
    {
        return View::make('pages.content.index');
    }

    /**
     * Edit the static content for pages in the app.
     *
     * @param $id
     *
     * @return bool
     */
    public function edit($id)
    {
        try {
            $pages = $this->api->get('pages/'.$id);
            $companies = $this->company->getAll(false);

            return view('pages.content.edit')->with('pages', $pages)->with('companies', $companies);
        } catch (Dingo\Api\Exception\InternalHttpException $e) {
            Log::error('Something failed with the response, : ' . $e->getResponse());
            return false;
        }
    }

    /**
     * Update static content for pages in the app.
     *
     * @param $id
     *
     * @return $this
     *
     * @throws Symfony\Component\HttpKernel\Exception\ConflictHttpException
     */
    public function update($id)
    {
        try {
            $data = [
                'id' => $id,
                'title' => trim(Input::get('title')),
                'section' => trim(Input::get('section')),
                'content' => Input::get('content'),
                'role_id' => !empty(Input::get('role_id')) ? Input::get('role_id') : null,
                'active' => (int) ((bool) Input::get('isActive')),
                'companyId' => (int) Input::get('company'),
            ];

            $page = $this->api->post('pages', $data);

            if (!$page) {
                Alert::error('Invalid content provided.');
            } else {
                Alert::success('Pages Content updated.');
            }

            return Redirect::back()->withInput();
        } catch (Symfony\Component\HttpKernel\Exception\ConflictHttpException $e) {
            throw new Symfony\Component\HttpKernel\Exception\ConflictHttpException('We got a conflict!');
        }
    }

    /**
     * Handles the datatables, this needs to be in a specific format to make it compatible
     * with the DataTable.
     *
     * Returns all pages.
     *
     * @return DataGrid
     */
    public function datatable()
    {
        $pages = $this->api->get('pages');

        $columns = [
            'id',
            'companyId',
            'title',
            'section',
            'active',
            'content',
            'roleId',
        ];

        $settings = array(
            'max_results' => 10,
            'sort' => 'id',

        );
        $response = DataGrid::make($pages, $columns, $settings);

        return $response;
    }
}
