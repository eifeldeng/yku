<?php

/**
 * @Author: winterswang
 * @Date:   2015-06-24 14:40:09
 * @Last Modified by:   wangguangchao
 * @Last Modified time: 2015-07-15 15:25:11
 */
namespace YkuServer\Coroutine;

class Task {
    protected $callbackData;
    protected $taskId;
    protected $corStack;
    protected $coroutine;
    protected $exception = null;
    
    /**
     * [__construct 构造函数，生成器+taskId, taskId由 scheduler管理]
     *
     * @param Generator $coroutine
     *            [description]
     * @param [type] $task
     *            [description]
     */
    public function __construct($taskId, \Generator $coroutine) {
        $this->taskId = $taskId;
        $this->coroutine = $coroutine;
        $this->corStack = new \SplStack ();
    }
    
    /**
     * [getTaskId 获取task id]
     *
     * @return [type] [description]
     */
    public function getTaskId() {
        return $this->taskId;
    }
    
    /**
     * [setException 设置异常处理]
     *
     * @param [type] $exception
     *            [description]
     */
    public function setException($exception) {
        $this->exception = $exception;
    }
    
    /**
     * [run 协程调度]
     *
     * @param Generator $gen
     *            [description]
     * @return [type] [description]
     */
    public function run(\Generator $gen) {
        while ( true ) {
            try {
                
                /*
                 * 异常处理
                 */
                if ($this->exception) {
                    
                    $gen->throw ( $this->exception );
                    $this->exception = null;
                    continue;
                }
                
                $value = $gen->current ();
                
                /*
                 * 中断内嵌 继续入栈
                 */
                if ($value instanceof \Generator) {
                    
                    $this->corStack->push ( $gen );
                    $gen = $value;
                    continue;
                }
                
                if (is_null ( $value ) && ! $this->corStack->isEmpty ()) {
                    
                    $gen = $this->corStack->pop ();
                    $gen->send ( $this->callbackData );
                    continue;
                }
                
                if ($value instanceof YkuServer\Coroutine\RetVal) {
                    
                    // end yeild
                    return false;
                }
                
                /*
                 * 中断内容为异步IO 发包 返回
                 */
                if (is_subclass_of ( $value, 'YkuServer\Client\Base' )) {
                    
                    // async send push gen to stack
                    $this->corStack->push ( $gen );
                    $value->send ( array (
                            $this,
                            'callback' 
                    ) );
                    return;
                }
                
                /*
                 * 出栈，回射数据
                 */
                if ($this->corStack->isEmpty ()) {
                    return;
                }
                $gen = $this->corStack->pop ();
                $gen->send ( $value );
            } catch ( \Exception $e ) {
                
                if ($this->corStack->isEmpty ()) {
                    
                    /*
                     * throw the exception
                     */
                    \SysLog::error ( __METHOD__ . " exception ===" . $e->getMessage (), __CLASS__ );
                    return;
                }
            }
        }
    }
    
    /**
     * [callback description]
     *
     * @param [type] $r
     *            [description]
     * @param [type] $key
     *            [description]
     * @param [type] $calltime
     *            [description]
     * @param [type] $res
     *            [description]
     * @return function [description]
     */
    public function callback($r, $key, $calltime, $res) {
        if (! $this->corStack->isEmpty ()) {
            $gen = $this->corStack->pop ();
            $this->callbackData = array (
                    'r' => $r,
                    'calltime' => $calltime,
                    'data' => $res 
            );
            
            $value = $gen->send ( $this->callbackData );
            
            $this->run ( $gen );
        }
    }
    
    /**
     * [isFinished 判断该task是否完成]
     *
     * @return boolean [description]
     */
    public function isFinished() {
        return ! $this->coroutine->valid ();
    }
    public function getCoroutine() {
        return $this->coroutine;
    }
}
