<?php
/**
 * Created by PhpStorm.
 * User: guojianing
 * Date: 2017/2/15
 * Time: 13:58
 */
class Controller_Api_Wechatxcx extends Controller_Api_Base {

    private $_video_list; //视频列表  
    private $_video_detail;//视频详情页 
    
    public $videosList = [
        [
            'id' => 1001,
            'author' => 'Gome Video',
            'avatar' => 'http://v1.varfn.com/avatar/user1001.jpg',
            'preview' => 'http://v1.varfn.com/public/pw1001.jpg',
            'videoType' => '直播',
            'videoTypeColor' => '#33ccff',
            'videoName' => '[直播] 小程序那些事',
            'videoDesc' => '2017年1月，小程序正式上线后，引发了一阵狂热，朋友圈也被小程序相关的话题持续刷屏。微信小程序作为一个全新的产品， 现阶段大家都处于探索阶段，而外界对于小程序论调不一。
在过去的一个月里，有些品牌通过小程序获得了大量的用户，尝到了第一波红利的甜头，但也有罗辑思维的退出，今日头条的暂停服务，引发了大家对于小程序的众多的猜测，对于微信小程序，究竟是该前进还是撤退？',
            'videoUrl' => 'https://api-gls.gomeplus.com/live/qF9TudxoGkd_QY_XN03Mnm-YHS6e91f_jFtcMxGSizA.m3u8'
            ],
        [
            'id' => 1002,
            'author' => '主播1002',
            'avatar' => 'http://v1.varfn.com/avatar/user1002.jpg',
            'preview' => 'http://v1.varfn.com/public/pw1002.jpg',
            'videoType' => '美食',
            'videoTypeColor' => '#f9bd1b',
            'videoName' => '唯有美食不可辜负',
            'videoDesc' => '美女主播带你逛吃逛吃逛吃。。。',
            'videoUrl' => 'http://v1.varfn.com/videos/video2.mp4'
            ],
        [
            'id' => 1003,
            'author' => '主播1003',
            'avatar' => 'http://v1.varfn.com/avatar/user1003.jpg',
            'preview' => 'http://v1.varfn.com/public/pw1003.jpg',
            'videoType' => '美妆',
            'videoTypeColor' => '#ff33cc',
            'videoName' => '爱美之心，人皆有之',
            'videoDesc' => '时尚美妆达人教你打扮自己。',
            'videoUrl' => 'http://v1.varfn.com/videos/video2.mp4'
            ],
        [
            'id' => 1004,
            'author' => '青丘小九九',
            'avatar' => 'http://v1.varfn.com/avatar/user1004.jpg',
            'preview' => 'http://v1.varfn.com/public/pw1004.jpg',
            'videoType' => '影视',
            'videoTypeColor' => '#00cc00',
            'videoName' => '三生三世十里桃花',
            'videoDesc' => '青丘小九九邀你观看《三生三世十里桃花》',
            'videoUrl' => 'http://v1.varfn.com/videos/video2.mp4'
            ],
        [
            'id' => 1005,
            'author' => '铲屎官的猫主子',
            'avatar' => 'http://v1.varfn.com/avatar/user1005.jpg',
            'preview' => 'http://v1.varfn.com/public/pw1005.jpg',
            'videoType' => '萌宠',
            'videoTypeColor' => '#ff0033',
            'videoName' => '猫主子的日常生活',
            'videoDesc' => '喵了个咪的，总有刁民想害朕。',
            'videoUrl' => 'http://v1.varfn.com/videos/video2.mp4'
            ],
        [
            'id' => 1006,
            'author' => '主播1002',
            'avatar' => 'http://v1.varfn.com/avatar/user1002.jpg',
            'preview' => 'http://v1.varfn.com/public/pw1002.jpg',
            'videoType' => '美食',
            'videoTypeColor' => '#f9bd1b',
            'videoName' => '唯有美食不可辜负',
            'videoDesc' => '美女主播带你逛吃逛吃逛吃。。。',
            'videoUrl' => 'http://v1.varfn.com/videos/video2.mp4'
            ],
        [
            'id' => 1007,
            'author' => '主播1003',
            'avatar' => 'http://v1.varfn.com/avatar/user1003.jpg',
            'preview' => 'http://v1.varfn.com/public/pw1003.jpg',
            'videoType' => '美妆',
            'videoTypeColor' => '#ff33cc',
            'videoName' => '爱美之心，人皆有之',
            'videoDesc' => '时尚美妆达人教你打扮自己。',
            'videoUrl' => 'http://v1.varfn.com/videos/video2.mp4'
            ],
        [
            'id' => 1008,
            'author' => '青丘小九九',
            'avatar' => 'http://v1.varfn.com/avatar/user1004.jpg',
            'preview' => 'http://v1.varfn.com/public/pw1004.jpg',
            'videoType' => '影视',
            'videoTypeColor' => '#00cc00',
            'videoName' => '三生三世十里桃花',
            'videoDesc' => '青丘小九九邀你观看《三生三世十里桃花》',
            'videoUrl' => 'http://v1.varfn.com/videos/video2.mp4'
            ],
        [
            'id' => 1009,
            'author' => '铲屎官的猫主子',
            'avatar' => 'http://v1.varfn.com/avatar/user1005.jpg',
            'preview' => 'http://v1.varfn.com/public/pw1005.jpg',
            'videoType' => '萌宠',
            'videoTypeColor' => '#ff0033',
            'videoName' => '猫主子的日常生活',
            'videoDesc' => '喵了个咪的，总有刁民想害朕。',
            'videoUrl' => 'http://v1.varfn.com/videos/video2.mp4'
            ],
        [
            'id' => 1010,
            'author' => '主播1002',
            'avatar' => 'http://v1.varfn.com/avatar/user1002.jpg',
            'preview' => 'http://v1.varfn.com/public/pw1002.jpg',
            'videoType' => '美食',
            'videoTypeColor' => '#f9bd1b',
            'videoName' => '唯有美食不可辜负',
            'videoDesc' => '美女主播带你逛吃逛吃逛吃。。。',
            'videoUrl' => 'http://v1.varfn.com/videos/video2.mp4'
            ],
        [
            'id' => 1011,
            'author' => '主播1003',
            'avatar' => 'http://v1.varfn.com/avatar/user1003.jpg',
            'preview' => 'http://v1.varfn.com/public/pw1003.jpg',
            'videoType' => '美妆',
            'videoTypeColor' => '#ff33cc',
            'videoName' => '爱美之心，人皆有之',
            'videoDesc' => '时尚美妆达人教你打扮自己。',
            'videoUrl' => 'http://v1.varfn.com/videos/video2.mp4'
            ],
        [
            'id' => 1012,
            'author' => '青丘小九九',
            'avatar' => 'http://v1.varfn.com/avatar/user1004.jpg',
            'preview' => 'http://v1.varfn.com/public/pw1004.jpg',
            'videoType' => '影视',
            'videoTypeColor' => '#00cc00',
            'videoName' => '三生三世十里桃花',
            'videoDesc' => '青丘小九九邀你观看《三生三世十里桃花》',
            'videoUrl' => 'http://v1.varfn.com/videos/video2.mp4'
            ],
        [
            'id' => 1013,
            'author' => '铲屎官的猫主子',
            'avatar' => 'http://v1.varfn.com/avatar/user1005.jpg',
            'preview' => 'http://v1.varfn.com/public/pw1005.jpg',
            'videoType' => '萌宠',
            'videoTypeColor' => '#ff0033',
            'videoName' => '猫主子的日常生活',
            'videoDesc' => '喵了个咪的，总有刁民想害朕。',
            'videoUrl' => 'http://v1.varfn.com/videos/video2.mp4'
            ],
        [
            'id' => 1014,
            'author' => '主播1002',
            'avatar' => 'http://v1.varfn.com/avatar/user1002.jpg',
            'preview' => 'http://v1.varfn.com/public/pw1002.jpg',
            'videoType' => '美食',
            'videoTypeColor' => '#f9bd1b',
            'videoName' => '唯有美食不可辜负',
            'videoDesc' => '美女主播带你逛吃逛吃逛吃。。。',
            'videoUrl' => 'http://v1.varfn.com/videos/video2.mp4'
            ],
        [
            'id' => 1015,
            'author' => '主播1003',
            'avatar' => 'http://v1.varfn.com/avatar/user1003.jpg',
            'preview' => 'http://v1.varfn.com/public/pw1003.jpg',
            'videoType' => '美妆',
            'videoTypeColor' => '#ff33cc',
            'videoName' => '爱美之心，人皆有之',
            'videoDesc' => '时尚美妆达人教你打扮自己。',
            'videoUrl' => 'http://v1.varfn.com/videos/video2.mp4'
            ],
        [
            'id' => 1016,
            'author' => '青丘小九九',
            'avatar' => 'http://v1.varfn.com/avatar/user1004.jpg',
            'preview' => 'http://v1.varfn.com/public/pw1004.jpg',
            'videoType' => '影视',
            'videoTypeColor' => '#00cc00',
            'videoName' => '三生三世十里桃花',
            'videoDesc' => '青丘小九九邀你观看《三生三世十里桃花》',
            'videoUrl' => 'http://v1.varfn.com/videos/video2.mp4'
            ],
        [
            'id' => 1017,
            'author' => '铲屎官的猫主子',
            'avatar' => 'http://v1.varfn.com/avatar/user1005.jpg',
            'preview' => 'http://v1.varfn.com/public/pw1005.jpg',
            'videoType' => '萌宠',
            'videoTypeColor' => '#ff0033',
            'videoName' => '猫主子的日常生活',
            'videoDesc' => '喵了个咪的，总有刁民想害朕。',
            'videoUrl' => 'http://v1.varfn.com/videos/video2.mp4'
            ],
        [
            'id' => 1018,
            'author' => '主播1002',
            'avatar' => 'http://v1.varfn.com/avatar/user1002.jpg',
            'preview' => 'http://v1.varfn.com/public/pw1002.jpg',
            'videoType' => '美食',
            'videoTypeColor' => '#f9bd1b',
            'videoName' => '唯有美食不可辜负',
            'videoDesc' => '美女主播带你逛吃逛吃逛吃。。。',
            'videoUrl' => 'http://v1.varfn.com/videos/video2.mp4'
            ],
        [
            'id' => 1019,
            'author' => '主播1003',
            'avatar' => 'http://v1.varfn.com/avatar/user1003.jpg',
            'preview' => 'http://v1.varfn.com/public/pw1003.jpg',
            'videoType' => '美妆',
            'videoTypeColor' => '#ff33cc',
            'videoName' => '爱美之心，人皆有之',
            'videoDesc' => '时尚美妆达人教你打扮自己。',
            'videoUrl' => 'http://v1.varfn.com/videos/video2.mp4'
            ],
        [
            'id' => 1020,
            'author' => '青丘小九九',
            'avatar' => 'http://v1.varfn.com/avatar/user1004.jpg',
            'preview' => 'http://v1.varfn.com/public/pw1004.jpg',
            'videoType' => '影视',
            'videoTypeColor' => '#00cc00',
            'videoName' => '三生三世十里桃花',
            'videoDesc' => '青丘小九九邀你观看《三生三世十里桃花》',
            'videoUrl' => 'http://v1.varfn.com/videos/video2.mp4'
            ],
        [
            'id' => 1021,
            'author' => '铲屎官的猫主子',
            'avatar' => 'http://v1.varfn.com/avatar/user1005.jpg',
            'preview' => 'http://v1.varfn.com/public/pw1005.jpg',
            'videoType' => '萌宠',
            'videoTypeColor' => '#ff0033',
            'videoName' => '猫主子的日常生活',
            'videoDesc' => '喵了个咪的，总有刁民想害朕。',
            'videoUrl' => 'http://v1.varfn.com/videos/video2.mp4'
            ]
        ];

