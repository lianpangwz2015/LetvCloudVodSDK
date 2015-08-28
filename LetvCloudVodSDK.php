<?php
/**
 * 乐视视频云 点播API
 *
 * @package Medz\SDK\LetvCloudSDK
 * @author Medz Seven <lovevipdsw@vip.qq.com>
 * @link http://medz.cn
 * @copyright © 2015, Medz Seven Developer. All rights reserved.
 * @document http://static.letvcloud.com/wiki/doc/LetvCloudOpenApi_v1.0.pdf
 * @discription 该SDK是根据乐视云视频API接口v2.3文档编写，
 **/
class LetvCloudVodSDK
{
	/**
	 * 储存当前实例化类
	 *
	 * @var object
	 **/
	private static $_instance;

	/**
	 * 状态码 表示全部
	 *
	 * @var int
	 **/
	const STATUS_ALL = 0;

	/**
	 * 状态码 标识可以正常播放的
	 *
	 * @var int
	 **/
	const STATUS_PLAY_OK = 10;

	/**
	 * 状态码 处理失败
	 *
	 * @var int
	 **/
	const STATUS_FAILED = 20;

	/**
	 * 状态码 正在处理
	 *
	 * @var int
	 **/
	const STATUS_WAIT = 30;

	/**
	 * 初始化上传API
	 *
	 * @var string
	 **/
	const API_UPLOAD_INIT = 'video.upload.init';

	/**
	 * 断点续传API
	 *
	 * @var string
	 **/
	const API_UPLOAD_RESUME = 'video.upload.resume';

	/**
	 * Flash上传API
	 *
	 * @var string
	 **/
	const API_UPLOAD_FLASH = 'video.upload.flash';

	/**
	 * 视频信息更新API
	 *
	 * @var string
	 **/
	const API_UPDATE = 'video.update';

	/**
	 * 视频列表API
	 *
	 * @var string
	 **/
	const API_LIST = 'video.list';

	/**
	 * 单个视频信息获取API
	 *
	 * @var string
	 **/
	const API_GET = 'video.get';

	/**
	 * 单个视频删除API
	 *
	 * @var string
	 **/
	const API_DELETE = 'video.del';

	/**
	 * 批量删除视频API
	 *
	 * @var string
	 **/
	const API_DELETE_BATCH = 'video.del.batch';

	/**
	 * 视频暂停API
	 *
	 * @var string
	 **/
	const API_VIDEO_PAUSE = 'video.pause';

	/**
	 * 视频恢复API
	 *
	 * @var string
	 **/
	const API_VIDEO_RESTORE = 'video.restore';

	/**
	 * 视频截图API
	 *
	 * @var string
	 **/
	const API_IMAGE_GET = 'image.get';

	/**
	 * 视频小时数据
	 *
	 * @var string
	 **/
	const API_VIDEO_HOUE = 'data.video.hour';

	/**
	 * 视频天数据
	 *
	 * @var string
	 **/
	const API_VIDEO_DATE = 'data.video.date';

	/**
	 * 视频所有数据
	 *
	 * @var string
	 **/
	const API_VIDEO_DATA_ALL = 'data.total.date';

	/**
	 * 视频播放首帧截图api
	 *
	 * @var string
	 **/
	const API_VIDEO_PIC = 'play.update.initpic';

	/**
	 * 不分片上传
	 *
	 * @var int
	 **/
	const UPLOAD_TYPE_0 = 0;

	/**
	 * 分片上传
	 *
	 * @var string
	 **/
	const UPLOAD_TYPE_1 = 1;

	/**
	 * API版本 目前按照官方定义，设置为2.0
	 *
	 * @var string
	 **/
	protected $version = '2.0';

	/**
	 * 数据返回格式
	 *
	 * @var string
	 **/
	protected $format = 'json';

	/**
	 * 消息储存
	 *
	 * @var string
	 **/
	protected $message;

	/**
	 * cURL 对象
	 *
	 * @var object
	 **/
	protected $cURL;

	/**
	 * cURL 请求数据
	 *
	 * @var string
	 **/
	protected $cData;

	/**
	 * 用户唯一ID
	 *
	 * @var string
	 **/
	protected $userUnique;

	/**
	 * 用户密钥
	 *
	 * @var string
	 **/
	protected $secretKey;

	/**
	 * API地址
	 *
	 * @var string
	 **/
	protected $apiRequestAddress;

