<?php
namespace App\Services\Rpc\Tasks\Clients;

use App\Services\ServeResult;
use App\Services\TaskServiceContract;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HttpRpc implements TaskServiceContract
{
    private $routeUrl;
    private $request;

    public function __construct(Request $request, string $abstract, array $uriReplaces)
    {
        if (!isset(HttpRpcServerConfig::$routes[$abstract]))
            throw new NotFoundHttpException();

        if (!$this->routeUrl = (HttpRpcServerConfig::$routes[$abstract][$method = strtoupper($request->method())] ?? null))
            throw new MethodNotAllowedHttpException(array_keys(HttpRpcServerConfig::$routes[$abstract]));

        $this->replaceUriParams($uriReplaces);

        $this->request = $request;
    }

    private function replaceUriParams(array $uriReplaces)
    {
        if ($uriReplaces)
            foreach ($uriReplaces as $uriReplace)
                $this->routeUrl = str_replace('$param', $uriReplace, $this->routeUrl);
    }

    public function run(): ServeResult
    {
        $rpcParams = array_merge($this->request->all(), ['rpc_identifier' =>  Crypt::encrypt(Auth::user())]);

        try {
            $response = (new Client())->request(
                strtoupper($this->request->method()),
                $this->routeUrl,
                strtoupper($this->request->method()) == HttpRpcServerConfig::GET_REQUEST_METHOD?
                    [
                        'query' => $rpcParams
                    ]:
                    [
                        'form_params' => $rpcParams,
                    ]
            );
        } catch (\Exception $e) {
            echo $e;
        }

        return ServeResult::make([], $response->getBody()->getContents());
    }
}