    public static $comments = [
            [
                'id' => 1,
                'uid' => 1001,
                'author' => '主播1001',
                'avatar' => 'http://v1.varfn.com/avatar/user1001.jpg',
                'content' => '白日依山尽，黄河入海流。美女主播带你体验智能家电打造的时尚家居生活。美女主播带你体验智能家电打造的时尚家居生活。美女主播带你体验智能家电打造的时尚家居生活。美女主播带你体验智能家电打造的时尚家居生活。美女主播带你体验智能家电打造的时尚家居生活。',
                'pushtime' => '1486980791697',
                'recomments' => [
                    [
                        'id' => 1002,
                        'uid' => 1002,
                        'author' => '主播1002',
                        'content' => '欲穷千里目，更上一层楼'
                    ]
                ],
                'likes' => 2,
                'isliked' => 1
            ],
            [
                'id' => 2,
                'uid' => 1002,
                'author' => '主播1002',
                'avatar' => 'http://v1.varfn.com/avatar/user1002.jpg',
                'content' => '白日依山尽，黄河入海流。美女主播带你体验智能家电打造的时尚家居生活。',
                'pushtime' => '1486980791697',
                'recomments' => [],
                'likes' => 5,
                'isliked' => 0
            ],
            [
                'id' => 3,
                'uid' => 1004,
                'author' => '青丘小九九',
                'avatar' => 'http://v1.varfn.com/avatar/user1004.jpg',
                'content' => '美女主播带你体验智能家电打造的时尚家居生活。',
                'pushtime' => '1486980791697',
                'recomments' => [],
                'likes' => 5,
                'isliked' => 0
            ],
            [
                'id' => 4,
                'uid' => 1004,
                'author' => '青丘小九九',
                'avatar' => 'http://v1.varfn.com/avatar/user1004.jpg',
                'content' => '大江东去，浪淘尽，千古风流人物。',
                'pushtime' => '1486980791697',
                'recomments' => [],
                'likes' => 0,
                'isliked' => 0
            ],
            [
                'id' => 5,
                'uid' => 1003,
                'author' => '主播1003',
                'avatar' => 'http://v1.varfn.com/avatar/user1003.jpg',
                'content' => '白日依山尽，黄河入海流。',
                'pushtime' => '1486980791697',
                'recomments' => [],
                'likes' => 2,
                'isliked' => 1
            ],
            [
                'id' => 6,
                'uid' => 1005,
                'author' => '铲屎官的猫主子',
                'avatar' => 'http://v1.varfn.com/avatar/user1005.jpg',
                'content' => '美女主播带你体验智能家电打造的时尚家居生活。',
                'pushtime' => '1486980791697',
                'recomments' => [],
                'likes' => 0,
                'isliked' => 0
            ],
            [
                'id' => 7,
                'uid' => 1001,
                'author' => '主播1001',
                'avatar' => 'http://v1.varfn.com/avatar/user1001.jpg',
                'content' => '白日依山尽，黄河入海流。',
                'pushtime' => '1486980791697',
                'recomments' => [],
                'likes' => 0,
                'isliked' => 0
            ],
            [
                'id' => 8,
                'uid' => 1005,
                'author' => '铲屎官的猫主子',
                'avatar' => 'http://v1.varfn.com/avatar/user1005.jpg',
                'content' => '美女主播带你体验智能家电打造的时尚家居生活。',
                'pushtime' => '1486980791697',
                'recomments' => [],
                'likes' => 0,
                'isliked' => 0
            ],
            [
                'id' => 9,
                'uid' => 1001,
                'author' => '主播1001',
                'avatar' => 'http://v1.varfn.com/avatar/user1001.jpg',
                'content' => '白日依山尽，黄河入海流。',
                'pushtime' => '1486980791697',
                'recomments' => [],
                'likes' => 5,
                'isliked' => 0
            ],
            [
                'id' => 10,
                'uid' => 1005,
                'author' => '铲屎官的猫主子',
                'avatar' => 'http://v1.varfn.com/avatar/user1005.jpg',
                'content' => '美女主播带你体验智能家电打造的时尚家居生活。',
                'pushtime' => '1486980791697',
                'recomments' => [],
                'likes' => 0,
                'isliked' => 0
            ],
            [
                'id' => 11,
                'uid' => 1003,
                'author' => '主播1003',
                'avatar' => 'http://v1.varfn.com/avatar/user1003.jpg',
                'content' => '白日依山尽，黄河入海流。',
                'pushtime' => '1486980791697',
                'recomments' => [],
                'likes' => 5,
                'isliked' => 0
            ]
        ];