	/**
	 * 单例获取当前SDK
	 *
	 * @return object 当前类
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	final public static function getInstance()
	{
		if (!self::$_instance instanceof self) {
			self::$_instance = new self;
		}

		return self::$_instance;
	}

	/**
	 * 设置用户唯一ID
	 *
	 * @param string $userUnique 用户唯一ID
	 * @return object self
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function setUserUnique($userUnique)
	{
		$this->userUnique = $userUnique;
		return $this;
	}

	/**
	 * 获取用户唯一ID
	 *
	 * @return string 用户唯一ID
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function getUserUnique()
	{
		return $this->userUnique;
	}

	/**
	 * 设置用户唯一密钥
	 *
	 * @param string $secretKey 用户唯一密钥
	 * @return object self
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function setSecretKey($secretKey)
	{
		$this->secretKey = $secretKey;
		return $this;
	}

	/**
	 * 获取用户密钥
	 *
	 * @return string 用户密钥
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function getSecreKey()
	{
		return $this->secretKey;
	}

	/**
	 * 构造方法，用于初始化信息
	 *
	 * @return void
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	final private function __construct()
	{
		$this->cURL = curl_init();
		curl_setopt($this->cURL, CURLOPT_FAILONERROR, false);
		curl_setopt($this->cURL, CURLOPT_RETURNTRANSFER, true);

		$this->setApiRequestAddress('http://api.letvcloud.com/open.php');
	}

	/**
	 * 克隆触发事件 用于禁止克隆
	 *
	 * @return void
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	final public function __clone()
	{
		$this->__destruct();
		trigger_error('Clone is not allow!', E_USER_ERROR);
		exit;
	}

	/**
	 * 析构方法，主要用于销毁单例对象
	 *
	 * @return void
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	final public function __destruct()
	{
		call_user_func(array($this, 'unInstance'));
		curl_close($this->cURL);
		$this->cURL = null;
	}

	/**
	 * 销毁单例数据
	 *
	 * @return void
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	final private static function unInstance()
	{
		self::$_instance = null;
	}

	/**
	 * 设置消息
	 *
	 * @return void
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	protected function setMessage($message)
	{
		$this->message = $message;
	}

	/**
	 * 获取消息
	 *
	 * @return string
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * 请求方法
	 *
	 * @param string $url 请求的地址
	 * @param array $postParam POST请求的数据
	 * @return boolean 是否请求成功
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	protected function request($url, array $postParam = array())
	{
		/* # 设置请求地址 */
		curl_setopt($this->cURL, CURLOPT_URL, $url);

		/* # 设置HTTPS请求信息 */
		if (strlen($url) > 5 and strtolower(substr($url, 0, 5)) == 'https') {
			curl_setopt($this->cURL, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($this->cURL, CURLOPT_SSL_VERIFYHOST, false);
		}

		/* # 设置POST信息 */
		if (is_array($postParam) and count($postParam) > 0) {
			curl_setopt($this->cURL, CURLOPT_POST, true);

			$postBodyString = '';

			foreach ($postParam as $key => $value) {
				if (substr($value, 0, 1) != '@') {
					$postBodyString .= $key . '=' . urlencode($value) . '&';
				} else {
					curl_setopt($this->cURL, CURLOPT_POSTFIELDS, $postParam);
					$postBodyString = null;
					break;
				}
			}
			unset($postParam, $key, $value);

			if ($postBodyString) {
				curl_setopt($this->cURL, CURLOPT_POSTFIELDS, substr($postBodyString, 0, -1));
			}
			unset($postBodyString);
		}

		/* # 执行请求 */
		$this->setData(curl_exec($this->cURL));

		if (curl_errno($this->cURL)) {
			$this->setData(null);
			$this->setMessage(curl_error($this->cURL));
			return false;
		} elseif (curl_getinfo($this->cURL, CURLINFO_HTTP_CODE) !== 200) {
			$this->setMessage($this->getData);
			$this->setData(null);
			return false;
		}
		return true;
	}

