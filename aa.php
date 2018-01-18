<?php

$xml = new DOMDocument();
$xml->load("1.xml");
 $xml->documentElement  ;
function getArray($node) {

    $array = false;

    if ($node->hasAttributes()) {
        foreach ($node->attributes as $attr) {
            $array[$attr->nodeName] = $attr->nodeValue;
        }
    }

    if ($node->hasChildNodes()) {
        if ($node->childNodes->length == 1) {
            $array[$node->firstChild->nodeName] = getArray($node->firstChild);
        } else {
            foreach ($node->childNodes as $childNode) {
                if ($childNode->nodeType != XML_TEXT_NODE) {
                    $array[$childNode->nodeName][] = getArray($childNode);
                }
            }
        }
    } else {
        return $node->nodeValue;
    }
    return $array;
}
$arrayxml = getArray($xml->firstChild ) ;


//体育运动
foreach ($arrayxml['Sports'][0]['Sport'] as $k=>$v)
{

    $sport[$k]['SportID']=$v['BetradarSportID'];
    if(isset($v['Texts']))
    {
        foreach ($v['Texts'][0]['Text'] as $kk=>$vv)
        {
            if(array_key_exists('#text',$vv['Value'])&&$vv['Language']=='BET')
            {
               $sport[$k]['Sport'] = $vv['Value']['#text'];
            }
        }
    }

    //分类
    if(isset($v['Category']))
    {
        //可能属于多个分类
        foreach ($v['Category'] as $cate=>$value)
        {
            $sport[$k]['Category'][$cate]['CategoryID']=$value['BetradarCategoryID'];
            if(isset($v['Category'][$cate]['Texts']))
            {
                foreach ($v['Category'][$cate]['Texts'][0]['Text'] as $kk=>$vv)
                {
                    if(array_key_exists('#text',$vv['Value'])&&$vv['Language']=='BET')
                    {
                        $sport[$k]['Category'][$cate]['Country'] = $vv['Value']['#text'];
                    }
                }
            }
               //联赛,可能有多个联赛
             if(isset($v['Category'][$cate]['Tournament']))
             {
                 foreach ($v['Category'][$cate]['Tournament'] as $tour=>$item)
                 {
                     $sport[$k]['Category'][$cate]['Tournament'][$tour]['TournamentID']=$item['BetradarTournamentID'];

                     foreach ($v['Category'][$cate]['Tournament'][$tour]['Texts'][0]['Text'] as $kk=>$vv)
                     {
                         if(array_key_exists('#text',$vv['Value']) &&  $vv['Language']=='BET') {
                             $sport[$k]['Category'][$cate]['Tournament'][$tour]['Country'] = $vv['Value']['#text'];
                         }
                     }
                     //比赛信息
                     if(isset($v['Category'][$cate]['Tournament'][$tour]['Match']))
                     {

                         //可能有多场比赛
                         foreach ($v['Category'][$cate]['Tournament'][$tour]['Match'] as $match=>$matchval)
                         {
                             $sport[$k]['Category'][$cate]['Tournament'][$tour]['Match'][$match]['MatchID']=$matchval['BetradarMatchID'];//比赛id

                             //比赛竞争对手，时间信息
                             foreach ($v['Category'][$cate]['Tournament'][$tour]['Match'][$match]['Fixture'][0]['Competitors'][0]['Texts'] as $kk=>$vv)
                             {
                                 if($vv['Text']['Type']==1)
                                 {
                                     $sport[$k]['Category'][$cate]['Tournament'][$tour]['Match'][$match]['HSUPERID'] =$vv['Text']['SUPERID'];
                                     foreach ($vv['Text']['Text'] as $kkk=>$vvv)
                                     {
                                         if(array_key_exists('#text',$vvv['Value']) &&  $vvv['Language']=='BET') {
                                             $sport[$k]['Category'][$cate]['Tournament'][$tour]['Match'][$match]['Hcountry'] = $vvv['Value']['#text'];
                                         }
                                     }
                                 }
                                 else
                                 {
                                     $sport[$k]['Category'][$cate]['Tournament'][$tour]['Match'][$match]['ASUPERID'] =$vv['Text']['SUPERID'];
                                     foreach ($vv['Text']['Text'] as $kkk=>$vvv)
                                     {

                                         if(array_key_exists('#text',$vvv['Value']) &&  $vvv['Language']=='BET') {
                                             $sport[$k]['Category'][$cate]['Tournament'][$tour]['Match'][$match]['Acountry'] = $vvv['Value']['#text'];
                                         }
                                     }
                                 }


                             }
                             //，时间信息
                             $sport[$k]['Category'][$cate]['Tournament'][$tour]['Match'][$match]['MatchDate']=$v['Category'][$cate]['Tournament'][0]['Match'][0]['Fixture'][0]['DateInfo'][0]['MatchDate']['#text'];
                             $sport[$k]['Category'][$cate]['Tournament'][$tour]['Match'][$match]['Off']=$v['Category'][$cate]['Tournament'][0]['Match'][0]['Fixture'][0]['StatusInfo'][0]['Off']['#text'];

                             if(array_key_exists('OddsType',$v['Category'][$cate]['Tournament'][$tour]['Match'][$match]['MatchOdds'][0]['Bet']))
                             {
                                 $sport[$k]['Category'][$cate]['Tournament'][$tour]['Match'][$match]['MatchOdds'][$tour]['OddsType'] = $v['Category'][$cate]['Tournament'][0]['Match'][0]['MatchOdds'][0]['Bet']['OddsType'];
                                 $sport[$k]['Category'][$cate]['Tournament'][$tour]['Match'][$match]['MatchOdds'][$tour]['Odds'] = $v['Category'][$cate]['Tournament'][0]['Match'][0]['MatchOdds'][0]['Bet']['Odds'];
                             }
                             else
                             {
                                 foreach ($v['Category'][$cate]['Tournament'][$tour]['Match'][$match]['MatchOdds'][0]['Bet'] as $kk=>$vv)
                                 {
                                     $sport[$k]['Category'][$cate]['Tournament'][$tour]['Match'][$match]['MatchOdds'][$kk]['OddsType'] = $vv['OddsType'];
                                     $sport[$k]['Category'][$cate]['Tournament'][$tour]['Match'][$match]['MatchOdds'][$kk]['Odds'] = $vv['Odds'];
                                 }
                             }

                             if(array_key_exists('OddsType',$v['Category'][$cate]['Tournament'][$tour]['Match'][$match]['Probabilities'][0]['PR']))
                             {
                                 $sport[$k]['Category'][$cate]['Tournament'][$tour]['Match'][$match]['Probabilities'][$tour]['OddsType'] = $v['Category'][$cate]['Tournament'][0]['Match'][0]['Probabilities'][0]['PR']['OddsType'];
                                 $sport[$k]['Category'][$cate]['Tournament'][$tour]['Match'][$match]['Probabilities'][0]['P'] =$v['Category'][$cate]['Tournament'][0]['Match'][0]['Probabilities'][0]['PR']['P'];
                             }
                             else
                             {
                                 foreach ($v['Category'][$cate]['Tournament'][$tour]['Match'][$match]['Probabilities'][0]['PR'] as $kk=>$vv)
                                 {
                                     $sport[$k]['Category'][$cate]['Tournament'][$tour]['Match'][$match]['Probabilities'][$kk]['OddsType'] = $vv['OddsType'];
                                     $sport[$k]['Category'][$cate]['Tournament'][$tour]['Match'][$match]['Probabilities'][$kk]['P'] =$vv['P'];
                                 }
                             }
                         }


                     }
                 }
            }
        }
    }
}