    public static $commentId = 11;
    
    public function before()
    {
        parent::before();
        $videoDomain = Kohana::$config->load('default.http.interface_video_domain'); 
        $this->_video_list =$videoDomain.'video/list';
        $this->_video_detail = $videoDomain.'video/info';
    }
    
    /*  
     *@获取点播视频列表
     *   
     * param : [int]    - $keyword  - 视频id     - 可选
     *         [int]    - $page     - 页数       - 可选       
     *         [string] - $callback - jsonp 回调 - 可选
     * author: snow wolf
     *@return: json
     */ 
    public function action_video_list()
    {
        $params = $data = $videoList = [];
        $params = [
            'keyword' => $this->request->query('keywords'),
            'page'    => $this->request->query('page'),
            'callback'=> $this->request->query('callback'),
        ];
        $videoList = $this->_curlrequestdata('get',$this->_video_list,$params);
        $data = $this->_process_data($videoList['body']);
        if($videoList['status'] !=200) $data['data'] = [];
        
        Curlresponse::json($videoList['status'],$data['message'],$data['data'],'');
    }
    
    /*
     *@获取视频详情
     *
     * param : [int ] - $video_id - 视频id
     * author: snow wolf
     *
     *@return: json
     */
    
    public function action_get_video_detail()
    {
        
        $params = $data = $videoDetail = [];
        $params = [
            'video_id'=>(int)$this->request->query('id'),
            'related' =>(int)$this->request->query('related')
        ];
        if($params['related'])
        {
            $videoDetail['status'] = 200;
            $data['message'] = '';
            if(!$tmpdata = $this->_get_releted_video_by_id($params['video_id']))  
            {
                $data['message'] = '没有数据';
            }
            $data['data'] = isset($tmpdata['data'])? $tmpdata['data']:[]; 
        }
        else
        {
            $videoDetail = $this->_curlrequestdata('get',$this->_video_detail,$params);
            $data = $this->_process_data($videoDetail['body']);
            if($videoDetail['status'] !=200) $data['data'] = [];
        }
        Curlresponse::json($videoDetail['status'],$data['message'],$data['data'],'');
    }

