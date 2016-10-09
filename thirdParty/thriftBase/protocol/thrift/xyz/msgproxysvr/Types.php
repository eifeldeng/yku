<?php
namespace xyz\msgproxysvr;

/**
 * Autogenerated by Thrift Compiler (0.9.1)
 *
 * DO NOT EDIT UNLESS YOU ARE SURE THAT YOU KNOW WHAT YOU ARE DOING
 *  @generated
 */
use Thrift\Base\TBase;
use Thrift\Type\TType;
use Thrift\Type\TMessageType;
use Thrift\Exception\TException;
use Thrift\Exception\TProtocolException;
use Thrift\Protocol\TProtocol;
use Thrift\Protocol\TBinaryProtocolAccelerated;
use Thrift\Exception\TApplicationException;


final class EM_ERRCODE {
  const EM_ERR_SUCCESS = 0;
  const EM_ERROR_HTTP_ERROR = -600;
  const EM_ERR_UPOLICY_LMT = -601;
  const EM_ERR_MTYPE_POLICY_LMT = -602;
  const EM_ERR_CH_POLICY_LMT = -603;
  const EM_ERR_WQ = -604;
  const EM_ERR_SNDCH = -605;
  const EM_ERR_CONTENT_TOOLONG = -606;
  const EM_ERR_CTYPE_NMATCH = -607;
  const EM_ERR_NEXIST_OFIELD = -608;
  const EM_ERR_NEXIST_MTYPE = -609;
  const EM_ERR_MTYPE_DISABLE = -610;
  const EM_ERR_READ_CACHE = -611;
  const EM_ERR_READ_DB = -612;
  const EM_ERR_LACK_NECEPARAM = -701;
  const EM_ERR_PARAM_FORMAT = -702;
  const EM_ERR_PARAM_CHECK = -703;
  static public $__names = array(
    0 => 'EM_ERR_SUCCESS',
    -600 => 'EM_ERROR_HTTP_ERROR',
    -601 => 'EM_ERR_UPOLICY_LMT',
    -602 => 'EM_ERR_MTYPE_POLICY_LMT',
    -603 => 'EM_ERR_CH_POLICY_LMT',
    -604 => 'EM_ERR_WQ',
    -605 => 'EM_ERR_SNDCH',
    -606 => 'EM_ERR_CONTENT_TOOLONG',
    -607 => 'EM_ERR_CTYPE_NMATCH',
    -608 => 'EM_ERR_NEXIST_OFIELD',
    -609 => 'EM_ERR_NEXIST_MTYPE',
    -610 => 'EM_ERR_MTYPE_DISABLE',
    -611 => 'EM_ERR_READ_CACHE',
    -612 => 'EM_ERR_READ_DB',
    -701 => 'EM_ERR_LACK_NECEPARAM',
    -702 => 'EM_ERR_PARAM_FORMAT',
    -703 => 'EM_ERR_PARAM_CHECK',
  );
}

final class EM_TYPEID {
  const EM_FAVORITE_UPDATE = 5;
  const EM_MAIL_NEW = 6;
  const EM_VEDIO_REVIEW = 7;
  const EM_VEDIO_REVIEW_COMMENT = 8;
  const EM_VEDIO_REVIEW_COMMENT_AT = 9;
  const EM_ATME_NOTE = 11;
  const EM_ATME_ZONE = 22;
  const EM_NEW_FANS = 12;
  const EM_VIP_COMSUME = 37;
  const EM_VIP_COUPONS = 38;
  static public $__names = array(
    5 => 'EM_FAVORITE_UPDATE',
    6 => 'EM_MAIL_NEW',
    7 => 'EM_VEDIO_REVIEW',
    8 => 'EM_VEDIO_REVIEW_COMMENT',
    9 => 'EM_VEDIO_REVIEW_COMMENT_AT',
    11 => 'EM_ATME_NOTE',
    22 => 'EM_ATME_ZONE',
    12 => 'EM_NEW_FANS',
    37 => 'EM_VIP_COMSUME',
    38 => 'EM_VIP_COUPONS',
  );
}

final class EM_CATGRY {
  const EM_SUBSCRIBE = 1;
  const EM_COLLECT = 2;
  const EM_PRIVATE_NEW = 3;
  const EM_COMMENT = 4;
  const EM_NOTE_ME = 5;
  const EM_NEW_FANS = 6;
  const EM_SYS_NOTIFY = 7;
  const EM_TASK_AWARD = 8;
  const EM_TASK_NOTIFY = 9;
  static public $__names = array(
    1 => 'EM_SUBSCRIBE',
    2 => 'EM_COLLECT',
    3 => 'EM_PRIVATE_NEW',
    4 => 'EM_COMMENT',
    5 => 'EM_NOTE_ME',
    6 => 'EM_NEW_FANS',
    7 => 'EM_SYS_NOTIFY',
    8 => 'EM_TASK_AWARD',
    9 => 'EM_TASK_NOTIFY',
  );
}

