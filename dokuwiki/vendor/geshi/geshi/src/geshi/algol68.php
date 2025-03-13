<?php
/*************************************************************************************
 * algol68.php
 * --------
 * Author: Neville Dempsey (NevilleD.sourceforge@sgr-a.net)
 * Copyright: (c) 2010 Neville Dempsey (https://sourceforge.net/projects/algol68/files/)
 * Release Version: 1.0.9.1
 * Date Started: 2010/04/24
 *
 * ALGOL 68 language file for GeSHi.
 *
 * CHANGES
 * -------
 * 2010/04/24 (1.0.8.8.0)
 *   - First Release - machine generated by http://rosettacode.org/geshi/
 * 2010/05/24 (1.0.8.8.1)
 *   - #2324 - converted comment detection to RegEx
 * 2010/06/16 (1.0.8.8.2)
 *   - separate symbols from keywords - quick fix
 * 2010/06/16 (1.0.8.8.3)
 *   - reverse length order symbols
 *   - Add RegEx for BITS and REAL literals (INT to do)
 *   - recognise LONG and SHORT prefixes to literals
 * 2010/07/23 (1.0.8.8.4)
 *   - fix errors detected by langcheck.php, eg rm tab, fix indenting, rm duplicate keywords, fix symbols as keywords etc
 *   - removed bulk of local variables from name space.
 *   - unfolded arrays
 *
 * TODO (updated yyyy/mm/dd)
 * -------------------------
 *   - Use "Parser Control" to fix KEYWORD parsing, eg: (INT minus one= -1; print(ABSminus one))
 *   - Parse $FORMATS$ more fully - if possible.
 *   - Pull reserved words from the source of A68G and A68RS
 *   - Pull stdlib PROC/OP/MODE symbols from the soruce of A68G and A68RS
 *   - Pull PROC/OP/MODE extensions from the soruce of A68G and A68RS
 *   - Use RegEx to detect extended precision PROC names, eg 'long long sin' etc
 *   - Use RegEx to detect white space std PROC names, eg 'new line'
 *   - Use RegEx to detect white space ext PROC names, eg 'cgs speed of light'
 *   - Use RegEx to detect BOLD symbols, eg userdefined MODEs and OPs
 *   - Add REgEx for INT literals - Adding INT breaks formatting...
 *   - Adding PIPE as a key word breaks formatting of "|" symbols!!
 *
 *************************************************************************************
 *
 *     This file is part of GeSHi.
 *
 *   GeSHi is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   GeSHi is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with GeSHi; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 ************************************************************************************/