    /*
     *@数据分析处理
     *
     * param : [array] - $interfaceData
     * author: snow wolf
     *
     *@return: array - 处理后的数据 
     */ 
     private function _process_data($interfaceData)
    {
        if(isset($interfaceData['message']) && !empty($interfaceData['message']))
        {
            $interfaceData['data'] = [];
        }

        /*若message 为空,且数组为空时,追加message*/
        if(empty($interfaceData['message']) && empty($interfaceData['data']))
        {
            $interfaceData['data'] = [];
            $interfaceData['message'] = '没有数据';
        }
        return $interfaceData;
    }
    
    /*
     *@ 控制发起请求
     * param : [string] - $method - 资源操作符
     *         [array ] - $params - 参数
     *
     *@author: snow wolf
     *@return: array
     */  
    private function _curlrequestdata($method='get',$url='',array $params)
    {
        try{
            if(empty($method) || empty($url)) throw new HTTP_Exception('Invalid argument');
            $request = Request::factory($url)->method($method)->query($params)->execute();
        } catch (HTTP_Exception $e) {
            echo $e->getMessage();die;
        } catch (Exception $e) {
            echo $e->getMessage();die;
        }
        return ['status'=>$request->status(),'body'=>json_decode($request->body(),true)];
    } 

    /*
     *@获取相关视频列表
     */
    public function action_related_video_list()
    {
        $page = isset($_GET['p']) ? $_GET['p'] : 1;
        $limit = 5;
        $start = ($page-1)*$limit;
        $list = array_slice($this->videosList, $start, $limit);
        echo json_encode(['data' => $list]);
    }

