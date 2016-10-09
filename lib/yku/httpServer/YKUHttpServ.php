<?php
class YKUHttpServ extends YkuServer\Network\Protocol\BaseServer {
    public $server;
    public function onRequest($request, $response) {
        // 统一进行路由和数据的预处理
        $req = HttpHelper::httpReqHandle ( $request );
        if ($req ['r'] === HttpHelper::HTTP_ERROR_URI) {
            $response->status ( 404 );
            $response->end ( "not found" );
            return;
        }
        
        $class = ucfirst ( $req ['route'] ['controller'] . 'Controller' );
        $fun = 'action_' . $req ['route'] ['action'];
        // 判断类是否存在
        if (! class_exists ( $class ) || ! method_exists ( ($class), ($fun) )) {
            $response->status ( 404 );
            $response->end ( "uri not found!" );
            return;
        }
        
        $obj = new $class ( $this->server, array (
                'request' => $req ['request'],
                'response' => $response 
        ), $request->fd );
        $request->scheduler->newTask ( $obj->$fun () );
        $request->scheduler->run ();
    }
    
    /**
     *
     * {@inheritDoc}
     *
     * @see \YkuServer\Network\Protocol\BaseServer::onStart()
     */
    public function onStart($server, $workerId) {
        $scheduler = new \YkuServer\Coroutine\Scheduler ();
        $server->scheduler = $scheduler;
    }
}
