<?php
/**
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package php
 * @name wips.php
 * @date 2012-02-01 14:57:50
 */
 


$config["wips"] =  array (
  'sql' => 
  array (
    'enabled' => true,
    'dfunction' => 'load_file,hex,substring,if,ord,char',
    'daction' => 'intooutfile,intodumpfile,unionselect,unionall,uniondistinct,(select',
    'dnote' => '/'.'*,*'.'/,#,--',
    'afullnote' => 'true',
    'dlikehex' => 'true',
  ),
);
?>