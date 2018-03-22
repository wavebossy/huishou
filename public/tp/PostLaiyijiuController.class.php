<?php
namespace WeiXin\Controller;
use Think\Controller;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
require_once __DIR__."/../../../ThinkPHP/Library/Vendor/qiniusdk/autoload.php";

/***
 * 1488382392@1488382392
 * 643524
 * Class PostLaiyijiuController
 * @package WeiXin\Controller
 */
class PostLaiyijiuController extends Controller {

    private function datas(){
        $data = array(
            array(
                "id"=>"001",
                "gtitle"=>"冬天天鹅绒四件套",
                "gname"=>"冬天天鹅绒四件套纯色加厚保暖珊瑚绒磨毛法莱绒绒毛床笠款床单式",
                "price1"=>"¥ 0.00",
                "price2"=>"¥ 248.00",
                "price3"=>"69",
                "Stock"=>"996",
                "imgUrl"=>"http://g-search3.alicdn.com/img/bao/uploaded/i4/i4/1747371608/TB2MYHyfl0kpuFjy1XaXXaFkVXa_!!1747371608.jpg_230x230.jpg",
            ),
            array(
                "id"=>"002",
                "gtitle"=>"夏季床上用品",
                "gname"=>"夏季床上用品磨毛四件套",
                "price1"=>"¥ 0.00",
                "price2"=>"¥ 123.00",
                "price3"=>"39",
                "Stock"=>"223",
                "imgUrl"=>"https://g-search3.alicdn.com/img/bao/uploaded/i4/i3/T19.RCXCddXXXXXXXX_!!0-item_pic.jpg_230x230.jpg",
            ),
            array(
                "id"=>"003",
                "gtitle"=>"房间电视柜",
                "gname"=>"摆件房间电视柜摆设",
                "price1"=>"¥ 39.90",
                "price2"=>"",
                "price3"=>"19",
                "Stock"=>"662",
                "imgUrl"=>"https://g-search2.alicdn.com/img/bao/uploaded/i4/i1/837878638/TB2UradaICO.eBjSZFzXXaRiVXa_!!837878638.jpg_230x230.jpg",
            ),
            array(
                "id"=>"004",
                "gtitle"=>"威尔斯",
                "gname"=>"威尔斯大品牌，今日特价",
                "price1"=>"¥ 62.30",
                "price2"=>"¥ 623.00",
                "price3"=>"19",
                "Stock"=>"满仓",
                "imgUrl"=>"https://g-search1.alicdn.com/img/bao/uploaded/i4/i1/1968605428/TB2K9ENfXXXXXa.XpXXXXXXXXXX_!!1968605428.jpg_230x230.jpg",
            ),
            array(
                "id"=>"005",
                "gtitle"=>"四季凉拖鞋",
                "gname"=>"日式春秋款厚底居家亚麻拖鞋女男夏情侣木地板室内防滑四季凉拖鞋",
                "price1"=>"¥ 19.80",
                "price2"=>"¥ 69.00",
                "price3"=>"19",
                "Stock"=>"21",
                "imgUrl"=>"https://g-search3.alicdn.com/img/bao/uploaded/i4/i3/841415634/TB2VmC3dF5N.eBjSZFmXXboSXXa_!!841415634.jpg_230x230.jpg",
            ),
            array(
                "id"=>"006",
                "gtitle"=>"居室内棉鞋",
                "gname"=>"韩版冬季棉拖鞋女高包跟冬天保暖棉鞋加绒厚底防水居家居室内棉鞋",
                "price1"=>"¥ 25.49",
                "price2"=>"¥ 99.00",
                "price3"=>"19",
                "Stock"=>"95",
                "imgUrl"=>"https://gd4.alicdn.com/imgextra/i2/1685672412/TB2pjK6ahwb61BjSZFlXXbuoVXa_!!1685672412.jpg_230x230.jpg",
            ),
            array(
                "id"=>"007",
                "gtitle"=>"百思图",
                "gname"=>"BASTO/百思图2017夏季织物舒适透气压花平跟男休闲鞋BJM06BM7",
                "price1"=>"¥ 99.00",
                "price2"=>"¥ 599.00",
                "price3"=>"129",
                "Stock"=>"442",
                "imgUrl"=>"https://img.alicdn.com/imgextra/i4/834807033/TB29yPdqJBopuFjSZPcXXc9EpXa_!!834807033.jpg_230x230.jpg",
            ),
            array(
                "id"=>"008",
                "gtitle"=>"冠琴手表男士",
                "gname"=>"冠琴手表男士防水全自动机械表精钢带夜光超薄双日历商务时尚男表",
                "price1"=>"¥ 188.00",
                "price2"=>"¥ 588.00",
                "price3"=>"88",
                "Stock"=>"充足",
                "imgUrl"=>"https://img.alicdn.com/bao/uploaded/i3/TB1yqdGRXXXXXazXFXXXXXXXXXX_!!0-item_pic.jpg_230x230.jpg",
            ),
            array(
                "id"=>"009",
                "gtitle"=>"流苏真皮尖头男靴",
                "gname"=>"ENPEFO正品流苏真皮尖头男靴2017夏季新款厚底高帮铆钉马丁靴6751",
                "price1"=>"¥ 899.00",
                "price2"=>"¥ 1621.00",
                "price3"=>"499",
                "Stock"=>"668",
                "imgUrl"=>"https://img.alicdn.com/imgextra/i4/2948562965/TB2bJ03qR0kpuFjSsziXXa.oVXa_!!2948562965.jpg_230x230.jpg",
            ),
            array(
                "id"=>"010",
                "gtitle"=>"旅行袋手提旅行包",
                "gname"=>"Inuk旅行袋手提旅行包女大容量行李包 短途出差旅行袋健身旅游包",
                "price1"=>"¥ 399.00",
                "price2"=>"¥ 420.00",
                "price3"=>"299.00",
                "Stock"=>"559",
                "imgUrl"=>"https://img.alicdn.com/imgextra/i4/2196975871/TB2174KbUgQMeJjy0FjXXaExFXa_!!2196975871.jpg_230x230.jpg",
            ),
            array(
                "id"=>"011",
                "gtitle"=>"风衣外套，女",
                "gname"=>"2017春秋季新款韩版宽松显瘦修身休闲收腰中长款过膝风衣外套女",
                "price1"=>"¥ 198.00 ",
                "price2"=>"¥ 298.00",
                "price3"=>"68",
                "Stock"=>"996",
                "imgUrl"=>"https://img.alicdn.com/bao/uploaded/i4/3069248568/TB1B6ReSVXXXXX5XXXXXXXXXXXX_!!0-item_pic.jpg_230x230.jpg",
            ),
            array(
                "id"=>"012",
                "gtitle"=>"印花桑蚕丝短袖衬衫",
                "gname"=>"衣架2017夏季新款印花桑蚕丝短袖衬衫女短款上衣YJ1623T0300",
                "price1"=>"¥ 949.00",
                "price2"=>"¥ 1890.00",
                "price3"=>"699",
                "Stock"=>"64",
                "imgUrl"=>"https://img.alicdn.com/bao/uploaded/i1/2871267209/TB1YTuQbMsSMeJjSspdXXXZ4pXa_!!0-item_pic.jpg_230x230.jpg",
            ),
            array(
                "id"=>"013",
                "gtitle"=>"凉鞋",
                "gname"=>"木林森夏季男士凉鞋真皮沙滩鞋休闲爸爸中老年两用凉拖QM72354733",
                "price1"=>"¥ 258.00",
                "price2"=>"¥ 618.00",
                "price3"=>"166",
                "Stock"=>"441",
                "imgUrl"=>"https://img.alicdn.com/imgextra/i2/1638440248/TB20J0vmHXlpuFjSszfXXcSGXXa_!!1638440248.jpg_230x230.jpg",
            ),
            array(
                "id"=>"014",
                "gtitle"=>"小直通西便裤",
                "gname"=>"2017秋季时尚新款气质修身个性百搭纯色休闲哈伦小直通西便裤",
                "price1"=>"¥ 159.00",
                "price2"=>"¥ 258.00",
                "price3"=>"39",
                "Stock"=>"661",
                "imgUrl"=>"https://img.alicdn.com/bao/uploaded/i1/TB1320tRVXXXXbdXpXXXXXXXXXX_!!0-item_pic.jpg_230x230.jpg",
            ),
            array(
                "id"=>"015",
                "gtitle"=>"男士短袖t恤",
                "gname"=>"Afs Jeep/战地吉普夏季男士短袖t恤纯棉韩版修身潮流上衣polo衫",
                "price1"=>"¥ 118.00",
                "price2"=>"¥ 198.00",
                "price3"=>"89",
                "Stock"=>"113",
                "imgUrl"=>"https://img.alicdn.com/imgextra/i2/1678623550/TB2L.MHwipnpuFjSZFkXXc4ZpXa_!!1678623550.jpg_230x230.jpg",
            ),
            array(
                "id"=>"016",
                "gtitle"=>"修身圆领男士短袖",
                "gname"=>"聚SELECTED思莱德纯棉新款纯色修身圆领男士短袖T恤C|417101531",
                "price1"=>"¥ 39.00",
                "price2"=>"¥ 99.00",
                "price3"=>"19",
                "Stock"=>"113",
                "imgUrl"=>"https://img.alicdn.com/bao/uploaded/i4/849905958/TB1lOC9bMMPMeJjy1XcXXXpppXa_!!0-item_pic.jpg_230x230.jpg",
            ),
            array(
                "id"=>"017",
                "gtitle"=>"商务男纯棉短袖",
                "gname"=>"聚SELECTED思莱德夏季新款纯色商务男纯棉短袖翻领T恤C|417106502",
                "price1"=>"¥ 139.00",
                "price2"=>"¥ 199.00",
                "price3"=>"79",
                "Stock"=>"226",
                "imgUrl"=>"https://img.alicdn.com/bao/uploaded/i4/849905958/TB1MNj0de7JL1JjSZFKXXc4KXXa_!!0-item_pic.jpg_230x230.jpg",
            ),
            array(
                "id"=>"018",
                "gtitle"=>"活力拼接撞色棒球外套",
                "gname"=>"cachecache 活力拼接撞色棒球外套 6809001579",
                "price1"=>"¥ 199.90",
                "price2"=>"¥ 399.90",
                "price3"=>"119",
                "Stock"=>"551",
                "imgUrl"=>"https://img.alicdn.com/imgextra/i4/1041248422/TB2rUc9a4z9F1JjSZFsXXaCGVXa_!!1041248422.jpg_230x230.jpg",
            ),
            array(
                "id"=>"019",
                "gtitle"=>"个性不规则牛仔长裤",
                "gname"=>"cachecache 个性不规则牛仔长裤 6108012206",
                "price1"=>"¥ 169.90",
                "price2"=>"¥ 229.90",
                "price3"=>"79",
                "Stock"=>"551",
                "imgUrl"=>"https://img.alicdn.com/imgextra/i4/1041248422/TB2AVLzabAlyKJjSZFwXXXtqpXa_!!1041248422.jpg_230x230.jpg",
            ),
            array(
                "id"=>"020",
                "gtitle"=>"纯色休闲长袖连帽外套",
                "gname"=>"cachecache 纯色休闲长袖连帽外套 6768007570",
                "price1"=>"¥ 229.90",
                "price2"=>"¥ 279.90",
                "price3"=>"119",
                "Stock"=>"551",
                "imgUrl"=>"https://img.alicdn.com/imgextra/i4/1041248422/TB2nknKXUsIL1JjSZPiXXXKmpXa_!!1041248422.jpg_230x230.jpg",
            ),
        );
        return $data;
    }

    // 个人中心
    public function ucenter(){
        $this->display();
    }

    // 首页
    public function index(){
        $this->assign("datas",$this->datas());
        $this->display();
    }

    // 商品详情
    public function gdetail(){
        $id = I("get.id");
        $datas = $this->datas();
        $goods = array();
        foreach ($datas as $data){
            if($data["id"] == $id){
                $goods = $data;
                break;
            }
        }
        if(empty($goods)){
            $this->error("该商品不存在","/wxxcx123/WeiXin/PostLaiyijiu/index");
        }else{
            $this->assign("gdetail",$goods);
            $this->display();
        }
    }


}