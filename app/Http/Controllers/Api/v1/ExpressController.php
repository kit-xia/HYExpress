<?php
namespace App\Http\Controllers\Api\v1;

use App\Providers\YidaServiceProvider;
use Illuminate\Http\Request;

/**
 * 快递物流预下单/下单处理
 */
class ExpressController extends BaseController
{
    // 易达178
    protected $yidaService;


    public function __construct()
    {
        $this->yidaService = new YidaServiceProvider();
    }

    /**
     * 快递预下单
     * @param Request $request
     * @return false|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function show(Request $request)
    {
        return $this->yidaService->show($request->all());
    }

    public function create(Request $request)
    {
        echo 2222;die;
    }


}
