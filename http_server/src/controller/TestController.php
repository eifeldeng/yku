<?php
class TestController extends Controller {
    public function actionTest() {
        $response = $this->argv ['response'];
        $res = (yield $this->test ());
        $response->end ( print_r ( $res, true ) );
        // 请求调用结束
    }
    private function test() {
        $test = new TestModel ();
        $res = (yield $test->muticallTest ());
        yield $res;
    }
}
