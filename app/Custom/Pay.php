<?php

namespace App\Custom;

class Pay
{
    private $p1_MerId = "8881015";
    private $merchantKey = "68375060ea354c239e928b7be1a51d4c";
    private $logName = "BANK_HTML.log";
    //支付请求，固定值"Buy" .
    private $p0_Cmd = "Buy";
    //送货地址
    private $p9_SAF = "0";

    public function create()
    {
        $p2_Order = date('YmdHis') . rand(11111, 99999);

        //支付金额, 单位:元，精确到分.
        $p3_Amt = 1;
        //交易币种,固定值"CNY".
        $p4_Cur = "CNY";
        //商品名称 用于支付时显示在帝岭科技网关左侧的订单产品信息.
        $p5_Pid = '111';
        //商品种类
        $p6_Pcat = 'class';
        //商品描述
        $p7_Pdesc = 'desc';
        //商户接收支付成功数据的地址,支付成功后普讯科技会向该地址发送两次成功通知.
        $p8_Url = 'http://www.zhekou9.com/zhifu/101ka_bank/callback.php';
        //商户扩展信息 商户可以任意填写1K 的字符串,支付成功时将原样返回.
        $pa_MP = '';
        //支付通道编码 默认为""，到101卡网关.若不需显示普讯101卡的页面，直接跳转到各银行、神州行支付、骏网一卡通等支付页面，该字段可依照附录:银行列表设置参数值.
        $pd_FrpId = 'weixin';
        //应答机制 默认为"1": 需要应答机制;
        $pr_NeedResponse = "1";
        //调用签名函数生成签名串
        $hmac = $this->getReqHmacString($p2_Order, $p3_Amt, $p4_Cur, $p5_Pid, $p6_Pcat, $p7_Pdesc, $p8_Url, $pa_MP, $pd_FrpId, $pr_NeedResponse);

        $url = "http://api.101ka.com/GateWay/Bank/Default.aspx";

        $post_data = [
            'p0_Cmd'    => $this->p0_Cmd,
            'p1_MerId'  => $this->p1_MerId,
            'p2_Order'  => $p2_Order,
            'p3_Amt'    => $p3_Amt,
            'p4_Cur'    => $p4_Cur,
            'p5_Pid'    => $p5_Pid,
            'p6_Pcat'   => $p6_Pcat,
            'p7_Pdesc'  => $p7_Pdesc,
            'p8_Url'    => $p8_Url,
            'p9_SAF'    => $this->p9_SAF,
            'pa_MP'     => $pa_MP,
            'pd_FrpId'  => $pd_FrpId,
            'pr_NeedResponse' => $pr_NeedResponse,
            'hmac'      => $hmac,
        ];

        $post_html = "<h3 style='margin: 0 auto'>请稍后...</h3><form id='jump' name='jump' action='{$url}' method='post'>";
        $post_html .= "<input type='hidden' name='p0_Cmd' value='{$this->p0_Cmd}'>";
        $post_html .= "<input type='hidden' name='p1_MerId' value='{$this->p1_MerId}'>";
        $post_html .= "<input type='hidden' name='p2_Order' value='{$p2_Order}'>";
        $post_html .= "<input type='hidden' name='p3_Amt' value='{$p3_Amt}'>";
        $post_html .= "<input type='hidden' name='p4_Cur' value='{$p4_Cur}'>";
        $post_html .= "<input type='hidden' name='p5_Pid' value='{$p5_Pid}'>";
        $post_html .= "<input type='hidden' name='p6_Pcat' value='{$p6_Pcat}'>";
        $post_html .= "<input type='hidden' name='p7_Pdesc' value='{$p7_Pdesc}'>";
        $post_html .= "<input type='hidden' name='p8_Url' value='{$p8_Url}'>";
        $post_html .= "<input type='hidden' name='p9_SAF' value='{$this->p9_SAF}'>";
        $post_html .= "<input type='hidden' name='pa_MP' value='{$pa_MP}'>";
        $post_html .= "<input type='hidden' name='pd_FrpId' value='{$pd_FrpId}'>";
        $post_html .= "<input type='hidden' name='pr_NeedResponse' value='{$pr_NeedResponse}'>";
        $post_html .= "<input type='hidden' name='hmac' value='{$hmac}'>";

        $post_html .= "<script>document.forms['jump'].submit();</script>";
        echo $post_html;
    }

