<?php
/**
 * TCpdf操作类
 *
 */
namespace app\common;

require __DIR__ . '/../../extend/tcpdf/tcpdf.php';
class phptcpdf
{
    public $author = "Sun.C.L";   //文档作者
    public $logo = "";
    public $save_path = ""; //文件保存地址
    public $font = ""; //字体

    /**
     * PhpLibPdf constructor.
     * @param $page 纸张版式 P 竖版 L横版
     * @param $unit 排版单位 默认 mm
     * @param $format 纸张大小 默认A4 如果有其他大小使用数组 array(width*height)
     * @param $unicode 文件编码 true的编码为Unicode
     * @param $encoding 转换HTML实体时的编码方式 moren utf-8
     * @param $diskcache 弃用功能(false)
     * @param string $page
     */
    public function __construct($page = "P",$unit = "mm",$format = "A4",$font = "")
    {
        $this->handler = new \TCPDF($page, $unit, $format, true, 'UTF-8', false);
        $this->font = $font;
    }

    /**
     * 生成PDF文件
     * @param $content 文档内容
     * @param string $filename 保存的文件名
     * @param string $title 内容标题
     * @param string $password 是否加密 false为不加密
     * @param bool $printHead 是否打印页眉
     * @param bool $printFooter 是否打印页脚
     * @param string $type 类型 D 下载 I 预览 FD保存服务器并下载 默认下载
     * @param string $savepath 保存路径
     * @param string $keyword 关键字
     * @return bool
     */
    public function createPdf($content = "",$filename = "",$savepath="./",$title = "",$password="",$printHead = false,$printFooter = false,$type = "I",$keyword = "")
    {
        if(empty($filename))
        {
            $filename = uniqid().".pdf";
        }

        // 设置文档信息
        $this->handler->SetCreator($this->author);
        $this->handler->SetAuthor($this->author);
        $this->handler->SetTitle($title);
        $this->handler->SetSubject('TCPDF Tutorial');
        $this->handler->SetKeywords($keyword);

        // 设置页眉信息
        $this->handler->SetHeaderData("", 0, '', "");

        //是否打印页眉
        $this->handler->setPrintHeader($printHead);

        // 设置页眉页脚字体
        $this->handler->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 8));
        $this->handler->setFooterFont(Array(PDF_FONT_NAME_DATA, '', 8));

        // 默认字体名字
        $this->handler->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // 设置位置
        //左右间距 左 上 右
        $this->handler->SetMargins(5, 5, 5);
        //页眉 距离
        $this->handler->SetHeaderMargin(1);
        //页脚 距离
        $this->handler->SetFooterMargin(1);

        //是否自动分页
        $this->handler->SetAutoPageBreak(TRUE, 1);

        // set image scale factor
        $this->handler->setImageScale(PDF_IMAGE_SCALE_RATIO);
        //设置页面打印页脚
        $this->handler->setPrintFooter($printFooter);
        // 设置字体
        $this->handler->SetFont($this->font, '', 15);
        //自动添加下一页
        $this->handler->AddPage();
        if(!empty($password))
        {
            //加密
            $this->handler->SetProtection(array('print', 'modify', 'copy', 'annot-forms', 'fill-forms', 'extract', 'assemble', 'print-high'), $password, $owner_pass=null, $mode=0, $pubkeys=null);
        }

        //$this->handler->Write(0, $title, '', 0, 'C', true, 0, false, false, 0);
        $this->handler->SetFont($this->font, '', 8);
        $this->handler->writeHTML($content, true, false, false, false, '');


        //导出文件
        $file = $filename;
        if($type == "F")
        {
            $path = $this->save_path.$savepath;
            if(!is_dir($path))
            {
                mkdir($path,0777,true);
            }
            $file = $path.$filename;
        }
        $this->handler->Output($file, $type);
        $outArr = array(
            "file_name" => $savepath.$filename,
            "local_path" => $file
        );
        return $outArr;
    }
}