class MsgDataUMC {
  static $_TSPEC;

  public $mid = null;
  public $msgtype_id = null;
  public $content = null;
  public $fields = null;
  public $msgcount = null;

  public function __construct($vals=null) {
    if (!isset(self::$_TSPEC)) {
      self::$_TSPEC = array(
        1 => array(
          'var' => 'mid',
          'type' => TType::STRING,
          ),
        2 => array(
          'var' => 'msgtype_id',
          'type' => TType::I32,
          ),
        3 => array(
          'var' => 'content',
          'type' => TType::STRING,
          ),
        4 => array(
          'var' => 'fields',
          'type' => TType::STRING,
          ),
        5 => array(
          'var' => 'msgcount',
          'type' => TType::I32,
          ),
        );
    }
    if (is_array($vals)) {
      if (isset($vals['mid'])) {
        $this->mid = $vals['mid'];
      }
      if (isset($vals['msgtype_id'])) {
        $this->msgtype_id = $vals['msgtype_id'];
      }
      if (isset($vals['content'])) {
        $this->content = $vals['content'];
      }
      if (isset($vals['fields'])) {
        $this->fields = $vals['fields'];
      }
      if (isset($vals['msgcount'])) {
        $this->msgcount = $vals['msgcount'];
      }
    }
  }

  public function getName() {
    return 'MsgDataUMC';
  }

  public function read($input)
  {
    $xfer = 0;
    $fname = null;
    $ftype = 0;
    $fid = 0;
    $xfer += $input->readStructBegin($fname);
    while (true)
    {
      $xfer += $input->readFieldBegin($fname, $ftype, $fid);
      if ($ftype == TType::STOP) {
        break;
      }
      switch ($fid)
      {
        case 1:
          if ($ftype == TType::STRING) {
            $xfer += $input->readString($this->mid);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 2:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->msgtype_id);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 3:
          if ($ftype == TType::STRING) {
            $xfer += $input->readString($this->content);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 4:
          if ($ftype == TType::STRING) {
            $xfer += $input->readString($this->fields);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 5:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->msgcount);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        default:
          $xfer += $input->skip($ftype);
          break;
      }
      $xfer += $input->readFieldEnd();
    }
    $xfer += $input->readStructEnd();
    return $xfer;
  }

  public function write($output) {
    $xfer = 0;
    $xfer += $output->writeStructBegin('MsgDataUMC');
    if ($this->mid !== null) {
      $xfer += $output->writeFieldBegin('mid', TType::STRING, 1);
      $xfer += $output->writeString($this->mid);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->msgtype_id !== null) {
      $xfer += $output->writeFieldBegin('msgtype_id', TType::I32, 2);
      $xfer += $output->writeI32($this->msgtype_id);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->content !== null) {
      $xfer += $output->writeFieldBegin('content', TType::STRING, 3);
      $xfer += $output->writeString($this->content);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->fields !== null) {
      $xfer += $output->writeFieldBegin('fields', TType::STRING, 4);
      $xfer += $output->writeString($this->fields);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->msgcount !== null) {
      $xfer += $output->writeFieldBegin('msgcount', TType::I32, 5);
      $xfer += $output->writeI32($this->msgcount);
      $xfer += $output->writeFieldEnd();
    }
    $xfer += $output->writeFieldStop();
    $xfer += $output->writeStructEnd();
    return $xfer;
  }

}

class retmsg {
  static $_TSPEC;

  public $ret = null;
  public $msg = null;
  public $msglist = null;

  public function __construct($vals=null) {
    if (!isset(self::$_TSPEC)) {
      self::$_TSPEC = array(
        1 => array(
          'var' => 'ret',
          'type' => TType::I32,
          ),
        2 => array(
          'var' => 'msg',
          'type' => TType::STRING,
          ),
        3 => array(
          'var' => 'msglist',
          'type' => TType::LST,
          'etype' => TType::STRUCT,
          'elem' => array(
            'type' => TType::STRUCT,
            'class' => '\xyz\msgproxysvr\MsgDataUMC',
            ),
          ),
        );
    }
    if (is_array($vals)) {
      if (isset($vals['ret'])) {
        $this->ret = $vals['ret'];
      }
      if (isset($vals['msg'])) {
        $this->msg = $vals['msg'];
      }
      if (isset($vals['msglist'])) {
        $this->msglist = $vals['msglist'];
      }
    }
  }