//print_r($sport);die;
//拼接入库

$db = mysqli_connect('127.0.0.7','root','');
mysqli_select_db($db,'betai');
mysqli_query($db,'set names utf8');
$sql = "insert into sport VALUES ";

//体育类别

//foreach ($sport as $k=>$v)
//{
//    $sql .='("'.$v['SportID'].'","'.$v['Sport'].'"),';
//}
//echo substr($sql,0,-1);
//mysqli_query($db,$sql);


//$sport  = [
//    [
//        'SportID'=>1,
//        'Sport'=>'score',
//        'Category'=>[
//            [
//                'CategoryID'=>235,
//                'Country'=>'china',
//                'Tournament'=>
//                [
//                    [
//                        'TournamentID'=>445,
//                        'Country'=>'chaid',
//                        'match'=>
//                            [
//
//                            ],
//                    ] ,
//
//                    [
//                        'TournamentID'=>445,
//                        'Country'=>'chaid',
//                        'match'=>
//                            [
//
//                            ],
//                    ]
//                ]
//            ],
//            [
//                'CategoryID'=>222,
//                'Country'=>'eng',
//                'Tournament'=>
//                    [
//                        [
//                            'TournamentID'=>1,
//                            'Country'=>'aaa',
//                            'match'=>
//                                [
//
//                                ],
//                        ],
//                        [
//                          'TournamentID'=>2,
//                          'Country'=>'bb',
//                          'match'=>
//                              [
//
//                              ],
//                       ]
//                    ]
//            ]
//        ]
//    ],
////    [
////        'SportID'=>2,
////        'Sport'=>'ssss',
////        'Category'=>[
////            [
////                'CategoryID'=>555,
////                'Country'=>'china',
////                'Tournament'=>
////                [
////                    [
////                        'TournamentID'=>445,
////                        'Country'=>'chaid',
////                        'match'=>
////                            [
////
////                            ],
////                    ],
////                    [
////                        'TournamentID'=>445,
////                        'Country'=>'chaid',
////                        'match'=>
////                            [
////
////                            ],
////
////                    ]
////                ]
////
////            ],
////            [
////                'CategoryID'=>365,
////                'Country'=>'eng',
////                'Tournament'=>
////                [
////                    [
////                        'TournamentID'=>445,
////                        'Country'=>'chaid',
////                        'match'=>
////                            [
////
////                            ],
////                    ] ,
////                    [
////                        'TournamentID'=>445,
////                        'Country'=>'chaid',
////                        'match'=>
////                            [
////
////                            ],
////                    ]
////                ]
////
////            ]
////        ]
////    ]
//];

