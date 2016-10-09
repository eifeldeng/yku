<?php
namespace xyz\shared;

/**
 * Autogenerated by Thrift Compiler (0.9.2)
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


final class EM_MSG_STATUS {
  const EM_CREATED = 0;
  const EM_POPED = 1;
  const EM_READED = 2;
  const EM_CANCELED = 3;
  const EM_DELETED = 4;
  static public $__names = array(
    0 => 'EM_CREATED',
    1 => 'EM_POPED',
    2 => 'EM_READED',
    3 => 'EM_CANCELED',
    4 => 'EM_DELETED',
  );
}

final class EM_MSG_DISPLAY {
  const EM_MSG_DISPLAY_STRONG = 0;
  const EM_MSG_DISPLAY_NORMAL = 1;
  const EM_MSG_DISPLAY_WEAK = 2;
  static public $__names = array(
    0 => 'EM_MSG_DISPLAY_STRONG',
    1 => 'EM_MSG_DISPLAY_NORMAL',
    2 => 'EM_MSG_DISPLAY_WEAK',
  );
}

final class EM_JUMP_TYPE {
  const EM_JUMPTO_NONE = 0;
  const EM_JUMPTO_BUYVIP = 1;
  const EM_JUMPTO_H5 = 2;
  const EM_JUMPTO_VEDIO = 3;
  const EM_JUMPTO_SHOW = 4;
  static public $__names = array(
    0 => 'EM_JUMPTO_NONE',
    1 => 'EM_JUMPTO_BUYVIP',
    2 => 'EM_JUMPTO_H5',
    3 => 'EM_JUMPTO_VEDIO',
    4 => 'EM_JUMPTO_SHOW',
  );
}

final class EM_POPUP_TYPE {
  const EM_POPUP = 1;
  const EM_NOTPOPUP = 2;
  static public $__names = array(
    1 => 'EM_POPUP',
    2 => 'EM_NOTPOPUP',
  );
}

final class EM_MSG_MODE {
  const EM_SINGLE_CREATED = 1;
  const EM_SINGLE_NODISPATCH = 2;
  const EM_SINGLE_DELETE = 3;
  const EM_SINGLE_DISPATCHING = 4;
  const EM_SINGLE_FINISHED = 5;
  const EM_GROUP_CREATED = 10;
  const EM_GROUP_NODISPATCH = 11;
  const EM_GROUP_DELETE = 12;
  static public $__names = array(
    1 => 'EM_SINGLE_CREATED',
    2 => 'EM_SINGLE_NODISPATCH',
    3 => 'EM_SINGLE_DELETE',
    4 => 'EM_SINGLE_DISPATCHING',
    5 => 'EM_SINGLE_FINISHED',
    10 => 'EM_GROUP_CREATED',
    11 => 'EM_GROUP_NODISPATCH',
    12 => 'EM_GROUP_DELETE',
  );
}

final class EM_TERM {
  const EM_ALL = 0;
  const EM_ONCE = 1;
  const EM_ANDROID = 2;
  const EM_IOS = 4;
  const EM_WIN = 8;
  const EM_WEB = 16;
  const EM_PC = 32;
  static public $__names = array(
    0 => 'EM_ALL',
    1 => 'EM_ONCE',
    2 => 'EM_ANDROID',
    4 => 'EM_IOS',
    8 => 'EM_WIN',
    16 => 'EM_WEB',
    32 => 'EM_PC',
  );
}

final class EM_TYPE {
  const EM_SYSTEM_PROCLAMATION = 2001;
  const EM_SYSTEM_EVENT = 2002;
  const EM_SINGLE = 0;
  const EM_GROUP = 1;
  const EM_DBCONF_NOTIFY = 2;
  const EM_DBCONF_FEE = 3;
  const EM_MOVIE_TICKET = 1000;
  const EM_GRADE = 1001;
  const EM_SELFCHANNEL_INCOME = 1002;
  const EM_SELFCHANNEL_CETIFICATION = 1003;
  const EM_VIDEO_PUBLISH = 1004;
  const EM_VEDIO_REVIEW = 1005;
  const EM_CHAT = 1006;
  const EM_HCHAT = 1007;
  const EM_BKBL = 1008;
  const EM_FUNC_SUBSCRIPTION = 3001;
  const EM_FUNC_ICHANNEL = 3002;
  const EM_FUNC_MAKETING = 3003;
  const EM_FUNC_IDENTIFY = 3004;
  const EM_USER_ACCOUNT = 3011;
  const EM_USER_ASSET = 3012;
  const EM_USER_VIDEO_VERIFY = 3101;
  const EM_USER_ICHANNEL_PROFIT = 3201;
  const EM_BUSINESS_ACTIVITY = 4001;
  const EM_BUSINESS_LIVE = 4002;
  const EM_BUSINESS_HELLO = 4003;
  static public $__names = array(
    2001 => 'EM_SYSTEM_PROCLAMATION',
    2002 => 'EM_SYSTEM_EVENT',
    0 => 'EM_SINGLE',
    1 => 'EM_GROUP',
    2 => 'EM_DBCONF_NOTIFY',
    3 => 'EM_DBCONF_FEE',
    1000 => 'EM_MOVIE_TICKET',
    1001 => 'EM_GRADE',
    1002 => 'EM_SELFCHANNEL_INCOME',
    1003 => 'EM_SELFCHANNEL_CETIFICATION',
    1004 => 'EM_VIDEO_PUBLISH',
    1005 => 'EM_VEDIO_REVIEW',
    1006 => 'EM_CHAT',
    1007 => 'EM_HCHAT',
    1008 => 'EM_BKBL',
    3001 => 'EM_FUNC_SUBSCRIPTION',
    3002 => 'EM_FUNC_ICHANNEL',
    3003 => 'EM_FUNC_MAKETING',
    3004 => 'EM_FUNC_IDENTIFY',
    3011 => 'EM_USER_ACCOUNT',
    3012 => 'EM_USER_ASSET',
    3101 => 'EM_USER_VIDEO_VERIFY',
    3201 => 'EM_USER_ICHANNEL_PROFIT',
    4001 => 'EM_BUSINESS_ACTIVITY',
    4002 => 'EM_BUSINESS_LIVE',
    4003 => 'EM_BUSINESS_HELLO',
  );
}

final class EM_USER_TYPE {
  const EM_NOT_LOGIN = 0;
  const EM_NO_VIP = 1;
  const EM_LVIP = 2;
  const EM_YEARLVIP = 4;
  const EM_VIP = 8;
  const EM_YEARVIP = 16;
  const EM_ALL = 32;
  const EM_EXPIRED_VIP = 64;
  static public $__names = array(
    0 => 'EM_NOT_LOGIN',
    1 => 'EM_NO_VIP',
    2 => 'EM_LVIP',
    4 => 'EM_YEARLVIP',
    8 => 'EM_VIP',
    16 => 'EM_YEARVIP',
    32 => 'EM_ALL',
    64 => 'EM_EXPIRED_VIP',
  );
}

class Jump {
  static $_TSPEC;

  /**
   * @var int
   */
  public $jumptype = null;
  /**
   * @var string
   */
  public $iosdata = null;
  /**
   * @var string
   */
  public $anddata = null;

  public function __construct($vals=null) {
    if (!isset(self::$_TSPEC)) {
      self::$_TSPEC = array(
        1 => array(
          'var' => 'jumptype',
          'type' => TType::I32,
          ),
        2 => array(
          'var' => 'iosdata',
          'type' => TType::STRING,
          ),
        3 => array(
          'var' => 'anddata',
          'type' => TType::STRING,
          ),
        );
    }
    if (is_array($vals)) {
      if (isset($vals['jumptype'])) {
        $this->jumptype = $vals['jumptype'];
      }
      if (isset($vals['iosdata'])) {
        $this->iosdata = $vals['iosdata'];
      }
      if (isset($vals['anddata'])) {
        $this->anddata = $vals['anddata'];
      }
    }
  }

  public function getName() {
    return 'Jump';
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
            $xfer += $input->readI32($this->jumptype);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 2:
          if ($ftype == TType::STRING) {
            $xfer += $input->readString($this->iosdata);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 3:
          if ($ftype == TType::STRING) {
            $xfer += $input->readString($this->anddata);
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
    $xfer += $output->writeStructBegin('Jump');
    if ($this->jumptype !== null) {
      $xfer += $output->writeFieldBegin('jumptype', TType::I32, 1);
      $xfer += $output->writeI32($this->jumptype);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->iosdata !== null) {
      $xfer += $output->writeFieldBegin('iosdata', TType::STRING, 2);
      $xfer += $output->writeString($this->iosdata);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->anddata !== null) {
      $xfer += $output->writeFieldBegin('anddata', TType::STRING, 3);
      $xfer += $output->writeString($this->anddata);
      $xfer += $output->writeFieldEnd();
    }
    $xfer += $output->writeFieldStop();
    $xfer += $output->writeStructEnd();
    return $xfer;
  }

}

class SMsgStatus {
  static $_TSPEC;

  /**
   * @var int
   */
  public $msgid = null;
  /**
   * @var int
   */
  public $access_time = null;
  /**
   * @var int
   */
  public $status = null;
  /**
   * @var int
   */
  public $u_insert_time = null;
  /**
   * @var int
   */
  public $u_update_time = null;
  /**
   * @var int
   */
  public $msg_exp_time = null;
  /**
   * @var int
   */
  public $type = null;
  /**
   * @var int
   */
  public $popup = null;
  /**
   * @var int
   */
  public $term = null;

  public function __construct($vals=null) {
    if (!isset(self::$_TSPEC)) {
      self::$_TSPEC = array(
        1 => array(
          'var' => 'msgid',
          'type' => TType::I64,
          ),
        2 => array(
          'var' => 'access_time',
          'type' => TType::I32,
          ),
        3 => array(
          'var' => 'status',
          'type' => TType::I32,
          ),
        5 => array(
          'var' => 'u_insert_time',
          'type' => TType::I32,
          ),
        6 => array(
          'var' => 'u_update_time',
          'type' => TType::I32,
          ),
        7 => array(
          'var' => 'msg_exp_time',
          'type' => TType::I32,
          ),
        8 => array(
          'var' => 'type',
          'type' => TType::I32,
          ),
        9 => array(
          'var' => 'popup',
          'type' => TType::I32,
          ),
        10 => array(
          'var' => 'term',
          'type' => TType::I32,
          ),
        );
    }
    if (is_array($vals)) {
      if (isset($vals['msgid'])) {
        $this->msgid = $vals['msgid'];
      }
      if (isset($vals['access_time'])) {
        $this->access_time = $vals['access_time'];
      }
      if (isset($vals['status'])) {
        $this->status = $vals['status'];
      }
      if (isset($vals['u_insert_time'])) {
        $this->u_insert_time = $vals['u_insert_time'];
      }
      if (isset($vals['u_update_time'])) {
        $this->u_update_time = $vals['u_update_time'];
      }
      if (isset($vals['msg_exp_time'])) {
        $this->msg_exp_time = $vals['msg_exp_time'];
      }
      if (isset($vals['type'])) {
        $this->type = $vals['type'];
      }
      if (isset($vals['popup'])) {
        $this->popup = $vals['popup'];
      }
      if (isset($vals['term'])) {
        $this->term = $vals['term'];
      }
    }
  }

  public function getName() {
    return 'SMsgStatus';
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
          if ($ftype == TType::I64) {
            $xfer += $input->readI64($this->msgid);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 2:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->access_time);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 3:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->status);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 5:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->u_insert_time);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 6:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->u_update_time);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 7:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->msg_exp_time);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 8:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->type);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 9:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->popup);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 10:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->term);
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
    $xfer += $output->writeStructBegin('SMsgStatus');
    if ($this->msgid !== null) {
      $xfer += $output->writeFieldBegin('msgid', TType::I64, 1);
      $xfer += $output->writeI64($this->msgid);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->access_time !== null) {
      $xfer += $output->writeFieldBegin('access_time', TType::I32, 2);
      $xfer += $output->writeI32($this->access_time);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->status !== null) {
      $xfer += $output->writeFieldBegin('status', TType::I32, 3);
      $xfer += $output->writeI32($this->status);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->u_insert_time !== null) {
      $xfer += $output->writeFieldBegin('u_insert_time', TType::I32, 5);
      $xfer += $output->writeI32($this->u_insert_time);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->u_update_time !== null) {
      $xfer += $output->writeFieldBegin('u_update_time', TType::I32, 6);
      $xfer += $output->writeI32($this->u_update_time);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->msg_exp_time !== null) {
      $xfer += $output->writeFieldBegin('msg_exp_time', TType::I32, 7);
      $xfer += $output->writeI32($this->msg_exp_time);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->type !== null) {
      $xfer += $output->writeFieldBegin('type', TType::I32, 8);
      $xfer += $output->writeI32($this->type);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->popup !== null) {
      $xfer += $output->writeFieldBegin('popup', TType::I32, 9);
      $xfer += $output->writeI32($this->popup);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->term !== null) {
      $xfer += $output->writeFieldBegin('term', TType::I32, 10);
      $xfer += $output->writeI32($this->term);
      $xfer += $output->writeFieldEnd();
    }
    $xfer += $output->writeFieldStop();
    $xfer += $output->writeStructEnd();
    return $xfer;
  }

}