  public function getName() {
    return 'retmsg';
  }

  public function read($input)
  {
    $xfer = 0;
    $fname = null;
    $ftype = 0;
    $fid = 0;
    $xfer += $input->readStructBegin($fname);
    while (true)
    {
      $xfer += $input->readFieldBegin($fname, $ftype, $fid);
      if ($ftype == TType::STOP) {
        break;
      }
      switch ($fid)
      {
        case 1:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->ret);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 2:
          if ($ftype == TType::STRING) {
            $xfer += $input->readString($this->msg);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 3:
          if ($ftype == TType::LST) {
            $this->msglist = array();
            $_size0 = 0;
            $_etype3 = 0;
            $xfer += $input->readListBegin($_etype3, $_size0);
            for ($_i4 = 0; $_i4 < $_size0; ++$_i4)
            {
              $elem5 = null;
              $elem5 = new \xyz\msgproxysvr\MsgDataUMC();
              $xfer += $elem5->read($input);
              $this->msglist []= $elem5;
            }
            $xfer += $input->readListEnd();
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        default:
          $xfer += $input->skip($ftype);
          break;
      }
      $xfer += $input->readFieldEnd();
    }
    $xfer += $input->readStructEnd();
    return $xfer;
  }

  public function write($output) {
    $xfer = 0;
    $xfer += $output->writeStructBegin('retmsg');
    if ($this->ret !== null) {
      $xfer += $output->writeFieldBegin('ret', TType::I32, 1);
      $xfer += $output->writeI32($this->ret);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->msg !== null) {
      $xfer += $output->writeFieldBegin('msg', TType::STRING, 2);
      $xfer += $output->writeString($this->msg);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->msglist !== null) {
      if (!is_array($this->msglist)) {
        throw new TProtocolException('Bad type in structure.', TProtocolException::INVALID_DATA);
      }
      $xfer += $output->writeFieldBegin('msglist', TType::LST, 3);
      {
        $output->writeListBegin(TType::STRUCT, count($this->msglist));
        {
          foreach ($this->msglist as $iter6)
          {
            $xfer += $iter6->write($output);
          }
        }
        $output->writeListEnd();
      }
      $xfer += $output->writeFieldEnd();
    }
    $xfer += $output->writeFieldStop();
    $xfer += $output->writeStructEnd();
    return $xfer;
  }

}

class retreadcmf {
  static $_TSPEC;

  public $ret = null;
  public $msg = null;

  public function __construct($vals=null) {
    if (!isset(self::$_TSPEC)) {
      self::$_TSPEC = array(
        1 => array(
          'var' => 'ret',
          'type' => TType::I32,
          ),
        2 => array(
          'var' => 'msg',
          'type' => TType::STRING,
          ),
        );
    }
    if (is_array($vals)) {
      if (isset($vals['ret'])) {
        $this->ret = $vals['ret'];
      }
      if (isset($vals['msg'])) {
        $this->msg = $vals['msg'];
      }
    }
  }

  public function getName() {
    return 'retreadcmf';
  }

  public function read($input)
  {
    $xfer = 0;
    $fname = null;
    $ftype = 0;
    $fid = 0;
    $xfer += $input->readStructBegin($fname);
    while (true)
    {
      $xfer += $input->readFieldBegin($fname, $ftype, $fid);
      if ($ftype == TType::STOP) {
        break;
      }
      switch ($fid)
      {
        case 1:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->ret);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 2:
          if ($ftype == TType::STRING) {
            $xfer += $input->readString($this->msg);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        default:
          $xfer += $input->skip($ftype);
          break;
      }
      $xfer += $input->readFieldEnd();
    }
    $xfer += $input->readStructEnd();
    return $xfer;
  }

  public function write($output) {
    $xfer = 0;
    $xfer += $output->writeStructBegin('retreadcmf');
    if ($this->ret !== null) {
      $xfer += $output->writeFieldBegin('ret', TType::I32, 1);
      $xfer += $output->writeI32($this->ret);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->msg !== null) {
      $xfer += $output->writeFieldBegin('msg', TType::STRING, 2);
      $xfer += $output->writeString($this->msg);
      $xfer += $output->writeFieldEnd();
    }
    $xfer += $output->writeFieldStop();
    $xfer += $output->writeStructEnd();
    return $xfer;
  }

}


