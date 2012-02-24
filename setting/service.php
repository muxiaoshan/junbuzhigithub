<?php
/**
 * Config File of [service]
 *
 * @time 2011-06-14 18:16:14
 */
$config["service"] =  array (
  'mail' => 
  array (
    'balance' => true,
  ),
  'sms' => 
  array (
    'driver' => 
    array (
      'ls' => 
      array (
        'name' => '电信通道',
        'intro' => '075开头，网关短信直发，价格便宜（自动重发功能暂时只支持此通道）<br/><a href="'.ihelper('tg.shop.sms.ls').'" target="_blank"><font color="red">点此在线购买</font></a>',
      ),
      'qxt' => 
      array (
        'name' => '移动和联通通道',
        'intro' => '106开头，更显正规，网关高效直发（如订阅数量较多，请使用此通道进行群发）<br/>批量群发短信时间为：早8点起-到晚21点。21点至早8点之间不再下发批量短信。单条发送不受此限制。<br/><a href="'.ihelper('tg.shop.sms.qxt').'" target="_blank"><font color="red">点此在线购买</font></a>',
      ),
    ),
    'autoERSend' => true,
  ),
  'push' => 
  array (
    'mthread' => false,
  ),
);
?>