class MsgDataStorage {
  static $_TSPEC;

  /**
   * @var int
   */
  public $msgid = null;
  /**
   * @var int
   */
  public $appid = null;
  /**
   * @var int
   */
  public $fromuid = null;
  /**
   * @var int
   */
  public $type = null;
  /**
   * @var int
   */
  public $toid = null;
  /**
   * @var int
   */
  public $display = null;
  /**
   * @var int
   */
  public $term = null;
  /**
   * @var int
   */
  public $tplid = null;
  /**
   * @var string
   */
  public $content = null;
  /**
   * @var int
   */
  public $mode = null;
  /**
   * @var int
   */
  public $expire_time = null;
  /**
   * @var int
   */
  public $access_time = null;
  /**
   * @var int
   */
  public $create_time = null;
  /**
   * @var int
   */
  public $update_time = null;
  /**
   * @var string
   */
  public $desc = null;
  /**
   * @var int
   */
  public $fade = null;
  /**
   * @var int
   */
  public $popup = null;
  /**
   * @var \xyz\shared\Jump
   */
  public $jump = null;

  public function __construct($vals=null) {
    if (!isset(self::$_TSPEC)) {
      self::$_TSPEC = array(
        1 => array(
          'var' => 'msgid',
          'type' => TType::I64,
          ),
        2 => array(
          'var' => 'appid',
          'type' => TType::I32,
          ),
        3 => array(
          'var' => 'fromuid',
          'type' => TType::I64,
          ),
        4 => array(
          'var' => 'type',
          'type' => TType::I32,
          ),
        5 => array(
          'var' => 'toid',
          'type' => TType::I64,
          ),
        6 => array(
          'var' => 'display',
          'type' => TType::I32,
          ),
        7 => array(
          'var' => 'term',
          'type' => TType::I32,
          ),
        8 => array(
          'var' => 'tplid',
          'type' => TType::I32,
          ),
        9 => array(
          'var' => 'content',
          'type' => TType::STRING,
          ),
        10 => array(
          'var' => 'mode',
          'type' => TType::I32,
          ),
        11 => array(
          'var' => 'expire_time',
          'type' => TType::I32,
          ),
        12 => array(
          'var' => 'access_time',
          'type' => TType::I32,
          ),
        13 => array(
          'var' => 'create_time',
          'type' => TType::I32,
          ),
        14 => array(
          'var' => 'update_time',
          'type' => TType::I32,
          ),
        15 => array(
          'var' => 'desc',
          'type' => TType::STRING,
          ),
        16 => array(
          'var' => 'fade',
          'type' => TType::I32,
          ),
        17 => array(
          'var' => 'popup',
          'type' => TType::I32,
          ),
        18 => array(
          'var' => 'jump',
          'type' => TType::STRUCT,
          'class' => '\xyz\shared\Jump',
          ),
        );
    }
    if (is_array($vals)) {
      if (isset($vals['msgid'])) {
        $this->msgid = $vals['msgid'];
      }
      if (isset($vals['appid'])) {
        $this->appid = $vals['appid'];
      }
      if (isset($vals['fromuid'])) {
        $this->fromuid = $vals['fromuid'];
      }
      if (isset($vals['type'])) {
        $this->type = $vals['type'];
      }
      if (isset($vals['toid'])) {
        $this->toid = $vals['toid'];
      }
      if (isset($vals['display'])) {
        $this->display = $vals['display'];
      }
      if (isset($vals['term'])) {
        $this->term = $vals['term'];
      }
      if (isset($vals['tplid'])) {
        $this->tplid = $vals['tplid'];
      }
      if (isset($vals['content'])) {
        $this->content = $vals['content'];
      }
      if (isset($vals['mode'])) {
        $this->mode = $vals['mode'];
      }
      if (isset($vals['expire_time'])) {
        $this->expire_time = $vals['expire_time'];
      }
      if (isset($vals['access_time'])) {
        $this->access_time = $vals['access_time'];
      }
      if (isset($vals['create_time'])) {
        $this->create_time = $vals['create_time'];
      }
      if (isset($vals['update_time'])) {
        $this->update_time = $vals['update_time'];
      }
      if (isset($vals['desc'])) {
        $this->desc = $vals['desc'];
      }
      if (isset($vals['fade'])) {
        $this->fade = $vals['fade'];
      }
      if (isset($vals['popup'])) {
        $this->popup = $vals['popup'];
      }
      if (isset($vals['jump'])) {
        $this->jump = $vals['jump'];
      }
    }
  }

