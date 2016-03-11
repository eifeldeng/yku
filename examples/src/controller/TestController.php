<?php
class TestController extends Controller {
    public function actionTest() {
        SysLog::info ( __METHOD__, __CLASS__ );
        $response = $this->argv ['response'];
        $res = (yield $this->test ());
        SysLog::debug ( __METHOD__ . " res  == " . print_r ( $res, true ), __CLASS__ );
        $response->end ( " test response " );
        yield Swoole\Coroutine\SysCall::end ( 'test for syscall end' );
    }
    private function test() {
        $test = new TestModel ();
        $res = (yield $test->udpTest ());
        SysLog::info ( __METHOD__ . " res == " . print_r ( $res, true ), __CLASS__ );
        if ($res ['r'] == 0) {
            
            // yield success
            SysLog::info ( __METHOD__ . " yield success data == " . print_r ( $res ['data'], true ), __CLASS__ );
            yield $res;
        } else {
            
            // yield failed
            SysLog::error ( __METHOD__ . " yield failed res == " . print_r ( $res, true ), __CLASS__ );
            yield array (
                    'r' => 1,
                    'error_msg' => 'yield failed' 
            );
        }
    }
}
