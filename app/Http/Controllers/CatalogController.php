<?php

namespace App\Http\Controllers;

use App\Application\Catalog\DTOs\CatalogFiltersDTO;
use App\Application\Catalog\UseCases\ListCatalog;
use App\Application\Catalog\UseCases\SearchCatalog;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request, ListCatalog $listCatalog)
    {
        $dto = CatalogFiltersDTO::fromRequest($request);
        $data = $listCatalog->execute($dto);

        return view('catalog', $data);
    }

    public function search(Request $request, SearchCatalog $searchCatalog)
    {
        $result = $searchCatalog->execute($request->get('q', ''));

        return response()->json($result);
    }
}
