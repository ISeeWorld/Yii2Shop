<h1>尊敬的用户<?php echo $adminuser; ?>你好</h1>

<p>您正在进行密码重置操作，请点击以下链接进行密码重置</p>

<?php $url = Yii::$app->urlManager->createAbsoluteUrl(['admin/manage/mailchangepass', 'timestamp' => $time, 'adminuser' => $adminuser, 'token' => $token]);?>
<a href="<?php echo $url; ?>" ><?php echo $url; ?></a>

<p> 本链接5分钟内有效</p>

<h1>本邮件系统自动发送，请勿回复</h1>