    // 通过id获取视频数据
    private function _get_releted_video_by_id($id=0){
        
        $data = [];
        foreach ($this->videosList as $key => $value) {
            if ($value['id'] == $id) {
                $data['data'] = $value;
                break;
            }
        }
        return $data;
    }

    // 获取推荐视频
    public function action_get_related_videos()
    {
        $id = $_GET['id'];
        $i = 0;
        $len = 5;
        $related = [];
        foreach ($this->videosList as $key => $value) {
            if ($value['id'] != $id) {
                array_push($related, $value);
                ++$i;
                if ($i > $len) {
                    break;
                }
            }
        }
        shuffle($related);
        echo json_encode(['data' => $related]);
    }

    // 获取视频评论列表数据
    public function action_get_comments()
    {
        $commentsList = array_reverse(self::$comments);
        $page = isset($_GET['p']) ? $_GET['p'] : 1;
        $limit = 5;
        $start = ($page-1)*$limit;
        $list = array_slice($commentsList, $start, $limit);
        echo json_encode(['data' => $list]);
    }

    // 添加评论
    public function action_recomment(){
        $content = $_REQUEST['content'];
        $author = $_REQUEST['userName'];
        $avatar = $_REQUEST['avatar'];
        $id = self::$commentId + 1;

        $commentsData = [
                'id' => $id,
                'uid' => 1003,
                'author' => $author,
                'avatar' => $avatar,
                'content' => $content,
                'pushtime' => time()+'',
                'recomments' => [],
                'likes' => 0,
                'isliked' => 0
            ];
        $commentsList = self::$comments;
        array_push($commentsList, $commentsData);
        self::$comments = $commentsList;
        self::$commentId = $id;

        return $this->action_get_comments();

    }

    // 点赞
    public function action_like(){
        $id = $_REQUEST['id'];
    }

}