if(!function_exists('geshi_langfile_algol68_vars')) {
    function geshi_langfile_algol68_vars(){
        $pre='(?<![0-9a-z_\.])';
        $post='?(?![0-9a-z]|\.(?:[eE][+\-]?)?\d)';
        $post=""; # assuming the RegEx is greedy #

        $_="\s*";

        $srad="Rr";        $rrad="[".$srad."]";  # either one digit, OR opt-space in digits #
        $sbin="0-1";       $rbin="[".$sbin."]";  $_bin=$rbin."(?:[".$sbin."\s]*".$rbin."|)";
        $snib="0-3";       $rnib="[".$snib."]";  $_nib=$rnib."(?:[".$snib."\s]*".$rnib."|)";
        $soct="0-7";       $roct="[".$soct."]";  $_oct=$roct."(?:[".$soct."\s]*".$roct."|)";
        $sdec="0-9";       $rdec="[".$sdec."]";  $_dec=$rdec."(?:[".$sdec."\s]*".$rdec."|)";
        $shex="0-9A-Fa-f"; $rhex="[".$shex."]";  $_hex=$rhex."(?:[".$shex."\s]*".$rhex."|)";

        # Define BITS: #
        $prebits=$pre; $postbits=$post;
        $bl="2".$_.$rrad.$_.$_bin;
        $bl=$bl."|"."2".$_.$rrad.$_.$_bin;
        $bl=$bl."|"."4".$_.$rrad.$_.$_nib;
        $bl=$bl."|"."8".$_.$rrad.$_.$_oct;
        $bl=$bl."|"."1".$_."0".$_.$rrad.$_.$_dec;
        $bl=$bl."|"."1".$_."6".$_.$rrad.$_.$_hex;

        # Define INT: #
        $preint=$pre; $postint=$post;
        # for some reason ".0 e - 2" is not recognised, but ".0 e + 2" IS!
        # work around: remove spaces between sign and digits! Maybe because
        # of the Unary '-' Operator
        $sign_="(?:-|\-|[-]|[\-]|\+|)";  # attempts # // FIXME: This should be used or removed. Assignment beneath

        $sign_="(?:-\s*|\+\s*|)"; # n.b. sign is followed by white space #

        $_int=$sign_.$_dec;
        $il=          $_int;                      # +_9           #

        //$GESHI_NUMBER_INT_BASIC='(?:(?<![0-9a-z_\.%])|(?<=\.\.))(?<![\d\.]e[+\-])([1-9]\d*?|0)(?![0-9a-z]|\.(?:[eE][+\-]?)?\d)';

        # Define REAL: #
        $prereal=$pre; $postreal=$post;
        $sexp="Ee\\\\";   $_exp="(?:⏨|[".$sexp."])".$_.$_int;
        $_decimal="[.]".$_.$_dec;

        # Add permitted permutations of various parts #
        $rl=        $_int.$_.$_decimal.$_.$_exp; # +_9_._9_e_+_9 #
        $rl=$rl."|".$_int.$_."[.]".$_.$_exp;     # +_9_.___e_+_9 #
        $rl=$rl."|".$_int.$_.$_exp;              # +_9_____e_+_9 #
        $rl=$rl."|".$sign_.$_decimal.$_.$_exp;   # +___._9_e_+_9 #

        $rl=$rl."|".$_int.$_.$_decimal;          # +_9_._9       #
        $rl=$rl."|".$sign_.$_decimal;            # +___._9       #

        # The following line damaged formatting...
        #$rl=$rl."|".$_int;                       # +_9           #

        # Apparently Algol68 does not support '2.', c.f. Algol 68G
        #$rl=$rl."|".$_int.$_."[.]";             # +_9_.         #

        # Literal prefixes are overridden by KEYWORDS :-(
        $LONGS="(?:(?:(LONG\s+)*|(SHORT\s+))*|)";

        return array(
            "BITS" => $prebits.$LONGS."(?:".$bl.")".$postbits,
            "INT" => $preint.$LONGS."(?:".$il.")".$postint,
            "REAL" => $prereal.$LONGS."(?:".$rl.")".$postreal,

            "BOLD" => 'color: #b1b100; font-weight: bold;',
            "ITALIC" => 'color: #b1b100;', # procedures traditionally italic #
            "NONSTD" => 'color: #FF0000; font-weight: bold;', # RED #
            "COMMENT" => 'color: #666666; font-style: italic;'
        );
    }
}
$a68=geshi_langfile_algol68_vars();

