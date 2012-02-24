<? include handler('template')->file('@inizd/install/header'); ?>
<div class="setup step2">
<h2>设置数据源</h2>
<p>配置mysql数据库信息</p>
</div>
<div class="stepstat">
<ul>
<li class="unactivated">1</li>
<li class="current">2</li>
<li class="unactivated">3</li>
<li class="unactivated last">4</li>
</ul>
<div class="stepstatbg stepstat2"></div>
</div>
</div> 
<form action="?mod=install&code=dbs&op=save" method="post" >
<input type="hidden" name="FORMHASH" value='<?=FORMHASH?>'/>
<div class="main">
<h2 class="title">数据库服务器</h2>
<table class="tb" style="margin:20px 0 20px 55px;">
<tr> 
<th>项目</th>
<th class="padleft">当前值</th>
<th class="padleft">注释</th>
</tr>
<tr>
<td>服务器</td>
<td class="padleft">
<input name="db[host]" type="text" value="localhost" />
</td>
<td class="padleft">国内空间上数据库服务器地址, 一般为localhost</td>
</tr>
<tr>
<td>用户名</td>
<td class="padleft">
<input name="db[username]" type="text" value="root" />
</td>
<td class="padleft">连接空间上数据库的用户名</td>
</tr>
<tr>
<td>密码</td>
<td class="padleft">
<input name="db[password]" type="text" />
</td>
<td class="padleft">连接空间上数据库的密码</td>
</tr>
<tr>
<td>数据库</td>
<td class="padleft">
<input name="db[name]" type="text" value="tttuangou" />
</td>
<td class="padleft">空间上数据库的名称</td>
</tr>
<tr>
<td>表前缀</td>
<td class="padleft">
<input name="db[prefix]" type="text" value="cenwor_" />
</td>
<td class="padleft">默认无需修改，如同一数据库安装多个天天团购时必须修改</td>
</tr>
</table>
<div class="btnbox marginbot">
<input type="button" onclick="history.back();" value="上一步" />
<input type="submit" value="下一步" />
</div>
</div>
</form>
<?=ui('loader')->js('#inizd/install/js/step.dbs')?>
<? include handler('template')->file('@inizd/install/footer'); ?>