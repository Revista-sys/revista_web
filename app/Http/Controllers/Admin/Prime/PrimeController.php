<?php

namespace App\Http\Controllers\Admin\Prime;
use App\Contracts\Repositories\PrimeRepositoryInterface;
use App\Enums\ViewPaths\Admin\Prime;
use App\Http\Controllers\BaseController;
use App\Traits\FileManagerTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\PrimeUpdateRequest;
use App\Services\PrimeService;

class PrimeController extends BaseController
{
    use FileManagerTrait {
        delete as deleteFile;
        update as updateFile;
    }

    public function __construct(
        private readonly PrimeRepositoryInterface $primeRepo,
         private readonly PrimeService       $primeService,
        
    )
    {
    }

    /**
     * @param Request|null $request
     * @param string|null $type
     * @return View Index function is the starting point of a controller
     * Index function is the starting point of a controller
     */
    public function index(Request|null $request, string $type = null): View
    {
        return $this->getListView($request);
    }

    public function getListView(Request $request): View
    {
       
        $primes = $this->primeRepo->getListWhereIn(
            orderBy: ['id'=>'desc'],
            searchValue: $request['searchValue'],
            filters: ['theme'=>theme_root_path()],
            dataLimit: getWebConfig(name: 'pagination_limit'),
        );

        return view(Prime::LIST[VIEW],  compact('primes'));
    }



   public function getUpdateView($id): View
    {
        
        $primes = $this->primeRepo->getFirstWhere(params: ['id'=>$id]);
        return view(Prime::UPDATE[VIEW], compact('primes'));
    }




       public function update(PrimeUpdateRequest $request, $id): RedirectResponse
    {
         $prime = $this->primeRepo->getFirstWhere(params: ['id'=>$id]);
        $data = $this->primeService->getProcessedData(request: $request);
        $this->primeRepo->update(id:$prime['id'], data:$data);
        Toastr::success('Plan Updated Successfully.');
        return redirect()->route(Prime::UPDATE[ROUTE]);
    }

    
}
