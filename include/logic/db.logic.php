<?php

/**
 * 逻辑区：数据库管理
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package logic
 * @name db.logic.php
 * @version 1.2
 * @changelog +
 * + 2012/01/16 = 1.0 > 1.2
 * 增加对字段索引的修复（初步）（限制：1、1个索引只能包含1个字段；2、索引名和字段名必须相同）
 */

class DBMgrLogic
{
    
    public function structAnalyze($cacheLife = 3600)
    {
        $ckey = 'logic.db.struct.analyze';
        $cmpResult = fcache($ckey, $cacheLife);
        if ($cmpResult) return $cmpResult;
                        $tables = dbc(DBCMax)->query('SHOW TABLES')->done();
                $fileStruct = file_get_contents(DATA_PATH.'install/struct.sql');
                $tableArrayKey = 'Tables_in_'.ini('settings.db_name');
        $tablePrefix = ini('settings.db_table_prefix');
        $tablePrefixLen = strlen($tablePrefix);
                $cmpResult = array();
        $dbTables = array();
        foreach ($tables as $i => $tableArray)
        {
            $tableName = $tableArray[$tableArrayKey];
            if (substr($tableName, 0, $tablePrefixLen) != $tablePrefix)
            {
                continue;
            }
            else
            {
                $tableName = substr($tableName, $tablePrefixLen);
            }
            $dbTables[$tableName] = '_Moyo_';
                        $sqlSearch = 'CREATE TABLE `{prefix}'.$tableName.'`';
            if (!stristr($fileStruct, $sqlSearch))
            {
                continue;
            }
            preg_match_all('/CREATE TABLE `\{prefix\}'.$tableName.'`\s\((.*?)\)\sENGINE=MyISAM/is', $fileStruct, $st);
            if (!$st[0])
            {
                continue;
            }
            $relSTString = $st[1][0];
                        $relSTTabs = explode("\n", $relSTString);
            $relSTIndexs = array();
            $tabRight = array();
            foreach ($relSTTabs as $i => $_rTab)
            {
                                if (trim($_rTab) == '')
                {
                    continue;
                }
                $_rTab = str_replace(array("\n", "\r"), '', $_rTab);
                if (substr($_rTab, -1) == ',')
                {
                    $_rTab = substr($_rTab, 0, -1);
                }
                                preg_match('/^\s+`([a-z0-9_]+)`/i', $_rTab, $_rTabMch);
                if ($_rTabMch[0])
                {
                    $_rTab_Field = $_rTabMch[1];
                }
                else
                {
                                        $_rTab_Field = false;
                                                            preg_match_all('/(.*?)key\s+(`([a-z0-9_]+)`\s+)?\(`([a-z0-9_]+)`\)/i', $_rTab, $_rTabMch);
                    if ($_rTabMch[0])
                    {
                        $key = trim($_rTabMch[1][0]);
                        if ($key == '') $key = 'INDEX';
                        else $key = strtoupper($key);
                        $idxName = $key == 'PRIMARY' ? $key : $_rTabMch[3][0];
                        $relSTIndexs[$tableName][$idxName] = array(
                            'field' => $_rTabMch[4][0],
                            'type' => $key
                        );
                    }
                }
                $_rTab = str_replace($_rTabMch[0], '', $_rTab);
                                if (stristr($_rTab, 'not null'))
                {
                    $_rTab_Null = 'NO';
                }
                else
                {
                    $_rTab_Null = 'YES';
                }
                $_rTab = str_ireplace('not null', '', $_rTab);
                                preg_match('/default (.*?)$/i', $_rTab, $_rTabMch);
                if ($_rTabMch)
                {
                    if (substr($_rTabMch[1], 0, 1) == "'")
                    {
                        $_rTab_Default = str_replace('\'', '', $_rTabMch[1]);
                    }
                    else
                    {
                        $_Val = $_rTabMch[1];
                        if (strtolower($_Val) == 'null')
                        {
                            $_rTab_Default = NULL;
                        }
                    }
                }
                else
                {
                    $_rTab_Default = false;
                }
                $_rTab = str_replace($_rTabMch[0], '', $_rTab);
                                if (stristr($_rTab, 'auto_increment'))
                {
                    $_rTab_Extra = 'AUTO_INCREMENT';
                }
                else
                {
                    $_rTab_Extra = '';
                }
                $_rTab = str_ireplace('auto_increment', '', $_rTab);
                                $_rTab_Type = trim($_rTab);
                if ($_rTab_Field)
                {
                    $tabRight[$_rTab_Field] = array(
                        'Field' => $_rTab_Field,
                        'Type' => $_rTab_Type,
                        'Null' => $_rTab_Null,
                        'Default' => $_rTab_Default,
                        'Extra' => $_rTab_Extra
                    );
                }
            }
                        $curSTTabs = dbc(DBCMax)->query('DESCRIBE '.$tablePrefix.$tableName)->done();
                        $curSTIndexsSRC = dbc(DBCMax)->query('SHOW INDEX FROM '.$tablePrefix.$tableName)->done();
            $curSTIndexsSRC || $curSTIndexsSRC = array();
            $curSTIndexs = array();
            foreach ($curSTIndexsSRC as $i => $curSTIndex)
            {
                $__curTabIdx = &$curSTIndexs[$tableName];
                $__key = $curSTIndex['Key_name'];
                $__curTabIdx[$__key] = array(
                    'field' => $curSTIndex['Column_name'],
                    'type' => $curSTIndex['Non_unique'] ? ($curSTIndex['Index_type'] == 'BTREE' ? 'INDEX' : $curSTIndex['Index_type']) : ($curSTIndex['Key_name'] == 'PRIMARY' ? 'PRIMARY' : 'UNIQUE')
                );
            }
            $tabCurrent = array();
            foreach ($curSTTabs as $i => $curSTTab)
            {
                $tabCurrent[$curSTTab['Field']] = $curSTTab;
            }
                        $tabIdxCMD = array();
            if (isset($relSTIndexs[$tableName]))
            {
                $rIdx = $relSTIndexs[$tableName];
                $rIdx || $rIdx = array();
                $cIdx = $curSTIndexs[$tableName];
                $cIdx || $cIdx = array();
                if ($rIdx)
                {
                    foreach ($rIdx as $idxName => $idxData)
                    {
                        if (isset($cIdx[$idxName]))
                        {
                                                        $cIdxData = $cIdx[$idxName];
                            if ($cIdxData && ($idxData['field'] != $cIdxData['field'] || $idxData['type'] != $cIdxData['type']))
                            {
                                $cIdxData = false;
                                $tabIdxCMD[$idxData['field']] .= ' DROP INDEX `'.$idxName.'`, ';
                            }
                        }
                        else
                        {
                            $cIdxData = false;
                        }
                        if (!$cIdxData)
                        {
                            $tabIdxCMD[$idxData['field']] .= ' ADD '.$idxData['type'].' `'.$idxName.'` (`'.$idxData['field'].'`)';
                        }
                    }
                }
            }
                        $lastField = '';
            foreach ($tabRight as $field => $struct)
            {
                $cmd = false;
                if (!isset($tabCurrent[$field]))
                {
                    $cmd = 'ADD';
                }
                else
                {
                    if (
                        strtolower($struct['Type']) != strtolower($tabCurrent[$field]['Type'])
                        ||
                        $struct['Null'] != $tabCurrent[$field]['Null']
                        ||
                        strtolower($struct['Default']) != strtolower($tabCurrent[$field]['Default'])
                        ||
                        strtolower($struct['Extra']) != strtolower($tabCurrent[$field]['Extra'])
                    )
                    {
                        $cmd = 'CHANGE';
                    }
                }
                if (isset($tabIdxCMD[$field]))
                {
                    $cmd .= '@IDX';
                }
                if ($cmd && $cmd != '@IDX')
                {
                                        $cmd = str_replace('@IDX', '', $cmd);
                    $curDATA = array(
                        'class' => 'field',
                        'table' => $tableName,
                        'field' => $field,
                        'cmd' => $cmd
                    );
                    $curDATA['sql'] = 
                        'ALTER TABLE `'.($tablePrefix.$tableName).'` '
                        .$cmd.
                        ($cmd=='ADD'?'':(' `'.$field.'`'))
                        .' `'.$field.'` '
                        .$struct['Type']
                        .($struct['Null']=='NO'?' NOT NULL':'')
                        .($struct['Default']===false?'':(' DEFAULT \''.$struct['Default'].'\''))
                        .($struct['Extra']?(' '.$struct['Extra']):'')
                        .( ($struct['Extra'] && strtolower($struct['Extra']) == 'auto_increment') ? ' PRIMARY KEY' : '')
                        .( ($cmd=='ADD') ? ($lastField ? (' AFTER `'.$lastField.'`') : ' FIRST' ) : '');
                    $cmpResult[] = $curDATA;
                }
                if ($cmd && stristr('@IDX', $cmd))
                {
                                        $curDATA = array(
                        'class' => 'field',
                        'table' => $tableName,
                        'field' => $field.'@IDX',
                        'cmd' => $cmd
                    );
                    $curDATA['sql'] = 'ALTER TABLE `'.($tablePrefix.$tableName).'`'.$tabIdxCMD[$field];
                    $cmpResult[] = $curDATA;
                }
                $lastField = $field;
            }
        }
                        preg_match_all('/DROP TABLE IF EXISTS `\{prefix\}([a-z0-9_]+)`;/i', $fileStruct, $_rTabMch);
        $localTables = $_rTabMch[1];
        foreach ($localTables as $i => $tableName)
        {
            if (!isset($dbTables[$tableName]))
            {
                $curDATA = array(
                    'class' => 'table',
                    'table' => $tableName,
                    'field' => '*',
                    'cmd' => 'ADD'
                );
                preg_match_all('/CREATE TABLE `\{prefix\}'.$tableName.'`\s\(.*?\).*?CHARSET=utf8;/is', $fileStruct, $sqlMch);
                $sql = $sqlMch[0][0];
                $sql = str_ireplace('`{prefix}', '`'.$tablePrefix, $sql);
                $charset = ini('settings.charset');
                if ($charset == 'gbk')
                {
                    $sql = str_ireplace('utf8;', 'gbk;', $sql);
                }
                $curDATA['sql'] = $sql;
                $cmpResult[] = $curDATA;
            }
        }
                return fcache($ckey, $cmpResult);
    }
}

?>