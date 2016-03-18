<?php
class PushController extends Controller {
    public function actionTest() {
        $response = $this->argv ['response'];
        $res = array (
                0,
                234234 
        );
        $response->end ( print_r ( $res, true ) );
        // 请求调用结束
    }
}