$language_data = array(
    'LANG_NAME' => 'ALGOL 68',
    'COMMENT_SINGLE' => array(),
    'COMMENT_MULTI' => array(
        '¢' => '¢',
        '£' => '£',
        '#' => '#',
        ),
    'COMMENT_REGEXP' => array(
        1 => '/\bCO((?:MMENT)?)\b.*?\bCO\\1\b/i',
        2 => '/\bPR((?:AGMAT)?)\b.*?\bPR\\1\b/i',
        3 => '/\bQUOTE\b.*?\bQUOTE\b/i'
        ),
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
    'QUOTEMARKS' => array('"'),
    'ESCAPE_CHAR' => '"',
    'NUMBERS' => GESHI_NUMBER_HEX_SUFFIX,  # Warning: Feature!! #
#                GESHI_NUMBER_HEX_SUFFIX, # Attempt ignore default #
    'KEYWORDS' => array(
# Extensions
        1 => array('KEEP', 'FINISH', 'USE', 'SYSPROCS', 'IOSTATE', 'USING', 'ENVIRON', 'PROGRAM', 'CONTEXT'),
#        2 => array('CASE', 'IN', 'OUSE', 'IN', 'OUT', 'ESAC', '(', '|', '|:', ')', 'FOR', 'FROM', 'TO', 'BY', 'WHILE', 'DO', 'OD', 'IF', 'THEN', 'ELIF', 'THEN', 'ELSE', 'FI', 'PAR', 'BEGIN', 'EXIT', 'END', 'GO', 'GOTO', 'FORALL', 'UPTO', 'DOWNTO', 'FOREACH', 'ASSERT'), #
        2 => array('CASE', 'IN', 'OUSE', /* 'IN',*/ 'OUT', 'ESAC', 'PAR', 'BEGIN', 'EXIT', 'END', 'GO TO', 'GOTO', 'FOR', 'FROM', 'TO', 'BY', 'WHILE', 'DO', 'OD', 'IF', 'THEN', 'ELIF', /* 'THEN',*/ 'ELSE', 'FI' ),
        3 => array('BITS', 'BOOL', 'BYTES', 'CHAR', 'COMPL', 'INT', 'REAL', 'SEMA', 'STRING', 'VOID'),
        4 => array('MODE', 'OP', 'PRIO', 'PROC', 'FLEX', 'HEAP', 'LOC', 'REF', 'LONG', 'SHORT', 'EITHER'),
# Extensions or deprecated keywords
# 'PIPE': keyword somehow interferes with the internal operation of GeSHi
        5 => array('FORALL', 'UPTO', 'DOWNTO', 'FOREACH', 'ASSERT', 'CTB', 'CT', 'CTAB', 'COMPLEX', 'VECTOR', 'SOUND' /*, 'PIPE'*/),
        6 => array('CHANNEL', 'FILE', 'FORMAT', 'STRUCT', 'UNION', 'OF'),
# '(', '|', '|:', ')',  #
#        7 => array('OF', 'AT', '@', 'IS', ':=:', 'ISNT', ':/=:', ':≠:', 'CTB', 'CT', '::', 'CTAB', '::=', 'TRUE', 'FALSE', 'EMPTY', 'NIL', '○', 'SKIP', '~'),
        7 => array('AT', 'IS', 'ISNT', 'TRUE', 'FALSE', 'EMPTY', 'NIL', 'SKIP'),
        8 => array('NOT', 'UP', 'DOWN', 'LWB', 'UPB', /* '-',*/ 'ABS', 'ARG', 'BIN', 'ENTIER', 'LENG', 'LEVEL', 'ODD', 'REPR', 'ROUND', 'SHORTEN', 'CONJ', 'SIGN'),
# OPERATORS ordered roughtly by PRIORITY #
#       9 => array('¬', '↑', '↓', '⌊', '⌈', '~', '⎩', '⎧'),
#        10 => array('+*', 'I', '+×', '⊥', '!', '⏨'),
        10 => array('I'),
#        11 => array('SHL', 'SHR', '**', 'UP', 'DOWN', 'LWB', 'UPB', '↑', '↓', '⌊', '⌈', '⎩', '⎧'),
        11 => array('SHL', 'SHR', /*'UP', 'DOWN', 'LWB', 'UPB'*/),
#        12 => array('*', '/', '%', 'OVER', '%*', 'MOD', 'ELEM', '×', '÷', '÷×', '÷*', '%×', '□', '÷:'),
        12 => array('OVER', 'MOD', 'ELEM'),
#        13 => array('-', '+'),
#        14 => array('<', 'LT', '<=', 'LE', '>=', 'GE', '>', 'GT', '≤', '≥'),
        14 => array('LT', 'LE', 'GE', 'GT'),
#        15 => array('=', 'EQ', '/=', 'NE', '≠', '~='),
        15 => array('EQ', 'NE'),
#        16 => array('&', 'AND', '∧', 'OR', '∨', '/\\', '\\/'),
        16 => array('AND', 'OR'),
        17 => array('MINUSAB', 'PLUSAB', 'TIMESAB', 'DIVAB', 'OVERAB', 'MODAB', 'PLUSTO'),
#        18 => array('-:=', '+:=', '*:=', '/:=', '%:=', '%*:=', '+=:', '×:=', '÷:=', '÷×:=', '÷*:=', '%×:=', '÷::=', 'MINUS', 'PLUS', 'DIV', 'MOD', 'PRUS'),
# Extensions or deprecated keywords
        18 => array('MINUS', 'PLUS', 'DIV', /* 'MOD',*/ 'PRUS', 'IS NOT'),
# Extensions or deprecated keywords
        19 => array('THEF', 'ANDF', 'ORF', 'ANDTH', 'OREL', 'ANDTHEN', 'ORELSE'),
# Built in procedures - from standard prelude #
        20 => array('int lengths', 'intlengths', 'int shorths', 'intshorths', 'max int', 'maxint', 'real lengths', 'reallengths', 'real shorths', 'realshorths', 'bits lengths', 'bitslengths', 'bits shorths', 'bitsshorths', 'bytes lengths', 'byteslengths', 'bytes shorths', 'bytesshorths', 'max abs char', 'maxabschar', 'int width', 'intwidth', 'long int width', 'longintwidth', 'long long int width', 'longlongintwidth', 'real width', 'realwidth', 'long real width', 'longrealwidth', 'long long real width', 'longlongrealwidth', 'exp width', 'expwidth', 'long exp width', 'longexpwidth', 'long long exp width', 'longlongexpwidth', 'bits width', 'bitswidth', 'long bits width', 'longbitswidth', 'long long bits width', 'longlongbitswidth', 'bytes width', 'byteswidth', 'long bytes width', 'longbyteswidth', 'max real', 'maxreal', 'small real', 'smallreal', 'long max int', 'longmaxint', 'long long max int', 'longlongmaxint', 'long max real', 'longmaxreal', 'long small real', 'longsmallreal', 'long long max real', 'longlongmaxreal', 'long long small real', 'longlongsmallreal', 'long max bits', 'longmaxbits', 'long long max bits', 'longlongmaxbits', 'null character', 'nullcharacter', 'blank', 'flip', 'flop', 'error char', 'errorchar', 'exp char', 'expchar', 'newline char', 'newlinechar', 'formfeed char', 'formfeedchar', 'tab char', 'tabchar'),
        21 => array('stand in channel', 'standinchannel', 'stand out channel', 'standoutchannel', 'stand back channel', 'standbackchannel', 'stand draw channel', 'standdrawchannel', 'stand error channel', 'standerrorchannel'),
        22 => array('put possible', 'putpossible', 'get possible', 'getpossible', 'bin possible', 'binpossible', 'set possible', 'setpossible', 'reset possible', 'resetpossible', 'reidf possible', 'reidfpossible', 'draw possible', 'drawpossible', 'compressible', 'on logical file end', 'onlogicalfileend', 'on physical file end', 'onphysicalfileend', 'on line end', 'onlineend', 'on page end', 'onpageend', 'on format end', 'onformatend', 'on value error', 'onvalueerror', 'on open error', 'onopenerror', 'on transput error', 'ontransputerror', 'on format error', 'onformaterror', 'open', 'establish', 'create', 'associate', 'close', 'lock', 'scratch', 'space', 'new line', 'newline', 'print', 'write f', 'writef', 'print f', 'printf', 'write bin', 'writebin', 'print bin', 'printbin', 'read f', 'readf', 'read bin', 'readbin', 'put f', 'putf', 'get f', 'getf', 'make term', 'maketerm', 'make device', 'makedevice', 'idf', 'term', 'read int', 'readint', 'read long int', 'readlongint', 'read long long int', 'readlonglongint', 'read real', 'readreal', 'read long real', 'readlongreal', 'read long long real', 'readlonglongreal', 'read complex', 'readcomplex', 'read long complex', 'readlongcomplex', 'read long long complex', 'readlonglongcomplex', 'read bool', 'readbool', 'read bits', 'readbits', 'read long bits', 'readlongbits', 'read long long bits', 'readlonglongbits', 'read char', 'readchar', 'read string', 'readstring', 'print int', 'printint', 'print long int', 'printlongint', 'print long long int', 'printlonglongint', 'print real', 'printreal', 'print long real', 'printlongreal', 'print long long real', 'printlonglongreal', 'print complex', 'printcomplex', 'print long complex', 'printlongcomplex', 'print long long complex', 'printlonglongcomplex', 'print bool', 'printbool', 'print bits', 'printbits', 'print long bits', 'printlongbits', 'print long long bits', 'printlonglongbits', 'print char', 'printchar', 'print string', 'printstring', 'whole', 'fixed', 'float'),
        23 => array('pi', 'long pi', 'longpi', 'long long pi', 'longlongpi'),
        24 => array('sqrt', 'curt', 'cbrt', 'exp', 'ln', 'log', 'sin', 'arc sin', 'arcsin', 'cos', 'arc cos', 'arccos', 'tan', 'arc tan', 'arctan', 'long sqrt', 'longsqrt', 'long curt', 'longcurt', 'long cbrt', 'longcbrt', 'long exp', 'longexp', 'long ln', 'longln', 'long log', 'longlog', 'long sin', 'longsin', 'long arc sin', 'longarcsin', 'long cos', 'longcos', 'long arc cos', 'longarccos', 'long tan', 'longtan', 'long arc tan', 'longarctan', 'long long sqrt', 'longlongsqrt', 'long long curt', 'longlongcurt', 'long long cbrt', 'longlongcbrt', 'long long exp', 'longlongexp', 'long long ln', 'longlongln', 'long long log', 'longlonglog', 'long long sin', 'longlongsin', 'long long arc sin', 'longlongarcsin', 'long long cos', 'longlongcos', 'long long arc cos', 'longlongarccos', 'long long tan', 'longlongtan', 'long long arc tan', 'longlongarctan'),
        25 => array('first random', 'firstrandom', 'next random', 'nextrandom', 'long next random', 'longnextrandom', 'long long next random', 'longlongnextrandom'),
        26 => array('real', 'bits pack', 'bitspack', 'long bits pack', 'longbitspack', 'long long bits pack', 'longlongbitspack', 'bytes pack', 'bytespack', 'long bytes pack', 'longbytespack', 'char in string', 'charinstring', 'last char in string', 'lastcharinstring', 'string in string', 'stringinstring'),
        27 => array('utc time', 'utctime', 'local time', 'localtime', 'argc', 'argv', 'get env', 'getenv', 'reset errno', 'reseterrno', 'errno', 'strerror'),
        28 => array('sinh', 'long sinh', 'longsinh', 'long long sinh', 'longlongsinh', 'arc sinh', 'arcsinh', 'long arc sinh', 'longarcsinh', 'long long arc sinh', 'longlongarcsinh', 'cosh', 'long cosh', 'longcosh', 'long long cosh', 'longlongcosh', 'arc cosh', 'arccosh', 'long arc cosh', 'longarccosh', 'long long arc cosh', 'longlongarccosh', 'tanh', 'long tanh', 'longtanh', 'long long tanh', 'longlongtanh', 'arc tanh', 'arctanh', 'long arc tanh', 'longarctanh', 'long long arc tanh', 'longlongarctanh', 'arc tan2', 'arctan2', 'long arc tan2', 'longarctan2', 'long long arc tan2', 'longlongarctan2'),
        29 => array('complex sqrt', 'complexsqrt', 'long complex sqrt', 'longcomplexsqrt', 'long long complex sqrt', 'longlongcomplexsqrt', 'complex exp', 'complexexp', 'long complex exp', 'longcomplexexp', 'long long complex exp', 'longlongcomplexexp', 'complex ln', 'complexln', 'long complex ln', 'longcomplexln', 'long long complex ln', 'longlongcomplexln', 'complex sin', 'complexsin', 'long complex sin', 'longcomplexsin', 'long long complex sin', 'longlongcomplexsin', 'complex arc sin', 'complexarcsin', 'long complex arc sin', 'longcomplexarcsin', 'long long complex arc sin', 'longlongcomplexarcsin', 'complex cos', 'complexcos', 'long complex cos', 'longcomplexcos', 'long long complex cos', 'longlongcomplexcos', 'complex arc cos', 'complexarccos', 'long complex arc cos', 'longcomplexarccos', 'long long complex arc cos', 'longlongcomplexarccos', 'complex tan', 'complextan', 'long complex tan', 'longcomplextan', 'long long complex tan', 'longlongcomplextan', 'complex arc tan', 'complexarctan', 'long complex arc tan', 'longcomplexarctan', 'long long complex arc tan', 'longlongcomplexarctan', 'complex sinh', 'complexsinh', 'complex arc sinh', 'complexarcsinh', 'complex cosh', 'complexcosh', 'complex arc cosh', 'complexarccosh', 'complex tanh', 'complextanh', 'complex arc tanh', 'complexarctanh')
        ),
    'SYMBOLS' => array(
        1 => array( /* reverse length sorted... */ '÷×:=', '%×:=', ':≠:', '÷*:=', '÷::=', '%*:=', ':/=:', '×:=', '÷:=', '÷×', '%:=', '%×', '*:=', '+:=', '+=:', '+×', '-:=', '/:=', '::=', ':=:', '÷*', '÷:', '↑', '↓', '∧', '∨', '≠', '≤', '≥', '⊥', '⌈', '⌊', '⎧', '⎩', /* '⏨', */ '□', '○', '%*', '**', '+*', '/=', '::', '/\\', '\\/', '<=', '>=', '|:', '~=', '¬', '×', '÷', '!', '%', '&', '(', ')', '*', '+', ',', '-', '/', ':', ';', '<', '=', '>', '?', '@', '[', ']', '^', '{', '|', '}', '~')
    ),
    'CASE_SENSITIVE' => array(
        GESHI_COMMENTS => false,
        1 => true,
        2 => true,
        3 => true,
        4 => true,
        5 => true,
        6 => true,
        7 => true,
        8 => true,
#        9 => true,
        10 => true,
        11 => true,
        12 => true,
#        13 => true,
        14 => true,
        15 => true,
        16 => true,
        17 => true,
        18 => true,
        19 => true,
        20 => true,
        21 => true,
        22 => true,
        23 => true,
        24 => true,
        25 => true,
        26 => true,
        27 => true,
        28 => true,
        29 => true
        ),
    'STYLES' => array(
        'KEYWORDS' => array(
            1 => $a68['NONSTD'], 2 => $a68['BOLD'], 3 => $a68['BOLD'], 4 => $a68['BOLD'],
            5 => $a68['NONSTD'], 6 => $a68['BOLD'], 7 => $a68['BOLD'], 8 => $a68['BOLD'],
            /* 9 => $a68['BOLD'],*/ 10 => $a68['BOLD'], 11 => $a68['BOLD'], 12 => $a68['BOLD'],
            /* 13 => $a68['BOLD'],*/ 14 => $a68['BOLD'], 15 => $a68['BOLD'], 16 => $a68['BOLD'], 17 => $a68['BOLD'],
            18 => $a68['NONSTD'], 19 => $a68['NONSTD'],
            20 => $a68['ITALIC'], 21 => $a68['ITALIC'], 22 => $a68['ITALIC'], 23 => $a68['ITALIC'],
            24 => $a68['ITALIC'], 25 => $a68['ITALIC'], 26 => $a68['ITALIC'], 27 => $a68['ITALIC'],
            28 => $a68['ITALIC'], 29 => $a68['ITALIC']
            ),
        'COMMENTS' => array(
            1 => $a68['COMMENT'], 2 => $a68['COMMENT'], 3 => $a68['COMMENT'], /* 4 => $a68['COMMENT'],
            5 => $a68['COMMENT'],*/ 'MULTI' => $a68['COMMENT']
            ),
        'ESCAPE_CHAR' => array(
            0 => 'color: #000099; font-weight: bold;'
            ),
        'BRACKETS' => array(
            0 => 'color: #009900;'
            ),
        'STRINGS' => array(
            0 => 'color: #0000ff;'
            ),
        'NUMBERS' => array(
            0 => 'color: #cc66cc;',
            ),
        'METHODS' => array(
            0 => 'color: #004000;',
            1 => 'color: #004000;'
            ),
        'SYMBOLS' => array(
            0 => 'color: #339933;',
            1 => 'color: #339933;'
            ),
        'REGEXPS' => array(
            0  => 'color: #cc66cc;',   # BITS #
            1  => 'color: #cc66cc;',   # REAL #
            /* 2  => 'color: #cc66cc;',   # INT # */
            ),
        'SCRIPT' => array()
        ),
    'URLS' => array(
        1 => '',
        2 => '',
        3 => '',
        4 => '',
        5 => '',
        6 => '',
        7 => '',
        8 => '',
#        9 => '',
        10 => '',
        11 => '',
        12 => '',
#        13 => '',
        14 => '',
        15 => '',
        16 => '',
        17 => '',
        18 => '',
        19 => '',
        20 => '',
        21 => '',
        22 => '',
        23 => '',
        24 => '',
        25 => '',
        26 => '',
        27 => '',
        28 => '',
        29 => ''
        ),
    'OOLANG' => true,
    'OBJECT_SPLITTERS' => array(
        0 => '→',
        1 => 'OF'
        ),
    'REGEXPS' => array(
        0 => $a68['BITS'],
        1 => $a68['REAL']
        # 2 => $a68['INT'], # Breaks formatting for some reason #
        # 2 => $GESHI_NUMBER_INT_BASIC # Also breaks formatting  #
    ),
    'STRICT_MODE_APPLIES' => GESHI_NEVER,
    'SCRIPT_DELIMITERS' => array(),
    'HIGHLIGHT_STRICT_BLOCK' => array()
);

unset($a68);