//分类

//$sql = "insert into category VALUES ";
//foreach ($sport as $k=>$v)
//{
//
//    foreach ($v['Category'] as $kk=>$vv)
//    {
//     $sql.='("'.$v['SportID'].'","'.$v['Sport'].'","'.$vv['CategoryID'].'","'.$vv['Country'].'"),';
//    }
//}
//mysqli_query($db,substr($sql,0,-1));

//联赛信息


//$sql = "insert into tournament (SportId,SportName,CategoryId,CategoryName,Id,TourCountry)VALUES ";
//
//foreach ($sport as $k=>$v)
//{
//    foreach ($v['Category'] as $kk=>$vv)
//    {
//        foreach ($vv['Tournament'] as $kkk=>$vvv)
//        {
//            $sql.='("'.$v['SportID'].'","'.$v['Sport'].'","'.$vv['CategoryID'].'","'.$vv['Country'].'","'.$vvv['TournamentID'].'","'.$vvv['Country'].'"),';
//        }
//    }
//}
//mysqli_query($db,substr($sql,0,-1));


//比赛信息
$sql = "insert into match (SportId,SportName,CategoryId,CategoryName,TournamentId,TournamentName,Id,Hid,Home,Aid,Away,MatchDate,Status)VALUES ";

//foreach ($sport as $k=>$v)
//{
//    foreach ($v['Category'] as $kk=>$vv)
//    {
//        foreach ($vv['Tournament'] as $kkk=>$vvv)
//        {
//            foreach ($vvv['Match'] as $kkkk=>$vvvv)
//            {
//                $sql.='("'.$v['SportID'].'","'.$v['Sport'].'","'.$vv['CategoryID'].'","'.$vv['Country'].'","'.$vvv['TournamentID'].'","'.$vvv['Country'].'","'.$vvvv['MatchID'].'","'.$vvvv['HSUPERID'].'","'.$vvvv['Hcountry'].'","'.$vvvv['ASUPERID'].'","'.$vvvv['Acountry'].'","'.$vvvv['MatchDate'].'","'.$vvvv['Off'].'"),';
//            }
//        }
//    }
//}
//mysqli_query($db,substr($sql,0,-1));

//赔率信息

$sql = "select * from odds";
$res = mysqli_query($db,$sql);
while($row=mysqli_fetch_array($res,MYSQLI_ASSOC)){
//var_dump($row);
//    foreach ($row as $k=>$v) {
//
        if($row['table'])
        {
            $arr[$row['table']] ['OddsType']= $row['OddsType'];
            $arr[$row['table']] ['field']= $row['field'];
            $arr[$row['table']] ['cate']= $row['cate'];
        }

//    }

}

