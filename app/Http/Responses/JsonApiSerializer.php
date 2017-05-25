<?php

namespace WA\Http\Responses;

use League\Fractal\Pagination\PaginatorInterface;
use League\Fractal\Serializer\JsonApiSerializer as FractalJsonApiSerializer;

class JsonApiSerializer extends FractalJsonApiSerializer
{
    /**
     * Serialize the paginator.
     *
     * @param PaginatorInterface $paginator
     *
     * @return array
     */
    public function paginator(PaginatorInterface $paginator)
    {

        $currentPage = (int)$paginator->getCurrentPage();
        $lastPage = (int)$paginator->getLastPage();

        $pagination = [
            'total'        => (int)$paginator->getTotal(),
            'count'        => (int)$paginator->getCount(),
            'per_page'     => (int)$paginator->getPerPage(),
            'current_page' => $currentPage,
            'total_pages'  => $lastPage,
        ];

        $params = $this->getParameters();

        $pagination['links'] = [];

        $pagination['links']['self'] = $paginator->getUrl($currentPage) . $params;
        $pagination['links']['first'] = $paginator->getUrl(1) . $params;

        if ($currentPage > 1) {
            $pagination['links']['prev'] = $paginator->getUrl($currentPage - 1) . $params;
        }

        if ($currentPage < $lastPage) {
            $pagination['links']['next'] = $paginator->getUrl($currentPage + 1) . $params;
        }

        $pagination['links']['last'] = $paginator->getUrl($lastPage) . $params;

        return ['pagination' => $pagination];
    }

    /**
     * Extract and repopulate the filter and sorting parameters
     *
     * @param bool $excludePage
     * @return string
     */
    private function getParameters($excludePage = true) {
        $params = '';
        $request = app()['request'];

        foreach ($request->all() as $key => $val) {
            if ($key == 'page' && $excludePage) {
                continue;
            }
            if (is_array($val)) {
                foreach ($val as $k => $v) {
                    if (is_array($v)) {
                        foreach ($v as $k1 => $v1) {
                            $params .= "&" . $key . "[" . $k . "]" . "[" . $k1 . "]=" . $v1;
                        }
                    } else {
                        $params .= "&" . $key . "[" . $k . "]=" . $v;
                    }
                }
            } else {
                $params .= "&" . $key . "=" . $val;
            }
        }

        return $params;
    }
}