    private function getReqHmacString($p2_Order, $p3_Amt, $p4_Cur, $p5_Pid, $p6_Pcat, $p7_Pdesc, $p8_Url, $pa_MP, $pd_FrpId, $pr_NeedResponse)
    {
        #进行签名处理，一定按照文档中标明的签名顺序进行
        $sbOld = "";
        #加入业务类型
        $sbOld = $sbOld . $this->p0_Cmd;
        #加入商户编号
        $sbOld = $sbOld . $this->p1_MerId;
        #加入商户订单号
        $sbOld = $sbOld . $p2_Order;
        #加入支付金额
        $sbOld = $sbOld . $p3_Amt;
        #加入交易币种
        $sbOld = $sbOld . $p4_Cur;
        #加入商品名称
        $sbOld = $sbOld . $p5_Pid;
        #加入商品分类
        $sbOld = $sbOld . $p6_Pcat;
        #加入商品描述
        $sbOld = $sbOld . $p7_Pdesc;
        #加入商户接收支付成功数据的地址
        $sbOld = $sbOld . $p8_Url;
        #加入送货地址标识
        $sbOld = $sbOld . $this->p9_SAF;
        #加入商户扩展信息
        $sbOld = $sbOld . $pa_MP;
        #加入支付通道编码
        $sbOld = $sbOld . $pd_FrpId;
        #加入是否需要应答机制
        $sbOld = $sbOld . $pr_NeedResponse;
        //LOG
        //$this->logstr($p2_Order, $sbOld, HmacMd5($sbOld, $this->merchantKey));
        return $this->HmacMd5($sbOld, $this->merchantKey);
    }


    private function HmacMd5($data, $key)
    {
        //需要配置环境支持iconv，否则中文参数不能正常处理
        $key = iconv("GB2312", "UTF-8", $key);
        $data = iconv("GB2312", "UTF-8", $data);

        $b = 64; // byte length for md5
        if (strlen($key) > $b) {
            $key = pack("H*", md5($key));
        }
        $key = str_pad($key, $b, chr(0x00));
        $ipad = str_pad('', $b, chr(0x36));
        $opad = str_pad('', $b, chr(0x5c));
        $k_ipad = $key ^ $ipad;
        $k_opad = $key ^ $opad;

        return md5($k_opad . pack("H*", md5($k_ipad . $data)));
    }

    //取得返回串中的所有参数
    public function getCallBackValue(&$r0_Cmd,&$r1_Code,&$r2_TrxId,&$r3_Amt,&$r4_Cur,&$r5_Pid,&$r6_Order,&$r7_Uid,&$r8_MP,&$r9_BType,&$hmac)
    {
        $r0_Cmd		= $_REQUEST['r0_Cmd'];
        $r1_Code	= $_REQUEST['r1_Code'];
        $r2_TrxId	= $_REQUEST['r2_TrxId'];
        $r3_Amt		= $_REQUEST['r3_Amt'];
        $r4_Cur		= $_REQUEST['r4_Cur'];
        $r5_Pid		= $_REQUEST['r5_Pid'];
        $r6_Order	= $_REQUEST['r6_Order'];
        $r7_Uid		= $_REQUEST['r7_Uid'];
        $r8_MP		= $_REQUEST['r8_MP'];
        $r9_BType	= $_REQUEST['r9_BType'];
        $hmac   = $_REQUEST['hmac'];

        return null;
    }

    public function CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac)
    {
        if($hmac==getCallbackHmacString($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType))
            return true;
        else
            return false;
    }

    function getCallbackHmacString($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType)
    {
        #取得加密前的字符串
        $sbOld = "";
        #加入商家ID
        $sbOld = $sbOld.$this->p1_MerId;
        #加入消息类型
        $sbOld = $sbOld.$r0_Cmd;
        #加入业务返回码
        $sbOld = $sbOld.$r1_Code;
        #加入交易ID
        $sbOld = $sbOld.$r2_TrxId;
        #加入交易金额
        $sbOld = $sbOld.$r3_Amt;
        #加入货币单位
        $sbOld = $sbOld.$r4_Cur;
        #加入产品Id
        $sbOld = $sbOld.$r5_Pid;
        #加入订单ID
        $sbOld = $sbOld.$r6_Order;
        #加入用户ID
        $sbOld = $sbOld.$r7_Uid;
        #加入商家扩展信息
        $sbOld = $sbOld.$r8_MP;
        #加入交易结果返回类型
        $sbOld = $sbOld.$r9_BType;

        //logstr($r6_Order,$sbOld,HmacMd5($sbOld,$this->merchantKey));
        return HmacMd5($sbOld,$this->merchantKey);
    }
}