//var_dump($arr);
foreach ($sport as $k=>$v)
{
    foreach ($v['Category'] as $kk=>$vv)
    {
        foreach ($vv['Tournament'] as $kkk=>$vvv)
        {
            foreach ($vvv['Match'] as $kkkk=>$vvvv)
            {

                foreach ($vvvv['MatchOdds'] as $kkkkk=>$vvvvv)
                {
                    odds($arr,$vvvvv,$vvvv['MatchID'],$db);
//
                }
            }
        }
    }
}

function odds($arr,$odds,$matchId,$db)
{
    foreach ($arr as $key=>$val)
    {
        if(in_array($odds['OddsType'],explode('/',$val['OddsType'])))
        {
            if($val['cate']=='0')
            {
                $filed = '';
                foreach ($odds['Odds'] as $kkkkkk=>$vvvvvv) {
                    switch ($vvvvvv['OutCome']) {
                        case '1':
                            $filed.=' H= "'. $vvvvvv['#text'].'",';
                            break;
                        case 'X':
                            $filed.=' D= "'. $vvvvvv['#text'].'",';
                            break;
                        case '2':
                            $filed.=' A= "'. $vvvvvv['#text'].'",';
                            break;
                        case "1/1":
                            $filed.=' HH= "'. $vvvvvv['#text'].'",';
                            break;
                        case "1/X":
                            $filed.=' HD= "'. $vvvvvv['#text'].'",';
                            break;
                        case "1/2":
                            $filed.=' HA= "'. $vvvvvv['#text'].'",';
                            break;

                        case "X/1":
                            $filed.=' DH= "'. $vvvvvv['#text'].'",';
                            break;
                        case "X/X":
                            $filed.=' DD= "'. $vvvvvv['#text'].'",';
                            break;
                        case "X/2":
                            $filed.=' DA= "'. $vvvvvv['#text'].'",';
                            break;

                        case "2/1":
                            $filed.= ' AH= "'. $vvvvvv['#text'].'",';
                            break;
                        case "2/X":
                            $filed.= ' AD= "'. $vvvvvv['#text'].'",';
                            break;
                        case "2/2":
                            $filed.= ' AA= "'. $vvvvvv['#text'].'",';
                            break;

                        case "No":
                            $filed.= ' N= "'. $vvvvvv['#text'].'",';
                            break;
                        case "Yes":
                            $filed.= ' Y= "'. $vvvvvv['#text'].'",';
                            break;
                        case "Odd":
                            $filed.= ' Odd= "'. $vvvvvv['#text'].'",';
                            break;
                        case "Even":
                            $filed.= ' Even= "'. $vvvvvv['#text'].'",';
                            break;
                        case "None":
                            $filed.= ' None= "'. $vvvvvv['#text'].'",';
                            break;

                        case "0-1 goals":
                            $filed.= ' `0-1` = "'. $vvvvvv['#text'].'",';
                            break;
                        case "2-3 goals":
                            $filed.= ' `2-3`= "'. $vvvvvv['#text'].'",';
                            break;
                        case "4-5 goals":
                            $filed.= ' `4-5`= "'. $vvvvvv['#text'].'",';
                            break;
                        case "6+":
                            $filed.= ' `6+`= "'. $vvvvvv['#text'].'",';
                            break;

                        case "1X":
                            $filed.= ' HD= "'. $vvvvvv['#text'].'",';
                            break;
                        case "12":
                            $filed.= ' HA= "'. $vvvvvv['#text'].'",';
                            break;
                        case "X2":
                            $filed.= ' DA= "'. $vvvvvv['#text'].'",';
                            break;
                    }
                }

                $sql = 'insert into '.$key .' SET '. $filed.'MatchId= '.$matchId.', OddTypeId = '.$odds['OddsType'];

                if(mysqli_query($db,$sql))
                {
                    echo 1;
                }else
                {
                    echo 2;
                }

            }
            elseif($val['cate']==='1'){
                $filed = '';
                foreach ($odds['Odds'] as $kkkkkk=>$vvvvvv) {
                    switch ($vvvvvv['OutCome']) {
                        case '1':
                            $filed.= ' `1`= "'. $vvvvvv['#text'].'",';
                            break;
                        case '2':
                            $filed.= ' `2`= "'. $vvvvvv['#text'].'",';
                            break;
                        case '3':
                            $filed.= ' `3`= "'. $vvvvvv['#text'].'",';
                            break;
                        case "4":
                            $filed.= ' `4`= "'. $vvvvvv['#text'].'",';
                            break;
                        case "5":
                            $filed.= '`5` = "'. $vvvvvv['#text'].'",';
                            break;
                        case "6+":
                            $filed.= ' `6+`= "'. $vvvvvv['#text'].'",';
                            break;
                        case "3+":
                            $filed.= ' `3+`= "'. $vvvvvv['#text'].'",';
                            break;
                    }
                }
                $sql = 'insert into '.$key .' SET '. $filed.'MatchId= '.$matchId.', OddTypeId = '.$odds['OddsType'];
                if(mysqli_query($db,$sql))
                {
                    echo 1;
                }else
                {
                    echo 2;
                }
            }
            else
            {
                $filed=[];
                foreach ($odds['Odds'] as $kkkkkk=>$vvvvvv) {
                    switch ($vvvvvv['OutCome']) {
                        case '1':
                            $filed[$vvvvvv['SpecialBetValue']]['H'] = $vvvvvv['#text'];
                            break;
                        case 'X':
                            $filed[$vvvvvv['SpecialBetValue']]['D'] = $vvvvvv['#text'];
                            break;
                        case '2':
                            $filed[$vvvvvv['SpecialBetValue']]['A'] = $vvvvvv['#text'];
                            break;
                        case "Over":
                            $filed[$vvvvvv['SpecialBetValue']]['Over'] = $vvvvvv['#text'];
                            break;
                        case "Under":
                            $filed[$vvvvvv['SpecialBetValue']]['Under'] = $vvvvvv['#text'];
                            break;

                        case "Under and home":
                            $filed[$vvvvvv['SpecialBetValue']]['UH'] = $vvvvvv['#text'];
                            break;
                        case "Under and draw":
                            $filed[$vvvvvv['SpecialBetValue']]['UD'] = $vvvvvv['#text'];
                            break;
                        case "Under and away":
                            $filed[$vvvvvv['SpecialBetValue']]['UA'] = $vvvvvv['#text'];
                            break;
                        case "Over and home":
                            $filed[$vvvvvv['SpecialBetValue']]['OH'] = $vvvvvv['#text'];
                            break;
                        case "Over and draw":
                            $filed[$vvvvvv['SpecialBetValue']]['OD'] = $vvvvvv['#text'];
                            break;
                        case "Over and away":
                            $filed[$vvvvvv['SpecialBetValue']]['OA'] = $vvvvvv['#text'];
                            break;
                    }
                }

                foreach ($filed as $item=>$value)
                {
                    $string='';
                    foreach ($value as $items=>$values)
                    {
                        $string .= '   `'.$items.'`= "'.$values.'",';
                    }
                    $sql = 'insert into '.$key .' SET  Line="'. $item.'", '.$string.'MatchId= '.$matchId.', OddTypeId = '.$odds['OddsType'];
                    if(mysqli_query($db,$sql))
                    {
                        echo 1;
                    }else
                    {
                        echo 2;
                    }
                }
            }
            echo  '<br/>';
        }
    }
}
//mysqli_query($db,substr($sql,0,-1));