	/**
	 * 设置原始数据
	 *
	 * @param string $data 原始数据
	 * @return void
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	protected function setData($data)
	{
		$this->cData = $data;
	}

	/**
	 * 获取原始数据
	 *
	 * @return string
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function getData()
	{
		return $this->cData;
	}

	/**
	 * 获取格式化后的数据
	 *
	 * @param boolean $returnArray 是否返回数组 如果是true，返回数组，否则返回对象
	 * @return array|object 格式化后的数据
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function getFormatData($returnArray = true)
	{
		$returnArray = (boolean) $returnArray;
		return json_decode($this->getData(), $returnArray);
	}

	/**
	 * 获取API版本
	 *
	 * @return string
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function getVersion()
	{
		return $this->version;
	}

	/**
	 * 获取数据格式
	 *
	 * @return string
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function getFormat()
	{
		return $this->format;
	}

	/**
	 * 设置API请求地址
	 *
	 * @param string $url API请求地址
	 * @return object self
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function setApiRequestAddress($url)
	{
		$this->apiRequestAddress = $url;
		return $this;
	}

	/**
	 * 获取API请求地址
	 *
	 * @return string
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function getApiRequestAddress()
	{
		return $this->apiRequestAddress;
	}

	/**
	 * 生成用户签字
	 *
	 * @param array $params 签字参数
	 * @return string
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function generateSign(array $params)
	{
		ksort($params);
		$keyStr = '';
		foreach ($params as $key => $value) {
			$keyStr .= $key . $value;
		}
		unset($params, $key, $value);
		$keyStr .= $this->getSecreKey();
		return md5($keyStr, false);
	}

	/**
	 * API信息请求
	 *
	 * @param $apiName API名称
	 * @param $apiParam API请求参数
	 * @return boolean 成功或者失败
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function api($apiName, array $apiParam = array())
	{
		/* # 设置系统参数 */
		$apiParam = array_merge(array(
			'user_unique' => $this->getUserUnique(),
			/*'timestamp' => time(),*/ /* # 暂时不知道为什么不能用当前时间，只能用SDK设置的固定值 */
			'timestamp'   => 1369300735578, /* # 估计是服务端生成签字问题 */
			'ver'         => $this->getVersion(),
			'format'      => $this->getFormat(),
			'api'         => $apiName
		), $apiParam);

		/* # 设置签字 */
		$apiParam['sign'] = $this->generateSign($apiParam);

		/* # 构造请求地址 */
		$url = $this->getApiRequestAddress();
		strpos($url, '?') or $url .= '?';
		foreach ($apiParam as $key => $value) {
			$url .= $key . '=' . urlencode($value) . '&';
		}
		$url = substr($url, 0, -1);

