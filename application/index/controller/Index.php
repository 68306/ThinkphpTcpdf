<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2018/9/17
 * Time: 下午9:10
 */
namespace app\index\controller;
use app\common\phptcpdf;
use think\Controller;

class Index extends Controller
{
    public function useTcpdf()
    {
        $tcpdf = new phptcpdf();
        $content = "PDF内容内容内容内容内容内容内容";
        $tcpdf->createPdf($content);
        exit();
    }
}