// $_url ='https://baike.baidu.com/item/%E6%B4%AA%E9%97%A8/47157?fr=aladdin#7_10&qq-pf-to=pcqq.c2c';
// echo $_ip = ipRand();
// getAutoHomeDealerMSG($_url,$_ip);
//// 抓取信息
//function getAutoHomeDealerMSG( $_url , $_ip )
//{
//    $ch = curl_init();
//    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
//    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);  // 从证书中检查SSL加密算法是否存在
//    curl_setopt ($ch, CURLOPT_URL, $_url);
//
//    curl_setopt ($ch, CURLOPT_HEADER, 0);
//
//    curl_setopt ($ch, CURLOPT_HTTPHEADER, array("CLIENT-IP:{$_ip}", "X-FORWARDED-FOR:{$_ip}"));  //此处可以改为任意假IP
//
//    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
//
//    curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
//
//    curl_setopt ($ch, CURLOPT_TIMEOUT, 120);
//
//    $result = curl_exec ($ch);
//
//    curl_close($ch);
//print_r($result);
////    return $result;
//}
//
//// 生成ip地址随机数
//function ipRand()
//{
//    $one = mt_rand( 1 , 255 );
//
//    $two = mt_rand( 1 , 255 );
//
//    $three = mt_rand( 1 , 255 );
//
//    $four = mt_rand( 1 , 255 );
//
//    $ipAddress = "{$one}.{$two}.{$three}.{$four}";
//
//    return $ipAddress;
//}
