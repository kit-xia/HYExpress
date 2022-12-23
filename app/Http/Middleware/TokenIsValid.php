<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Members;


class TokenIsValid extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $token = JWTAuth::getToken();
            if (empty($token)) {
                return response()->json(['code' => 401, 'msg' => '未登录']);
            }

            $user_info = JWTAuth::setToken($token)->getPayload()->get('sub');
            if ($user_info) {
                $user = Members::where('openid', $user_info->openid)->first();
                if (!$user) {
                    return response()->json(['code' => 402, 'msg' => '用户异常']);
                }

                //如果想向控制器里传入用户信息，将数据添加到$request里面
                $request->attributes->add(['memberId' => $user->id]); //添加参数
            }
            //其他地方获取用户值
//            var_dump($request->attributes->get('memberId'));exit();
            return $next($request);
        } catch (TokenExpiredException $e) {
            try {
                $token = JWTAuth::refresh();
                if ($token) {
                    return response()->json(['code' => 403, 'msg' => '新token', 'token' => $token]);
                }
            } catch (JWTException $e) {
                return response()->json(['code' => 404, 'msg' => 'token无效', 'token' => '']);
            }
        }
    }
}