		return $this->request($url);
	}

	/**
	 * 初始化上传信息
	 *
	 * @param string $videoName 视频名称
	 * @param int $uploadType self::UPLOAD_TYPE_0|self::UPLOAD_TYPE_1 上传方式UPLOAD_TYPE_0为完整上传，UPLOAD_TYPE_1为分片上传
	 * @param int $videoSize 视频尺寸 单位字节
	 * @param string 用户客户端IP地址
	 * @return array 上传信息
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function uploadInit($videoName, $uploadType = self::UPLOAD_TYPE_0, $videoSize = 0, $clientIP = '')
	{
		/* # 创建参数并设置视频名称 */
		$params = array(
			'video_name' => $videoName
		);

		/* # 设置是否是分分片上传 */
		$uploadType === self::UPLOAD_TYPE_1 AND $params['uploadtype'] = self::UPLOAD_TYPE_1;

		/* # 设置客户端IP */
		$clientIP and $params['client_ip'] = $clientIP;

		/* # 设置视频尺寸 */
		$videoSize = floatval($videoSize);
		$videoSize > 0 and $params['file_size'] = $videoSize;

		return $this->api(self::API_UPLOAD_INIT, $params);
	}

	/**
	 * Web方式上传地址（或者叫做本地视频上传）
	 *
	 * @param string $uploadURL 视频的上传地址（通过上传初始化获得）
	 * @param string $videoFileAddress 视频文件绝对地址
	 * @return boolean
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function upload2Web($uploadURL, $videoFileAddress)
	{
		return $this->request($uploadURL, array(
			'video_file' => '@' . $videoFileAddress
		));
	}

	/**
	 * 断点续传
	 *
	 * @param string $token 视频上传标识
	 * @param int $uploadType self::UPLOAD_TYPE_0|self::UPLOAD_TYPE_1 上传方式UPLOAD_TYPE_0为完整上传，UPLOAD_TYPE_1为分片上传
	 * @return boolean
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function uploadResume($token, $uploadType = self::UPLOAD_TYPE_0)
	{
		$params = array(
			'token' => $token
		);

		$uploadType === self::UPLOAD_TYPE_1 and $params['uploadtype'] = self::UPLOAD_TYPE_1;

		return $this->api(self::API_UPLOAD_RESUME, $params);
	}

	/**
	 * 视频Flash方式上传（无需初始化视频）
	 *
	 * @param string $videoName 视频名称
	 * @param string $jsCallback JS回掉方法
	 * @param int $width 上传控件宽度
	 * @param int $height 上传控件高度
	 * @return boolean
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function upload2Flash($videoName, $jsCallback = '', $width = 600, $height = 450, $clientIP = '')
	{
		$params = array(
			'video_name'   => $videoName,
			'flash_width'  => intval($width),
			'flash_height' => intval($height)
		);

		/* # 设置JS回掉 */
		empty($jsCallback) or $params['js_callback'] = $jsCallback;

		/* # 设置客户端IP */
		$clientIP and $params['client_ip'] = $clientIP;

		return $this->api(self::API_UPLOAD_FLASH, $params);
	}

	/**
	 * 视频进度 or 状态查询
	 *
	 * @param string $progressUrl 视频上传的回调地址
	 * @param string $token 初始化视频时候的视频token
	 * @return boolean
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function getUploadProgress($progressUrl, $token)
	{
		return $this->request($progressUrl, array(
			'token' => $token
		));
	}

	/**
	 * 视频重命名
	 *
	 * @param int $videoID 视频ID
	 * @param string 视频新名称 最多60字
	 * @return boolean
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function update2Rename($videoID, $newName)
	{
		return $this->api(self::API_UPDATE, array(
			'video_id'   => intval($videoID),
			'video_name' => $newName
		));
	}

	/**
	 * 更新视频描述
	 *
	 * @param int $videoID 视频ID
	 * @param string $discription 视频描述 最多600字
	 * @return boolean
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function update2Desc($videoID, $discription)
	{
		return $this->api(self::API_UPDATE, array(
			'video_id'   => intval($videoID),
			'video_desc' => $discription
		));
	}

	/**
	 * 设置视频是否收费，默认不收费
	 * 收费视频乐视有用户鉴权，建议不要轻易设置
	 *
	 * @param int $videoID 视频ID
	 * @param boolean $isPay 是否收费， 默认不收费
	 * @return boolean
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function update2IsPay($videoID, $isPay = false)
	{
		$isPay = (boolean) $isPay;
		return $this->api(self::API_UPDATE, array(
			'video_id' => intval($videoID),
			'is_pay'   => intval($isPay)
		));
	}

	/**
	 * 更新视频标签
	 *
	 * @param int $videoID 视频ID
	 * @param array $tags 设置视频标签，多个参数传入，每个传输必须是标签名称
	 * @return boolean
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 * @descript 标签只允许有五个，每个标签不要超过10个字
	 **/
	public function update2Tag($videoID, array $tag)
	{
		foreach ($tag as $key => $value) {
			if (!is_string($value)) {
				unset($tag[$key]);
				continue;
			}
			$value = strval($value);
			$tag[$key] = $value;
		}
		unset($key, $value);

		/* # 去重 */
		$tag = array_flip($tag);
		$tag = array_flip($tag);

		if (count($tag) > 5) {
			$this->setMessage('视频标签过多');
			return false;
		}

		$tag = implode(' ', $tag);
		
		return $this->api(self::API_UPDATE, array(
			'video_id' => intval($videoID),
			'tag'      => $tag
		));
	}

	/**
	 * 设置视频播放时候默认的首帧视频图片
	 *
	 * @return boolean
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function update2Pic($videoID, $picUrl)
	{
		return $this->api(self::API_VIDEO_PIC, array(
			'video_id' => floatval($videoID),
			'init_pic' => $picUrl
		));
	}

	/**
	 * 获取视频列表
	 *
	 * @param int $index 页索引，默认为1
	 * @param int $size 页数量，默认为10条，做多每页100
	 * @param int $status 状态，默认 STATUS_ALL，STATUS_ALL全部状态的视频， STATUS_PLAY_OK播放完成的视频， STATUS_FAILED树立失败的视频，STATUS_WAIT正在处理的
	 * @return boolean
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function getList($index = 1, $size = 10, $status = self::STATUS_ALL)
	{
		return $this->api(self::API_LIST, array(
			'index'  => intval($index),
			'size'   => intval($size),
			'status' => intval($status)
		));
	}

	/**
	 * 获取单个视频信息
	 *
	 * @param int $videoID 视频ID
	 * @return boolean
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function getInfo($videoID)
	{
		return $this->api(self::API_GET, array(
			'video_id' => intval($videoID)
		));
	}

	/**
	 * 删除单个视频
	 *
	 * @param int $videoID 视频ID
	 * @return boolean
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function delete2One($videoID)
	{
		return $this->api(self::API_DELETE, array(
			'video_id' => intval($videoID)
		));
	}

	/**
	 * 批量删除视频
	 *
	 * @param array $ids 视频列表 批量删除每次最多50条
	 * @return boolean
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function delete2Batch(array $ids)
	{
		foreach ($ids as $key => $value) {
			if (!is_numeric($value)) {
				unset($ids[$key]);
				continue;
			}
			$ids[$key] = intval($value);
		}

		$ids = implode('-', $ids);
		
		return $this->api(self::API_DELETE_BATCH, array(
			'video_id_list' => $ids
		));
	}

	/**
	 * 视频暂停播放（或者叫做，暂停遍历视频以及转码）
	 *
	 * @param int $videoID 需要暂停的视频ID
	 * @return boolean
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function videoPause($videoID)
	{
		return $this->api(self::API_VIDEO_PAUSE, array(
			'video_id' => intval($videoID)
		));
	}

	/**
	 * 视频恢复播放（恢复暂停遍历以及转码的视频）
	 *
	 * @param int $videoID
	 * @return boolean
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function videoRestore($videoID)
	{
		return $this->api(self::API_VIDEO_RESTORE, array(
			'video_id' => intval($videoID)
		));
	}

	/**
	 * 获取视频截图 给个尺寸会返回八张不同时间点的截图供选择
	 *
	 * @param int $videoID 视频ID
	 * @param string $size 截图尺寸，只有一下尺寸供选择，不可自定义尺寸获取，尺寸如下：100_100、200_200、300_300、120_90、128_96、132_99、160_120、200_150、400_300、160_90、320_180、640_360、90_120、120_160、150_200、300_400
	 * @return boolean
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function getVideoImage($videoID, $size = '640_360')
	{
		return $this->api(self::API_IMAGE_GET, array(
			'video_id' => intval($videoID),
			'size'     => $size
		));
	}

	/**
	 * 获取视频小时数据
	 *
	 * @param string $date 日期 格式：yyy-mm-dd
	 * @param int $hour 小时 0-23之间
	 * @param int $index 索引页起始 默认为1
	 * @param int $size 每页显示的数量 默认10条，最多每页100条
	 * @param int $videoID 视频ID 默认null，传入则只获取这个视频的数据
	 * @return boolean
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function getData2Hour($date, $hour = null, $index = 1, $size = 10, $videoID = null)
	{
		$params = array(
			'date'  => $data,
			'index' => floatval($index),
			'size'  => floatval($size)
		);

		/* # 小时 */
		$hour != null and $params['hour'] = intval($hour);

		/* # 视频ID */
		$videoID != null and $params['video_id'] = floatval($videoID);

		return $this->api(self::API_VIDEO_HOUE, $params);
	}

	/**
	 * 按照时间天范围获取视频数据
	 *
	 * @param string $startDate 开始日期 格式：yyyy-mm-dd
	 * @param string $endDate 结束日期 格式：yyyy-mm-dd
	 * @param int $index 起始页数 默认是1
	 * @param int $size 每页显示的条数 默认是10，，最多每页显示100
	 * @return boolean
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function getData2Date($startDate, $endDate, $index = 1, $size = 10, $videoID = null)
	{
		$params = array(
			'start_date' => $startDate,
			'end_date'   => $endDate,
			'index'      => floatval($index),
			'size'       => intval($size)
		);

		/* # 视频ID */
		$videoID != null and $params['video_id'] = floatval($videoID);

		return $this->api(self::API_VIDEO_DATE, $params);
	}

	/**
	 * 按照日期，获取所有视频数据
	 *
	 * @param  string $start_date 开始日期，格式为：yyyy-mm-dd
	 * @param  string $end_date 结束日期，格式为：yyyy-mm-dd
	 * @param  int $index 开始页索引，默认值为1
	 * @param  int $size 分页大小，默认值为10，最大值为100
	 * @return boolean
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function getData2All($startDate, $endDate, $index = 1, $size = 10)
	{
		return $this->api(self::API_VIDEO_DATA_ALL, array(
			'start_date' => $startDate,
			'end_date'   => $endDate,
			'index'      => floatval($index),
			'size'       => intval($size)
		));
	}

} // END public class LetvCloudSDK

// $sdk = LetvCloudSDK::getInstance();
// $sdk->setUserUnique('39c10s3ekd')->setSecretKey('9f4d54d62590c27a5564821b88429e2e');
// $sdk->getList(); /* # 列表 */
// var_dump($sdk, $sdk->getFormatData());
// // var_dump($sdk->delete2Batch(array(1, 2, 3, '4', null, '5')));
// // var_dump($sdk->update2Tag(15502849, array('demo1', 'demo2', 'demo1')));

// var_dump($sdk->getVideoImage(15502849, '640_360'), $sdk->getFormatData());