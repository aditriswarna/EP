<?php 
use yii\helpers\Html;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>EQUIPPP | A Merchant Banking Platform for Social Projects</title>
</head>

<body bgcolor="#f2f2f1">
<table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><img src="<?php echo SITE_URL. yii::getAlias('@web').'/images/email/header-img1.png' ?>" width="227" height="102" alt="" /><img src="<?php echo SITE_URL. yii::getAlias('@web').'/images/email/header-img2.png' ?>" width="423" height="102" alt="" align="absbottom" style="margin:0px; padding:0px;" /></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">
    
    <br />
    <table width="95%" border="0" cellspacing="0" cellpadding="0" align="center" >
  <tr>
    <td style="font-family:Arial, Geneva, sans-serif; font-size:14px; color:#45482f; line-height:20px">
    
      <p>Hi <?php echo $userdata[0]['fname'].' '.$userdata[0]['lname']; ?>,</p>
      <p>You have added <strong><?php echo $coowner[0]['fname'].' '.$coowner[0]['lname']; ?></strong> as a co-owner for the project <strong><?php echo $projectdata[0]['project_title']; ?></strong>.</p>
      <p>Thank You, <br />
        <strong style="color:#73a90b">The EquiPPP Team </strong></p></td>
  </tr>
</table>
    <br /></td>
  </tr> 
  <tr>
    <td>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="11" height="11" align="left" style="border-collapse:collapse;"><img src="<?php echo SITE_URL. yii::getAlias('@web').'/images/email/btm-lft-strip.png' ?>" width="11" height="11" alt="" align="absbottom" style="margin:0px; padding:0px;"/></td>
    <td valign="top" bgcolor="#FFFFFF" style="line-height: 0px;"><img src="<?php echo SITE_URL. yii::getAlias('@web').'/images/email/spacer.gif' ?>" width="11" height="11" alt="" /><img src="<?php echo SITE_URL. yii::getAlias('@web').'/images/email/spacer.gif' ?>" width="1" height="11" alt="" /></td>
    <td width="11" align="right" valign="bottom" style="border-collapse:collapse;"><img src="<?php echo SITE_URL. yii::getAlias('@web').'/images/email/btm-rt-strip.png' ?>" width="11" height="11" alt="" align="absbottom" style="margin:0px; padding:0px;" /></td>
  </tr>
</table>
    </td>
  </tr>
</table>
<br />
<table width='640' border='0' align="center" cellpadding='0' cellspacing='0'>
  <tr>
    <td style='color: #949a9e; font-family: Tahoma,Helvetica; font-size: 10px; line-height: 16px; vertical-align: top;' align='left'>&copy; 2016 EquiPPP. All rights reserved.<br />
      <br />
      If you want to obtain more information on EquiPPP, please visit our website at <a href="http://www.equippp.com/">www.equippp.com/</a>
      <br />
      <br />
      Your privacy is important to us. Please review EquiPPP&#39;s <a href='http://www.equippp.com/privacy-policy/'>Online Privacy Policy</a></td>
  </tr>
</table>
<p><br />
</p>
</body>
</html>
