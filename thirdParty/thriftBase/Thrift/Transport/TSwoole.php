<?php

namespace Thrift\Transport;

use Thrift\Transport\TTransport;
use Thrift\Exception\TException;
use Thrift\Exception\TTransportException;
use Thrift\Factory\TStringFuncFactory;

class TSwoole extends TTransport {
    
    /**
     * Handle to PHP socket
     *
     * @var resource
     */
    private $handle_ = null;
    
    /**
     * Remote hostname
     *
     * @var string
     */
    protected $host_ = 'localhost';
    
    /**
     * Remote port
     *
     * @var int
     */
    protected $port_ = '9090';
    
    /**
     * Send timeout in seconds.
     *
     * Combined with sendTimeoutUsec this is used for send timeouts.
     *
     * @var int
     */
    private $sendTimeoutSec_ = 0;
    
    /**
     * Recv timeout in seconds
     *
     * Combined with recvTimeoutUsec this is used for recv timeouts.
     *
     * @var int
     */
    private $recvTimeoutSec_ = 0;
    
    /**
     * Debugging on?
     *
     * @var bool
     */
    protected $debug_ = FALSE;
    
    /**
     * Debug handler
     *
     * @var mixed
     */
    protected $debugHandler_ = null;
    private $seqid_ = 0;
    
    /**
     * Socket constructor
     *
     * @param string $host
     *            Remote hostname
     * @param int $port
     *            Remote port
     * @param bool $persist
     *            Whether to use a persistent socket
     * @param string $debugHandler
     *            Function to call for error logging
     */
    public function __construct($host = 'localhost', $port = 9090, $persist = FALSE, $debugHandler = null) {
        $this->host_ = $host;
        $this->port_ = $port;
        $this->persist_ = $persist;
        $this->debugHandler_ = $debugHandler ? $debugHandler : 'error_log';
    }
    
    /**
     *
     * @param resource $handle            
     * @return void
     */
    public function setHandle($handle) {
        $this->handle_ = $handle;
    }
    
    /**
     * Sets the send timeout.
     *
     * @param int $timeout
     *            Timeout in milliseconds.
     */
    public function setSendTimeout($timeout) {
        $this->sendTimeoutSec_ = floor ( $timeout / 1000 );
    }
    
    /**
     * Sets the receive timeout.
     *
     * @param int $timeout
     *            Timeout in milliseconds.
     */
    public function setRecvTimeout($timeout) {
        $this->recvTimeoutSec_ = floor ( $timeout / 1000 );
    }
    
    /**
     * Sets debugging output on or off
     *
     * @param bool $debug            
     */
    public function setDebug($debug) {
        $this->debug_ = $debug;
    }
    
    /**
     * Get the host that this socket is connected to
     *
     * @return string host
     */
    public function getHost() {
        return $this->host_;
    }
    
    /**
     * Get the remote port that this socket is connected to
     *
     * @return int port
     */
    public function getPort() {
        return is_resource ( $this->handle_ );
    }
    
    /**
     * Tests whether this is open
     *
     * @return bool true if the socket is open
     */
    public function isOpen() {
        return FALSE;
    }
    
    /**
     * Connects the socket.
     */
    public function open() {
        if ($this->isOpen ()) {
            throw new TTransportException ( 'swoole_client already connected', TTransportException::ALREADY_OPEN );
        }
        
        if (empty ( $this->host_ )) {
            throw new TTransportException ( 'Cannot open null host', TTransportException::NOT_OPEN );
        }
        
        if ($this->port_ <= 0) {
            throw new TTransportException ( 'Cannot open without port', TTransportException::NOT_OPEN );
        }
        
        $this->handle_ = new \Swoole\Coroutine\Client ( SWOOLE_SOCK_TCP );
        $this->handle_->connect ( $this->host_, $this->port_, 1 );
        $termproperty = new \xyz\msgcentersvr\TerminalProperty ();
        $termproperty->op_term = 4;
        $termproperty->uid = 378683036;
        $args = new \xyz\msgcentersvr\msgcentersvr_delete_onemsg_args ();
        $args->termproperty = $termproperty;
        $args->msgid = 123;
        
        $writeTransport = new \Thrift\Transport\TMemoryBuffer ();
        $protocol = new \Thrift\Protocol\TBinaryProtocol ( $writeTransport );
        $protocol->writeMessageBegin ( 'delete_onemsg', \Thrift\Type\TMessageType::CALL, $this->seqid_ );
        $args->write ( $protocol );
        $protocol->writeMessageEnd ();
        $protocol->getTransport ()->flush ();
        $buf = $writeTransport->getBuffer ();
        $len = strlen ( $buf );
        $lenbuf = pack ( 'N', $len );
        $data = $lenbuf . $buf;
        $this->handle_->send ( $data );
        
        $value = $this->handle_->recv ();
        
        $rseqid = 0;
        $fname = null;
        $mtype = 0;
        $head = "";
        $transport = new \Thrift\Transport\TMemoryBuffer ();
        $transport->write ( $value );
        $protocol = new \Thrift\Protocol\TBinaryProtocolAccelerated ( $transport );
        $result = new \xyz\msgcentersvr\msgcentersvr_delete_onemsg_result ();
        $protocol->readI32 ( $head );
        $protocol->readMessageBegin ( $fname, $mtype, $rseqid );
        $result->read ( $protocol );
        $protocol->readMessageEnd ();
        var_dump ( $result );
        
        $this->handle_->close ();
        exit ();
    }
    
    /**
     * Closes the socket.
     */
    public function close() {
    }
    
    /**
     * Read from the socket at most $len bytes.
     *
     * This method will not wait for all the requested data, it will return as
     * soon as any data is received.
     *
     * @param int $len
     *            Maximum number of bytes to read.
     * @return string Binary data
     */
    public function read($len) {
        $null = null;
        $read = array (
                $this->handle_ 
        );
        $data = $this->handle_->recv ();
        if ($data === false) {
            throw new TTransportException ( 'swoole_client: Could not read ' . $len . ' bytes from ' . $this->host_ . ':' . $this->port_ );
        } elseif ($data == '' && feof ( $this->handle_ )) {
            throw new TTransportException ( 'swoole_client read 0 bytes' );
        }
        
        return $data;
    }
    
    /**
     * Write to the socket.
     *
     * @param string $buf
     *            The data to write
     */
    public function write($buf) {
        $null = null;
        $write = array (
                $this->handle_ 
        );
        while ( TStringFuncFactory::create ()->strlen ( $buf ) > 0 ) {
            echo 1;
            exit ();
            $this->handle_->send ( $buf );
        }
    }
    
    /**
     * Flush output to the socket.
     *
     * Since read(), readAll() and write() operate on the sockets directly,
     * this is a no-op
     *
     * If you wish to have flushable buffering behaviour, wrap this TSocket
     * in a TBufferedTransport.
     */
    public function flush() {
        // no-op
    }
}
