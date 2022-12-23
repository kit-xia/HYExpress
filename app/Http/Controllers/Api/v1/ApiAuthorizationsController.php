<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use App\Models\Members;

class ApiAuthorizationsController extends BaseController
{

    public function auth(Request $request)
    {
//        $code = $request->get('code');
//        //$rawData = $request->get('rawData');
//
//        // 根据 code 获取微信 openid 和 session_key
//        $miniProgram = \EasyWeChat\Factory::miniProgram(config('wechat.mini_program.default'));
//        $data = $miniProgram->auth->session($code);
//
//        //判断code是否过期
//        if (isset($data['errcode'])) {
//            return response()->json(
//                [
//                    'code' => 404,
//                    'massage' => 'code已过期或不正确'
//                ]
//            );
//        }

        $user = Members::UpdateOrCreate(['openid' => 'oH22y5I6cCHohAKEaezOemw2rQuY'], [
            'openid' => 'oH22y5I6cCHohAKEaezOemw2rQuY',
            'nickname' => $data['nickname'] ?? '',
            'avatar' => '',
            'session_key' => $data['session_key'] ?? '',
            'mobile' => '',
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $customClaims = [
            'sub' => [
                "openid" => $user->openid,
                'session_key' => $user->session_key
            ]
        ];

        $payload = JWTFactory::customClaims($customClaims)->make();

        if (!$token = JWTAuth::encode($payload)->get()) {
            return response()->json(
                [
                    'code' => 401,
                    'massage' => 'Unauthorized'
                ]
            );
        }
//        $ttl = $request->out_time ?? config('jwt.ttl'); # 设置token 过期时间

//        if (!$token = Auth::guard('api')->setTTL($ttl)->tokenById($user->id)) {
//            return ['code' => 500, 'massage' => 'token 过期'];
//        }

//        return apiJson($this->respondWithToken($token));

        return response()->json(
            [
                'code' => 200,
                'massage' => 'Success',
                'token' => $token
            ]
        );
    }

}
