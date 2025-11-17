<?php
$MemNum = 203;

include_once 'inc\\config.inc.php';

include_once '..\admin.loader.php';
include_once '..\global.loader.php';
include_once '..\lang.loader.php';

$cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
$cNeoSQLConnectODBC->ConnectRanWeb();

$szTemp = "SELECT [UserNum]
      ,[SerialTruemoney]
      ,[Status]
      ,[CardRank]
      ,[RefillDate]
      ,[UpdateDate]
  FROM [BBSAsiaGame].[dbo].[Refill]
where MemNum = 233
and Status = 1
and CardRank = 2
and DAY(RefillDate) = 6
and MONTH(RefillDate) = 8
and YEAR(RefillDate) = 2016";

$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );

$pData = new _tdata();

while( $cNeoSQLConnectODBC->FetchRow() )
{
    $pData->AddData( "UserNum" , $cNeoSQLConnectODBC->Result( "UserNum" , ODBC_RETYPE_INT ) );
    $pData->AddData( "SerialTruemoney" , $cNeoSQLConnectODBC->Result( "SerialTruemoney" , ODBC_RETYPE_ENG ) );
    $pData->NextData();
}

$cNeoSQLConnectODBC->CloseRanWeb();

function GetEventNum( $UserNum )
{
    global $pData;
    
    $EventNum = 0;
    
    for( $i = 0 ; $i < $pData->GetRollData() ; $i++ )
    {
        $ppData = $pData->GetData( $i );
        
        if ( $ppData["UserNum"] == $UserNum )
        {
            ++$EventNum;
        }
    }
    
    return $EventNum;
}

$UserReportData = new _tdata();

for( $i = 0 ; $i < $pData->GetRollData() ; $i++ )
{
    $ppData = $pData->GetData( $i );
    
    $work = true;
    for( $n = 0 ; $n < $UserReportData->GetRollData() ; ++$n)
    {
        $pppData = $UserReportData->GetData( $n );
        
        if( $ppData["UserNum"] == $pppData["UserNum"] )
        {
            $work = false;
            break;
        }
    }
    
    if(!$work) continue;
    
    echo $ppData["UserNum"] . " Event : " . GetEventNum( $ppData["UserNum"] ) . "<br>";
    
    $UserReportData->AddData( "UserNum" , $ppData["UserNum"] );
    $UserReportData->NextData();
}

echo "Program end.";
?>