  public function getName() {
    return 'MsgDataStorage';
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
          if ($ftype == TType::I64) {
            $xfer += $input->readI64($this->msgid);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 2:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->appid);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 3:
          if ($ftype == TType::I64) {
            $xfer += $input->readI64($this->fromuid);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 4:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->type);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 5:
          if ($ftype == TType::I64) {
            $xfer += $input->readI64($this->toid);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 6:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->display);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 7:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->term);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 8:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->tplid);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 9:
          if ($ftype == TType::STRING) {
            $xfer += $input->readString($this->content);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 10:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->mode);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 11:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->expire_time);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 12:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->access_time);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 13:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->create_time);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 14:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->update_time);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 15:
          if ($ftype == TType::STRING) {
            $xfer += $input->readString($this->desc);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 16:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->fade);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 17:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->popup);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 18:
          if ($ftype == TType::STRUCT) {
            $this->jump = new \xyz\shared\Jump();
            $xfer += $this->jump->read($input);
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
    $xfer += $output->writeStructBegin('MsgDataStorage');
    if ($this->msgid !== null) {
      $xfer += $output->writeFieldBegin('msgid', TType::I64, 1);
      $xfer += $output->writeI64($this->msgid);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->appid !== null) {
      $xfer += $output->writeFieldBegin('appid', TType::I32, 2);
      $xfer += $output->writeI32($this->appid);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->fromuid !== null) {
      $xfer += $output->writeFieldBegin('fromuid', TType::I64, 3);
      $xfer += $output->writeI64($this->fromuid);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->type !== null) {
      $xfer += $output->writeFieldBegin('type', TType::I32, 4);
      $xfer += $output->writeI32($this->type);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->toid !== null) {
      $xfer += $output->writeFieldBegin('toid', TType::I64, 5);
      $xfer += $output->writeI64($this->toid);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->display !== null) {
      $xfer += $output->writeFieldBegin('display', TType::I32, 6);
      $xfer += $output->writeI32($this->display);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->term !== null) {
      $xfer += $output->writeFieldBegin('term', TType::I32, 7);
      $xfer += $output->writeI32($this->term);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->tplid !== null) {
      $xfer += $output->writeFieldBegin('tplid', TType::I32, 8);
      $xfer += $output->writeI32($this->tplid);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->content !== null) {
      $xfer += $output->writeFieldBegin('content', TType::STRING, 9);
      $xfer += $output->writeString($this->content);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->mode !== null) {
      $xfer += $output->writeFieldBegin('mode', TType::I32, 10);
      $xfer += $output->writeI32($this->mode);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->expire_time !== null) {
      $xfer += $output->writeFieldBegin('expire_time', TType::I32, 11);
      $xfer += $output->writeI32($this->expire_time);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->access_time !== null) {
      $xfer += $output->writeFieldBegin('access_time', TType::I32, 12);
      $xfer += $output->writeI32($this->access_time);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->create_time !== null) {
      $xfer += $output->writeFieldBegin('create_time', TType::I32, 13);
      $xfer += $output->writeI32($this->create_time);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->update_time !== null) {
      $xfer += $output->writeFieldBegin('update_time', TType::I32, 14);
      $xfer += $output->writeI32($this->update_time);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->desc !== null) {
      $xfer += $output->writeFieldBegin('desc', TType::STRING, 15);
      $xfer += $output->writeString($this->desc);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->fade !== null) {
      $xfer += $output->writeFieldBegin('fade', TType::I32, 16);
      $xfer += $output->writeI32($this->fade);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->popup !== null) {
      $xfer += $output->writeFieldBegin('popup', TType::I32, 17);
      $xfer += $output->writeI32($this->popup);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->jump !== null) {
      if (!is_object($this->jump)) {
        throw new TProtocolException('Bad type in structure.', TProtocolException::INVALID_DATA);
      }
      $xfer += $output->writeFieldBegin('jump', TType::STRUCT, 18);
      $xfer += $this->jump->write($output);
      $xfer += $output->writeFieldEnd();
    }
    $xfer += $output->writeFieldStop();
    $xfer += $output->writeStructEnd();
    return $xfer;
  }

}

