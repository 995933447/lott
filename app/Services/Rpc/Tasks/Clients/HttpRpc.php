<?php
namespace App\Services\Rpc\Tasks\Clients;

use App\Services\Rpc\Tasks\Clients\HttpRpcServerConfig;
use App\Services\ServeResult;
use App\Services\TaskServiceContract;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Utils\Encryptor\Encryptor;

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
            foreach ($uriReplaces as $uriReplace) {
                $pos = strpos($this->routeUrl, HttpRpcServerConfig::URI_PARAM_REPLACEMENT);
                if ($pos === false) {
                    break;
                }
                $this->routeUrl = substr_replace($this->routeUrl, $uriReplace, $pos, strlen(HttpRpcServerConfig::URI_PARAM_REPLACEMENT));
            }
    }

    public function run(): ServeResult
    {
        $rpcParams = array_merge($this->request->all(), ['rpc_identifier' =>  Encryptor::serializeToEncrypt(Auth::user())]);
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

        return ServeResult::make([], $response->getBody()->getContents());
    }
}
