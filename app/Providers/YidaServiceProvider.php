<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;

class YidaServiceProvider extends ServiceProvider
{
    protected $sign;
    protected $timestamp;
    protected $method = ['show' => 'SMART_PRE_ORDER', 'execute' => 'SUBMIT_ORDER_V2'];

    public function __construct()
    {
        list($t1, $t2) = explode(' ', microtime());
        $this->timestamp = (string) round(((floatval($t1) + floatval($t2)) * 1000));
        $this->sign = $this->createSign();
    }

    /**
     * 快递物流预下单
     *
     * @param array $requestParams
     * @return false|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function show(array $requestParams)
    {
        try {
            $body = [
                "apiMethod" => $this->method['show'],
                "businessParams" => $requestParams,
                "sign" => $this->sign,
                "timestamp" => $this->timestamp,
                "username" => config('express.yida.user')
            ];
            $client = new Client(
                [
                    'base_uri' => config('express.yida.host'),
                    'timeout'  => 2.0
                ]
            );
            $request = $client->request('POST', config('express.yida.api'), ['json' => $body]);
            $content = $request->getBody()->getContents();
            $response = json_decode($content, true);

            return response()->json([
                'code' => $response['code'],
                'msg' => $response['msg'],
                'data' => $response['data']
            ]);
        } catch (\Throwable $throwable) {
            return response()->json([
                'code' => 500,
                'msg' => '系统开小差了，请稍后再试！',
                'data' => null
            ]);
        }
    }

    public function execute(array $requestParams)
    {

    }

    /**
     * 创建签名
     * @return string
     */
    private function createSign(): string
    {
        $sign_Array = [
            "privateKey" => env('YIDA_KEY'),
            "timestamp" => $this->timestamp,
            "username" => env('YIDA_USER')
        ];

        return strtoupper(MD5(json_encode($sign_Array, 320)));
    }
}