class MsgStatusStorage {
  static $_TSPEC;

  /**
   * @var array
   */
  public $msginfolist = null;

  public function __construct($vals=null) {
    if (!isset(self::$_TSPEC)) {
      self::$_TSPEC = array(
        1 => array(
          'var' => 'msginfolist',
          'type' => TType::MAP,
          'ktype' => TType::I64,
          'vtype' => TType::STRUCT,
          'key' => array(
            'type' => TType::I64,
          ),
          'val' => array(
            'type' => TType::STRUCT,
            'class' => '\xyz\shared\SMsgStatus',
            ),
          ),
        );
    }
    if (is_array($vals)) {
      if (isset($vals['msginfolist'])) {
        $this->msginfolist = $vals['msginfolist'];
      }
    }
  }

  public function getName() {
    return 'MsgStatusStorage';
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
          if ($ftype == TType::MAP) {
            $this->msginfolist = array();
            $_size0 = 0;
            $_ktype1 = 0;
            $_vtype2 = 0;
            $xfer += $input->readMapBegin($_ktype1, $_vtype2, $_size0);
            for ($_i4 = 0; $_i4 < $_size0; ++$_i4)
            {
              $key5 = 0;
              $val6 = new \xyz\shared\SMsgStatus();
              $xfer += $input->readI64($key5);
              $val6 = new \xyz\shared\SMsgStatus();
              $xfer += $val6->read($input);
              $this->msginfolist[$key5] = $val6;
            }
            $xfer += $input->readMapEnd();
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
    $xfer += $output->writeStructBegin('MsgStatusStorage');
    if ($this->msginfolist !== null) {
      if (!is_array($this->msginfolist)) {
        throw new TProtocolException('Bad type in structure.', TProtocolException::INVALID_DATA);
      }
      $xfer += $output->writeFieldBegin('msginfolist', TType::MAP, 1);
      {
        $output->writeMapBegin(TType::I64, TType::STRUCT, count($this->msginfolist));
        {
          foreach ($this->msginfolist as $kiter7 => $viter8)
          {
            $xfer += $output->writeI64($kiter7);
            $xfer += $viter8->write($output);
          }
        }
        $output->writeMapEnd();
      }
      $xfer += $output->writeFieldEnd();
    }
    $xfer += $output->writeFieldStop();
    $xfer += $output->writeStructEnd();
    return $xfer;
  }

}


