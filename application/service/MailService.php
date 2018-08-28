<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/8/22
 * Time: 下午2:54
 */

namespace app\service;
use app\mail\enmu\MailConnectTypeEnum;
use PHPMailer\PHPMailer\PHPMailer;
use think\facade\Env;

class MailService
{
    /**根据邮箱配置发送系统邮件
     * @param $mail
     * @param $to
     * @param string $subject
     * @param $content
     * @param bool $isHTML
     * @param $attachment
     * @return bool
     */
    public static function sendMail($mail,$to,$subject='',$content,$isHTML=true,$attachment=null){
        $phpMailer=new PHPMailer();
        $phpMailer->SMTPDebug=config('app_debug');
        $phpMailer->isSMTP();
        $phpMailer->SMTPAuth=true;
        $phpMailer->Host=$mail->smtp_host;
        $phpMailer->Port=$mail->smtp_port;
        if($mail->connect_type==MailConnectTypeEnum::TLS){
            $phpMailer->SMTPSecure='tls';
        }
        else{
            $phpMailer->SMTPSecure='ssl';
        }
        $phpMailer->CharSet='UTF-8';
        $phpMailer->Username=$mail->account;
        $phpMailer->Password=$mail->password;
        $phpMailer->From=$mail->address;
        $phpMailer->FromName=$mail->name;
        $phpMailer->isHTML($isHTML);
        if(is_array($to)){
            foreach ($to as $item){
                $phpMailer->addAddress($item);
            }
        }
        else{
            $phpMailer->addAddress($to);
        }
        $phpMailer->Subject=$subject;
        $phpMailer->Body=$content;
        if(is_array($attachment)){
            foreach ($attachment as $item){
                $file=Env::get('root_path').'public'.$item;
                if (is_file($file)){
                    $phpMailer->addAttachment($file);
                }
            }
        }
        else if($attachment){
            $file=Env::get('root_path').'public'.$attachment;
            if(is_file($file)){
                $phpMailer->addAttachment($attachment);
            }
        }
        return $phpMailer->send()?true:$phpMailer->ErrorInfo;
    }
}